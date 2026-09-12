<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class ReportService
{
    /* =========================================================================
    | TASK REPORT
    | ========================================================================= */

    /**
     * Prepare all data required for the Task Report.
     */
    public function taskReportData(Request $request, User $user): array
    {
        $role = $this->role($user);

        /*
        |--------------------------------------------------------------------------
        | Base statistics query
        |--------------------------------------------------------------------------
        */

        $statsQuery = Task::query();

        $this->applyTaskRoleScope($statsQuery, $user, $role);

        /*
        |--------------------------------------------------------------------------
        | Apply report filters
        |--------------------------------------------------------------------------
        */

        $statsQuery->reportFilter($request);

        /*
        |--------------------------------------------------------------------------
        | Main tasks query
        |--------------------------------------------------------------------------
        */

        $query = Task::with([
            'project',
            'assignedUser',
        ]);

        $this->applyTaskRoleScope($query, $user, $role);

        $query->reportFilter($request);

        $tasks = $query
            ->latest('updated_at')
            ->paginate(10)
            ->appends($request->query());

        /*
        |--------------------------------------------------------------------------
        | Dropdown/filter data
        |--------------------------------------------------------------------------
        */

        $filterData = $this->taskReportFilterData($user, $role);

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $statistics = $this->taskStatistics($statsQuery);

        return array_merge(
            [
                'tasks' => $tasks,
            ],
            $filterData,
            $statistics
        );
    }

    /**
     * Apply task visibility rules according to user role.
     */
    protected function applyTaskRoleScope(
        Builder $query,
        User $user,
        string $role
    ): Builder {
        if ($role === 'manager') {
            $query->whereHas('project', function ($q) use ($user) {
                $q->where('manager_id', $user->id);
            });
        } elseif ($role === 'team_leader') {
            $query->whereHas('assignedUser.teams', function ($q) use ($user) {
                $q->where('team_leader_id', $user->id);
            });
        } elseif ($role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        return $query;
    }

    /**
     * Get Task Report dropdown/filter data.
     */
    protected function taskReportFilterData(
        User $user,
        string $role
    ): array {
        if ($role === 'manager') {
            $projects = Project::where(
                'manager_id',
                $user->id
            )->get();

            $employees = User::whereHas(
                'tasks.project',
                function ($q) use ($user) {
                    $q->where(
                        'manager_id',
                        $user->id
                    );
                }
            )
                ->distinct()
                ->get();

            $allTaskTitles = Task::whereHas(
                'project',
                function ($q) use ($user) {
                    $q->where(
                        'manager_id',
                        $user->id
                    );
                }
            )
                ->distinct()
                ->pluck('title');
        } elseif ($role === 'team_leader') {
            $projects = Project::whereHas(
                'teams',
                function ($q) use ($user) {
                    $q->where(
                        'team_leader_id',
                        $user->id
                    );
                }
            )->get();

            $employees = User::whereHas(
                'teams',
                function ($q) use ($user) {
                    $q->where(
                        'team_leader_id',
                        $user->id
                    );
                }
            )->get();

            $allTaskTitles = Task::whereHas(
                'assignedUser.teams',
                function ($q) use ($user) {
                    $q->where(
                        'team_leader_id',
                        $user->id
                    );
                }
            )
                ->distinct()
                ->pluck('title');
        } elseif ($role === 'employee') {
            $projects = Project::whereHas(
                'tasks',
                function ($q) use ($user) {
                    $q->where(
                        'user_id',
                        $user->id
                    );
                }
            )->get();

            $employees = User::where(
                'id',
                $user->id
            )->get();

            $allTaskTitles = Task::where(
                'user_id',
                $user->id
            )
                ->distinct()
                ->pluck('title');
        } else {
            $projects = Project::all();
            $employees = User::all();

            $allTaskTitles = Task::distinct()
                ->pluck('title');
        }

        return [
            'projects' => $projects,
            'employees' => $employees,
            'allTaskTitles' => $allTaskTitles,
        ];
    }

    /**
     * Calculate Task Report statistics.
     */
    protected function taskStatistics(Builder $statsQuery): array
    {
        $today = Carbon::today();

        $totalTasksCount = (clone $statsQuery)->count();

        $completedTasksCount = (clone $statsQuery)
            ->where('status', 'completed')
            ->count();

        $acceptedTasksCount = (clone $statsQuery)
            ->where('status', 'accepted')
            ->count();

        $rejectedTasksCount = (clone $statsQuery)
            ->where('status', 'rejected')
            ->count();

        $inProgressTasksCount = (clone $statsQuery)
            ->where('status', 'in_progress')
            ->where(function ($query) use ($today) {
                $query
                    ->whereNull('due_date')
                    ->orWhereDate('due_date', '>', $today);
            })
            ->count();

        $pendingTasksCount = (clone $statsQuery)
            ->where('status', 'pending')
            ->where(function ($query) use ($today) {
                $query
                    ->whereNull('due_date')
                    ->orWhereDate('due_date', '>', $today);
            })
            ->count();

        $overdueTasksCount = (clone $statsQuery)
            ->whereDate('due_date', '<', $today)
            ->whereNotIn('status', [
                'completed',
                'accepted',
                'rejected',
            ])
            ->count();

        $dueTodayTasksCount = (clone $statsQuery)
            ->whereDate('due_date', $today)
            ->whereNotIn('status', [
                'completed',
                'accepted',
                'rejected',
            ])
            ->count();

        return [
            'totalTasksCount' => $totalTasksCount,
            'completedTasksCount' => $completedTasksCount,
            'acceptedTasksCount' => $acceptedTasksCount,
            'rejectedTasksCount' => $rejectedTasksCount,
            'inProgressTasksCount' => $inProgressTasksCount,
            'pendingTasksCount' => $pendingTasksCount,
            'overdueTasksCount' => $overdueTasksCount,
            'dueTodayTasksCount' => $dueTodayTasksCount,
        ];
    }


    /* =========================================================================
    | PROJECT REPORT
    | ========================================================================= */

    /**
     * Prepare all data required for the Project Report.
     */
    public function projectReportData(
        Request $request,
        User $user
    ): array {
        $role = $this->role($user);

        $selectedStatus = $request->input('status');

        /*
        |--------------------------------------------------------------------------
        | Remove status before Project::reportFilter()
        |--------------------------------------------------------------------------
        |
        | Project::reportFilter() eventually calls filterAndSearch(),
        | which already has its own status handling.
        |
        */

        $filterRequest = clone $request;

        $filterRequest->query->remove('status');
        $filterRequest->request->remove('status');

        /*
        |--------------------------------------------------------------------------
        | Main Project Query
        |--------------------------------------------------------------------------
        */

        $query = Project::with([
            'manager',

            'tasks' => function ($taskQuery) use ($user, $role) {
                if ($role === 'team_leader') {
                    $taskQuery->whereHas(
                        'assignedUser.teams',
                        function ($t) use ($user) {
                            $t->where(
                                'team_leader_id',
                                $user->id
                            );
                        }
                    );
                }

                if ($role === 'employee') {
                    $taskQuery->where(
                        'user_id',
                        $user->id
                    );
                }

                $taskQuery->with('assignedUser');
            },
        ]);

        /*
        |--------------------------------------------------------------------------
        | Project role visibility
        |--------------------------------------------------------------------------
        */

        $this->applyProjectRoleScope(
            $query,
            $user,
            $role
        );

        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        */

        $query
            ->reportFilter($filterRequest)
            ->statusFilter($selectedStatus);

        /*
        |--------------------------------------------------------------------------
        | Task count according to current user
        |--------------------------------------------------------------------------
        */

        $query->withCount([
            'tasks' => function ($taskQuery) use ($user, $role) {
                if ($role === 'team_leader') {
                    $taskQuery->whereHas(
                        'assignedUser.teams',
                        function ($t) use ($user) {
                            $t->where(
                                'team_leader_id',
                                $user->id
                            );
                        }
                    );
                } elseif ($role === 'employee') {
                    $taskQuery->where(
                        'user_id',
                        $user->id
                    );
                }
            },
        ]);

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $projects = $query
            ->paginate(10)
            ->appends($request->query());

        /*
        |--------------------------------------------------------------------------
        | Filter data
        |--------------------------------------------------------------------------
        */

        $filterData = $this->projectReportFilterData(
            $user,
            $role
        );

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $statistics = $this->projectStatistics(
            $user,
            $role
        );

        return array_merge(
            [
                'projects' => $projects,
            ],
            $filterData,
            $statistics
        );
    }

    /**
     * Apply Project visibility according to user role.
     */
    protected function applyProjectRoleScope(
        Builder $query,
        User $user,
        string $role
    ): Builder {
        if ($role === 'manager') {
            $query->where(
                'manager_id',
                $user->id
            );
        } elseif ($role === 'team_leader') {
            $query->whereHas(
                'teams',
                function ($q) use ($user) {
                    $q->where(
                        'team_leader_id',
                        $user->id
                    );
                }
            );
        } elseif ($role === 'employee') {
            $query->whereHas(
                'tasks',
                function ($q) use ($user) {
                    $q->where(
                        'user_id',
                        $user->id
                    );
                }
            );
        } elseif ($role !== 'admin') {
            $query->whereHas(
                'users',
                function ($q) use ($user) {
                    $q->where(
                        'users.id',
                        $user->id
                    );
                }
            );
        }

        return $query;
    }

    /**
     * Get Project Report dropdown/filter data.
     */
    protected function projectReportFilterData(
        User $user,
        string $role
    ): array {
        if ($role === 'manager') {
            $allTitles = Project::where(
                'manager_id',
                $user->id
            )
                ->pluck('title')
                ->unique();

            $managers = User::where(
                'id',
                $user->id
            )->get();
        } elseif ($role === 'team_leader') {
            $allTitles = Project::whereHas(
                'teams',
                fn($t) => $t->where(
                    'team_leader_id',
                    $user->id
                )
            )
                ->pluck('title')
                ->unique();

            $managers = User::whereHas(
                'managedProjects',
                function ($p) use ($user) {
                    $p->whereHas(
                        'teams',
                        function ($t) use ($user) {
                            $t->where(
                                'team_leader_id',
                                $user->id
                            );
                        }
                    );
                }
            )->get();
        } elseif ($role === 'employee') {
            $allTitles = Project::whereHas(
                'tasks',
                fn($t) => $t->where(
                    'user_id',
                    $user->id
                )
            )
                ->pluck('title')
                ->unique();

            $managers = User::whereHas(
                'managedProjects',
                function ($p) use ($user) {
                    $p->whereHas(
                        'tasks',
                        fn($t) => $t->where(
                            'user_id',
                            $user->id
                        )
                    );
                }
            )->get();
        } else {
            $allTitles = Project::pluck('title')->unique();

            $managers = User::where(
                'role',
                'manager'
            )->get();
        }

        return [
            'allTitles' => $allTitles,
            'managers' => $managers,
        ];
    }

    /**
     * Calculate Project Report statistics.
     */
    protected function projectStatistics(
        User $user,
        string $role
    ): array {
        $today = Carbon::today();

        $statsQuery = Project::query();

        $this->applyProjectRoleScope(
            $statsQuery,
            $user,
            $role
        );

        $totalProjects = (clone $statsQuery)->count();

        $completedProjectsCount = (clone $statsQuery)
            ->whereIn(
                'status',
                [
                    'completed',
                    'complete',
                ]
            )
            ->count();

        $inProgressProjectsCount = (clone $statsQuery)
            ->where(
                'status',
                'in_progress'
            )
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')
                    ->orWhereDate(
                        'end_date',
                        '>=',
                        $today
                    );
            })
            ->count();

        $pendingProjectsCount = (clone $statsQuery)
            ->where(
                'status',
                'pending'
            )
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')
                    ->orWhereDate(
                        'end_date',
                        '>',
                        $today
                    );
            })
            ->count();

        $overdueProjectsCount = (clone $statsQuery)
            ->whereNotIn(
                'status',
                [
                    'completed',
                    'complete',
                ]
            )
            ->whereNotNull('end_date')
            ->whereDate(
                'end_date',
                '<',
                $today
            )
            ->count();

        $dueTodayProjectsCount = (clone $statsQuery)
            ->whereNotIn(
                'status',
                [
                    'completed',
                    'complete',
                ]
            )
            ->whereNotNull('end_date')
            ->whereDate(
                'end_date',
                $today
            )
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Total Tasks inside visible projects
        |--------------------------------------------------------------------------
        */

        $totalTasksQuery = Task::whereIn(
            'project_id',
            (clone $statsQuery)->select('id')
        );

        if ($role === 'team_leader') {
            $totalTasksQuery->whereHas(
                'assignedUser.teams',
                function ($q) use ($user) {
                    $q->where(
                        'team_leader_id',
                        $user->id
                    );
                }
            );
        } elseif ($role === 'employee') {
            $totalTasksQuery->where(
                'user_id',
                $user->id
            );
        }

        $totalTasksCount = $totalTasksQuery->count();

        return [
            'totalProjects' => $totalProjects,
            'completedProjectsCount' => $completedProjectsCount,
            'inProgressProjectsCount' => $inProgressProjectsCount,
            'pendingProjectsCount' => $pendingProjectsCount,
            'overdueProjectsCount' => $overdueProjectsCount,
            'dueTodayProjectsCount' => $dueTodayProjectsCount,
            'totalTasksCount' => $totalTasksCount,
        ];
    }


    /* =========================================================================
    | USER REPORT
    | ========================================================================= */

    /**
     * Prepare all data required for User Report.
     */
    public function userReportData(
        Request $request,
        User $user
    ): array {
        $role = $this->role($user);

        /*
        |--------------------------------------------------------------------------
        | Base statistics query
        |--------------------------------------------------------------------------
        */

        $statsQuery = User::query();

        $this->applyUserRoleScope(
            $statsQuery,
            $user,
            $role
        );

        /*
        |--------------------------------------------------------------------------
        | Main users query
        |--------------------------------------------------------------------------
        */

        $query = User::query();

        $this->applyUserRoleScope(
            $query,
            $user,
            $role
        );

        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        |
        | Position is handled separately below because the Position filter
        | displays a normalized value while the database may contain:
        |
        | Front-End1
        | Front-End2
        | Front_End
        |
        | which should all match:
        |
        | Front End
        |
        */

        $query->filter($request->only([
            'search',
            'name',
            'role',
            'department',
            'status',
            'date_from',
            'date_to',
        ]));

        /*
        |--------------------------------------------------------------------------
        | Position Filter
        |--------------------------------------------------------------------------
        */

        $selectedPosition = $request->input('position');

        if (
            $selectedPosition !== null &&
            trim($selectedPosition) !== ''
        ) {
            $normalizedPosition = $this->normalizeTeamName(
                $selectedPosition
            );

            /*
            |--------------------------------------------------------------------------
            | MySQL normalized comparison
            |--------------------------------------------------------------------------
            |
            | 1. LOWER()              => case-insensitive
            | 2. REPLACE("-", " ")     => Front-End -> Front End
            | 3. REPLACE("_", " ")     => Front_End -> Front End
            | 4. REGEXP_REPLACE()      => Front-End1 -> Front-End
            | 5. TRIM()                => remove extra spaces
            |
            */

            $query->whereRaw(
                "TRIM(
                    REGEXP_REPLACE(
                        REPLACE(
                            REPLACE(
                                LOWER(position),
                                '-',
                                ' '
                            ),
                            '_',
                            ' '
                        ),
                        '[0-9]+',
                        ''
                    )
                ) = ?",
                [
                    strtolower($normalizedPosition),
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $users = $query
            ->paginate(10)
            ->appends($request->query());

        /*
        |--------------------------------------------------------------------------
        | Dropdown data
        |--------------------------------------------------------------------------
        */

        $filterData = $this->userReportFilterData(
            $statsQuery,
            $user,
            $role
        );

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $statistics = $this->userStatistics(
            $statsQuery
        );

        return array_merge(
            [
                'users' => $users,
            ],
            $filterData,
            $statistics,
            [
                'statsQuery' => $statsQuery,
            ]
        );
    }

    /**
     * Apply User visibility according to current role.
     */
    protected function applyUserRoleScope(
        Builder $query,
        User $user,
        string $role
    ): Builder {
        if ($role === 'manager') {
            $query->where(function ($q) use ($user) {
                $q->whereHas(
                    'projects',
                    function ($subQ) use ($user) {
                        $subQ->where(
                            'projects.manager_id',
                            $user->id
                        );
                    }
                )
                    ->orWhereHas(
                        'tasks.project',
                        function ($subQ) use ($user) {
                            $subQ->where(
                                'projects.manager_id',
                                $user->id
                            );
                        }
                    )
                    ->orWhereHas(
                        'ledTeams.project',
                        function ($subQ) use ($user) {
                            $subQ->where(
                                'projects.manager_id',
                                $user->id
                            );
                        }
                    )
                    ->orWhereHas(
                        'teams.project',
                        function ($subQ) use ($user) {
                            $subQ->where(
                                'projects.manager_id',
                                $user->id
                            );
                        }
                    )
                    ->orWhereHas(
                        'managedProjects',
                        function ($subQ) use ($user) {
                            $subQ->where(
                                'manager_id',
                                $user->id
                            );
                        }
                    );
            });
        } elseif ($role === 'team_leader') {
            $query->where(function ($q) use ($user) {
                $q->where(
                    'id',
                    $user->id
                )
                    ->orWhereHas(
                        'teams',
                        function ($subQ) use ($user) {
                            $subQ->where(
                                'team_leader_id',
                                $user->id
                            );
                        }
                    )
                    ->orWhereHas(
                        'ledTeams',
                        function ($subQ) use ($user) {
                            $subQ->where(
                                'team_leader_id',
                                $user->id
                            );
                        }
                    )
                    ->orWhereHas(
                        'managedProjects',
                        function ($subQ) use ($user) {
                            $subQ->whereHas(
                                'teams',
                                function ($tSub) use ($user) {
                                    $tSub->where(
                                        'team_leader_id',
                                        $user->id
                                    );
                                }
                            );
                        }
                    )
                    ->orWhereHas(
                        'projects',
                        function ($subQ) use ($user) {
                            $subQ->whereHas(
                                'teams',
                                function ($tSub) use ($user) {
                                    $tSub->where(
                                        'team_leader_id',
                                        $user->id
                                    );
                                }
                            );
                        }
                    );
            });
        } elseif ($role !== 'admin') {
            $query->where(
                'id',
                $user->id
            );
        }

        return $query;
    }

    /**
     * Get User Report filter data.
     */
    protected function userReportFilterData(
        Builder $statsQuery,
        User $user,
        string $role
    ): array {
        $cleanPositionsCallback = function ($positionsQuery) {
            return $positionsQuery
                ->pluck('position')
                ->map(function ($position) {
                    $clean = preg_replace(
                        '/[0-9]+/',
                        '',
                        $position
                    );

                    $clean = strtolower(
                        trim(
                            str_replace(
                                ['-', '_'],
                                ' ',
                                $clean
                            )
                        )
                    );

                    return ucwords(
                        trim($clean)
                    );
                })
                ->unique()
                ->filter()
                ->values();
        };

        if (
            $role === 'manager' ||
            $role === 'team_leader'
        ) {
            $allNames = (clone $statsQuery)
                ->distinct()
                ->pluck('name')
                ->filter()
                ->values();

            $allPositions = $cleanPositionsCallback(
                clone $statsQuery
            );

            $allDepartments = (clone $statsQuery)
                ->distinct()
                ->pluck('department')
                ->filter()
                ->values();
        } else {
            $allNames = User::distinct()
                ->pluck('name')
                ->filter()
                ->values();

            $allPositions = $cleanPositionsCallback(
                User::query()
            );

            $allDepartments = User::distinct()
                ->pluck('department')
                ->filter()
                ->values();
        }

        return [
            'allNames' => $allNames,
            'allPositions' => $allPositions,
            'allDepartments' => $allDepartments,
        ];
    }

    /**
     * Calculate User Report statistics.
     */
    protected function userStatistics(
        Builder $statsQuery
    ): array {
        $totalUsersCount = (clone $statsQuery)
            ->count();

        $activeUsersCount = (clone $statsQuery)
            ->where(
                'status',
                'active'
            )
            ->count();

        $inactiveUsersCount = (clone $statsQuery)
            ->where(
                'status',
                '!=',
                'active'
            )
            ->count();

        return [
            'totalUsersCount' => $totalUsersCount,
            'activeUsersCount' => $activeUsersCount,
            'inactiveUsersCount' => $inactiveUsersCount,
        ];
    }


    /* =========================================================================
    | TEAM REPORT
    | ========================================================================= */

    /**
     * Prepare all data required for Team Report.
     */
    public function teamReportData(
        Request $request,
        User $user
    ): array {
        $role = $this->role($user);

        /*
        |--------------------------------------------------------------------------
        | Base statistics query
        |--------------------------------------------------------------------------
        */

        $statsQuery = Team::query();

        $this->applyTeamRoleScope(
            $statsQuery,
            $user,
            $role
        );

        /*
        |--------------------------------------------------------------------------
        | Main query
        |--------------------------------------------------------------------------
        */

        $query = Team::with([
            'leader',
            'members',
            'project',
        ]);

        $this->applyTeamRoleScope(
            $query,
            $user,
            $role
        );

        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        */

        $query->filter($request->only([
            'project_id',
            'team_leader_id',
        ]));

        /*
        |--------------------------------------------------------------------------
        | Team Name Filter
        |--------------------------------------------------------------------------
        |
        | Team name is handled separately below because the Team filter must
        | compare the normalized team name rather than the raw database name.
        |
        */

        $selectedTeamName = $request->input('team_name');

        if (
            $selectedTeamName !== null &&
            trim($selectedTeamName) !== ''
        ) {
            $normalizedTeamName = $this->normalizeTeamName(
                $selectedTeamName
            );

            /*
            |--------------------------------------------------------------------------
            | MySQL normalized comparison
            |--------------------------------------------------------------------------
            |
            | 1. LOWER()              => case-insensitive
            | 2. REPLACE("-", " ")     => Front-End -> Front End
            | 3. REPLACE("_", " ")     => Front_End -> Front End
            | 4. REGEXP_REPLACE()      => Front-End1 -> Front-End
            | 5. TRIM()                => remove extra spaces
            |
            */

            $query->whereRaw(
                "TRIM(
                    REGEXP_REPLACE(
                        REPLACE(
                            REPLACE(
                                LOWER(name),
                                '-',
                                ' '
                            ),
                            '_',
                            ' '
                        ),
                        '[0-9]+',
                        ''
                    )
                ) = ?",
                [
                    strtolower($normalizedTeamName),
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $teams = $query
            ->paginate(10)
            ->appends($request->query());

        /*
        |--------------------------------------------------------------------------
        | Filter data
        |--------------------------------------------------------------------------
        */

        $filterData = $this->teamReportFilterData(
            $user,
            $role
        );

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $statistics = $this->teamStatistics(
            $statsQuery
        );

        return array_merge(
            [
                'teams' => $teams,
            ],
            $filterData,
            $statistics,
            [
                'statsQuery' => $statsQuery,
            ]
        );
    }

    /**
     * Apply Team visibility according to current role.
     */
    protected function applyTeamRoleScope(
        Builder $query,
        User $user,
        string $role
    ): Builder {
        if ($role === 'manager') {
            $query->whereHas(
                'project',
                function ($q) use ($user) {
                    $q->where(
                        'manager_id',
                        $user->id
                    );
                }
            );
        } elseif ($role === 'team_leader') {
            $query->where(
                'team_leader_id',
                $user->id
            );
        } elseif ($role === 'employee') {
            $query->whereHas(
                'members',
                function ($q) use ($user) {
                    $q->where(
                        'users.id',
                        $user->id
                    );
                }
            );
        } elseif ($role !== 'admin') {
            $query->where(function ($q) use ($user) {
                $q->where(
                    'team_leader_id',
                    $user->id
                )
                    ->orWhereHas(
                        'members',
                        fn($subQ) => $subQ->where(
                            'users.id',
                            $user->id
                        )
                    );
            });
        }

        return $query;
    }

    /**
     * Get Team Report filter data.
     */
    protected function teamReportFilterData(
        User $user,
        string $role
    ): array {
        /*
        |--------------------------------------------------------------------------
        | Normalize Team names for display.
        |--------------------------------------------------------------------------
        |
        | Examples:
        |
        | Front-End1 -> Front End
        | Front-End2 -> Front End
        | Front_End  -> Front End
        |
        | The important point is that unique() is applied AFTER normalization.
        | Therefore the filter button appears only once.
        |
        */

        $cleanTeamNamesCallback = function ($teamsQuery) {
            return $teamsQuery
                ->pluck('name')
                ->map(function ($teamName) {
                    return $this->normalizeTeamName($teamName);
                })
                ->filter()
                ->unique()
                ->sort()
                ->values();
        };

        if ($role === 'manager') {
            $rawTeamsQuery = Team::whereHas(
                'project',
                function ($q) use ($user) {
                    $q->where(
                        'manager_id',
                        $user->id
                    );
                }
            );

            $allTeamNames = $cleanTeamNamesCallback(
                $rawTeamsQuery
            );

            $projects = Project::where(
                'manager_id',
                $user->id
            )->get();

            $leaders = User::whereHas(
                'ledTeams.project',
                function ($q) use ($user) {
                    $q->where(
                        'manager_id',
                        $user->id
                    );
                }
            )
                ->distinct()
                ->get();
        } elseif ($role === 'team_leader') {
            $rawTeamsQuery = Team::where(
                'team_leader_id',
                $user->id
            );

            $allTeamNames = $cleanTeamNamesCallback(
                $rawTeamsQuery
            );

            $projects = Project::whereHas(
                'teams',
                function ($q) use ($user) {
                    $q->where(
                        'team_leader_id',
                        $user->id
                    );
                }
            )->get();

            $leaders = User::where(
                'id',
                $user->id
            )->get();
        } elseif ($role === 'employee') {
            $rawTeamsQuery = Team::whereHas(
                'members',
                function ($q) use ($user) {
                    $q->where(
                        'users.id',
                        $user->id
                    );
                }
            );

            $allTeamNames = $cleanTeamNamesCallback(
                $rawTeamsQuery
            );

            $projects = Project::whereHas(
                'teams.members',
                function ($q) use ($user) {
                    $q->where(
                        'users.id',
                        $user->id
                    );
                }
            )->get();

            $leaders = User::whereHas(
                'ledTeams.members',
                function ($q) use ($user) {
                    $q->where(
                        'users.id',
                        $user->id
                    );
                }
            )
                ->distinct()
                ->get();
        } else {
            /*
            |--------------------------------------------------------------------------
            | ADMIN
            |--------------------------------------------------------------------------
            |
            | Admin can see every team.
            |
            | Team names are normalized before unique(), so similar names are
            | displayed as one filter button.
            |
            */

            $allTeamNames = $cleanTeamNamesCallback(
                Team::query()
            );

            $projects = Project::all();

            /*
            |--------------------------------------------------------------------------
            | Only actual Team Leaders
            |--------------------------------------------------------------------------
            */

            $leaders = User::whereHas(
                'ledTeams'
            )
                ->distinct()
                ->get();
        }

        return [
            'allTeamNames' => $allTeamNames,
            'projects' => $projects,
            'leaders' => $leaders,
        ];
    }

    /**
     * Normalize Team name for display/filtering.
     *
     * Examples:
     * Front-End1  -> Front End
     * Front-End2  -> Front End
     * Front_End   -> Front End
     * FULL STACK  -> Full Stack
     */
    protected function normalizeTeamName(?string $teamName): string
    {
        if ($teamName === null) {
            return '';
        }

        $clean = preg_replace(
            '/[0-9]+/',
            '',
            $teamName
        );

        $clean = str_replace(
            ['-', '_'],
            ' ',
            $clean
        );

        $clean = strtolower(
            trim($clean)
        );

        return ucwords(
            preg_replace(
                '/\s+/',
                ' ',
                $clean
            )
        );
    }

    /**
     * Calculate Team Report statistics.
     */
    protected function teamStatistics(
        Builder $statsQuery
    ): array {
        $totalTeamsCount = (clone $statsQuery)
            ->count();

        $totalTeamMembersCount = User::whereHas(
            'teams',
            function ($q) use ($statsQuery) {
                $q->whereIn(
                    'teams.id',
                    (clone $statsQuery)->select('id')
                );
            }
        )->count();

        return [
            'totalTeamsCount' => $totalTeamsCount,
            'totalTeamMembersCount' => $totalTeamMembersCount,
        ];
    }


    /* =========================================================================
    | HELPERS
    | ========================================================================= */

    /**
     * Normalize user role.
     */
    protected function role(User $user): string
    {
        return strtolower(
            trim(
                $user->role ?? ''
            )
        );
    }
}
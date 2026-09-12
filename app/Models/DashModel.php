<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashModel extends Model
{
    public static function getDashboardStats($user)
    {
        $role = strtolower(trim($user->role ?? ''));

        $taskStats = self::getTaskStats($user, $role);

        $totalProjects = self::getTotalProjects($user, $role);

        $userStats = self::getUserStats($user, $role);

        return [
            'totalProjects'    => $totalProjects,

            'totalTasks'       => $taskStats->total_tasks ?? 0,

            'totalEmployees'   => $userStats['totalEmployees'],
            'totalManagers'    => $userStats['totalManagers'],
            'totalTeamLeaders' => $userStats['totalTeamLeaders'],
            'totalTeams'       => $userStats['totalTeams'],

            'pendingTasks'     => $taskStats->pending_count ?? 0,
            'inProgressTasks'  => $taskStats->in_progress_count ?? 0,
            'completedTasks'   => $taskStats->completed_count ?? 0,
            'acceptedTasks'   => $taskStats->accepted_count ?? 0,
            'rejectedTasks'   => $taskStats->rejected_count ?? 0,

            'overdueTasks'     => $taskStats->overdue_count ?? 0,
            'dueTodayTasks'    => $taskStats->due_today_count ?? 0,
        ];
    }


    protected static function getTaskStats($user, $role)
    {
        $taskQuery = Task::query();

        // ================================================================
        // ROLE-BASED TASK SCOPE
        // ================================================================

        if ($role === 'employee') {

            $taskQuery->where('user_id', $user->id);
        } elseif ($role === 'team_leader') {

            $taskQuery->where(function ($q) use ($user) {

                $q->where('user_id', $user->id)

                    ->orWhereHas('assignedUser', function ($subQ) use ($user) {

                        $subQ->whereHas('teams', function ($t) use ($user) {
                            $t->where('team_leader_id', $user->id);
                        })

                            ->orWhereHas('ledTeams', function ($t) use ($user) {
                                $t->where('team_leader_id', $user->id);
                            });
                    });
            });
        } elseif ($role === 'manager') {

            $taskQuery->whereHas('project', function ($subQuery) use ($user) {
                $subQuery->where('manager_id', $user->id);
            });
        }


        // ================================================================
        // DYNAMIC TASK STATUS
        //
        // completed / accepted / rejected always keep their real status.
        //
        // pending / in_progress become:
        // overdue   -> when due_date is before today
        // due_today -> when due_date is today
        //
        // Otherwise their original status remains.
        // ================================================================

        $today = Carbon::today()->toDateString();

        return $taskQuery

            ->selectRaw('COUNT(*) as total_tasks')

            // ------------------------------------------------------------
            // PENDING
            // Only tasks whose real status is pending and are not due
            // today or overdue.
            // ------------------------------------------------------------

            ->selectRaw(
                "SUM(
                    CASE
                        WHEN status = 'pending'
                        AND (
                            due_date IS NULL
                            OR DATE(due_date) > ?
                        )
                        THEN 1
                        ELSE 0
                    END
                ) as pending_count",
                [$today]
            )

            // ------------------------------------------------------------
            // IN PROGRESS
            // Only tasks whose real status is in_progress and are not
            // due today or overdue.
            // ------------------------------------------------------------

            ->selectRaw(
                "SUM(
                    CASE
                        WHEN status = 'in_progress'
                        AND (
                            due_date IS NULL
                            OR DATE(due_date) > ?
                        )
                        THEN 1
                        ELSE 0
                    END
                ) as in_progress_count",
                [$today]
            )

            // ------------------------------------------------------------
            // COMPLETED
            // ------------------------------------------------------------

            ->selectRaw(
                "SUM(
                    CASE
                        WHEN status = 'completed'
                        THEN 1
                        ELSE 0
                    END
                ) as completed_count"
            )

            // ------------------------------------------------------------
            // ACCEPTED
            // ------------------------------------------------------------

            ->selectRaw(
                "SUM(
                    CASE
                        WHEN status = 'accepted'
                        THEN 1
                        ELSE 0
                    END
                ) as accepted_count"
            )

            // ------------------------------------------------------------
            // REJECTED
            // ------------------------------------------------------------

            ->selectRaw(
                "SUM(
                    CASE
                        WHEN status = 'rejected'
                        THEN 1
                        ELSE 0
                    END
                ) as rejected_count"
            )

            // ------------------------------------------------------------
            // OVERDUE
            //
            // Accepted, rejected and completed are NEVER overdue.
            // ------------------------------------------------------------

            ->selectRaw(
                "SUM(
                    CASE
                        WHEN due_date IS NOT NULL
                        AND DATE(due_date) < ?
                        AND status NOT IN (
                            'completed',
                            'accepted',
                            'rejected'
                        )
                        THEN 1
                        ELSE 0
                    END
                ) as overdue_count",
                [$today]
            )

            // ------------------------------------------------------------
            // DUE TODAY
            //
            // Accepted, rejected and completed are NEVER due_today.
            // ------------------------------------------------------------

            ->selectRaw(
                "SUM(
                    CASE
                        WHEN due_date IS NOT NULL
                        AND DATE(due_date) = ?
                        AND status NOT IN (
                            'completed',
                            'accepted',
                            'rejected'
                        )
                        THEN 1
                        ELSE 0
                    END
                ) as due_today_count",
                [$today]
            )

            ->first();
    }


    protected static function getTotalProjects($user, $role)
    {
        $projectQuery = Project::query();

        if ($role === 'employee') {

            $projectQuery->where(function ($q) use ($user) {

                $q->whereHas('tasks', function ($sub) use ($user) {
                    $sub->where('user_id', $user->id);
                })

                    ->orWhereHas('teams.members', function ($sub) use ($user) {
                        $sub->where('users.id', $user->id);
                    });
            });
        } elseif ($role === 'team_leader') {

            $projectQuery->whereHas('teams', function ($sub) use ($user) {
                $sub->where('team_leader_id', $user->id);
            });
        } elseif ($role === 'manager') {

            $projectQuery->where('manager_id', $user->id);
        }

        return $projectQuery->count();
    }


    protected static function getUserStats($user, $role)
    {
        if ($role === 'admin') {

            return [
                'totalEmployees'   => User::where('role', 'employee')->count(),
                'totalManagers'    => User::where('role', 'manager')->count(),
                'totalTeamLeaders' => User::where('role', 'team_leader')->count(),
                'totalTeams'       => DB::table('teams')->count(),
            ];
        }


        if ($role === 'manager') {

            $managerUsersQuery = User::where(function ($q) use ($user) {

                $q->whereHas('projects', function ($sq) use ($user) {
                    $sq->where('projects.manager_id', $user->id);
                })

                    ->orWhereHas('tasks.project', function ($sq) use ($user) {
                        $sq->where('projects.manager_id', $user->id);
                    })

                    ->orWhereHas('ledTeams.project', function ($sq) use ($user) {
                        $sq->where('projects.manager_id', $user->id);
                    })

                    ->orWhereHas('teams.project', function ($sq) use ($user) {
                        $sq->where('projects.manager_id', $user->id);
                    })

                    ->orWhereHas('managedProjects', function ($sq) use ($user) {
                        $sq->where('manager_id', $user->id);
                    });
            });


            return [
                'totalEmployees' => (clone $managerUsersQuery)
                    ->where('role', 'employee')
                    ->count(),

                'totalManagers' => (clone $managerUsersQuery)
                    ->where('role', 'manager')
                    ->count(),

                'totalTeamLeaders' => (clone $managerUsersQuery)
                    ->where('role', 'team_leader')
                    ->count(),

                'totalTeams' => DB::table('teams')
                    ->join(
                        'projects',
                        'teams.project_id',
                        '=',
                        'projects.id'
                    )
                    ->where(
                        'projects.manager_id',
                        $user->id
                    )
                    ->count(),
            ];
        }


        if ($role === 'team_leader') {

            $teamLeaderUsersQuery = User::where(function ($q) use ($user) {

                $q->where('id', $user->id)

                    ->orWhereHas('teams', function ($sq) use ($user) {
                        $sq->where(
                            'team_leader_id',
                            $user->id
                        );
                    })

                    ->orWhereHas('ledTeams', function ($sq) use ($user) {
                        $sq->where(
                            'team_leader_id',
                            $user->id
                        );
                    })

                    ->orWhere('id', function ($mSub) use ($user) {

                        $mSub->select('projects.manager_id')
                            ->from('teams')
                            ->join(
                                'projects',
                                'teams.project_id',
                                '=',
                                'projects.id'
                            )
                            ->where(
                                'teams.team_leader_id',
                                $user->id
                            )
                            ->limit(1);
                    });
            });


            return [
                'totalEmployees' => (clone $teamLeaderUsersQuery)
                    ->where('role', 'employee')
                    ->count(),

                'totalManagers' => (clone $teamLeaderUsersQuery)
                    ->where('role', 'manager')
                    ->count(),

                'totalTeamLeaders' => (clone $teamLeaderUsersQuery)
                    ->where('role', 'team_leader')
                    ->count(),

                'totalTeams' => DB::table('teams')
                    ->where(
                        'team_leader_id',
                        $user->id
                    )
                    ->count(),
            ];
        }


        if ($role === 'employee') {

            $totalEmployees = User::where('role', 'employee')
                ->where(function ($q) use ($user) {

                    $q->where('id', $user->id)

                        ->orWhereHas('teams', function ($sub) use ($user) {

                            $sub->whereIn(
                                'teams.id',
                                function ($tSub) use ($user) {

                                    $tSub->select('team_id')
                                        ->from('team_user')
                                        ->where(
                                            'user_id',
                                            $user->id
                                        );
                                }
                            );
                        })

                        ->orWhereHas('tasks', function ($sub) use ($user) {

                            $sub->where('user_id', $user->id);
                        });
                })
                ->distinct()
                ->count();


            $totalManagers = User::where('role', 'manager')
                ->where(function ($q) use ($user) {

                    $q->whereHas(
                        'managedProjects',
                        function ($sub) use ($user) {

                            $sub->whereHas(
                                'tasks',
                                function ($t) use ($user) {
                                    $t->where(
                                        'user_id',
                                        $user->id
                                    );
                                }
                            )

                                ->orWhereHas(
                                    'users',
                                    function ($u) use ($user) {
                                        $u->where(
                                            'users.id',
                                            $user->id
                                        );
                                    }
                                )

                                ->orWhereHas(
                                    'teams.members',
                                    function ($tm) use ($user) {
                                        $tm->where(
                                            'users.id',
                                            $user->id
                                        );
                                    }
                                );
                        }
                    )

                        ->orWhere('id', function ($mSub) use ($user) {

                            $mSub->select('projects.manager_id')
                                ->from('teams')
                                ->join(
                                    'team_user',
                                    'teams.id',
                                    '=',
                                    'team_user.team_id'
                                )
                                ->join(
                                    'projects',
                                    'teams.project_id',
                                    '=',
                                    'projects.id'
                                )
                                ->where(
                                    'team_user.user_id',
                                    $user->id
                                );
                        });
                })
                ->distinct()
                ->count();


            $totalTeamLeaders = User::where(
                'role',
                'team_leader'
            )
                ->whereHas(
                    'ledTeams.members',
                    function ($q) use ($user) {
                        $q->where(
                            'users.id',
                            $user->id
                        );
                    }
                )
                ->count();


            $totalTeams = DB::table('team_user')
                ->where(
                    'user_id',
                    $user->id
                )
                ->count();


            return [
                'totalEmployees'   => $totalEmployees,
                'totalManagers'    => $totalManagers,
                'totalTeamLeaders' => $totalTeamLeaders,
                'totalTeams'       => $totalTeams,
            ];
        }


        return [
            'totalEmployees'   => 0,
            'totalManagers'    => 0,
            'totalTeamLeaders' => 0,
            'totalTeams'       => 0,
        ];
    }
}
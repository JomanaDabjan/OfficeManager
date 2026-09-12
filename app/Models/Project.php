<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'budget',
        'manager_id',
        'team_id',
        'team_leader_id',
        'status',
        'start_date',
        'end_date'
    ];

    /* ==========================================================================
    | RELATIONSHIPS
    |========================================================================== */

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'project_user')
            ->withPivot('role');
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /* ==========================================================================
    | SCOPE: PROJECTS AVAILABLE FOR CURRENT USER
    |========================================================================== */

    /**
     * Filter projects according to the authenticated user's role.
     *
     * Manager:
     *      Sees projects managed by himself.
     *
     * Team Leader:
     *      Sees projects containing teams led by himself.
     *
     * Employee:
     *      Sees projects belonging to teams where he is a member
     *      OR projects containing teams led by himself.
     *
     * Admin:
     *      Can see all projects.
     *
     * Unknown role:
     *      Returns no projects.
     */
    public function scopeForUser(Builder $query, $user): Builder
    {
        $role = strtolower(trim($user->role ?? ''));

        if ($role === 'manager') {

            $query->where('manager_id', $user->id);
        } elseif ($role === 'team_leader') {

            $query->whereHas('teams', function ($teamQuery) use ($user) {

                $teamQuery->where('team_leader_id', $user->id);
            });
        } elseif ($role === 'employee') {

            $query->where(function ($subQ) use ($user) {

                $subQ->whereHas('teams.members', function ($memberQuery) use ($user) {

                    $memberQuery->where('users.id', $user->id);
                })->orWhereHas('teams', function ($teamQuery) use ($user) {

                    $teamQuery->where('team_leader_id', $user->id);
                });
            });
        } elseif ($role !== 'admin') {

            $query->whereRaw('1 = 0');
        }

        return $query;
    }

    /* ==========================================================================
    | PROJECTS AVAILABLE FOR TEAM CONTROLLER
    |========================================================================== */

    public static function availableFor($user)
    {
        $role = strtolower(trim($user->role ?? ''));

        return self::query()
            ->when($role === 'manager', function ($query) use ($user) {
                $query->where('manager_id', $user->id);
            })
            ->when($role === 'team_leader' || $role === 'employee', function ($query) use ($user) {
                $query->whereHas('teams', function ($teamQuery) use ($user) {
                    $teamQuery->where('team_leader_id', $user->id)
                        ->orWhereHas('members', function ($memberQuery) use ($user) {
                            $memberQuery->where('users.id', $user->id);
                        });
                });
            });
    }

    /* ==========================================================================
    | AVAILABLE PROJECTS FOR TASK CONTROLLER
    |========================================================================== */

    public static function availableProjectsFor($user)
    {
        $role = strtolower(trim($user->role ?? ''));

        return Project::query()
            ->when($role === 'manager', function ($query) use ($user) {
                $query->where('manager_id', $user->id);
            })
            ->when($role === 'team_leader', function ($query) use ($user) {
                $query->whereHas('teams', function ($query) use ($user) {
                    $query->where('team_leader_id', $user->id);
                });
            })
            ->get();
    }

    /* ==========================================================================
    | SCOPE: PROJECTS RELATED TO A SPECIFIC USER
    |========================================================================== */

    /**
     * Filter projects related to a specific user.
     *
     * A project is considered related to the user when:
     *
     * 1. The user is the project manager
     * OR
     * 2. The user is a member of one of the project's teams
     * OR
     * 3. The user is the leader of one of the project's teams.
     */
    public function scopeForSpecificUser(Builder $query, $userId): Builder
    {
        return $query->where(function ($subQ) use ($userId) {

            $subQ->where('manager_id', $userId)

                ->orWhereHas('teams.members', function ($memberQuery) use ($userId) {

                    $memberQuery->where('users.id', $userId);
                })

                ->orWhereHas('teams', function ($teamQuery) use ($userId) {

                    $teamQuery->where('team_leader_id', $userId);
                });
        });
    }

    /* ==========================================================================
    | SCOPE: FILTER AND SEARCH
    |========================================================================== */

    /**
     * Main report filtering and searching scope.
     *
     * This scope keeps the original filtering behavior while delegating
     * user-access filtering to scopeForUser().
     */
    public function scopeFilterAndSearch(
        Builder $query,
        $user,
        Request $request
    ): Builder {

        $query->forUser($user);

        /* -----------------------------------------------------------------
        | FILTER BY SPECIFIC USER
        |----------------------------------------------------------------- */

        if ($request->filled('user_id') && $request->user_id !== 'all') {

            $query->forSpecificUser($request->user_id);
        }

        if ($request->filled('title') && $request->title !== 'all') {

            $query->where('title', $request->title);
        }

        if ($request->filled('manager_id') && $request->manager_id !== 'all') {

            $query->where('manager_id', $request->manager_id);
        }

        if ($request->filled('status') && $request->status !== 'all') {

            $status = $request->status;

            if ($status === 'active') {

                $query->whereIn(
                    'status',
                    ['pending', 'in_progress']
                );
            } elseif ($status === 'overdue') {

                $query->whereNotIn(
                    'status',
                    ['completed', 'complete']
                )
                    ->whereDate('end_date', '<', Carbon::today())

                    ->where(function ($q) {

                        $q->whereDoesntHave('tasks')

                            ->orWhereHas('tasks', function ($tQuery) {

                                $tQuery->whereNotIn(
                                    'status',
                                    ['complete', 'completed']
                                );
                            });
                    });
            } elseif ($status === 'due_today') {

                $query->whereNotIn(
                    'status',
                    ['completed', 'complete']
                )
                    ->whereDate('end_date', '=', Carbon::today())

                    ->where(function ($q) {

                        $q->whereDoesntHave('tasks')

                            ->orWhereHas('tasks', function ($tQuery) {

                                $tQuery->whereNotIn(
                                    'status',
                                    ['complete', 'completed']
                                );
                            });
                    });
            } elseif ($status === 'completed') {

                $query->whereIn(
                    'status',
                    ['completed', 'complete']
                );
            } else {

                $query->where('status', $status)

                    ->where(function ($q) {

                        $q->whereNull('end_date')

                            ->orWhereDate(
                                'end_date',
                                '>',
                                Carbon::today()
                            );
                    });
            }
        }

        if ($request->filled('price') && $request->price !== 'all') {

            if ($request->price === 'low') {

                $query->where('budget', '<', 1000);
            } elseif ($request->price === 'medium') {

                $query->whereBetween(
                    'budget',
                    [1000, 5000]
                );
            } elseif ($request->price === 'high') {

                $query->where('budget', '>', 5000);
            }
        }

        // -----------------------------------------------------------------
        // FILTER BY CREATED DATE RANGE (DATE FROM & DATE TO)
        // Supports both directions: older -> newer OR newer -> older
        // -----------------------------------------------------------------
        $dateFrom = $request->input('date_from');
        $dateTo   = $request->input('date_to');

        if ($dateFrom && $dateTo) {

            $startDate = min($dateFrom, $dateTo);
            $endDate   = max($dateFrom, $dateTo);

            $query->whereDate(
                'created_at',
                '>=',
                $startDate
            )->whereDate(
                'created_at',
                '<=',
                $endDate
            );
        } elseif ($dateFrom) {

            $query->whereDate(
                'created_at',
                '>=',
                $dateFrom
            );
        } elseif ($dateTo) {

            $query->whereDate(
                'created_at',
                '<=',
                $dateTo
            );
        }

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'title',
                    'like',
                    "%{$search}%"
                )

                    ->orWhere(
                        'description',
                        'like',
                        "%{$search}%"
                    );
            });
        }

        return $query;
    }

    /* ==========================================================================
    | AVAILABLE PROJECT TITLES
    |========================================================================== */

    /**
     * Get unique project titles available to the given user.
     *
     * This replaces the complex $allTitles query
     * previously located inside the Controller.
     */
    public static function availableTitlesFor($user): Collection
    {
        return static::query()
            ->forUser($user)
            ->select('title')
            ->whereNotNull('title')
            ->distinct()
            ->orderBy('title')
            ->pluck('title');
    }

    /* ==========================================================================
    | AVAILABLE PROJECT MANAGERS
    |========================================================================== */

    /**
     * Get project managers that are relevant to the given user.
     *
     * Admin:
     *      All users who manage projects.
     *
     * Manager:
     *      Himself only.
     *
     * Team Leader:
     *      Managers of projects containing teams led by him.
     *
     * Employee:
     *      Managers of projects belonging to his teams.
     */
    public static function availableManagersFor($user): Collection
    {
        $role = strtolower(trim($user->role ?? ''));

        if ($role === 'admin') {

            return User::whereHas('managedProjects')
                ->select('id', 'name')
                ->distinct()
                ->orderBy('name')
                ->get();
        }

        if ($role === 'manager') {

            return User::where('id', $user->id)
                ->whereHas('managedProjects')
                ->select('id', 'name')
                ->distinct()
                ->orderBy('name')
                ->get();
        }

        if ($role === 'team_leader') {

            return User::whereHas('managedProjects', function ($projectQuery) use ($user) {

                $projectQuery->whereHas('teams', function ($teamQuery) use ($user) {

                    $teamQuery->where(
                        'team_leader_id',
                        $user->id
                    );
                });
            })
                ->select('id', 'name')
                ->distinct()
                ->orderBy('name')
                ->get();
        }

        if ($role === 'employee') {

            return User::whereHas('managedProjects', function ($projectQuery) use ($user) {

                $projectQuery->where(function ($projectSubQuery) use ($user) {

                    $projectSubQuery

                        ->whereHas('teams.members', function ($memberQuery) use ($user) {

                            $memberQuery->where(
                                'users.id',
                                $user->id
                            );
                        })

                        ->orWhereHas('teams', function ($teamQuery) use ($user) {

                            $teamQuery->where(
                                'team_leader_id',
                                $user->id
                            );
                        });
                });
            })
                ->select('id', 'name')
                ->distinct()
                ->orderBy('name')
                ->get();
        }

        return collect();
    }

    /* ==========================================================================
    | REPORT FILTER SCOPE
    |========================================================================== */

    /**
     * Local Scope for Report Filtering.
     */
    public function scopeReportFilter(
        Builder $query,
        Request $request
    ): Builder {

        $user = auth()->user();

        return $query->filterAndSearch(
            $user,
            $request
        );
    }

    /* ==========================================================================
    | STATUS FILTER
    |========================================================================== */

    /**
     * Local Scope to filter projects dynamically by status based on real-time dates.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStatusFilter(
        Builder $query,
        ?string $status
    ): Builder {

        if (!$status || $status === 'all') {

            return $query;
        }

        return match ($status) {

            'active' => $query->whereIn(
                'status',
                ['pending', 'in_progress']
            ),

            'completed' => $query->whereIn(
                'status',
                ['completed', 'complete']
            ),

            'overdue' => $query
                ->whereNotIn(
                    'status',
                    ['completed', 'complete']
                )
                ->whereNotNull('end_date')
                ->whereDate(
                    'end_date',
                    '<',
                    today()
                ),

            'due_today' => $query
                ->whereNotIn(
                    'status',
                    ['completed', 'complete']
                )
                ->whereNotNull('end_date')
                ->whereDate(
                    'end_date',
                    '=',
                    today()
                ),

            'in_progress' => $query
                ->where(
                    'status',
                    'in_progress'
                )
                ->where(function ($q) {

                    $q->whereNull('end_date')
                        ->orWhereDate(
                            'end_date',
                            '>=',
                            today()
                        );
                }),

            'pending' => $query
                ->where(
                    'status',
                    'pending'
                )
                ->where(function ($q) {

                    $q->whereNull('end_date')
                        ->orWhereDate(
                            'end_date',
                            '>',
                            today()
                        );
                }),

            default => $query,
        };
    }
}
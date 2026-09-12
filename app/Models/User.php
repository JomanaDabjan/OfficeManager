<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
//use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

// =========================================================================
// MAIN USER MODEL CLASS DEFINITION
// =========================================================================

/**
 * The User Model represents the 'users' table in the database.
 * It extends Laravel's Authenticatable class to handle user authentication,
 * relationships, and custom query scopes.
 */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    // =========================================================================
    // MASS ASSIGNMENT PROTECTION (FILLABLE ATTRIBUTES)
    // =========================================================================
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'position',
        'department',
        'status',
        'working_hours',
        'joining_date',
        'team_id',
    ];

    // =========================================================================
    // HIDDEN ATTRIBUTES FOR SERIALIZATION
    // =========================================================================
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // =========================================================================
    // ATTRIBUTE CASTING CONFIGURATION
    // =========================================================================
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // =========================================================================
    // ELOQUENT RELATIONSHIPS SECTION
    // =========================================================================

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function managedProjects()
    {
        return $this->hasMany(Project::class, 'manager_id');
    }

    public function ledTeams()
    {
        return $this->hasMany(Team::class, 'team_leader_id');
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_user');
    }

    // =========================================================================
    // AVAILABLE TEAM LEADERS
    // =========================================================================

    public static function availableTeamLeadersFor($user)
    {
        $role = strtolower(trim($user->role ?? ''));

        return self::query()
            ->when($role === 'manager', function ($query) use ($user) {
                $query->whereHas('ledTeams.project', function ($projectQuery) use ($user) {
                    $projectQuery->where('manager_id', $user->id);
                });
            })
            ->when($role === 'team_leader', function ($query) use ($user) {
                $query->where('id', $user->id);
            })
            ->when($role === 'employee', function ($query) use ($user) {
                $query->whereHas('ledTeams', function ($teamQuery) use ($user) {
                    $teamQuery->whereHas('members', function ($memberQuery) use ($user) {
                        $memberQuery->where('users.id', $user->id);
                    });
                });
            }, function ($query) {
                $query->whereHas('ledTeams');
            });
    }

    // =========================================================================
    // AVAILABLE EMPLOYEES
    // =========================================================================

    public static function availableUsersFor($user)
    {
        $role = strtolower(trim($user->role ?? ''));

        return self::query()
            ->where('role', 'employee')
            ->when($role === 'manager', function ($query) use ($user) {
                $query->whereHas('teams.project', function ($query) use ($user) {
                    $query->where('manager_id', $user->id);
                });
            })
            ->when($role === 'team_leader', function ($query) use ($user) {
                $query->whereHas('teams', function ($query) use ($user) {
                    $query->where('team_leader_id', $user->id);
                });
            })
            ->when($role === 'employee', function ($query) use ($user) {
                $query->where('id', $user->id);
            })
            ->get();
    }

    // =========================================================================
    // USERS VISIBLE TO THE CURRENT USER
    // =========================================================================

    public function scopeVisibleTo(Builder $query, $user): Builder
    {
        $role = strtolower(trim($user->role ?? ''));

        if ($role === 'manager') {
            $query->where(function ($sub) use ($user) {
                $sub->whereHas('tasks.project', function ($sq) use ($user) {
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
        } elseif ($role === 'team_leader') {
            // 1. جلب معرفات المشاريع التي يتبع لها قائد الفريق من خلال فرقه
            $projectIds = DB::table('teams')
                ->where('team_leader_id', $user->id)
                ->pluck('project_id')
                ->filter()
                ->unique()
                ->toArray();

            // 2. جلب معرفات (IDs) مديري المشاريع الخاصة بتلك المشاريع
            $managerIds = DB::table('projects')
                ->whereIn('id', $projectIds)
                ->whereNotNull('manager_id')
                ->pluck('manager_id')
                ->unique()
                ->toArray();

            $query->where(function ($sub) use ($user, $managerIds) {
                $sub->whereHas('teams', function ($sq) use ($user) {
                    $sq->where('teams.team_leader_id', $user->id);
                })
                    ->orWhereHas('ledTeams', function ($sq) use ($user) {
                        $sq->where('team_leader_id', $user->id);
                    })
                    // 3. إضافة مديري المشاريع إلى النتائج المرئية لقائد الفريق
                    ->orWhereIn('id', $managerIds);
            });
        } elseif ($role === 'employee') {
            // جلب المشاريع المرتبطة بالموظف عن طريق الفرق فقط
            $projectIds = DB::table('teams')
                ->join('team_user', 'teams.id', '=', 'team_user.team_id')
                ->where('team_user.user_id', $user->id)
                ->pluck('teams.project_id')
                ->merge(
                    DB::table('teams')
                        ->where('team_leader_id', $user->id)
                        ->pluck('project_id')
                )
                ->filter()
                ->unique()
                ->toArray();

            $query->where(function ($sub) use ($user, $projectIds) {
                $sub->whereHas('tasks.project', function ($sq) use ($projectIds) {
                    $sq->whereIn('projects.id', $projectIds);
                })
                    ->orWhereHas('teams.project', function ($sq) use ($projectIds) {
                        $sq->whereIn('projects.id', $projectIds);
                    })
                    ->orWhereHas('ledTeams.project', function ($sq) use ($projectIds) {
                        $sq->whereIn('projects.id', $projectIds);
                    })
                    ->orWhereHas('managedProjects', function ($sq) use ($projectIds) {
                        $sq->whereIn('id', $projectIds);
                    })
                    ->orWhere('users.id', $user->id);
            });
        }

        return $query;
    }


    // =========================================================================
    // LOCAL QUERY SCOPES FOR FILTERING USERS
    // =========================================================================

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        // -----------------------------------------------------------------
        // 1. FILTER BY USER NAME
        // -----------------------------------------------------------------
        $query->when($filters['name'] ?? null, function ($query, $name) {
            $query->where('name', $name);
        });

        // -----------------------------------------------------------------
        // 2. FILTER BY USER ROLE
        // -----------------------------------------------------------------
        $query->when($filters['role'] ?? null, function ($query, $role) {
            if ($role !== 'all') {
                if (strtolower($role) === 'project manager' || strtolower($role) === 'manager' || strtolower($role) === 'project_manager') {
                    $query->where('role', 'Manager');
                } else {
                    $query->where('role', $role);
                }
            }
        });

        // -----------------------------------------------------------------
        // 3. FILTER BY JOB POSITION
        // -----------------------------------------------------------------
        $query->when($filters['position'] ?? null, function ($query, $position) {
            // تنظيف القيمة القادمة من الطلب لتوحيدها
            $cleanSearchPosition = ucwords(
                preg_replace(
                    '/\s+/',
                    ' ',
                    str_replace(
                        ['-', '_'],
                        ' ',
                        strtolower(
                            preg_replace('/[0-9]+/', '', $position)
                        )
                    )
                )
            );

            // مطابقة position في قاعدة البيانات بعد إزالة الأرقام والشرطات والـ underscores
            // وتوحيد المسافات وحالة الأحرف بنفس طريقة قيمة زر الفلترة
            $query->whereRaw(
                "TRIM(
                    REGEXP_REPLACE(
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
                        ),
                        '[[:space:]]+',
                        ' '
                    )
                ) = ?",
                [strtolower(trim($cleanSearchPosition))]
            );
        });

        // -----------------------------------------------------------------
        // 4. FILTER BY DEPARTMENT
        // -----------------------------------------------------------------
        $query->when($filters['department'] ?? null, function ($query, $department) {
            $query->where('department', $department);
        });

        // -----------------------------------------------------------------
        // 5. FILTER BY ACCOUNT STATUS
        // -----------------------------------------------------------------
        $query->when($filters['status'] ?? null, function ($query, $status) {
            if ($status !== 'all') {
                $query->where('status', $status);
            }
        });

        // -----------------------------------------------------------------
        // 6. FILTER BY JOINING DATE RANGE
        // -----------------------------------------------------------------
        $dateFrom = $filters['date_from'] ?? null;
        $dateTo   = $filters['date_to'] ?? null;

        if ($dateFrom && $dateTo) {
            $startDate = min($dateFrom, $dateTo);
            $endDate   = max($dateFrom, $dateTo);

            $query->whereDate('joining_date', '>=', $startDate)
                ->whereDate('joining_date', '<=', $endDate);
        } elseif ($dateFrom) {
            $query->whereDate('joining_date', '>=', $dateFrom);
        } elseif ($dateTo) {
            $query->whereDate('joining_date', '<=', $dateTo);
        }

        return $query;
    }
}
<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
//use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder; // Added for Query Scope Builder typing

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

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_user')->withPivot('role');
    }

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

    public function collaboratingTasks()
    {
        return $this->belongsToMany(Task::class, 'task_user', 'user_id', 'task_id')->withTimestamps();
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
            $query->where('position', $position);
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
        // 6. FILTER BY JOINING DATE START RANGE (DATE FROM)
        // -----------------------------------------------------------------
        $query->when($filters['date_from'] ?? null, function ($query, $dateFrom) {
            $query->whereDate('joining_date', '>=', $dateFrom);
        });

        // -----------------------------------------------------------------
        // 7. FILTER BY JOINING DATE END RANGE (DATE TO)
        // -----------------------------------------------------------------
        $query->when($filters['date_to'] ?? null, function ($query, $dateTo) {
            $query->whereDate('joining_date', '<=', $dateTo);
        });

        return $query;
    }
}
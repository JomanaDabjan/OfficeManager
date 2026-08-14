<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * =========================================================================
 * TASK MODEL CLASS
 * =========================================================================
 * This model represents the 'tasks' table in the database and handles
 * relationships, mass assignment fields, and local scopes for filtering.
 */
class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * These fields can be filled using mass assignment methods like create() or update().
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'user_id',
        'project_id',
        'status',
        'attachment',
        'rejection_reason',
    ];

    /**
     * =====================================================================
     * MODEL RELATIONSHIPS
     * =====================================================================
     */

    /**
     * Relationship: A task belongs to a single project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Relationship: A task belongs to an assigned user (alternative naming).
     * The foreign key 'user_id' explicitly links the task to the users table.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship: A task belongs to a standard user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * =====================================================================
     * LOCAL SCOPES FOR CLEAN FILTERING & SEARCHING
     * =====================================================================
     * Local scopes allow us to modularize query constraints, keeping controllers
     * clean and readable by moving query logic directly inside the Model.
     */

    /**
     * Scope to filter and search tasks based on user role and request parameters.
     *
     * How to use it in Controller:
     * Task::filterAndSearch($user, $request)->get();
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \App\Models\User $user
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterAndSearch($query, $user, $request)
    {
        // =====================================================================
        // 1. ROLE-BASED ACCESS CONTROL
        // =====================================================================
        // If the logged-in user is an employee, restrict tasks to only their own assigned tasks.
        if ($user->role === 'employee') {
            $query->where('user_id', $user->id);
        }

        // =====================================================================
        // 2. STATUS FILTERING
        // =====================================================================
        // Check if a specific status filter is applied and is valid.
        if ($request->filled('filter') && $request->filter !== 'all') {
            $filter = $request->filter;
            if (in_array($filter, ['pending', 'in_progress', 'completed', 'accepted', 'rejected'])) {
                $query->where('status', $filter);
            }
        }

        // =====================================================================
        // 3. TITLE FILTERING
        // =====================================================================
        // Filter tasks by an exact title match if selected from a dropdown.
        if ($request->filled('title') && $request->title !== 'all') {
            $query->where('title', $request->title);
        }

        // =====================================================================
        // 4. ASSIGNED USER FILTERING
        // =====================================================================
        // Filter tasks by a specific user ID (mostly used by admins/managers).
        if ($request->filled('assigned_to') && $request->assigned_to !== 'all') {
            $query->where('user_id', $request->assigned_to);
        }

        // =====================================================================
        // 5. ATTACHMENT PRESENCE FILTERING
        // =====================================================================
        // Filter tasks based on whether they contain file attachments or not.
        if ($request->filled('has_attachment') && $request->has_attachment !== 'all') {
            if ($request->has_attachment === 'yes') {
                $query->whereNotNull('attachment');
            } elseif ($request->has_attachment === 'no') {
                $query->whereNull('attachment');
            }
        }

        // =====================================================================
        // 6. TEXT SEARCH (TITLE & DESCRIPTION)
        // =====================================================================
        // Perform a flexible search matching keywords inside the title or description.
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    /**
     * =====================================================================
     * CACHED STATUS COUNTS FOR DASHBOARD BADGES
     * =====================================================================
     * This method retrieves the count of tasks grouped by their status.
     * It uses caching to reduce database load and improve performance.
     *
     * @param int|null $userId Optional user ID to filter counts for a specific user.
     * @return \Illuminate\Support\Collection
     */
    public static function getStatusCounts($userId = null)
    {
        $cacheKey = 'task_counts_' . ($userId ?? 'admin');

        // Use Laravel's cache system to store the counts for 10 minutes.
        return cache()->remember($cacheKey, now()->addMinutes(10), function () use ($userId) {
            return self::when($userId, fn($q) => $q->where('user_id', $userId))
                ->select('status', \DB::raw('count(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status');
        });
    }

    /**
     * =====================================================================
     * SCOPE: ADVANCED REPORT FILTERS (FOR REPORT CONTROLLER)
     * =====================================================================
     * Centralized query filtering logic for task reports, supporting dropdowns,
     * status checks, and relationships to keep report controllers completely clean.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeReportFilter($query, $request)
    {
        /* Filter by task title if provided and not set to 'all' */
        if ($request->filled('title') && $request->title != 'all') {
            $query->where('title', $request->title);
        }

        /* Filter by project ID if provided and not set to 'all' */
        if ($request->filled('project_id') && $request->project_id != 'all') {
            $query->where('project_id', $request->project_id);
        }

        /* Filter by user ID if provided and not set to 'all' */
        if ($request->filled('user_id') && $request->user_id != 'all') {
            $query->where('user_id', $request->user_id);
        }

        /* Filter by task status if provided and not set to 'all' */
        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        return $query;
    }
}
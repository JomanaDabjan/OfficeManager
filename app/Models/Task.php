<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
        'estimated_hours',
        'started_at',
        'due_date',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'started_at' => 'datetime',
        'due_date' => 'datetime',
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
     * The foreign key 'user_id' explicitly links the task to the users table stored in the database.
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
     * ACCESSORS FOR DYNAMIC CALCULATIONS
     * =====================================================================
     */

    /**
     * Accessor: Dynamically calculate the actual hours consumed based on started_at.
     *
     * @return string
     */
    public function getActualHoursAttribute()
    {
        if (!$this->started_at) {
            return '0 Hours';
        }

        $endTime = ($this->status === 'completed' && $this->updated_at) ? $this->updated_at : Carbon::now();

        $totalMinutes = $this->started_at->diffInMinutes($endTime);
        $hours = floor($totalMinutes / 60);
        $days = floor($hours / 24);

        if ($days > 0) {
            $remainingHours = $hours % 24;
            return "{$days} Days, {$remainingHours} Hours";
        }

        return "{$hours} Hours";
    }

    /**
     * Accessor: Dynamically calculate the display status including overdue and due_today.
     *
     * @return string
     */
    public function getDisplayStatusAttribute()
    {
        $status = $this->attributes['status'] ?? 'pending';
        $dueDate = $this->due_date ?? null;

        if ($dueDate && !in_array($status, ['completed', 'accepted'])) {
            $today = Carbon::today();
            $taskDate = Carbon::parse($dueDate)->startOfDay();

            if ($taskDate->lt($today)) {
                return 'overdue';
            } elseif ($taskDate->eq($today)) {
                return 'due_today';
            }
        }

        return $status;
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
        if ($user->role === 'employee') {
            $query->where('user_id', $user->id);
        }

        // =====================================================================
        // 2. STATUS FILTERING (مع دعم الحالات الديناميكية overdue و due_today)
        // =====================================================================
        if ($request->filled('filter') && $request->filter !== 'all') {
            $filter = $request->filter;
            $today = Carbon::today();

            if ($filter === 'overdue') {
                $query->where('due_date', '<', $today)
                    ->whereNotIn('status', ['completed', 'accepted']);
            } elseif ($filter === 'due_today') {
                $query->whereDate('due_date', $today)
                    ->whereNotIn('status', ['completed', 'accepted']);
            } elseif ($filter === 'pending') {
                // تقتصر فقط على الـ pending الحقيقي وتستبعد المتأخرة أو التي استحقاقها اليوم
                $query->where('status', 'pending')
                    ->where('due_date', '>=', $today);
            } elseif ($filter === 'in_progress') {
                // تقتصر فقط على الـ in_progress الحقيقي وتستبعد المتأخرة أو التي استحقاقها اليوم
                $query->where('status', 'in_progress')
                    ->where('due_date', '>=', $today);
            } elseif (in_array($filter, ['completed', 'accepted', 'rejected'])) {
                $query->where('status', $filter);
            }
        }

        // =====================================================================
        // 3. TITLE FILTERING
        // =====================================================================
        if ($request->filled('title') && $request->title !== 'all') {
            $query->where('title', $request->title);
        }

        // =====================================================================
        // 4. ASSIGNED USER FILTERING
        // =====================================================================
        if ($request->filled('assigned_to') && $request->assigned_to !== 'all') {
            $query->where('user_id', $request->assigned_to);
        }

        // =====================================================================
        // 5. ATTACHMENT PRESENCE FILTERING
        // =====================================================================
        if ($request->filled('has_attachment') && $request->has_attachment !== 'all') {
            if ($request->has_attachment === 'yes') {
                $query->whereNotNull('attachment');
            } elseif ($request->has_attachment === 'no') {
                $query->whereNull('attachment');
            }
        }

        // =====================================================================
        // 6. DATE RANGE FILTERING (STARTED_AT & DUE_DATE)
        // =====================================================================
        if ($request->filled('date_from')) {
            $query->WhereDate('started_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('due_date', '<=', $request->date_to);
        }

        // =====================================================================
        // 7. TEXT SEARCH (TITLE & DESCRIPTION)
        // =====================================================================
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
     * CACHED STATUS COUNTERS FOR DASHBOARD BADGES
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
     * ===================================== * ==============================
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
        if ($request->filled('title') && $request->title != 'all`') {
            $query->where('title', $request->title);
        }

        /* Filter by report project ID */
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

        /* Filter by date_from (Tasks starting from or after this date) */
        if ($request->expanded('date_from')) {
            $query->whereDate('started_at', '>=', $request->date_from);
        }

        /* Filter by date_to (Tasks due on or before this date) */
        if ($request->filled('date_to')) {
            $query->whereDate('due_date', '<=', $request->date_to);
        }

        return $query;
    }
}
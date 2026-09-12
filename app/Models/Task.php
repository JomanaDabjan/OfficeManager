<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'user_id',
        'project_id',
        'team_id',
        'status',
        'attachment',
        'rejection_reason',
        'estimated_hours',
        'started_at',
        'due_date',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'due_date' => 'datetime',
        'attachment' => 'array',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /* -----------------------------------------------------------------
     * GET TEAMS ASSOCIATED WITH A PROJECT
     * ----------------------------------------------------------------- */
    public static function teamsForProject($projectId)
    {
        if (!$projectId) {
            return collect();
        }

        $project = Project::find($projectId);

        if (!$project) {
            return collect();
        }

        $user = auth()->user();
        $role = strtolower(trim($user->role ?? ''));

        if ($role === 'team_leader') {
            return $project->teams()
                ->where('team_leader_id', $user->id)
                ->get();
        }

        return $project->teams()->get();
    }

    /* -----------------------------------------------------------------
     * GET EMPLOYEES ASSOCIATED WITH A TEAM
     * ----------------------------------------------------------------- */
    public static function employeesForTeam($teamId)
    {
        if (!$teamId) {
            return collect();
        }

        $team = Team::find($teamId);

        if (!$team) {
            return collect();
        }

        return $team->members()
            ->where(function ($query) {
                $query
                    ->whereRaw('LOWER(role) = ?', ['employee'])
                    ->orWhereRaw('LOWER(role) = ?', ['employees']);
            })
            ->get();
    }

    /* -----------------------------------------------------------------
     * VALIDATE TEAM BELONGS TO PROJECT
     * ----------------------------------------------------------------- */
    public static function teamBelongsToProject($teamId, $projectId)
    {
        if (!$teamId || !$projectId) {
            return false;
        }

        return Project::query()
            ->where('id', $projectId)
            ->whereHas('teams', function ($query) use ($teamId) {
                $query->where('teams.id', $teamId);
            })
            ->exists();
    }

    /* -----------------------------------------------------------------
     * VALIDATE EMPLOYEE BELONGS TO TEAM
     * ----------------------------------------------------------------- */
    public static function employeeBelongsToTeam($userId, $teamId)
    {
        if (!$userId || !$teamId) {
            return false;
        }

        return Team::query()
            ->where('id', $teamId)
            ->whereHas('members', function ($query) use ($userId) {
                $query
                    ->where('users.id', $userId)
                    ->where(function ($query) {
                        $query
                            ->whereRaw('LOWER(role) = ?', ['employee'])
                            ->orWhereRaw('LOWER(role) = ?', ['employees']);
                    });
            })
            ->exists();
    }

    public function getActualHoursAttribute()
    {
        if (!$this->started_at) {
            return '0 Hours';
        }

        $endTime = $this->status === 'completed' && $this->updated_at
            ? $this->updated_at
            : Carbon::now();

        $totalMinutes = $this->started_at->diffInMinutes($endTime);
        $hours = floor($totalMinutes / 60);
        $days = floor($hours / 24);

        if ($days > 0) {
            $remainingHours = $hours % 24;

            return "{$days} Days, {$remainingHours} Hours";
        }

        return "{$hours} Hours";
    }

    /* -----------------------------------------------------------------
     * GET DISPLAY STATUS
     * -----------------------------------------------------------------
     *
     * Determines the real display status of the task based on its
     * original status and due date.
     *
     * Accepted, Rejected and Completed are final statuses and must
     * never become Overdue or Due Today.
     * ----------------------------------------------------------------- */
    public function getDisplayStatusAttribute()
    {
        $status = $this->attributes['status'] ?? 'pending';
        $dueDate = $this->due_date;

        if (
            $status === 'completed' ||
            $status === 'accepted' ||
            $status === 'rejected'
        ) {
            return $status;
        }

        if ($dueDate) {
            $today = Carbon::today();
            $taskDate = Carbon::parse($dueDate)->startOfDay();

            if ($taskDate->lt($today)) {
                return 'overdue';
            }

            if ($taskDate->eq($today)) {
                return 'due_today';
            }
        }

        return $status;
    }

    /* -----------------------------------------------------------------
     * GET DISPLAY STATUS LABEL
     * ----------------------------------------------------------------- */
    public function getDisplayStatusLabelAttribute()
    {
        return match ($this->display_status) {
            'pending' => 'Pending',
            'in_progress' => 'In Progress',
            'due_today' => 'Due Today',
            'overdue' => 'Overdue',
            'completed' => 'Completed',
            'complete' => 'Completed',
            'accepted' => 'Accepted',
            'rejected' => 'Rejected',
            default => ucfirst(str_replace('_', ' ', $this->display_status)),
        };
    }

    /* -----------------------------------------------------------------
     * GET STATUS BADGE CLASS
     * ----------------------------------------------------------------- */
    public function getStatusClassAttribute()
    {
        return match ($this->display_status) {
            'completed', 'complete' => 'badge-success',
            'in_progress' => 'badge-warning',
            'pending' => 'badge-info',
            'accepted' => 'badge-success',
            'rejected', 'overdue' => 'badge-danger',
            'due_today' => 'badge-purple',
            default => 'badge-secondary',
        };
    }

    /* -----------------------------------------------------------------
     * GET STATUS BADGE STYLE
     * ----------------------------------------------------------------- */
    public function getStatusStyleAttribute()
    {
        return match ($this->display_status) {
            'accepted' => 'background-color: rgba(40, 167, 69, 0.12) !important; color: #28a745 !important; border: 1px solid rgba(40, 167, 69, 0.25) !important;',

            'rejected' => 'background-color: rgba(220, 53, 69, 0.12) !important; color: #dc3545 !important; border: 1px solid rgba(220, 53, 69, 0.25) !important;',

            'due_today' => 'background-color: #6f42c1 !important; color: #ffffff !important;',

            default => '',
        };
    }

    /* -----------------------------------------------------------------
     * GET DAYS REMAINING
     * ----------------------------------------------------------------- */
    public function getDaysRemainingAttribute()
    {
        if (!$this->due_date) {
            return null;
        }

        $today = Carbon::today();
        $due = Carbon::parse($this->due_date)->startOfDay();

        return (int) $today->diffInDays($due, false);
    }

    /* -----------------------------------------------------------------
     * GET TASK DAYS LABEL
     * ----------------------------------------------------------------- */
    public function getDaysRemainingLabelAttribute()
    {
        if (!$this->due_date) {
            return 'No Deadline';
        }

        if ($this->display_status === 'completed') {
            return 'TASK COMPLETED';
        }

        $diff = $this->days_remaining;

        if ($this->started_at) {
            $start = Carbon::parse($this->started_at)->startOfDay();

            if (Carbon::today()->lt($start)) {
                $totalDays = (int) $start->diffInDays(
                    Carbon::parse($this->due_date)->startOfDay(),
                    false
                );

                return "{$totalDays} DAYS TOTAL (Not Started)";
            }
        }

        if ($diff > 1) {
            return "{$diff} DAYS REMAINING";
        }

        if ($diff === 1) {
            return "1 DAY REMAINING";
        }

        if ($diff === 0) {
            return "THIS IS THE LAST DAY";
        }

        return "OVERDUE BY " . abs($diff) . " DAYS";
    }

    /* -----------------------------------------------------------------
     * GET ATTACHMENTS LIST
     * -----------------------------------------------------------------
     *
     * The attachment column is already cast to an array.
     * This accessor provides one single source for the attachment list.
     * ----------------------------------------------------------------- */
    public function getAttachmentsListAttribute()
    {
        if (empty($this->attachment)) {
            return [];
        }

        return is_array($this->attachment)
            ? $this->attachment
            : [$this->attachment];
    }

    /* -----------------------------------------------------------------
     * GET ATTACHMENTS FILE COUNT
     * ----------------------------------------------------------------- */
    public function getFileCountAttribute()
    {
        return count($this->attachments_list);
    }

    public function scopeForUser($query, $user)
    {
        $role = strtolower(trim($user->role ?? ''));

        if ($role === 'employee') {
            $query
                ->where('user_id', $user->id)
                ->whereHas('project.teams.members', function ($query) use ($user) {
                    $query->where('users.id', $user->id);
                });
        } elseif ($role === 'manager') {
            $query->whereHas('project', function ($query) use ($user) {
                $query->where('manager_id', $user->id);
            });
        } elseif ($role === 'team_leader') {
            $query->whereHas('assignedUser.teams', function ($query) use ($user) {
                $query->where('team_leader_id', $user->id);
            });
        }

        return $query;
    }

    public function scopeForSpecificUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeTaskStatus($query, ?string $status)
    {
        if (!$status || $status === 'all') {
            return $query;
        }

        return $query->where('status', $status);
    }

    public static function availableTitlesFor($user, $userId = null)
    {
        return self::query()
            ->forUser($user)
            ->when($userId, function ($query) use ($userId) {
                $query->forSpecificUser($userId);
            })
            ->distinct()
            ->pluck('title');
    }

    public function scopeFilterAndSearch($query, $user, $request)
    {
        $this->applyTaskDateStatusFilter(
            $query,
            $request->filter ?? null
        );

        if ($request->filled('title') && $request->title !== 'all') {
            $query->where('title', $request->title);
        }

        if ($request->filled('assigned_to') && $request->assigned_to !== 'all') {
            $query->where('user_id', $request->assigned_to);
        }

        if (
            $request->filled('has_attachment') &&
            $request->has_attachment !== 'all'
        ) {
            if ($request->has_attachment === 'yes') {
                $query->whereNotNull('attachment');
            } elseif ($request->has_attachment === 'no') {
                $query->whereNull('attachment');
            }
        }

        /* -----------------------------------------------------------------
         * FILTER BY DATE RANGE
         * Supports both directions:
         * From -> To
         * To -> From
         * ----------------------------------------------------------------- */
        $dateFrom = $request->input('date_from');
        $dateTo   = $request->input('date_to');

        if ($dateFrom && $dateTo) {

            $startDate = min($dateFrom, $dateTo);
            $endDate   = max($dateFrom, $dateTo);

            $query->whereDate(
                'started_at',
                '>=',
                $startDate
            )->whereDate(
                'due_date',
                '<=',
                $endDate
            );
        } elseif ($dateFrom) {

            $query->whereDate(
                'started_at',
                '>=',
                $dateFrom
            );
        } elseif ($dateTo) {

            $query->whereDate(
                'due_date',
                '<=',
                $dateTo
            );
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($query) use ($search) {
                $query
                    ->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    protected function applyTaskDateStatusFilter($query, ?string $status)
    {
        if (!$status || $status === 'all') {
            return $query;
        }

        $today = Carbon::today();

        /*
         * Overdue must not include:
         * completed
         * accepted
         * rejected
         */
        if ($status === 'overdue') {
            return $query
                ->whereDate('due_date', '<', $today)
                ->whereNotIn('status', [
                    'completed',
                    'accepted',
                    'rejected'
                ]);
        }

        /*
         * Due Today must not include:
         * completed
         * accepted
         * rejected
         */
        if ($status === 'due_today') {
            return $query
                ->whereDate('due_date', $today)
                ->whereNotIn('status', [
                    'completed',
                    'accepted',
                    'rejected'
                ]);
        }

        /*
         * Pending and In Progress are only shown as their
         * original status while their due date is not passed.
         */
        if (in_array($status, ['pending', 'in_progress'])) {
            return $query
                ->where('status', $status)
                ->where(function ($query) use ($today) {
                    $query
                        ->whereNull('due_date')
                        ->orWhereDate('due_date', '>', $today);
                });
        }

        /*
         * Final statuses must always remain their exact status.
         */
        if (in_array($status, [
            'completed',
            'accepted',
            'rejected'
        ])) {
            return $query->where('status', $status);
        }

        return $query;
    }

    public static function getStatusCounts($userId = null)
    {
        $cacheKey = 'task_counts_' . ($userId ?? 'admin');

        /*
         * Use a short cache period so status changes such as
         * Accepted/Rejected appear quickly.
         */
        return cache()->remember(
            $cacheKey,
            now()->addSeconds(30),
            function () use ($userId) {

                $today = Carbon::today()->toDateString();

                return self::when(
                    $userId,
                    fn($query) => $query->where('user_id', $userId)
                )
                    ->selectRaw(
                        "
                        CASE
                            WHEN status IN ('completed', 'accepted', 'rejected')
                                THEN status
                            WHEN due_date IS NOT NULL AND DATE(due_date) < ?
                                THEN 'overdue'
                            WHEN due_date IS NOT NULL AND DATE(due_date) = ?
                                THEN 'due_today'
                            ELSE status
                        END as display_status,
                        COUNT(*) as total
                        ",
                        [$today, $today]
                    )
                    ->groupBy('display_status')
                    ->pluck('total', 'display_status');
            }
        );
    }

    public function scopeReportFilter($query, $request)
    {
        $user = auth()->user();

        if (
            $user &&
            strtolower(trim($user->role ?? '')) === 'team_leader'
        ) {
            $query->whereHas('assignedUser.teams', function ($query) use ($user) {
                $query->where('team_leader_id', $user->id);
            });
        }

        if ($request->filled('title') && $request->title !== 'all') {
            $query->where('title', $request->title);
        }

        if (
            $request->filled('project_id') &&
            $request->project_id !== 'all'
        ) {
            $query->where('project_id', $request->project_id);
        }

        if (
            $request->filled('year') ||
            ($request->filled('user_id') && $request->user_id !== 'alt')
        ) {
            if (
                $request->filled('user_id') &&
                $request->user_id !== 'all'
            ) {
                $query->where('user_id', $request->user_id);
            }
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $this->applyTaskDateStatusFilter(
                $query,
                $request->status
            );
        }

        /* -----------------------------------------------------------------
         * REPORT DATE RANGE
         * Supports both directions:
         * From -> To
         * To -> From
         * ----------------------------------------------------------------- */
        $dateFrom = $request->input('date_from');
        $dateTo   = $request->input('date_to');

        if ($dateFrom && $dateTo) {

            $startDate = min($dateFrom, $dateTo);
            $endDate   = max($dateFrom, $dateTo);

            $query->whereDate(
                'started_at',
                '>=',
                $startDate
            )->whereDate(
                'due_date',
                '<=',
                $endDate
            );
        } elseif ($dateFrom) {

            $query->whereDate(
                'started_at',
                '>=',
                $dateFrom
            );
        } elseif ($dateTo) {

            $query->whereDate(
                'due_date',
                '<=',
                $dateTo
            );
        }

        return $query;
    }
}
<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DeadlineNotificationService
{
    /**
     * Number of days before the deadline
     * at which a notification should appear.
     */
    protected int $warningDays = 3;

    /**
     * Get deadline notifications for the currently
     * authenticated user.
     */
    public function getNotifications(?User $user = null): Collection
    {
        $user = $user ?? Auth::user();

        if (!$user) {
            return collect();
        }

        $role = strtolower(trim($user->role ?? ''));

        return match ($role) {
            'admin' => $this->getAdminNotifications($user),
            'manager' => $this->getManagerNotifications($user),
            'team_leader', 'team leader', 'teamleader' => $this->getTeamLeaderNotifications($user),
            'employee' => $this->getEmployeeNotifications($user),
            default => collect(),
        };
    }

    /**
     * Admin can see all approaching project/task deadlines.
     */
    protected function getAdminNotifications(User $user): Collection
    {
        $projects = Project::query()
            ->whereNotIn('status', ['completed', 'complete'])
            ->whereNotNull('end_date')
            ->whereDate('end_date', '<=', now()->addDays($this->warningDays))
            ->orderBy('end_date')
            ->get();

        $tasks = Task::query()
            ->whereNotIn('status', [
                'completed',
                'complete',
                'rejected',
            ])
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<=', now()->addDays($this->warningDays))
            ->orderBy('due_date')
            ->get();

        return $this->buildNotifications($projects, $tasks);
    }

    /**
     * Manager can see deadlines for projects managed by them
     * and tasks belonging to those projects.
     */
    protected function getManagerNotifications(User $user): Collection
    {
        $projects = Project::query()
            ->where('manager_id', $user->id)
            ->whereNotIn('status', ['completed', 'complete'])
            ->whereNotNull('end_date')
            ->whereDate('end_date', '<=', now()->addDays($this->warningDays))
            ->orderBy('end_date')
            ->get();

        $projectIds = $projects->pluck('id');

        $tasks = Task::query()
            ->whereIn('project_id', $projectIds)
            ->whereNotIn('status', [
                'completed',
                'complete',
                'rejected',
            ])
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<=', now()->addDays($this->warningDays))
            ->orderBy('due_date')
            ->get();

        return $this->buildNotifications($projects, $tasks);
    }

    /**
     * Team leader can see deadlines for projects connected
     * to teams led by them and tasks belonging to those projects.
     */
    protected function getTeamLeaderNotifications(User $user): Collection
    {
        $projects = Project::query()
            ->whereHas('teams', function ($query) use ($user) {
                $query->where('team_leader_id', $user->id);
            })
            ->whereNotIn('status', ['completed', 'complete'])
            ->whereNotNull('end_date')
            ->whereDate('end_date', '<=', now()->addDays($this->warningDays))
            ->orderBy('end_date')
            ->get();

        $projectIds = $projects->pluck('id');

        $tasks = Task::query()
            ->whereIn('project_id', $projectIds)
            ->whereNotIn('status', [
                'completed',
                'complete',
                'rejected',
            ])
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<=', now()->addDays($this->warningDays))
            ->orderBy('due_date')
            ->get();

        return $this->buildNotifications($projects, $tasks);
    }

    /**
     * Employee can see:
     * - Projects connected to their assigned tasks.
     * - Projects connected to teams they belong to.
     * - Their own assigned tasks.
     */
    protected function getEmployeeNotifications(User $user): Collection
    {
        $projects = Project::query()
            ->where(function ($query) use ($user) {
                $query->whereHas('tasks', function ($subQuery) use ($user) {
                    $subQuery->where('user_id', $user->id);
                })
                    ->orWhereHas('teams.members', function ($subQuery) use ($user) {
                        $subQuery->where('users.id', $user->id);
                    });
            })
            ->whereNotIn('status', ['completed', 'complete'])
            ->whereNotNull('end_date')
            ->whereDate('end_date', '<=', now()->addDays($this->warningDays))
            ->orderBy('end_date')
            ->get();

        $tasks = Task::query()
            ->where('user_id', $user->id)
            ->whereNotIn('status', [
                'completed',
                'complete',
                'rejected',
            ])
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<=', now()->addDays($this->warningDays))
            ->orderBy('due_date')
            ->get();

        return $this->buildNotifications($projects, $tasks);
    }

    /**
     * Build a unified notification collection
     * for projects and tasks.
     */
    protected function buildNotifications(
        Collection $projects,
        Collection $tasks
    ): Collection {
        $notifications = collect();

        foreach ($projects as $project) {
            $notifications->push(
                $this->makeProjectNotification($project)
            );
        }

        foreach ($tasks as $task) {
            $notifications->push(
                $this->makeTaskNotification($task)
            );
        }

        return $notifications
            ->sortBy(function ($notification) {
                return $notification->deadline_timestamp;
            })
            ->values();
    }

    /**
     * Create a project notification object.
     */
    protected function makeProjectNotification(Project $project): object
    {
        $deadline = Carbon::parse($project->end_date);

        return (object) [
            'type' => 'project',
            'title' => 'Project deadline',
            'name' => $project->title,
            'id' => $project->id,
            'days_remaining' => $this->getDaysRemaining($deadline),
            'deadline' => $deadline,
            'deadline_timestamp' => $deadline->timestamp,
            'url' => route('admin.project.show', $project),
        ];
    }

    /**
     * Create a task notification object.
     */
    protected function makeTaskNotification(Task $task): object
    {
        $deadline = Carbon::parse($task->due_date);

        return (object) [
            'type' => 'task',
            'title' => 'Task deadline',
            'name' => $task->title,
            'id' => $task->id,
            'days_remaining' => $this->getDaysRemaining($deadline),
            'deadline' => $deadline,
            'deadline_timestamp' => $deadline->timestamp,
            'url' => route('admin.task.show', $task),
        ];
    }

    /**
     * Calculate the number of calendar days remaining.
     *
     * 0  = today
     * 1  = tomorrow
     * >1 = remaining days
     * <0 = deadline passed
     */
    protected function getDaysRemaining(Carbon $deadline): int
    {
        return now()->startOfDay()->diffInDays(
            $deadline->copy()->startOfDay(),
            false
        );
    }

    /**
     * Change the warning period if needed.
     */
    public function setWarningDays(int $days): self
    {
        $this->warningDays = max(0, $days);

        return $this;
    }

    /**
     * Get the current warning period.
     */
    public function getWarningDays(): int
    {
        return $this->warningDays;
    }
}

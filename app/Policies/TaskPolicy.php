<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * =========================================================================
 * TASK POLICY CLASS
 * =========================================================================
 * This policy handles all authorization logic and security rules for tasks,
 * determining whether a user is allowed to view, create, update, delete,
 * or modify the status of a task based on their role.
 */
class TaskPolicy
{
    /**
     * Determine whether the user can view any tasks.
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        // Everyone authenticated can view the index listing (filtered inside controller)
        return true;
    }

    /**
     * Determine whether the user can view the task details.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Task $task
     * @return \Illuminate\Auth\Access\Response
     */
    public function view(User $user, Task $task): Response
    {
        $role = strtolower(trim($user->role));

        $isAdmin = ($role === 'admin');

        // مدير المشروع: يرى المهمة فقط إذا كانت تابعة لمشروعه بشكل مباشر، أو تابعة لفريق يخص مشروعه
        $isProjectManager = ($role === 'manager' && (
            // 1. المهمة مرتبطة مباشرة بمشروع يديره هذا المدير
            ($task->project && $task->project->manager_id === $user->id) ||

            // 2. أو المهمة مسندة لفريق يتبع لمشروع يديره هذا المدير
            ($task->team && $task->team->project && $task->team->project->manager_id === $user->id)
        ));

        // قائد الفريق: يرى المهمة إذا كان الموظف المعين بالمهمة ينتمي إلى فريق يترأسه قائد الفريق الحالي
        $isTeamLeader = ($role === 'team_leader' && $task->assignedUser && $task->assignedUser->teams()->where('team_leader_id', $user->id)->exists());

        $isAssignedEmployee = ($role === 'employee' && $task->user_id === $user->id);

        return ($isAdmin || $isProjectManager || $isTeamLeader || $isAssignedEmployee)
            ? Response::allow()
            : Response::deny('Unauthorized action. You do not have permission to view this task.');
    }

    /**
     * Determine whether the user can create tasks.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response
     */
    public function create(User $user): Response
    {
        return $this->isAdminManagerOrTeamLeader($user)
            ? Response::allow()
            : Response::deny('Unauthorized action. Only administrators, managers, and team leaders can create tasks.');
    }

    /**
     * Determine whether the user can update the task.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Task $task
     * @return \Illuminate\Auth\Access\Response
     */
    public function update(User $user, Task $task): Response
    {
        $role = strtolower(trim($user->role));

        if ($role === 'admin' || $role === 'manager') {
            return Response::allow();
        }

        // التحقق مما إذا كان قائد الفريق مسؤولاً عن الفريق المرتبط بمشروع هذه المهمة
        if ($role === 'team_leader' && $task->project) {
            $isTeamLeaderOfProject = $task->project->teams()->where('team_leader_id', $user->id)->exists();
            if ($isTeamLeaderOfProject) {
                return Response::allow();
            }
        }

        return Response::deny('Unauthorized action. Only administrators, managers, and authorized team leaders can update tasks.');
    }

    public function delete(User $user, Task $task): Response
    {
        $role = strtolower(trim($user->role));

        if ($role === 'admin' || $role === 'manager') {
            return Response::allow();
        }

        // التحقق مما إذا كان قائد الفريق مسؤولاً عن الفريق المرتبط بمشروع هذه المهمة
        if ($role === 'team_leader' && $task->project) {
            $isTeamLeaderOfProject = $task->project->teams()->where('team_leader_id', $user->id)->exists();
            if ($isTeamLeaderOfProject) {
                return Response::allow();
            }
        }

        return Response::deny('Unauthorized action. Only administrators, managers, and authorized team leaders can delete tasks.');
    }

    /**
     * Determine whether the employee can accept or reject their task.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Task $task
     * @return \Illuminate\Auth\Access\Response
     */
    public function modifyStatus(User $user, Task $task): Response
    {
        $role = strtolower(trim($user->role));

        // Ensure the user is an employee and owns the task assigned to them
        return ($role === 'employee' && $task->user_id === $user->id)
            ? Response::allow()
            : Response::deny('Unauthorized action. You can only modify your own assigned tasks.');
    }

    // =========================================================================
    // HELPER METHODS (PRIVATE)
    // =========================================================================

    /**
     * Check if the given user has an admin or manager role.
     * This avoids repeating role check arrays across multiple policy methods.
     *
     * @param \App\Models\User $user
     * @return bool
     */
    private function isAdminOrManager(User $user): bool
    {
        $role = strtolower(trim($user->role)); // Normalize role to lowercase and trim whitespace

        return in_array($role, ['admin', 'manager']);
    }

    /**
     * Check if the given user has an admin, manager, or team leader role.
     *
     * @param \App\Models\User $user
     * @return bool
     */
    private function isAdminManagerOrTeamLeader(User $user): bool
    {
        $role = strtolower(trim($user->role));

        return in_array($role, ['admin', 'manager', 'team_leader']);
    }
}

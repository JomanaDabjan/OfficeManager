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

        // 1. Admin can view any task
        // 2. Manager can view the task ONLY if they manage the project associated with this task
        // 3. Employee can view the task ONLY if it is assigned to them
        $isAdmin = ($role === 'admin');
        $isProjectManager = ($role === 'manager' && $task->project && $task->project->manager_id === $user->id);
        $isAssignedEmployee = ($role === 'employee' && $task->user_id === $user->id);

        return ($isAdmin || $isProjectManager || $isAssignedEmployee)
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
        return $this->isAdminOrManager($user)
            ? Response::allow()
            : Response::deny('Unauthorized action. Only administrators and managers can create tasks.');
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
        return $this->isAdminOrManager($user)
            ? Response::allow()
            : Response::deny('Unauthorized action. Only administrators and managers can update tasks.');
    }

    /**
     * Determine whether the user can delete the task.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Task $task
     * @return \Illuminate\Auth\Access\Response
     */
    public function delete(User $user, Task $task): Response
    {
        return $this->isAdminOrManager($user)
            ? Response::allow()
            : Response::deny('Unauthorized action. Only administrators and managers can delete tasks.');
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
}
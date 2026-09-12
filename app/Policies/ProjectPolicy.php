<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * =========================================================================
 * PROJECT POLICY CLASS (ROLE-BASED AUTHORIZATION & SECURITY)
 * =========================================================================
 * This policy class handles all security rules for project resources.
 * It determines if a logged-in user (Admin, Manager, Team Leader, or Employee) is allowed
 * to view, create, update, or delete a specific project.
 */
class ProjectPolicy
{
    /**
     * Determine whether the user can view any project records in the list.
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        // =====================================================================
        // RULE: Global Index Access
        // =====================================================================
        // Allow all authenticated users to open the project listing page.
        // Specific records are filtered securely inside the controller.
        return true;
    }

    /**
     * Determine whether the user can view a specific project details page.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Project $project
     * @return \Illuminate\Auth\Access\Response
     */
   public function view(User $user, Project $project): Response
    {
        // =====================================================================
        // TIER 1: Admin Full Access
        // =====================================================================
        // Admins can view any project in the system without restrictions.
        if ($user->role === 'admin') {
            return Response::allow();
        }

        // =====================================================================
        // TIER 2: Manager Ownership Check
        // =====================================================================
        // Managers can only view projects where their ID matches the manager_id.
        if ($user->role === 'manager') {
            return $project->manager_id === $user->id
                ? Response::allow()
                : Response::deny('You are not authorized to view this project because it is assigned to another manager.');
        }

        // =====================================================================
        // TIER 2.5: Team Leader Ownership Check
        // =====================================================================
        // Team Leaders can view projects associated with the teams they lead.
        if ($user->role === 'team_leader') {
            return $project->teams()->where('team_leader_id', $user->id)->exists()
                ? Response::allow()
                : Response::deny('You are not authorized to view this project because it is assigned to another team leader.');
        }

        // =====================================================================
        // TIER 3: Employee Assignment & Team Check
        // =====================================================================
        // Regular employees can view projects they are directly assigned to OR belong to a team assigned to the project.
        $isDirectlyAssigned = $project->users()->where('user_id', $user->id)->exists();
        
        $isInTeamOfProject = \Illuminate\Support\Facades\DB::table('team_user')
            ->join('teams', 'teams.id', '=', 'team_user.team_id')
            ->where('teams.project_id', $project->id)
            ->where('team_user.user_id', $user->id)
            ->exists();

        return ($isDirectlyAssigned || $isInTeamOfProject)
            ? Response::allow()
            : Response::deny('You do not have permission to view this project as you are not assigned to it.');
    }

    /**
     * Determine whether the user can create new projects.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response
     */
    public function create(User $user): Response
    {
        // =====================================================================
        // RULE: Administrative Privilege Only
        // =====================================================================
        // Only users with the 'admin' role are permitted to create new projects.
        return $user->role === 'admin'
            ? Response::allow()
            : Response::deny('Access denied. Only administrators are allowed to create new projects.');
    }

    /**
     * Determine whether the user can update an existing project.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Project $project
     * @return \Illuminate\Auth\Access\Response
     */
    public function update(User $user, Project $project): Response
    {
        // =====================================================================
        // TIER 1: Admin Full Update Access
        // =====================================================================
        // Admins can modify any project in the database.
        if ($user->role === 'admin') {
            return Response::allow();
        }

        // =====================================================================
        // TIER 2: Manager Ownership Check for Updates
        // =====================================================================
        // Managers can only modify projects assigned directly to them.
        if ($user->role === 'manager') {
            return $project->manager_id === $user->id
                ? Response::allow()
                : Response::deny('You cannot edit this project because you are not the assigned manager.');
        }

        // =====================================================================
        // TIER 2.5: Team Leader Update Check
        // =====================================================================
        // Team Leaders can modify projects where they are assigned as the team leader.
        if ($user->role === 'team_leader') {
            return $project->team_leader_id === $user->id
                ? Response::allow()
                : Response::deny('You cannot edit this project because you are not the assigned team leader.');
        }

        // =====================================================================
        // TIER 3: Employee Denial
        // =====================================================================
        // Employees are blocked from editing any project details.
        return Response::deny('Employees are strictly prohibited from modifying project details.');
    }

    /**
     * Determine whether project records can be deleted.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Project $project
     * @return \Illuminate\Auth\Access\Response
     */
    public function delete(User $user, Project $project): Response
    {
        // =====================================================================
        // RULE: Strict Deletion Lockout
        // =====================================================================
        // Deleting records is a destructive action restricted solely to Admins.
        return $user->role === 'admin'
            ? Response::allow()
            : Response::deny('Unauthorized action. Only administrators can delete projects.');
    }
}
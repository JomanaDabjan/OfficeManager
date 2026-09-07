<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * =========================================================================
 * TEAM POLICY CLASS (ROLE-BASED AUTHORIZATION & ACCESS CONTROL)
 * =========================================================================
 * This policy handles all security and access permissions for Team resources.
 * It strictly regulates which user roles (Admin, Manager, Team Leader, Employee)
 * can view, create, update, or delete teams within the application.
 */
class TeamPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can view any team records in the list.
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        // =====================================================================
        // RULE 1: INDEX VIEW ACCESS
        // =====================================================================
        // Allow all authenticated users to open the team listing page.
        // Specific record filtering is handled inside the controller query logic.
        return true;
    }

    /**
     * Determine whether the user can view specific team details.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Team $team
     * @return \Illuminate\Auth\Access\Response
     */
    public function view(User $user, Team $team): Response
    {
        $role = strtolower(trim($user->role));

        // =====================================================================
        // RULE 2: DETAILED TEAM VIEWING PERMISSIONS
        // =====================================================================

        // 1. Admin: Full system-wide visibility
        if ($role === 'admin') {
            return Response::allow();
        }

        // 2. Manager: Can view if they manage this specific team OR belong to it as a member
        if ($role === 'manager') {
            return ($team->manager_id === $user->id || $team->users()->where('user_id', $user->id)->exists())
                ? Response::allow()
                : Response::deny('Unauthorized action. You are not assigned to manage or participate in this team.');
        }

        // 3. Team Leader: Can view if they lead this team or belong to it
        if ($role === 'team_leader') {
            return ($team->team_leader_id === $user->id || $team->users()->where('user_id', $user->id)->exists())
                ? Response::allow()
                : Response::deny('Unauthorized action. You are not assigned to lead or participate in this team.');
        }

        // 4. Employee: Can view only if assigned as a member of this team
        return $team->users()->where('user_id', $user->id)->exists()
            ? Response::allow()
            : Response::deny('Unauthorized action. You are not a member of this team.');
    }

    /**
     * Determine whether the user can create new teams.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response
     */
    public function create(User $user): Response
    {
        // =====================================================================
        // RULE 3: TEAM CREATION RESTRICTIONS
        // =====================================================================
        // Creating structural entities like teams is reserved for higher management.

        return $this->isAdminOrManager($user)
            ? Response::allow()
            : Response::deny('Unauthorized action. Only administrators and managers can create new teams.');
    }

    /**
     * Determine whether the user can update an existing team's information.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Team $team
     * @return \Illuminate\Auth\Access\Response
     */
    public function update(User $user, Team $team): Response
    {
        $role = strtolower(trim($user->role));

        // =====================================================================
        // RULE 4: TEAM UPDATE PERMISSIONS
        // =====================================================================

        // 1. Admin: Can edit any team in the database
        if ($role === 'admin') {
            return Response::allow();
        }

        // 2. Manager: Can edit the team if they own/manage it
        if ($role === 'manager' && $team->manager_id === $user->id) {
            return Response::allow();
        }

        // 3. Team Leader: Can update team settings if assigned as team leader
        if ($role === 'team_leader' && $team->team_leader_id === $user->id) {
            return Response::allow();
        }

        // 4. Employees are strictly prohibited from updating teams
        return Response::deny('Unauthorized action. You do not have permission to modify this team.');
    }

    /**
     * Determine whether the user can delete a team.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Team $team
     * @return \Illuminate\Auth\Access\Response
     */
    public function delete(User $user, Team $team): Response
    {
        $role = strtolower(trim($user->role));

        // =====================================================================
        // RULE 5: DELETION LOCKOUT RULES
        // =====================================================================

        // 1. Admin: Can delete any team
        if ($role === 'admin') {
            return Response::allow();
        }

        // 2. Manager: Can delete a team only if they are its designated manager
        if ($role === 'manager' && $team->manager_id === $user->id) {
            return Response::allow();
        }

        // Team Leaders and Employees cannot delete teams
        return Response::deny('Unauthorized action. Only administrators or team managers can delete teams.');
    }

    // =========================================================================
    // HELPER METHODS (PRIVATE)
    // =========================================================================

    /**
     * Helper check for administrative level roles (Admin & Manager).
     *
     * @param \App\Models\User $user
     * @return bool
     */
    private function isAdminOrManager(User $user): bool
    {
        $role = strtolower(trim($user->role));

        return in_array($role, ['admin', 'manager']);
    }
}
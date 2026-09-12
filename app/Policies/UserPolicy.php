<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * =========================================================================
 * USER POLICY CLASS (ROLE-BASED AUTHORIZATION & ACCESS CONTROL)
 * =========================================================================
 * This policy handles all security rules and access permissions for User resources.
 * It strictly regulates which roles (Admin, Manager, Team Leader, Employee)
 * can view, create, update, or delete users within the application based on
 * project and team relationships.
 */
class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can view any user records in the list.
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        // =====================================================================
        // RULE 1: GLOBAL INDEX LISTING ACCESS
        // =====================================================================
        // Allow all authenticated users to open the user listing page.
        // Specific user records are filtered securely inside the controller.
        return true;
    }

    /**
     * Determine whether the user can view a specific user's details profile.
     *
     * @param \App\Models\User $user (The currently logged-in user)
     * @param \App\Models\User $model (The target user profile being viewed)
     * @return \Illuminate\Auth\Access\Response
     */
    public function view(User $user, User $model): Response
    {
        $role = strtolower(trim($user->role));

        // =====================================================================
        // RULE 2: DETAILED USER PROFILE VIEWING PERMISSIONS
        // =====================================================================

        // 1. Admin: Full system-wide visibility to view any user profile
        if ($role === 'admin') {
            return Response::allow();
        }

        // 2. Users can always view their own profile information
        if ($user->id === $model->id) {
            return Response::allow();
        }

        // 3. Manager: Can view users ONLY if they belong to projects managed by this manager
        if ($role === 'manager') {
            $isManaged = $model->teams()
                ->whereHas('project', function ($query) use ($user) {
                    $query->where('manager_id', $user->id);
                })->exists()
                || $model->projects()->where('manager_id', $user->id)->exists()
                || $model->ledTeams()->whereHas('project', function ($query) use ($user) {
                    $query->where('manager_id', $user->id);
                })->exists();

            return $isManaged
                ? Response::allow()
                : Response::deny('Unauthorized action. You can only view users associated with your managed projects.');
        }

        // 4. Team Leader: Can view users ONLY if they belong to a team led by this team leader or share the same team/project
        if ($role === 'team_leader') {
            $isLed = $model->teams()->where('team_leader_id', $user->id)->exists()
                || $model->teams()->whereIn('team_id', $user->teams()->pluck('teams.id'))->exists();

            return $isLed
                ? Response::allow()
                : Response::deny('Unauthorized action. You can only view users assigned to your projects or teams.');
        }

        // 5. Employee: Restricted from viewing other user profiles unless explicitly shared
        return Response::deny('Unauthorized action. You do not have permission to view other user profiles.');
    }

    /**
     * Determine whether the user can create new user accounts.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response
     */
    public function create(User $user): Response
    {
        // =====================================================================
        // RULE 3: USER CREATION RESTRICTIONS
        // =====================================================================
        // Only administrators are allowed to create new system user accounts.
        return $user->role === 'admin'
            ? Response::allow()
            : Response::deny('Access denied. Only administrators are allowed to create new users.');
    }

    /**
     * Determine whether the user can update an existing user's profile.
     *
     * @param \App\Models\User $user
     * @param \App\Models\User $model
     * @return \Illuminate\Auth\Access\Response
     */
    public function update(User $user, User $model): Response
    {
        $role = strtolower(trim($user->role));

        // =====================================================================
        // RULE 4: USER PROFILE UPDATE PERMISSIONS
        // =====================================================================

        // 1. Admin: Can update any user profile in the database
        if ($role === 'admin') {
            return Response::allow();
        }

        // 2. Users can update their own personal profile
        if ($user->id === $model->id) {
            return Response::allow();
        }

        // 3. Manager & Team Leader: Restricted from modifying other user profiles for security
        if (in_array($role, ['manager', 'team_leader'])) {
            return Response::deny('Managers and Team Leaders are not authorized to modify user profiles.');
        }

        // 5. Employees cannot modify other user accounts
        return Response::deny('Employees are strictly prohibited from modifying other user profiles.');
    }

    /**
     * Determine whether the user can delete a user record.
     *
     * @param \App\Models\User $user
     * @param \App\Models\User $model
     * @return \Illuminate\Auth\Access\Response
     */
    public function delete(User $user, User $model): Response
    {
        // =====================================================================
        // RULE 5: USER DELETION LOCKOUT RULES
        // =====================================================================
        // Deleting user accounts is a destructive security action restricted solely to Admins.
        return $user->role === 'admin'
            ? Response::allow()
            : Response::deny('Unauthorized action. Only administrators can delete user accounts.');
    }
}
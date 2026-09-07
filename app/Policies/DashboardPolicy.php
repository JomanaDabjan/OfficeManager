<?php

namespace App\Policies;

use App\Models\User;

// =========================================================================
// DASHBOARD POLICY CLASS DEFINITION FOR BEGINNERS
// =========================================================================

/**
 * Welcome, beginner Laravel developer! 
 * 
 * What is a Policy?
 * A Policy is a special security class in Laravel used to determine whether 
 * the currently logged-in user is authorized to perform a specific action 
 * on a model or resource (in this case, viewing the Dashboard).
 * 
 * Why do we use DashboardPolicy?
 * Instead of writing messy role-checking code directly inside controllers 
 * or blade files, we centralize all permission rules here in one clean place. 
 * This makes your code secure, organized, and very easy to maintain.
 */
class DashboardPolicy
{
    // =====================================================================
    // VIEW DASHBOARD PERMISSION CHECK
    // =====================================================================
    /**
     * Determine whether the user can view the dashboard.
     * 
     * How this method works step-by-step:
     * 1. It receives the currently authenticated user as an object ($user).
     * 2. It grabs the user's role ($user->role).
     * 3. `trim()` removes any accidental blank spaces around the role text.
     * 4. `strtolower()` converts the role to lowercase to avoid uppercase/lowercase bugs (e.g., "Admin" vs "admin").
     * 5. `in_array()` checks if this cleaned role exists inside our allowed list of roles:
     *    - 'admin'       -> System Administrator with full access
     *    - 'manager'     -> Department or Project Manager
     *    - 'team_leader' -> Leader managing specific project teams
     *    - 'employee'    -> Regular staff member
     * 
     * Return value:
     * - Returns true  -> The user IS ALLOWED to access the dashboard.
     * - Returns false -> The user IS BLOCKED and gets a 403 Forbidden error.
     * 
     * @param User $user The currently logged-in user instance.
     * @return bool Returns true if authorized, false otherwise.
     */
    public function viewDashboard(User $user): bool
    {
        return in_array(strtolower(trim($user->role)), [
            'admin',
            'manager',
            'team_leader',
            'employee',
        ]);
    }
}
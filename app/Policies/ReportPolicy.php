<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * =========================================================================
 * REPORT POLICY CLASS (ROLE-BASED AUTHORIZATION & ACCESS CONTROL)
 * =========================================================================
 * This policy handles all security rules and access permissions for Report resources.
 * It strictly regulates which roles (Admin, Manager, Team Leader, Employee) 
 * can view reports within the application based on project, task, team, 
 * and user relationships. All users have view-only access based on their scope.
 */
class ReportPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can view any report records in the list.
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        // =====================================================================
        // RULE 1: GLOBAL INDEX LISTING ACCESS
        // =====================================================================
        // Allow all authenticated users to open the report listing page.
        // Specific records are filtered securely inside the controller.
        return true;
    }

    /**
     * Determine whether the user can view a specific report details.
     *
     * @param \App\Models\User $user (The currently logged-in user)
     * @param \App\Models\Report $report (The target report being viewed)
     * @return \Illuminate\Auth\Access\Response
     */
    public function view(User $user, Report $report): Response
    {
        $role = strtolower(trim($user->role));

        // =====================================================================
        // RULE 2: DETAILED REPORT VIEWING PERMISSIONS
        // =====================================================================

        // 1. Admin: Full system-wide visibility to view any report
        if ($role === 'admin') {
            return Response::allow();
        }

        // 2. Manager: Can view reports related to projects they manage, tasks within those projects, teams belonging to those projects, or users within those projects
        if ($role === 'manager') {
            $isAuthorized = 
                // Check if the report belongs to a project managed by this manager
                ($report->project && $report->project->manager_id === $user->id) ||
                // Check if the report belongs to a task within a project managed by this manager
                ($report->task && $report->task->project && $report->task->project->manager_id === $user->id) ||
                // Check if the report belongs to a team belonging to a project managed by this manager
                ($report->team && $report->team->project && $report->team->project->manager_id === $user->id) ||
                // Check if the report belongs to a user associated with this manager's projects
                ($report->reportUser && (
                    $report->reportUser->projects()->where('manager_id', $user->id)->exists()
                ));

            return $isAuthorized
                ? Response::allow()
                : Response::deny('Unauthorized action. You can only view reports related to your managed projects, tasks, or associated teams and users.');
        }

        // 3. Team Leader: Can view reports related to their led projects, tasks, teams, or team members
        if ($role === 'team_leader') {
            $isAuthorized = 
                // Check if the report belongs to a project led by this team leader
                ($report->project && $report->project->team_leader_id === $user->id) ||
                // Check if the report belongs to a task within a project led by this team leader
                ($report->task && $report->task->project && $report->task->project->team_leader_id === $user->id) ||
                // Check if the report belongs to a team led by this team leader
                ($report->team && $report->team->team_leader_id === $user->id) ||
                // Check if the report belongs to a user associated with this team leader's projects or teams
                ($report->reportUser && (
                    $report->reportUser->projects()->where('team_leader_id', $user->id)->exists() ||
                    $report->reportUser->teams()->where('team_leader_id', $user->id)->exists() ||
                    $report->reportUser->projects()->whereHas('users', fn($q) => $q->where('user_id', $user->id))->exists() ||
                    $report->reportUser->teams()->whereHas('users', fn($q) => $q->where('user_id', $user->id))->exists()
                ));

            return $isAuthorized
                ? Response::allow()
                : Response::deny('Unauthorized action. You can only view reports related to your projects, tasks, teams, or team members.');
        }

        // 4. Employee: Can view reports related to their assigned project, assigned tasks, belonging team, or their own user report
        if ($role === 'employee') {
            $isAuthorized = 
                // Check if the report belongs to a project assigned to this employee
                ($report->project && $report->project->users()->where('user_id', $user->id)->exists()) ||
                // Check if the report belongs to a task assigned to this employee
                ($report->task && $report->task->user_id === $user->id) ||
                // Check if the report belongs to a team the employee belongs to
                ($report->team && $report->team->users()->where('user_id', $user->id)->exists()) ||
                // Check if the report belongs to the employee themselves
                ($report->user_id === $user->id);

            return $isAuthorized
                ? Response::allow()
                : Response::deny('Unauthorized action. You can only view reports related to your assigned projects, tasks, teams, or your own user report.');
        }

        return Response::deny('Unauthorized action. You do not have permission to view this report.');
    }

    /**
     * Determine whether the user can create new reports.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response
     */
    public function create(User $user): Response
    {
        // =====================================================================
        // RULE 3: REPORT CREATION RESTRICTIONS (VIEW-ONLY ENFORCEMENT)
        // =====================================================================
        // As requested, reporting permissions are restricted to viewing only for all roles.
        return Response::deny('Creation of reports is restricted. All roles have view-only access.');
    }

    /**
     * Determine whether the user can update an existing report.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Report $report
     * @return \Illuminate\Auth\Access\Response
     */
    public function update(User $user, Report $report): Response
    {
        // =====================================================================
        // RULE 4: REPORT UPDATE RESTRICTIONS (VIEW-ONLY ENFORCEMENT)
        // =====================================================================
        // All roles are restricted to view-only capabilities for reports.
        return Response::deny('Modification of reports is restricted. All roles have view-only access.');
    }

    /**
     * Determine whether the user can delete a report record.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Report $report
     * @return \Illuminate\Auth\Access\Response
     */
    public function delete(User $user, Report $report): Response
    {
        // =====================================================================
        // RULE 5: REPORT DELETION RESTRICTIONS (VIEW-ONLY ENFORCEMENT)
        // =====================================================================
        // Deletion is blocked to preserve report history; view-only access applies.
        return Response::deny('Deletion of reports is restricted. All roles have view-only access.');
    }
}
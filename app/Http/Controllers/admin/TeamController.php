<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\Project;
use App\Models\User;
use App\Http\Requests\TeamStoreRequest; // Import the Store Form Request
use App\Http\Requests\TeamUpdateRequest; // Import the Update Form Request
use App\Http\Requests\MemberRequest; // Import the Member Request for validation
use Illuminate\Support\Facades\DB; // Import DB facade for database transactions
use Illuminate\Http\Request; // Import Request class for handling HTTP requests
use Illuminate\Support\Facades\Log; // Import Log facade for error logging
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth; // Import Auth facade for user authentication

/*
|--------------------------------------------------------------------------
| TeamController Class
|--------------------------------------------------------------------------
| This controller handles all HTTP requests related to team management.
| It acts as the bridge between the database (Models) and the user interface (Views),
| allowing admins to view, create, edit, update, and delete teams safely.
*/

class TeamController extends Controller
{
    use AuthorizesRequests; // Include authorization trait for policy checks
    /*
    |--------------------------------------------------------------------------
    | Display a listing of the teams.
    |--------------------------------------------------------------------------
    | This method fetches all teams from the database along with their related
    | projects, project managers, and counts how many members belong to each team.
    | It also applies filtering via the model scope and provides data for dropdowns.
    */

    public function index(Request $request)
    {
        $this->authorize('viewAny', Team::class);

        $user = Auth::user();

        $teams = Team::with(['project.manager', 'leader', 'members'])
            ->forUser($user)
            ->filter($request->only([
                'team_name',
                'project_id',
                'team_leader_id',
            ]))
            ->withCount('members')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $allTeamNames = Team::availableNamesFor($user);

        $projects = Project::availableFor($user)->get();

        $leaders = User::availableTeamLeadersFor($user)->get();

        return view(
            'contents.team.Index',
            compact(
                'teams',
                'allTeamNames',
                'projects',
                'leaders'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Show the form for creating a new team.
    |--------------------------------------------------------------------------
    | This method retrieves all available projects and employee users from the
    | database and passes them to the create view so admins can select a project
    | and assign members when building a new team.
    */

    public function create()
    {
        $this->authorize('create', Team::class);

        /* Fetch all projects to link the team to one */
        $projects = Project::all();

        /* Fetch all users whose role is an employee to choose team members */
        $employees = User::where('role', 'employee')->get();

        /* Fetch all users who can act as Team Leaders (adjust the condition based on your roles system) */
        $teamLeaders = User::where('role', 'team_leader')->get();

        /* Return the create form view with projects, employees, and team leaders data */
        return view('contents.team.Create', compact('projects', 'employees', 'teamLeaders'));
    }

    /*
    |--------------------------------------------------------------------------
    | Store a newly created team in storage.
    |--------------------------------------------------------------------------
    | This method uses TeamStoreRequest for automatic validation. It wraps
    | database operations inside explicit DB transaction methods (beginTransaction,
    | commit, rollback) within a try-catch block to ensure data integrity
    | and log any failure securely.
    */

    public function store(TeamStoreRequest $request)
    {
        $this->authorize('create', Team::class);

        DB::beginTransaction();

        try {
            /* Create a new team record using already validated data from the Form Request */
            $team = Team::create($request->validated());

            /* Check if members were provided, then sync them via pivot table */
            if ($request->has('members')) {
                $team->members()->sync($request->members);
            }

            DB::commit();

            /* Redirect back to the teams table list with a success notification message */
            return redirect()->route('admin.team.index')->with('success', 'Team created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing team: ' . $e->getMessage());

            return redirect()->back()->withInput()->with('error', 'An error occurred while creating the team.');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Display the specified team details (Route Model Binding).
    |--------------------------------------------------------------------------
    | By using Route Model Binding (type-hinting Team $team), Laravel automatically
    | queries the database for the team and throws a 404 error if it doesn't exist.
    */

    public function show(Team $team)
    {
        $this->authorize('view', $team);

        /* Load related project details, project manager, team members, and associated tasks */
        $team->load(['project.manager', 'members', 'tasks.assignedUser']);

        /* Return the show view with the specific team data object */
        return view('contents.team.Show', compact('team'));
    }

    /*
    |--------------------------------------------------------------------------
    | Show the form for adding new members to an existing team.
    |--------------------------------------------------------------------------
    | This method retrieves the specified team and all employee users,
    | then returns a view dedicated to adding members.
    */

    public function createMembers(Team $team)
    {
        $this->authorize('update', $team);

        $currentUser = auth()->user();

        $employees = User::whereIn('role', ['employee', 'team_leader'])
            ->where('status', '!=', 'deactivated')
            ->get();

        $filteredEmployees = $employees;
        $projectManagerId = $team->manager ? $team->manager->id : null;

        if ($currentUser) {
            $userRole = strtolower($currentUser->role ?? '');

            if ($userRole === 'team_leader' || $userRole === 'manager' || $userRole === 'admin') {
                $filteredEmployees = $employees->filter(function ($employee) use ($currentUser, $team, $projectManagerId, $userRole) {

                    // 1. التعديل الحاسم: استبعاد أي شخص رتبته الحالية team_leader من قائمة الأعضاء الجدد
                    if ($employee->role === 'team_leader') {
                        return false;
                    }

                    // 2. استبعاد قائد الفريق الحالي للفريق نفسه (إذا كان قد تحول لـ employee صدفة)
                    if ($team->team_leader_id && $employee->id == $team->team_leader_id) {
                        return false;
                    }

                    // 3. استبعاد الموظفين الذين يقودون فرقاً أخرى مسبقاً
                    $isLeadingOtherTeam = \App\Models\Team::where('team_leader_id', $employee->id)
                        ->where('id', '!=', $team->id)
                        ->exists();

                    if ($isLeadingOtherTeam) {
                        return false;
                    }

                    $employeePos = strtolower(trim($employee->position ?? ''));
                    $cleanEmployeePos = str_replace([' ', '-', '_', '.'], '', $employeePos);

                    $cleanTeamName = strtolower(str_replace(['team', '_', '-', ' ', '.', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0'], '', $team->name));

                    // فحص ما إذا كان الفريق هو Full Stack
                    $isFullStackTeam = str_contains($cleanTeamName, 'fullstack');
                    $currentUserPos = strtolower(trim($currentUser->position ?? ''));
                    $cleanUserPos = str_replace([' ', '-', '_', '.'], '', $currentUserPos);
                    $isUserFullStack = str_contains($cleanUserPos, 'fullstack');

                    if ($isFullStackTeam || $isUserFullStack) {
                        return str_contains($cleanEmployeePos, 'fullstack');
                    }

                    // 4. شروط الـ Team Leader للفرق الأخرى
                    if ($userRole === 'team_leader') {
                        if ($projectManagerId && $employee->id == $projectManagerId) {
                            return false;
                        }

                        $isCurrentUser = ($employee->id == $currentUser->id);
                        if ($isCurrentUser) {
                            return true;
                        }

                        return ($cleanEmployeePos === $cleanUserPos);
                    }

                    // 5. شروط الـ Manager والـ Admin
                    if ($userRole === 'manager' || $userRole === 'admin') {
                        return !empty($cleanEmployeePos) && (
                            str_contains($cleanTeamName, $cleanEmployeePos) ||
                            str_contains($cleanEmployeePos, $cleanTeamName)
                        );
                    }

                    return false;
                });
            }
        }

        $groupedEmployees = $filteredEmployees->groupBy(function ($employee) {
            $pos = strtolower(str_replace([' ', '-', '_'], '', $employee->position ?? ''));
            if (str_contains($pos, 'fullstack')) {
                return 'Full Stack';
            }
            return $employee->position ?: 'Unspecified Position';
        });

        return view('contents.team.members.MemberCreate', compact('team', 'filteredEmployees', 'groupedEmployees'));
    }

    /*
    |--------------------------------------------------------------------------
    | Store newly added members for an existing team.
    |--------------------------------------------------------------------------
    | This method uses MemberRequest for automatic validation, then appends
    | the new members to the team using syncWithoutDetaching within explicit
    | database transaction methods and error handling.
    */

    public function storeMembers(MemberRequest $request, Team $team)
    {
        $this->authorize('update', $team);

        DB::beginTransaction();

        try {
            $team->members()->syncWithoutDetaching($request->validated('members'));

            DB::commit();

            return redirect()->route('admin.team.show', $team->id)->with('success', 'Members added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error adding team members: ' . $e->getMessage());

            return redirect()->back()->withInput()->with('error', 'An error occurred while adding members.');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Remove a specific member from the specified team.
    |--------------------------------------------------------------------------
    | This method detaches a single member from the team's pivot table safely
    | using explicit database transactions and error handling.
    */

    public function destroyMember(Team $team, $memberId)
    {
        $this->authorize('update', $team);

        DB::beginTransaction();

        try {
            // Detach the specific member from the team
            $team->members()->detach($memberId);

            DB::commit();

            return redirect()->route('admin.team.show', $team->id)->with('success', 'Member removed from team successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error removing team member: ' . $e->getMessage());

            return redirect()->route('admin.team.show', $team->id)->with('error', 'An error occurred while removing the member.');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Show the form for editing the specified team.
    |--------------------------------------------------------------------------
    | This method receives the team via Route Model Binding, along with all projects
    | and employees, so the admin can modify existing team selections.
    */

    public function edit(Team $team)
    {
        $this->authorize('update', $team);

        /* Fetch all projects to allow changing the linked project if needed */
        $projects = Project::all();

        /* Fetch all employee users to update team memberships */
        $employees = User::where('role', 'employee')->get();

        /* Fetch team leaders for updating */
        $teamLeaders = User::whereIn('role', ['team_leader'])->get();

        /* Return the edit view with the team, projects, employees, and team leaders data */
        return view('contents.team.Edit', compact('team', 'projects', 'employees', 'teamLeaders'));
    }

    /*
    |--------------------------------------------------------------------------
    | Update the specified team in storage.
    |--------------------------------------------------------------------------
    | This method uses TeamUpdateRequest for validation and explicit DB transactions
    | (beginTransaction, commit, rollback) wrapped in a try-catch block to safely
    | handle updates and log errors.
    */

    public function update(TeamUpdateRequest $request, Team $team)
    {
        $this->authorize('update', $team);

        DB::beginTransaction();

        try {
            /* Update the team record using validated data from TeamUpdateRequest */
            $team->update($request->validated());

            // تم حذف سطر الـ sync هنا لكي لا يتم تفريغ الأعضاء عند تحديث حقول الفريق الأساسية

            DB::commit();

            /* Redirect back to the teams table list with a success message */
            return redirect()->route('admin.team.index')->with('success', 'Team updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating team: ' . $e->getMessage());

            return redirect()->back()->withInput()->with('error', 'An error occurred while updating the team.');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Remove the specified team from storage.
    |--------------------------------------------------------------------------
    | This method deletes the team safely using explicit database transaction
    | controls and error handling with logging.
    |--------------------------------------------------------------------------
    | */

    public function destroy(Team $team)
    {
        $this->authorize('delete', $team);

        DB::beginTransaction();

        try {
            /* Detach all members from the pivot table before deleting the team */
            $team->members()->detach();

            /* Delete the team record from the database */
            $team->delete();

            DB::commit();

            /* Redirect back to the teams index with a success notification */
            return redirect()->route('admin.team.index')->with('success', 'Team deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting team: ' . $e->getMessage());

            return redirect()->route('admin.team.index')->with('error', 'An error occurred while deleting the team.');
        }
    }
}
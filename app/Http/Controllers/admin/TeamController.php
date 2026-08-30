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
        /* Fetch teams with pagination, eager loading, filtering scope, and member count */
        $teams = Team::with(['project.manager', 'leader', 'members'])
            ->filter($request->all()) // Apply the local scope filter for team name, project, and leader
            ->withCount('members')
            ->paginate(10);

        /* Fetch data required for the filtering dropdowns in the UI */
        $allTeamNames = Team::pluck('name')->unique();
        $projects = Project::all();
        $leaders = User::all(); // Adjust based on your leader role system

        /* Return the index view located in resources/views/contents/team/Index.blade.php with all data */
        return view('contents.team.Index', compact('teams', 'allTeamNames', 'projects', 'leaders'));
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
        /* Fetch all projects to link the team to one */
        $projects = Project::all();

        /* Fetch all users whose role is an employee to choose team members */
        $employees = User::where('role', 'employee')->get();

        /* Fetch all users who can act as Team Leaders (adjust the condition based on your roles system) */
        $teamLeaders = User::where('role', 'employee')->get(); // أو استبدلها بالشرط الخاص بقادة الفرق لديك

        /* Return the create form view with projects, employees, and team leaders data */
        return view('contents.team.Create', compact('projects', 'employees', 'teamLeaders'));
    }

    /*
    |--------------------------------------------------------------------------
    | Store a newly created team in storage.
    |--------------------------------------------------------------------------
    | This method uses TeamStoreRequest for automatic validation. It wraps
    | database operations inside a DB transaction to ensure data integrity
    | when creating the team and syncing its members.
    */
    public function store(TeamStoreRequest $request)
    {
        /* Use database transaction to safely roll back if any error occurs */
        return DB::transaction(function () use ($request) {
            /* Create a new team record using already validated data from the Form Request */
            $team = Team::create($request->validated());

            /* Check if members were provided, then sync them via pivot table */
            if ($request->has('members')) {
                $team->members()->sync($request->members);
            }

            /* Redirect back to the teams table list with a success notification message */
            return redirect()->route('admin.team.index')->with('success', 'Team created successfully.');
        });
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
        $employees = User::where('role', 'employee')->get();
        return view('contents.team.members.create', compact('team', 'employees'));
    }

    /*
    |--------------------------------------------------------------------------
    | Store newly added members for an existing team.
    |--------------------------------------------------------------------------
    | This method uses MemberRequest for automatic validation, then appends
    | the new members to the team using syncWithoutDetaching to keep old members.
    */
    public function storeMembers(MemberRequest $request, Team $team)
    {
        return DB::transaction(function () use ($request, $team) {
            $team->members()->syncWithoutDetaching($request->validated('members'));

            return redirect()->route('admin.team.show', $team->id)->with('success', 'Members added successfully.');
        });
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
        /* Fetch all projects to allow changing the linked project if needed */
        $projects = Project::all();

        /* Fetch all employee users to update team memberships */
        $employees = User::where('role', 'employee')->get();

        /* Fetch team leaders for updating */
        $teamLeaders = User::whereIn('role', ['manager', 'admin'])->get();

        /* Return the edit view with the team, projects, employees, and team leaders data */
        return view('contents.team.Edit', compact('team', 'projects', 'employees', 'teamLeaders'));
    }

    /*
    |--------------------------------------------------------------------------
    | Update the specified team in storage.
    |--------------------------------------------------------------------------
    | This method uses TeamUpdateRequest for validation and DB transactions
    | to safely update team details and sync updated member selections.
    */
    public function update(TeamUpdateRequest $request, Team $team)
    {
        /* Use database transaction to ensure safe updates */
        return DB::transaction(function () use ($request, $team) {
            /* Update the team record using validated data from TeamUpdateRequest */
            $team->update($request->validated());

            /* Sync team members (if members field is absent, pass an empty array to clear/sync) */
            $team->members()->sync($request->input('members', []));

            /* Redirect back to the teams table list with a success message */
            return redirect()->route('admin.team.index')->with('success', 'Team updated successfully.');
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Remove the specified team from storage.
    |--------------------------------------------------------------------------
    | This method deletes the team safely using a database transaction.
    | Pivot table records are automatically unlinked via cascading rules or model events.
    |--------------------------------------------------------------------------
    */
    public function destroy(Team $team)
    {
        /* Use database transaction for safe deletion */
        return DB::transaction(function () use ($team) {
            /* Detach all members from the pivot table before deleting the team */
            $team->members()->detach();

            /* Delete the team record from the database */
            $team->delete();

            /* Redirect back to the teams index with a success notification */
            return redirect()->route('admin.team.index')->with('success', 'Team deleted successfully.');
        });
    }
}
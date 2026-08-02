<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class ProjectController extends Controller
{
    // =========================================================================
    // INDEX METHOD: LIST PROJECTS
    // =========================================================================

    /**
     * Display a listing of projects filtered by the user's role.
     */
    public function index()
    {
        // Retrieve the currently authenticated user instance
        $user = Auth::user();

        // Admin sees all projects; Manager sees their own; Employee sees assigned projects.
        if ($user->role === 'admin') {
            // Fetch all projects with manager relationship for admin users
            $projects = Project::with('manager')->paginate(10);
        } elseif ($user->role === 'manager') {
            // Fetch only projects managed by the specific manager ID
            $projects = Project::where('manager_id', $user->id)->paginate(10);
        } else {
            // Fetch projects associated with the authenticated employee
            $projects = $user->projects()->paginate(10);
        }

        // Return the index view with paginated project results
        return view('contents.project.Index', compact('projects'));
    }

    // =========================================================================
    // CREATE METHODS: SHOW FORM & STORE DATA
    // =========================================================================

    /**
     * Show the form for creating a new project.
     * (Restricted to Admin)
     */
    public function create()
    {
        // Verify if the user has administrative privileges
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        // Return the project creation view
        return view('contents.project.Create');
    }

    /**
     * Store a newly created project in the database.
     * (Restricted to Admin)
     */
    public function store(ProjectStoreRequest $request)
    {
        // Double-check admin authorization before processing storage
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        try {
            // Initiate database transaction to ensure data integrity
            DB::beginTransaction();

            // Create the project using validated request data
            Project::create($request->validated());

            // Commit transaction if insertion succeeds
            DB::commit();

            // Redirect back to project listing with success message
            return redirect()->route('admin.project.index')->with('success', 'Project created successfully.');
        } catch (Exception $e) {
            // Roll back database changes on failure
            DB::rollBack();

            // Return back with input retention and error details
            return redirect()->back()->with('error', 'The Error Is: ' . $e->getMessage())->withInput();
        }
    }

    // =========================================================================
    // SHOW METHOD: DISPLAY SINGLE PROJECT DETAILS
    // =========================================================================

    /**
     * Display the specified project details with authorization checks.
     */
    public function show(Project $project)
    {
        $user = Auth::user();

        // Security Check: Ensure Managers and Employees only access projects they are linked to.
        if ($user->role === 'manager' && $project->manager_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }
        if ($user->role === 'employee' && !$project->users()->where('user_id', $user->id)->exists()) {
            abort(403, 'Unauthorized access.');
        }

        // Eager load related manager, tasks, and assigned users data
        $project->load(['manager', 'tasks', 'users']);

        // Return the detailed view of the project
        return view('contents.project.Show', compact('project'));
    }

    // =========================================================================
    // EDIT & UPDATE METHODS: MODIFY EXISTING PROJECTS
    // =========================================================================

    /**
     * Show the form for editing the project.
     */
    public function edit(Project $project)
    {
        $user = Auth::user();

        // Employees cannot access the edit interface
        if ($user->role === 'employee') {
            abort(403, 'Unauthorized access.');
        }

        // Manager specific security check for editing.
        if ($user->role === 'manager' && $project->manager_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        // Return the edit view passing the project instance
        return view('contents.project.Edit', compact('project'));
    }

    /**
     * Update the project in the database.
     */
    public function update(ProjectUpdateRequest $request, Project $project)
    {
        $user = Auth::user();

        // Employees cannot execute updates
        if ($user->role === 'employee') {
            abort(403, 'Unauthorized access.');
        }

        try {
            // Start database transaction block
            DB::beginTransaction();

            // Retrieve validated input data array
            $data = $request->validated();

            // Manager restriction: Managers cannot change the project manager_id.
            if ($user->role === 'manager') {
                if ($project->manager_id !== $user->id) {
                    abort(403, 'Unauthorized access.');
                }
                // Unset manager_id to prevent unauthorized reassignment
                unset($data['manager_id']);
            }

            // Update project record with processed data
            $project->update($data);

            // Commit changes to database
            DB::commit();

            // Redirect to project index with success feedback
            return redirect()->route('admin.project.index')->with('success', 'Project updated successfully.');
        } catch (Exception $e) {
            // Revert transaction in case of any exceptions
            DB::rollBack();

            // Redirect back with old input values and error message
            return redirect()->back()->with('error', 'The Error Is: ' . $e->getMessage())->withInput();
        }
    }

    // =========================================================================
    // DESTROY METHOD: DELETE PROJECT
    // =========================================================================

    /**
     * Remove the project from storage.
     * (Restricted to Admin)
     */
    public function destroy(Project $project)
    {
        // Enforce strict administrative authorization for deletion
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        try {
            // Delete the project instance from storage
            $project->delete();

            // Redirect to index with success notification
            return redirect()->route('admin.project.index')->with('success', 'Project deleted.');
        } catch (Exception $e) {
            // Redirect back capturing deletion errors
            return redirect()->back()->with('error', 'The Error Is: ' . $e->getMessage())->withInput();
        }
    }
}
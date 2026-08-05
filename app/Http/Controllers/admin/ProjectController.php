<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

/**
 * =========================================================================
 * PROJECT CONTROLLER CLASS
 * =========================================================================
 * This controller manages all CRUD (Create, Read, Update, Delete) operations
 * for projects, including role-based authorization for Admins, Managers, and Employees.
 */
class ProjectController extends Controller
{
    // =========================================================================
    // INDEX METHOD: LIST & FILTER PROJECTS
    // =========================================================================

    /**
     * Display a listing of projects filtered by user roles, title, manager, and status.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // -----------------------------------------------------------------
        // STEP 1: Get currently authenticated user instance
        // -----------------------------------------------------------------
        $user = Auth::user();

        // -----------------------------------------------------------------
        // STEP 2: Initialize query based on user role permissions
        // -----------------------------------------------------------------
        if ($user->role === 'admin') {
            // Admin sees all system projects with eager loaded manager relationship
            $query = Project::with('manager');
        } elseif ($user->role === 'manager') {
            // Manager sees only projects assigned specifically to their manager ID
            $query = Project::where('manager_id', $user->id);
        } else {
            // Regular employee sees only projects associated directly with their user account
            $query = $user->projects();
        }

        // =====================================================================
        // SERVER-SIDE FILTERING LOGIC
        // =====================================================================

        // Apply specific project title filter if selected from the dropdown
        if ($request->filled('title') && $request->title !== 'all') {
            $query->where('title', $request->title);
        }

        // Apply manager filter if selected and user is admin (managers/employees see their own context)
        if ($request->filled('manager_id') && $request->manager_id !== 'all') {
            $query->where('manager_id', $request->manager_id);
        }

        // Apply status filter if provided and not set to the default 'all' option
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Apply general text search filter across title or description fields
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Fetch paginated project results and preserve query parameters in pagination links
        $projects = $query->latest()->paginate(10)->appends($request->query());

        // =====================================================================
        // DROPDOWN DATA FETCHING FOR VIEW FILTERS
        // =====================================================================

        // Retrieve unique project titles for the dropdown filter options
        $allTitles = Project::select('title')->distinct()->pluck('title');

        // Retrieve unique users who act as project managers to populate the manager dropdown
        $managers = User::whereHas('managedProjects')->select('id', 'name')->distinct()->get();

        // Return the project index view with paginated records and filter collections
        return view('contents.project.Index', compact('projects', 'allTitles', 'managers'));
    }

    // =========================================================================
    // CREATE METHODS: SHOW FORM & STORE DATA
    // =========================================================================

    /**
     * Show the form for creating a new project.
     * (Restricted to Admin)
     *
     * @return \Illuminate\View\View
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
     *
     * @param \App\Http\Requests\ProjectStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
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
     *
     * @param \App\Models\Project $project
     * @return \Illuminate\View\View
     */
    public function show(Project $project)
    {
        // Get currently authenticated user instance
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
     *
     * @param \App\Models\Project $project
     * @return \Illuminate\View\View
     */
    public function edit(Project $project)
    {
        // Get currently authenticated user instance
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
     *
     * @param \App\Http\Requests\ProjectUpdateRequest $request
     * @param \App\Models\Project $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProjectUpdateRequest $request, Project $project)
    {
        // Get currently authenticated user instance
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
     *
     * @param \App\Models\Project $project
     * @return \Illuminate\Http\RedirectResponse
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

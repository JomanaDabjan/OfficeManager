<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * =========================================================================
 * PROJECT CONTROLLER CLASS
 * =========================================================================
 * This controller manages all CRUD (Create, Read, Update, Delete) operations
 * for projects. It utilizes Laravel Policies for security, Form Requests for
 * validation, and Implicit Route Model Binding for automatic record retrieval.
 */
class ProjectController extends Controller
{
    use AuthorizesRequests;
    /**
     * =====================================================================
     * DISPLAY PROJECT LISTING
     * =====================================================================
     * Display a paginated list of projects with filtering, searching, and
     * role-based access permissions tailored to the authenticated user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Get the currently authenticated user instance
        $user = Auth::user();

        // -----------------------------------------------------------------
        // 1. QUERY EXECUTION WITH MODEL LOCAL SCOPE
        // -----------------------------------------------------------------
        // Fetch projects, apply role permissions, search filters, and order
        // by latest while maintaining query parameters in pagination links.
        $projects = Project::with('manager')
            ->filterAndSearch($user, $request)
            ->latest()
            ->paginate(10)
            ->appends($request->query());

        // -----------------------------------------------------------------
        // 2. PREPARING DATA FOR VIEW FILTER DROPDOWNS
        // -----------------------------------------------------------------
        // Fetch unique project titles and managers who actually manage projects.
        $allTitles = Project::select('title')->distinct()->pluck('title');
        $managers  = User::whereHas('managedProjects')->select('id', 'name')->distinct()->get();

        // Return view with packed data variables
        return view('contents.project.Index', compact('projects', 'allTitles', 'managers'));
    }

    /**
     * =====================================================================
     * SHOW CREATE PROJECT FORM
     * =====================================================================
     * Display the form required to create a new project.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Fetch all users with role 'manager'
        $managers = User::where('role', 'manager')->get();

        // Authorize action using ProjectPolicy (Throws 403 if unauthorized)
        $this->authorize('create', Project::class);

        return view('contents.project.Create', compact('managers'));
    }

    /**
     * =====================================================================
     * STORE A NEW PROJECT
     * =====================================================================
     * Validate and save a newly created project inside a secure database
     * transaction to maintain complete data integrity.
     *
     * @param \App\Http\Requests\ProjectStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ProjectStoreRequest $request)
    {
        // Double check creation authorization via policy
        $this->authorize('create', Project::class);

        try {
            // Start database transaction block
            DB::beginTransaction();

            // Create the project record using validated form request data
            Project::create($request->validated());

            // Commit transaction if everything succeeds
            DB::commit();

            return redirect()->route('admin.project.index')->with('success', 'Project created successfully.');
        } catch (Exception $e) {
            // Rollback database changes if any error occurs
            DB::rollBack();

            // Log the actual error message for developer debugging
            Log::error('Project Creation Error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Something went wrong while creating the project. Please try again later.')
                ->withInput();
        }
    }

    /**
     * =====================================================================
     * DISPLAY SPECIFIC PROJECT DETAILS
     * =====================================================================
     * Show detailed information for a specific project instance using
     * Implicit Route Model Binding (Laravel automatically fetches the Project).
     *
     * @param \App\Models\Project $project
     * @return \Illuminate\View\View
     */
    public function show(Project $project)
    {
        // Authorize viewing capability through policy
        $this->authorize('view', $project);

        // Eager load related manager, tasks, and users relationships
        $project->load(['manager', 'tasks', 'users']);

        return view('contents.project.Show', compact('project'));
    }

    /**
     * =====================================================================
     * SHOW EDIT PROJECT FORM
     * =====================================================================
     * Display the form to modify an existing project using Route Model Binding.
     *
     * @param \App\Models\Project $project
     * @return \Illuminate\View\View
     */
    public function edit(Project $project)
    {
        // Fetch all users with role 'manager'
        $managers = User::where('role', 'manager')->get();

        // Authorize edit action on this specific project
        $this->authorize('update', $project);

        return view('contents.project.Edit', compact('project', 'managers'));
    }

    /**
     * =====================================================================
     * UPDATE AN EXISTING PROJECT
     * =====================================================================
     * Process updates using FormRequest and Implicit Route Model Binding.
     *
     * @param \App\Http\Requests\ProjectUpdateRequest $request
     * @param \App\Models\Project $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProjectUpdateRequest $request, Project $project)
    {
        // Authorize update action via policy
        $this->authorize('update', $project);

        try {
            // Start database transaction block
            DB::beginTransaction();

            $data = $request->validated();

            // Security check: Prevent regular managers from changing the manager_id field
            if (Auth::user()->role === 'manager') {
                unset($data['manager_id']);
            }

            // Update the database record using the bound model instance
            $project->update($data);

            // Commit transaction if everything succeeds
            DB::commit();

            return redirect()->route('admin.project.index')->with('success', 'Project updated successfully.');
        } catch (Exception $e) {
            // Rollback database changes if any error occurs
            DB::rollBack();

            // Log the error message for developer debugging
            Log::error('Project Update Error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Something went wrong while updating the project. Please try again later.')
                ->withInput();
        }
    }

    /**
     * =====================================================================
     * DELETE A PROJECT
     * =====================================================================
     * Securely remove a project record from the database using Model Binding.
     *
     * @param \App\Models\Project $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Project $project)
    {
        // Authorize deletion through policy (Restricted exclusively to admins)
        $this->authorize('delete', $project);

        try {
            // Start database transaction block
            DB::beginTransaction();

            // Delete the database row entry
            $project->delete();

            // Commit transaction if everything succeeds
            DB::commit();

            return redirect()->route('admin.project.index')->with('success', 'Project deleted successfully.');
        } catch (Exception $e) {
            // Rollback database changes if any error occurs
            DB::rollBack();

            // اعرض الخطأ مباشرة على الشاشة للتأكد من السبب
            dd($e->getMessage());

            // Log the error message for developer debugging
            //Log::error('Project Deletion Error: ' . $e->getMessage());

            //return redirect()->back()->with('error', 'Something went wrong while deleting the project. Please try again later.');
        }
    }
}
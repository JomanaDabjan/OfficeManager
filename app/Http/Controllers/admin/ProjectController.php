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
    /**
     * Define middleware to manage access control based on user roles.
     */
    public function __construct()
    {
        // 1. Admin: Has full access to all methods
        $this->middleware('role:admin');

        // 2. Project Manager: Has specific access to edit and update,
        // but not to delete or create new projects via this route
        $this->middleware('role:manager')->only(['index', 'show', 'edit', 'update']);

        // 3. Employee: Has restricted access, only to view projects and their details
        $this->middleware('role:employee')->only(['index', 'show']);
    }

    /**
     * Display a listing of projects filtered by the user's role.
     */
    public function index()
    {
        $user = Auth::user();

        // Admin sees all projects; Manager sees their own; Employee sees assigned projects.
        if ($user->role === 'admin') {
            $projects = Project::with('manager')->paginate(10);
        } elseif ($user->role === 'manager') {
            $projects = Project::where('manager_id', $user->id)->paginate(10);
        } else {
            $projects = $user->projects()->paginate(10);
        }

        return view('admin.contents.tables.ShowProjects', compact('projects'));
    }

    /**
     * Show the form for creating a new project.
     */
    public function create()
    {
        return view('admin.contents.forms.CreateProject');
    }

    /**
     * Store a newly created project in the database.
     */
    public function store(ProjectStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            Project::create($request->validated());
            DB::commit();
            return redirect()->route('admin.projects.index')->with('success', 'Project created successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'The Error Is: ' . $e->getMessage())->withInput();
        }
    }

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

        $project->load(['manager', 'tasks', 'users']);
        return view('admin.contents.details.ShowProjectDetails', compact('project'));
    }

    /**
     * Show the form for editing the project.
     */
    public function edit(Project $project)
    {
        // Manager specific security check for editing.
        if (Auth::user()->role === 'manager' && $project->manager_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        return view('admin.contents.forms.EditProject', compact('project'));
    }

    /**
     * Update the project in the database.
     */
    public function update(ProjectUpdateRequest $request, Project $project)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            // Manager restriction: Managers cannot change the project manager_id.
            if (Auth::user()->role === 'manager') {
                $project->manager_id !== Auth::id() ? abort(403) : null;
                unset($data['manager_id']);
            }

            $project->update($data);
            DB::commit();
            return redirect()->route('admin.projects.index')->with('success', 'Project updated successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'The Error Is: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the project from storage.
     */
    public function destroy(Project $project)
    {
        try {
            $project->delete();
            return redirect()->route('admin.projects.index')->with('success', 'Project deleted.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'The Error Is: ' . $e->getMessage())->withInput();
        }
    }
}
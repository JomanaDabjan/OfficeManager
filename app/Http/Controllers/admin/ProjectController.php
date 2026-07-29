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

        return view('contents.project.Index', compact('projects'));
    }

    /**
     * Show the form for creating a new project.
     * (Restricted to Admin)
     */
    public function create()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        return view('contents.project.Create');
    }

    /**
     * Store a newly created project in the database.
     * (Restricted to Admin)
     */
    public function store(ProjectStoreRequest $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        try {
            DB::beginTransaction();
            Project::create($request->validated());
            DB::commit();
            return redirect()->route('admin.project.index')->with('success', 'Project created successfully.');
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
        return view('contents.project.Show', compact('project'));
    }

    /**
     * Show the form for editing the project.
     */
    public function edit(Project $project)
    {
        $user = Auth::user();

        // Employees cannot edit
        if ($user->role === 'employee') {
            abort(403, 'Unauthorized access.');
        }

        // Manager specific security check for editing.
        if ($user->role === 'manager' && $project->manager_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        return view('contents.project.Edit', compact('project'));
    }

    /**
     * Update the project in the database.
     */
    public function update(ProjectUpdateRequest $request, Project $project)
    {
        $user = Auth::user();

        // Employees cannot update
        if ($user->role === 'employee') {
            abort(403, 'Unauthorized access.');
        }

        try {
            DB::beginTransaction();

            $data = $request->validated();

            // Manager restriction: Managers cannot change the project manager_id.
            if ($user->role === 'manager') {
                if ($project->manager_id !== $user->id) {
                    abort(403, 'Unauthorized access.');
                }
                unset($data['manager_id']);
            }

            $project->update($data);
            DB::commit();
            return redirect()->route('admin.project.index')->with('success', 'Project updated successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'The Error Is: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the project from storage.
     * (Restricted to Admin)
     */
    public function destroy(Project $project)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        try {
            $project->delete();
            return redirect()->route('admin.project.index')->with('success', 'Project deleted.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'The Error Is: ' . $e->getMessage())->withInput();
        }
    }
}

<?php

namespace App\Http\Controllers\projectmanager;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProManagerProjectUpdateRequest;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // This function will display just the projects that are managed by the currently authenticated user (the project manager). It will retrieve the projects from the database where the manager_id matches the id of the currently authenticated user. The results will be paginated, showing 10 projects per page.
        $projects = Project::where('manager_id', auth()->id())->paginate(10);
        return view('project_manager.contents.tables.ShowProjects', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort(403);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProManagerProjectUpdateRequest $request)
    {
        abort(403);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        // This function will display the details of a specific project. It will first check if the authenticated user is the manager of the project. If the authenticated user is not the manager, a 403 Forbidden response will be returned. If the authenticated user is the manager, the function will load the related tasks and users associated with the project and return a view to display the project details.

        if ($project->manager_id !== auth()->id()) {
            abort(403, 'You are not authorized to view this project.');
        }
        // It will load the related tasks and users associated with the project and return a view to display the project details.
        $project->load(['tasks', 'users']);
        return view('project_manager.contents.details.ShowProjectDetails', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        // Security check to ensure that the authenticated user is the manager of the project. If not, a 403 Forbidden response will be returned. If the authenticated user is the manager, the function will return a view to display the form for editing the project details.
        if ($project->manager_id !== Auth::id()) {
            abort(403, 'You are not authorized to edit this project.');
        }

        return view('project_manager.contents.updateform.ProjectUpdateForm', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProManagerProjectUpdateRequest $request, Project $projectmanagerpro)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $projectmanagerpro->update($data);
            DB::commit();
            return redirect()->route('projectmanagerpro.index')->with('success', 'Project updated successfully');
        } catch (Exception $ex) {
            DB::rollback();
            return redirect()->back()->with('error', 'The Error Is:' . $ex->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        abort(403);
    }
}

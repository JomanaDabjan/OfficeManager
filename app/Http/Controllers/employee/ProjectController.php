<?php

namespace App\Http\Controllers\employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the projects assigned to the current employee.
     */
    public function index()
    {
        // Retrieve only the projects the authenticated employee is assigned to
        // using the many-to-many relationship defined in the User model.
        $projects = Auth::user()->projects()->paginate(10);

        return view('employee.contents.tables.ShowProjects', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified project details.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\View\View
     */
    public function show(Project $project)
    {
        // Security check: Ensure the employee is a member of this project before allowing access to its details.
        if (!$project->users()->where('user_id', Auth::id())->exists()) {
            abort(403, 'You are not authorized to view this project.');
        }

        // Load only the tasks assigned to this specific employee within the project
        $project->load(['tasks' => function ($query) {
            $query->where('user_id', Auth::id());
        }]);

        return view('employee.contents.details.ShowEmployeeDetails', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

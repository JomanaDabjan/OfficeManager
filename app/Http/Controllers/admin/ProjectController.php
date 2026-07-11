<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;
//use Illuminate\Http\Request;
use App\Models\Project;
//use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;



class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // To display just 10 projects per page, we can use the paginate method instead of all(). This will also provide pagination links in the view.
        $projects = Project::with('manager')->paginate(10);

        return view('admin.contents.tables.ShowProjects', compact('projects'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // When creating a project, the admin will need to select a manager from a list of existing managers.
        $managers = \App\Models\User::where('role', 'manager')->get();
        return view('admin.contents.createforms.ProjectCreateForm', compact('managers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectStoreRequest $request)
    {
        try {
            DB::beginTransaction(); // لبدء عملية حفظ آمنة
            $data = $request->validated();
            Project::create($data);
            DB::commit(); // حفظ التغييرات في حال النجاح
            return redirect()->route('project.index')->with('success', 'Project Added Correctly');
        } catch (Exception $ex) {
            DB::rollBack(); // التراجع في حال حدوث خطأ
            return redirect()->back()->with('error', 'The Error Is: ' . $ex->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        // Load the related manager, tasks, and users for the project to display detailed information in the admin panel

        $project->load(['manager', 'tasks', 'users']);
        return view('admin.contents.details.ProjectDetails', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        // When editing a project, the admin will need to select a manager from a list of existing managers for this project.

        $managers = \App\Models\User::where('role', 'manager')->get();
        return view('admin.contents.updateforms.ProjectUpdateForm', compact('project', 'managers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProjectUpdateRequest $request, Project $project)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $project->update($data);
            DB::commit();
            return redirect()->route('project.index')->with('success', 'Project Updated Correctly');
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('error', 'The Error Is: ' . $ex->getMessage())->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        try {
            DB::beginTransaction();
            $project->delete();
            DB::commit();
            return redirect()->route('project.index')->with('success', 'Project Deleted Correctly');
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('error', 'The Error Is: ' . $ex->getMessage())->withInput();
        }
    }
}

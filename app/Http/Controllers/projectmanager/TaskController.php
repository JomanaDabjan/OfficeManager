<?php

namespace App\Http\Controllers\projectmanager;

use App\Http\Controllers\Controller;
//use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;
use App\Http\Requests\ProManagerTaskStoreRequest;
use App\Http\Requests\ProManagerTaskUpdateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{

    /**
     * Display Only the tasks for the projects managed by the currently authenticated project manager.
     *  It retrieves the tasks from the database where the associated project's manager_id matches the id of the currently authenticated user.
     *  The results are paginated, showing 10 tasks per page.
     */
    public function index()
    {
        // Just bring the tasks for the projects managed by the currently authenticated project manager.
        $tasks = Task::whereHas('project', function ($query) {
            $query->where('manager_id', Auth::id());
        })->paginate(10);

        return view('project_manager.contents.Tables.ShowTasks', compact('tasks'));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create()
    {
        $projects = Project::where('manager_id', Auth::id())->get();
        return view('project_manager.contents.createforms.TaskCreateForm', compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProManagerTaskStoreRequest $request)
    {
        try {
            DB::beginTransaction(); // To start a safe save operation
            $data = $request->validated();
            Task::create($data);
            DB::commit();

            return redirect()->route('promanagertask.index')->with('success', 'Task created successfully.');
        } catch (Exception $ex) {
            DB::rollback();
            return redirect()->back()->with('error', 'The Error Is: ' . $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     *  This function is to edit a task, but it first checks if the task belongs to a project managed by the currently authenticated project manager.
     *  If not, it aborts with a 403 error. If the check passes,
     *  it retrieves the projects managed by the current user and returns a view for editing the task.
     */
    public function edit(Task $task)
    {
        // Security check: Ensure that the task belongs to a project managed by the currently authenticated project manager.

        if ($task->project->manager_id !== Auth::id()) {
            abort(403, 'You are not authorized to edit this task.');
        }

        $projects = Project::where('manager_id', Auth::id())->get();
        return view('project_manager.contents.updateforms.TaskUpdateForm', compact('task', 'projects'));
    }
    /**
     * This Function is to update a task, but it first checks if the task belongs to a project managed by the currently authenticated project manager.
     */
    public function update(ProManagerTaskUpdateRequest $request, Task $task)
    {
        try {
            DB::beginTransaction(); // To start a safe save operation
            $data = $request->validated();
            $task->update($data);
            DB::commit();

            return redirect()->route('task.index')->with('success', 'Task updated successfully.');
        } catch (Exception $ex) {
            DB::rollback();
            return redirect()->back()->with('error', 'The Error Is: ' . $ex->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified task from table.
     */
    public function destroy(Task $task)
    {
        // Security check: Ensure the task belongs to a project managed by the current user
        if ($task->project->manager_id !== Auth::id()) {
            abort(403, 'You are not authorized to delete this task.');
        }

        try {
            DB::beginTransaction();
            $task->delete();
            DB::commit();
            return redirect()->route('manager.tasks.index')->with('success', 'Task deleted successfully.');
        } catch (Exception $ex) {
            DB::rollback();
            // Log the error for debugging and redirect with an error message
            return redirect()->back()->with('error', 'The Error Is: ' . $ex->getMessage());
        }
    }
}
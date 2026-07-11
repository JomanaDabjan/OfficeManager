<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Task;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use Exception;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::all();
        return view('admin.contents.tables.ShowTasks', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // When creating a task, the admin will need to select a project from a list of existing projects and an employee from a list of existing employees.

        $projects = \App\Models\Project::all();
        $employees = \App\Models\User::where('role', 'employee')->get();
        return view('admin.contents.createforms.TaskCreateForm', compact('projects', 'employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskStoreRequest $request)
    {
        try {
            DB::beginTransaction(); // To start a safe save operation
            $data = $request->validated();
            Task::create($data);
            DB::commit(); // Save changes if successful
            return redirect()->route('task.index')->with('success', 'Task Added Correctly');
        } catch (Exception $ex) {
            DB::rollBack(); // To rollback changes if an error occurs
            return redirect()->back()->with('error', 'The Error Is: ' . $ex->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //This Function Is Used To Show Task Details In Admin Panel

        $task->load(['project', 'assignedUser']); // Load the related project and assigned user for the task
        return view('admin.contents.details.TaskDetails', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        // When editing a task, the admin will need to select a project from a list of existing projects and an employee from a list of existing employees.

        $projects = \App\Models\Project::all();
        $employees = \App\Models\User::where('role', 'employee')->get();
        return view('admin.contents.updateforms.TaskUpdateForm', compact('task', 'projects', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskUpdateRequest $request, Task $task)
    {
        try {
            DB::beginTransaction(); // To start a safe update operation
            $data = $request->validated();
            $task->update($data);
            DB::commit(); // To save changes if successful
            return redirect()->route('task.index')->with('success', 'Task Updated Correctly');
        } catch (Exception $ex) {
            DB::rollBack(); // To rollback changes if an error occurs
            return redirect()->back()->with('error', 'The Error Is: ' . $ex->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        try {
            DB::beginTransaction();
            $task->delete();
            DB::commit();
            return redirect()->route('task.index')->with('success', 'Task Deleted Correctly');
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('error', 'The Error Is: ' . $ex->getMessage());
        }
    }
}

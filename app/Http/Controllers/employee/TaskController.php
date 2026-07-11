<?php

namespace App\Http\Controllers\employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Requests\EmpTaskUpdateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class TaskController extends Controller
{
    /**
     * Display a listing of the tasks assigned to the employee.
     */
    public function index()
    {
        // Retrieve only the tasks assigned to the logged-in employee
        $tasks = Task::where('user_id', Auth::id())->paginate(10);

        return view('employee.contents.tables.ShowTasks', compact('tasks'));
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
     * Display the specified task details.
     */
    public function show(Task $task)
    {
        // Security check: Ensure the task is assigned to this employee
        if ($task->user_id !== Auth::id()) {
            abort(403, 'You are not authorized to view this task.');
        }

        return view('employee.contents.details.ShowTaskDetails', compact('task'));
    }

    /**
     * Show the form for editing the task status.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\View\View
     */
    public function edit(Task $task)
    {
        // Security check: Ensure the task belongs to the currently authenticated employee
        if ($task->user_id !== Auth::id()) {
            abort(403, 'You are not authorized to edit this task.');
        }

        // Return the edit form with the task data
        return view('employee.contents.updateforms.TaskUpdateForm', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmpTaskUpdateRequest $request, Task $task)
    {
        // Security check: Ensure the task is assigned to this employee
        if ($task->user_id !== Auth::id()) {
            abort(403, 'You are not authorized to update this task.');
        }
        try {
            DB::beginTransaction();
            // Update the task with validated data
            $data = $request->validated();
            $task->update($data);
            DB::commit();
            return redirect()->route('task.index')->with('success', 'Task updated successfully.');
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('error', 'The Error Is: ' . $ex->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
    }
}

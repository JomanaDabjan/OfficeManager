<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest; // General request for Admin/Manager
use App\Http\Requests\EmpTaskUpdateRequest; // Specific request for Employee
use App\Models\Task;
//use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class TaskController extends Controller
{
    /**
     * Define middleware to manage task access based on roles.
     */
    public function __construct()
    {
        // Admin and Manager can create and assign tasks.
        $this->middleware('role:admin,manager')->only(['create', 'store', 'edit', 'update', 'destroy']);

        // Employee can only view and update the status of their assigned tasks.
        $this->middleware('role:employee')->only(['index', 'updateStatus']);
    }

    /**
     * Display a listing of tasks.
     */
    public function index()
    {
        $user = Auth::user();

        // Admin/Manager see tasks related to their scope; Employee sees their assigned tasks.
        $tasks = ($user->role === 'employee')
            ? Task::where('user_id', $user->id)->paginate(10)
            : Task::paginate(10);

        return view('admin.contents.tables.ShowTasks', compact('tasks'));
    }

    /**
     * Store a newly created task.
     */
    public function store(TaskStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            Task::create($request->validated());
            DB::commit();
            return redirect()->route('admin.tasks.index')->with('success', 'Task created successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified task.
     */
    public function update(Task $task)
    {
        try {
            // Choose the request object dynamically based on user role
            // EmpTaskUpdateRequest already contains the logic to restrict employees to status-only updates
            $request = (auth()->user()->role === 'employee')
                ? app(EmpTaskUpdateRequest::class)
                : app(TaskUpdateRequest::class);

            DB::beginTransaction();

            $task->update($request->validated());

            DB::commit();

            return redirect()->route('admin.tasks.index')
                ->with('success', 'Task updated successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Destroy a task.
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('admin.tasks.index')->with('success', 'Task deleted.');
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Http\Requests\EmpTaskUpdateRequest;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Exception;

class TaskController extends Controller
{
    /**
     * Display a listing of tasks.
     */
    public function index()
    {
        $user = Auth::user();

        // Admin/Manager see all tasks; Employee sees only their assigned tasks.
        $tasks = ($user->role === 'employee')
            ? Task::where('user_id', $user->id)->paginate(10)
            : Task::paginate(10);

        return view('contents.task.Index', compact('tasks'));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create()
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'manager'])) {
            abort(403, 'Unauthorized action.');
        }

        // Fetch projects and filter users to include only employees
        $projects = Project::all();
        $users = User::where('role', 'employee')->get();

        return view('contents.task.Create', compact('projects', 'users'));
    }

    /**
     * Store a newly created task.
     */
    public function store(TaskStoreRequest $request)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'manager'])) {
            abort(403, 'Unauthorized action.');
        }

        try {
            DB::beginTransaction();

            $data = $request->validated();

            // Handle MULTIPLE file uploads if attachments exist
            if ($request->hasFile('attachments')) {
                $uploadedFiles = [];

                foreach ($request->file('attachments') as $file) {
                    // Added uniqid() to prevent file name collisions if uploaded at the exact same second
                    $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                    $file->storeAs('tasks_attachments', $filename, 'public');
                    $uploadedFiles[] = 'tasks_attachments/' . $filename;
                }

                // Store array of paths as JSON in the database
                $data['attachment'] = json_encode($uploadedFiles);
            }

            Task::create($data);
            DB::commit();

            return redirect()->route('admin.task.index')->with('success', 'Task created successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified task.
     */
    public function edit(Task $task)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'manager'])) {
            abort(403, 'Unauthorized action.');
        }

        // Fetch projects and filter users to include only employees for editing
        $projects = Project::all();
        $users = User::where('role', 'employee')->get();

        return view('contents.task.Edit', compact('task', 'projects', 'users'));
    }

    /**
     * Update the specified task.
     */
    public function update(TaskUpdateRequest $request, Task $task)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'manager'])) {
            abort(403, 'Unauthorized action.');
        }

        try {
            DB::beginTransaction();

            $data = $request->validated();

            // Handle MULTIPLE file uploads if new attachments are provided
            if ($request->hasFile('attachments')) {

                // 1. Delete old files from storage
                if ($task->attachment) {
                    $oldAttachments = json_decode($task->attachment, true);

                    // Check if it's an array (JSON)
                    if (is_array($oldAttachments)) {
                        foreach ($oldAttachments as $oldFile) {
                            if (Storage::disk('public')->exists($oldFile)) {
                                Storage::disk('public')->delete($oldFile);
                            }
                        }
                    }
                    // Fallback just in case it was a single file string from old code
                    elseif (Storage::disk('public')->exists($task->attachment)) {
                        Storage::disk('public')->delete($task->attachment);
                    }
                }

                // 2. Upload new files
                $uploadedFiles = [];
                foreach ($request->file('attachments') as $file) {
                    $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                    $file->storeAs('tasks_attachments', $filename, 'public');
                    $uploadedFiles[] = 'tasks_attachments/' . $filename;
                }

                // Update attachment field with new JSON
                $data['attachment'] = json_encode($uploadedFiles);
            }

            $task->update($data);
            DB::commit();

            return redirect()->route('admin.task.index')
                ->with('success', 'Task updated successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Employee accepts the task.
     */
    public function accept(Task $task)
    {
        $user = Auth::user();

        if ($user->role !== 'employee' || $task->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        try {
            DB::beginTransaction();
            $task->update([
                'status' => 'accepted',
                'rejection_reason' => null
            ]);
            DB::commit();

            return redirect()->back()->with('success', 'Task accepted successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Employee rejects the task with a reason.
     */
    public function reject(EmpTaskUpdateRequest $request, Task $task)
    {
        $user = Auth::user();

        if ($user->role !== 'employee' || $task->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        try {
            DB::beginTransaction();
            $task->update([
                'status' => 'rejected',
                'rejection_reason' => $request->rejection_reason
            ]);
            DB::commit();

            return redirect()->back()->with('success', 'Task rejected successfully.');
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
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'manager'])) {
            abort(403, 'Unauthorized action.');
        }

        try {
            DB::beginTransaction();

            // Delete associated files from storage before deleting the task
            if ($task->attachment) {
                $attachments = json_decode($task->attachment, true);

                if (is_array($attachments)) {
                    foreach ($attachments as $file) {
                        if (Storage::disk('public')->exists($file)) {
                            Storage::disk('public')->delete($file);
                        }
                    }
                } elseif (Storage::disk('public')->exists($task->attachment)) {
                    Storage::disk('public')->delete($task->attachment);
                }
            }

            $task->delete();

            DB::commit();

            return redirect()->route('admin.task.index')->with('success', 'Task deleted.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
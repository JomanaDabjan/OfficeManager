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
    // =========================================================================
    // DISPLAY TASKS LISTING METHOD
    // =========================================================================

    /**
     * Display a listing of tasks.
     */
    public function index()
    {
        // Get currently authenticated user instance
        $user = Auth::user();

        // Admin/Manager see all tasks; Employee sees only their assigned tasks.
        $tasks = ($user->role === 'employee')
            ? Task::where('user_id', $user->id)->paginate(10)
            : Task::paginate(10);

        // Return the tasks index view with paginated records
        return view('contents.task.Index', compact('tasks'));
    }

    // =========================================================================
    // SHOW CREATE TASK FORM METHOD
    // =========================================================================

    /**
     * Show the form for creating a new task.
     */
    public function create()
    {
        // Get currently authenticated user instance
        $user = Auth::user();

        // Restrict access: only admins and managers can view the create form
        if (!in_array($user->role, ['admin', 'manager'])) {
            abort(403, 'Unauthorized action.');
        }

        // Fetch projects and filter users to include only employees
        $projects = Project::all();
        $users = User::where('role', 'employee')->get();

        // Return the create task view with dropdown dependencies
        return view('contents.task.Create', compact('projects', 'users'));
    }

    // =========================================================================
    // STORE NEW TASK METHOD
    // =========================================================================

    /**
     * Store a newly created task.
     */
    public function store(TaskStoreRequest $request)
    {
        // Get currently authenticated user instance
        $user = Auth::user();

        // Restrict access: only admins and managers can store tasks
        if (!in_array($user->role, ['admin', 'manager'])) {
            abort(403, 'Unauthorized action.');
        }

        try {
            // Begin database transaction for safe execution
            DB::beginTransaction();

            // Retrieve validated input data from form request
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

            // Create new task record in database
            Task::create($data);

            // Commit database transaction
            DB::commit();

            // Redirect back to task index with success message
            return redirect()->route('admin.task.index')->with('success', 'Task created successfully.');
        } catch (Exception $e) {
            // Rollback database transaction on failure
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // SHOW EDIT TASK FORM METHOD
    // =========================================================================

    /**
     * Show the form for editing the specified task.
     */
    public function edit(Task $task)
    {
        // Get currently authenticated user instance
        $user = Auth::user();

        // Restrict access: only admins and managers can access edit form
        if (!in_array($user->role, ['admin', 'manager'])) {
            abort(403, 'Unauthorized action.');
        }

        // Fetch projects and filter users to include only employees for editing
        $projects = Project::all();
        $users = User::where('role', 'employee')->get();

        // Return the edit view with task and dropdown data
        return view('contents.task.Edit', compact('task', 'projects', 'users'));
    }

    // =========================================================================
    // UPDATE EXISTING TASK METHOD
    // =========================================================================

    /**
     * Update the specified task.
     */
    public function update(TaskUpdateRequest $request, Task $task)
    {
        // Get currently authenticated user instance
        $user = Auth::user();

        // Restrict access: only admins and managers can update tasks
        if (!in_array($user->role, ['admin', 'manager'])) {
            abort(403, 'Unauthorized action.');
        }

        try {
            // Begin database transaction
            DB::beginTransaction();

            // Retrieve validated request data
            $data = $request->validated();

            // Handle MULTIPLE file uploads if new attachments are provided
            if ($request->hasFile('attachments')) {

                // 1. Delete old files from storage
                if ($task->attachment) {
                    $oldAttachments = json_decode($task->attachment, true);

                    // Check if it's an array (JSON) for multiple files which helps avoid errors if the old code stored a single string instead of JSON
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

                // Update attachment field with new JSON for storage it in database
                $data['attachment'] = json_encode($uploadedFiles);
            }

            // Update task record details
            $task->update($data);

            // Commit transaction changes
            DB::commit();

            // Redirect to index with success notification
            return redirect()->route('admin.task.index')
                ->with('success', 'Task updated successfully.');
        } catch (Exception $e) {
            // Rollback transaction on error
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // EMPLOYEE ACCEPT TASK METHOD
    // =========================================================================

    /**
     * Employee accepts the task.
     */
    public function accept(Task $task)
    {
        // Get currently authenticated user instance
        $user = Auth::user();

        // Ensure user is an employee and owns the specific assigned task
        if ($user->role !== 'employee' || $task->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        try {
            // Begin transaction for status update
            DB::beginTransaction();

            // Update status and clear rejection reason
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

    // =========================================================================
    // EMPLOYEE REJECT TASK METHOD
    // =========================================================================

    /**
     * Employee rejects the task with a reason.
     */
    public function reject(EmpTaskUpdateRequest $request, Task $task)
    {
        // Get currently authenticated user instance
        $user = Auth::user();

        // Verify authorization for assigned employee role and ownership
        if ($user->role !== 'employee' || $task->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        try {
            // Begin transaction block
            DB::beginTransaction();

            // Update status and record rejection reason input
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

    // =========================================================================
    // DESTROY / DELETE TASK METHOD
    // =========================================================================

    /**
     * Destroy a task.
     */
    public function destroy(Task $task)
    {
        // Get currently authenticated user instance
        $user = Auth::user();

        // Restrict delete permissions strictly to admin and manager roles
        if (!in_array($user->role, ['admin', 'manager'])) {
            abort(403, 'Unauthorized action.');
        }

        try {
            // Begin transaction for safe file and record deletion
            DB::beginTransaction();

            // Delete associated files from storage before deleting the task record
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

            // Delete the task model instance from database
            $task->delete();

            // Commit database transaction
            DB::commit();

            return redirect()->route('admin.task.index')->with('success', 'Task deleted.');
        } catch (Exception $e) {
            // Rollback database transaction on error
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
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

/**
 * =========================================================================
 * TASK CONTROLLER CLASS
 * =========================================================================
 * This controller handles all administrative and employee task management
 * operations including listing, creating, updating, accepting, rejecting,
 * and deleting tasks with multi-file attachment support.
 */
class TaskController extends Controller
{
    // =========================================================================
    // DISPLAY TASKS LISTING INDEX METHOD
    // =========================================================================

    /**
     * Display a listing of tasks with search, filters, and pagination.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // -----------------------------------------------------------------
        // STEP 1: Get currently authenticated user instance
        // -----------------------------------------------------------------
        $user = Auth::user();

        // -----------------------------------------------------------------
        // STEP 2: Initialize query based on user role
        // Employees only see their own assigned tasks, while admins/managers see all tasks
        // -----------------------------------------------------------------
        $query = ($user->role === 'employee')
            ? Task::where('user_id', $user->id)->with('user')
            : Task::with('user');

        // -----------------------------------------------------------------
        // STEP 3: Apply Status / Review Action Filter if requested
        // -----------------------------------------------------------------
        if (request()->has('filter') && !empty(request('filter')) && request('filter') !== 'all') {
            $filter = request('filter');
            // Check if filter value matches allowed database status types
            if (in_array($filter, ['pending', 'in_progress', 'completed', 'accepted', 'rejected'])) {
                $query->where('status', $filter);
            }
        }

        // -----------------------------------------------------------------
        // STEP 4: Apply Title Filter if requested
        // -----------------------------------------------------------------
        if (request()->has('title') && !empty(request('title'))) {
            $query->where('title', request('title'));
        }

        // -----------------------------------------------------------------
        // STEP 5: Apply Assigned To User Filter if requested
        // -----------------------------------------------------------------
        if (request()->has('assigned_to') && !empty(request('assigned_to'))) {
            $query->where('user_id', request('assigned_to'));
        }

        // -----------------------------------------------------------------
        // STEP 6: Apply Attachment Presence Filter if requested
        // -----------------------------------------------------------------
        if (request()->has('has_attachment') && !empty(request('has_attachment'))) {
            $attachmentFilter = request('has_attachment');
            if ($attachmentFilter === 'yes') {
                $query->whereNotNull('attachment'); // Has files attached
            } elseif ($attachmentFilter === 'no') {
                $query->whereNull('attachment'); // No files attached
            }
        }

        // -----------------------------------------------------------------
        // STEP 7: Apply Global Search Query across title and description
        // -----------------------------------------------------------------
        if (request()->has('search') && !empty(request('search'))) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // -----------------------------------------------------------------
        // STEP 8: Fetch paginated results and append query strings to links
        // -----------------------------------------------------------------
        $tasks = $query->latest()->paginate(10)->withQueryString();

        // -----------------------------------------------------------------
        // STEP 9: Fetch lookup data for dropdown filters across pages
        // -----------------------------------------------------------------
        // Get unique task titles for filtering dropdown
        $allTitles = Task::when($user->role === 'employee', fn($q) => $q->where('user_id', $user->id))
            ->select('title')
            ->distinct()
            ->pluck('title');

        // Get only users with an 'employee' role who have tasks assigned for filter dropdown
        $allUsers = User::has('tasks')
            ->where('role', 'employee')
            ->select('id', 'name')
            ->distinct()
            ->get();

        // -----------------------------------------------------------------
        // STEP 10: Calculate individual counts for status summary cards/badges
        // -----------------------------------------------------------------
        $pendingTasks    = Task::when($user->role === 'employee', fn($q) => $q->where('user_id', $user->id))->where('status', 'pending')->count();
        $inProgressTasks = Task::when($user->role === 'employee', fn($q) => $q->where('user_id', $user->id))->where('status', 'in_progress')->count();
        $completedTasks  = Task::when($user->role === 'employee', fn($q) => $q->where('user_id', $user->id))->where('status', 'completed')->count();
        $acceptedTasks   = Task::when($user->role === 'employee', fn($q) => $q->where('user_id', $user->id))->where('status', 'accepted')->count();
        $rejectedTasks   = Task::when($user->role === 'employee', fn($q) => $q->where('user_id', $user->id))->where('status', 'rejected')->count();

        // -----------------------------------------------------------------
        // STEP 11: Return view with all processed variables compacted
        // -----------------------------------------------------------------
        return view('contents.task.Index', compact(
            'tasks',
            'allTitles',
            'allUsers',
            'pendingTasks',
            'inProgressTasks',
            'completedTasks',
            'acceptedTasks',
            'rejectedTasks'
        ));
    }

    // =========================================================================
    // SHOW CREATE TASK FORM METHOD
    // =========================================================================

    /**
     * Show the form for creating a new task.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Get currently authenticated user instance
        $user = Auth::user();

        // Security check: Only allow admin or manager roles to view the creation form
        if (!in_array($user->role, ['admin', 'manager'])) {
            abort(403, 'Unauthorized action.');
        }

        // Fetch all projects for selection dropdown
        $projects = Project::all();

        // Fetch only users who have the 'employee' role for task assignment dropdown
        $users = User::where('role', 'employee')->get();

        // Return the create task form view passing projects and filtered employee users
        return view('contents.task.Create', compact('projects', 'users'));
    }

    // =========================================================================
    // STORE NEW TASK METHOD
    // =========================================================================

    /**
     * Store a newly created task in the database.
     *
     * @param \App\Http\Requests\TaskStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(TaskStoreRequest $request)
    {
        // Get currently authenticated user instance
        $user = Auth::user();

        // Security check: Only allow admin or manager roles to store tasks
        if (!in_array($user->role, ['admin', 'manager'])) {
            abort(403, 'Unauthorized action.');
        }

        try {
            // Start a database transaction to ensure data integrity
            DB::beginTransaction();

            // Retrieve validated input data from form request class
            $data = $request->validated();

            // Check if the request contains multiple file attachments
            if ($request->hasFile('attachments')) {
                $uploadedFiles = [];

                // Loop through each uploaded file item
                foreach ($request->file('attachments') as $file) {
                    // Generate a unique filename using timestamp, unique ID, and original file name to prevent name collisions
                    $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();

                    // Store file securely in the public storage folder under 'tasks_attachments'
                    $file->storeAs('tasks_attachments', $filename, 'public');

                    // Append relative file path to array collection
                    $uploadedFiles[] = 'tasks_attachments/' . $filename;
                }

                // Encode the array of file paths into a JSON string for database storage
                $data['attachment'] = json_encode($uploadedFiles);
            }

            // Create the new task record using the prepared data array
            Task::create($data);

            // Commit the database transaction if everything succeeds
            DB::commit();

            // Redirect back to task index page with a success flash message
            return redirect()->route('admin.task.index')->with('success', 'Task created successfully.');
        } catch (Exception $e) {
            // Rollback database transaction if any exception or error occurs
            DB::rollBack();

            // Redirect back with error message details
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // SHOW EDIT TASK FORM METHOD
    // =========================================================================

    /**
     * Show the form for editing the specified task.
     *
     * @param \App\Models\Task $task
     * @return \Illuminate\View\View
     */
    public function edit(Task $task)
    {
        // Get currently authenticated user instance
        $user = Auth::user();

        // Security check: Only allow admin or manager roles to open the edit form
        if (!in_array($user->role, ['admin', 'manager'])) {
            abort(403, 'Unauthorized action.');
        }

        // Fetch all projects and filter users to include only employees for editing dropdowns
        $projects = Project::all();
        $users = User::where('role', 'employee')->get();

        // Return the edit view passing the task instance along with dependency options
        return view('contents.task.Edit', compact('task', 'projects', 'users'));
    }

    // =========================================================================
    // UPDATE EXISTING TASK METHOD
    // =========================================================================

    /**
     * Update the specified task in the database.
     *
     * @param \App\Http\Requests\TaskUpdateRequest $request
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(TaskUpdateRequest $request, Task $task)
    {
        // Get currently authenticated user instance
        $user = Auth::user();

        // Security check: Only allow admin or manager roles to update tasks
        if (!in_array($user->role, ['admin', 'manager'])) {
            abort(403, 'Unauthorized action.');
        }

        try {
            // Start database transaction
            DB::beginTransaction();

            // Retrieve validated input data from form request
            $data = $request->validated();

            // Check if new file attachments are provided in the update request
            if ($request->hasFile('attachments')) {

                // Step 1: Delete old attachment files from server storage if they exist
                if ($task->attachment) {
                    $oldAttachments = json_decode($task->attachment, true);

                    // Check if old attachment data is stored as a JSON array
                    if (is_array($oldAttachments)) {
                        foreach ($oldAttachments as $oldFile) {
                            if (Storage::disk('public')->exists($oldFile)) {
                                Storage::disk('public')->delete($oldFile);
                            }
                        }
                    }
                    // Fallback check in case old data was stored as a single string path
                    elseif (Storage::disk('public')->exists($task->attachment)) {
                        Storage::disk('public')->delete($task->attachment);
                    }
                }

                // Step 2: Process and store the newly uploaded attachment files
                $uploadedFiles = [];
                foreach ($request->file('attachments') as $file) {
                    $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                    $file->storeAs('tasks_attachments', $filename, 'public');
                    $uploadedFiles[] = 'tasks_attachments/' . $filename;
                }

                // Encode new file paths array into JSON format for database update
                $data['attachment'] = json_encode($uploadedFiles);
            }

            // Update the task record with the new data
            $task->update($data);

            // Commit database transaction changes
            DB::commit();

            // Redirect back to task index with a success message
            return redirect()->route('admin.task.index')
                ->with('success', 'Task updated successfully.');
        } catch (Exception $e) {
            // Rollback database changes on failure
            DB::rollBack();

            // Redirect back with error message
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // EMPLOYEE ACCEPT TASK METHOD
    // =========================================================================

    /**
     * Allow an assigned employee to accept their task.
     *
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function accept(Task $task)
    {
        // Get currently authenticated user instance
        $user = Auth::user();

        // Security check: Ensure user is an employee and is the owner of this specific task
        if ($user->role !== 'employee' || $task->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        try {
            // Start transaction for task acceptance status update
            DB::beginTransaction();

            // Update task status to 'accepted' and clear any previous rejection reasons
            $task->update([
                'status' => 'accepted',
                'rejection_reason' => null
            ]);

            // Commit transaction
            DB::commit();

            // Redirect back with success message
            return redirect()->back()->with('success', 'Task accepted successfully.');
        } catch (Exception $e) {
            // Rollback on error
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // EMPLOYEE REJECT TASK METHOD
    // =========================================================================

    /**
     * Allow an assigned employee to reject their task with a specific reason.
     *
     * @param \App\Http\Requests\EmpTaskUpdateRequest $request
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(EmpTaskUpdateRequest $request, Task $task)
    {
        // Get currently authenticated user instance
        $user = Auth::user();

        // Security check: Verify employee role and task ownership match
        if ($user->role !== 'employee' || $task->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        try {
            // Start transaction block
            DB::beginTransaction();

            // Update task status to 'rejected' and save the provided rejection reason
            $task->update([
                'status' => 'rejected',
                'rejection_reason' => $request->rejection_reason
            ]);

            // Commit transaction
            DB::commit();

            // Redirect back with success message
            return redirect()->back()->with('success', 'Task rejected successfully.');
        } catch (Exception $e) {
            // Rollback on error
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // DESTROY / DELETE TASK METHOD
    // =========================================================================

    /**
     * Delete the specified task and its associated storage files.
     *
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Task $task)
    {
        // Get currently authenticated user instance
        $user = Auth::user();

        // Security check: Restrict deletion permissions strictly to admin and manager roles
        if (!in_array($user->role, ['admin', 'manager'])) {
            abort(403, 'Unauthorized action.');
        }

        try {
            // Start database transaction for safe file and record deletion
            DB::beginTransaction();

            // Check if task has attachments and delete them from public storage directory
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

            // Delete the task database record instance
            $task->delete();

            // Commit database transaction
            DB::commit();

            // Redirect back to task index page with success confirmation message
            return redirect()->route('admin.task.index')->with('success', 'Task deleted.');
        } catch (Exception $e) {
            // Rollback database transaction on error
            DB::rollBack();

            // Redirect back with error message
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
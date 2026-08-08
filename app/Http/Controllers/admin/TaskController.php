<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Http\Requests\EmpTaskUpdateRequest;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Services\TaskAttachmentService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * =========================================================================
 * TASK CONTROLLER CLASS
 * =========================================================================
 * This controller handles all CRUD (Create, Read, Update, Delete) operations
 * and review workflows for tasks. It implements Laravel Policies for security
 * and utilizes a dedicated Service class to manage file uploads cleanly.
 */
class TaskController extends Controller
{
    use AuthorizesRequests;
    /**
     * Protected instance for the file attachment service.
     * @var \App\Services\TaskAttachmentService
     */
    protected $attachmentService;

    /**
     * =====================================================================
     * CONTROLLER CONSTRUCTOR
     * =====================================================================
     * Injecting the TaskAttachmentService automatically via Laravel's Service Container.
     *
     * @param \App\Services\TaskAttachmentService $attachmentService
     */
    public function __construct(TaskAttachmentService $attachmentService)
    {
        $this->attachmentService = $attachmentService;
    }

    /**
     * =====================================================================
     * DISPLAY TASK LISTING
     * =====================================================================
     * Display a paginated list of tasks with filtering, searching, and
     * dashboard status counters tailored to the authenticated user's role.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Get the currently authenticated user
        $user = Auth::user();

        // Check if the current user role is strictly an employee
        $isEmployee = ($user->role === 'employee');

        // -----------------------------------------------------------------
        // 1. QUERY EXECUTION WITH MODEL LOCAL SCOPE
        // -----------------------------------------------------------------
        // Fetch tasks with related models, apply filters/search, order by latest,
        // and paginate results keeping query parameters intact.
        $tasks = Task::with('user', 'project')
            ->filterAndSearch($user, $request)
            ->latest()
            ->paginate(10)
            ->appends($request->query());

        // -----------------------------------------------------------------
        // 2. STATUS COUNTERS FOR DASHBOARD BADGES
        // -----------------------------------------------------------------
        // Retrieve count summaries grouped by status, respecting role restrictions.
        $statusCounts = Task::getStatusCounts($isEmployee ? $user->id : null);

        // -----------------------------------------------------------------
        // 3. PREPARING DATA ARRAY FOR THE BLADE VIEW
        // -----------------------------------------------------------------
        $data = [
            'tasks'           => $tasks,
            'pendingTasks'    => $statusCounts['pending'] ?? 0,
            'inProgressTasks' => $statusCounts['in_progress'] ?? 0,
            'completedTasks'  => $statusCounts['completed'] ?? 0,
            'acceptedTasks'   => $statusCounts['accepted'] ?? 0,
            'rejectedTasks'   => $statusCounts['rejected'] ?? 0,

            // Fetch unique task titles for dropdown filters based on role
            'allTitles'       => Task::when($isEmployee, fn($q) => $q->where('user_id', $user->id))
                ->distinct()
                ->pluck('title'),

            // Fetch all users who have employee role and have assigned tasks
            'allUsers'        => User::where('role', 'employee')->get(),
        ];

        // Return the view with packed data variables
        return view('contents.task.Index', $data);
    }

    /**
     * =====================================================================
     * SHOW CREATE TASK FORM
     * =====================================================================
     * Display the form required to create a new task.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Authorize action using TaskPolicy (Ensures only authorized users can proceed)
        $this->authorize('create', Task::class);

        // Fetch required dropdown lists
        $projects = Project::all();
        $users = User::where('role', 'employee')->get();

        return view('contents.task.Create', compact('projects', 'users'));
    }

    /**
     * =====================================================================
     * STORE A NEW TASK
     * =====================================================================
     * Validate and save a newly created task into the database inside a
     * database transaction to ensure complete data integrity.
     *
     * @param \App\Http\Requests\TaskStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(TaskStoreRequest $request)
    {
        // Double check authorization rule via policy
        $this->authorize('create', Task::class);

        try {
            // Start database transaction block
            DB::beginTransaction();

            // Get validated form data
            $data = $request->validated();

            // Handle file upload securely using the custom service class
            $attachmentData = $this->attachmentService->uploadAttachments($request);
            if ($attachmentData) {
                $data['attachment'] = $attachmentData;
            }

            // Create the task record in database
            Task::create($data);

            // Commit transaction if everything succeeds
            DB::commit();

            return redirect()->route('admin.task.index')->with('success', 'Task created successfully.');
        } catch (Exception $e) {
            // Rollback database changes if any exception or error occurs
            DB::rollBack();

            // Log the error message for debugging purposes
            Log::error('Task Creation Error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Something went wrong while creating the task. Please try again later.')
                ->withInput();
        }
    }

    /**
     * =====================================================================
     * SHOW EDIT TASK FORM
     * =====================================================================
     * Display the form to modify an existing task.
     *
     * @param \App\Models\Task $task
     * @return \Illuminate\View\View
     */
    public function edit(Task $task)
    {
        // Authorize user action on this specific task instance
        $this->authorize('update', $task);

        $projects = Project::all();
        $users = User::where('role', 'employee')->get();

        return view('contents.task.Edit', compact('task', 'projects', 'users'));
    }

    /**
     * =====================================================================
     * UPDATE AN EXISTING TASK
     * =====================================================================
     * Process updates to a task, including replacing old file attachments safely.
     *
     * @param \App\Http\Requests\TaskUpdateRequest $request
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(TaskUpdateRequest $request, Task $task)
    {
        // Authorize update action via policy
        $this->authorize('update', $task);

        try {
            DB::beginTransaction();

            $data = $request->validated();

            // Check if a new attachment file is uploaded in the request
            if ($request->hasFile('attachment')) {
                // Step 1: Remove the old file from storage folder
                $this->attachmentService->deleteAttachments($task->attachment);

                // Step 2: Upload the new file and assign its path to data array
                $data['attachment'] = $this->attachmentService->uploadAttachments($request);
            }

            // Update database record
            $task->update($data);

            DB::commit();

            return redirect()->route('admin.task.index')->with('success', 'Task updated successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Task Update Error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Something went wrong while updating the task. Please try again later.')
                ->withInput();
        }
    }

    /**
     * =====================================================================
     * ACCEPT TASK STATUS
     * =====================================================================
     * Allows an assigned employee to accept their designated task.
     *
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function accept(Task $task)
    {
        // Authorize status modification capability
        $this->authorize('modifyStatus', $task);

        try {
            DB::beginTransaction();

            // Update status and clear any prior rejection reason text
            $task->update([
                'status' => 'accepted',
                'rejection_reason' => null
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Task accepted successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Task Acceptance Error: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong. Please try again later.');
        }
    }

    /**
     * =====================================================================
     * REJECT TASK STATUS
     * =====================================================================
     * Allows an assigned employee to reject a task by providing a reason.
     *
     * @param \App\Http\Requests\EmpTaskUpdateRequest $request
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(EmpTaskUpdateRequest $request, Task $task)
    {
        // Authorize status modification capability
        $this->authorize('modifyStatus', $task);

        try {
            DB::beginTransaction();

            // Update status and save the employee's rejection reason
            $task->update([
                'status' => 'rejected',
                'rejection_reason' => $request->rejection_reason
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Task rejected successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Task Rejection Error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Something went wrong. Please try again later.')
                ->withInput();
        }
    }

    /**
     * =====================================================================
     * DELETE A TASK
     * =====================================================================
     * Securely remove a task record from the database and clean up its
     * associated uploaded files from the storage server.
     *
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Task $task)
    {
        // Authorize deletion through policy
        $this->authorize('delete', $task);

        try {
            DB::beginTransaction();

            // Delete associated physical storage files via service class
            $this->attachmentService->deleteAttachments($task->attachment);

            // Delete the database row entry
            $task->delete();

            DB::commit();

            return redirect()->route('admin.task.index')->with('success', 'Task deleted successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Task Deletion Error: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong while deleting the task. Please try again later.');
        }
    }
}
<?php

namespace App\Http\Controllers\Admin;

// =========================================================================
// IMPORT NECESSARY CLASSES AND PACKAGES
// =========================================================================
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

// Import Barryvdh DomPDF facade for PDF export functionality
use Barryvdh\DomPDF\Facade\Pdf;
// Import custom Excel export class for tasks
use App\Exports\TaskExport;
// Import Excel facade from Maatwebsite package
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\View\View;

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
            'pendingTasks'   => $statusCounts['pending'] ?? 0,
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
     * PRINT, PDF, AND EXCEL EXPORT METHODS FOR TASKS (WITH FILTERING APPLIED)
     * =====================================================================
     */

    public function printTasksReport(Request $request): View
    {
        $user = Auth::user();
        $tasks = collect();

        // استخدام نطاق الفلترة والصلاحيات الخاص بالمهام
        Task::with(['user', 'project'])->filterAndSearch($user, $request)->chunk(500, function ($chunk) use (&$tasks) {
            $tasks = $tasks->concat($chunk);
        });

        $chunks = $tasks->values()->chunk(6);

        return view('contents.report.partial.Task_table', compact('chunks'));
    }

    public function exportTasksPdf(Request $request)
    {
        $user = Auth::user();
        $tasks = collect();

        Task::with(['user', 'project'])->filterAndSearch($user, $request)->chunk(500, function ($chunk) use (&$tasks) {
            $tasks = $tasks->concat($chunk);
        });

        $chunks = $tasks->values()->chunk(6);
        $isPdf = true;

        $pdf = Pdf::loadView('contents.report.partial.Task_table', compact('chunks', 'isPdf'));
        return $pdf->download('tasks-report.pdf');
    }

    public function exportTasksExcel(Request $request)
    {
        return Excel::download(new TaskExport($request), 'tasks-report.xlsx');
    }

    /**
     * =====================================================================
     * SHOW TASK DETAILS
     * =====================================================================
     * Display detailed view for a specific task along with its relationships.
     *
     * @param \App\Models\Task $task
     * @return \Illuminate\View\View
     */
    public function show(Task $task)
    {
        // Authorize action using TaskPolicy (Ensures only authorized users can view details)
        $this->authorize('view', $task);

        // Load necessary relationships to prevent N+1 query issues in the view
        $task->load(['project', 'assignedUser', 'user']);

        // Then fetch the project associated with the task
        $project = $task->project;

        // Return the view with the task instance
        return view('contents.task.Show', compact('task', 'project'));
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
    // ... (rest of the methods remain unchanged)
    {
        $this->authorize('modifyStatus', $task);

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
            Log::error('Task Acceptance Error: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong. Please try again later.');
        }
    }

    public function reject(EmpTaskUpdateRequest $request, Task $task)
    {
        $this->authorize('modifyStatus', $task);

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
            Log::error('Task Rejection Error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Something went wrong. Please try again later.')
                ->withInput();
        }
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        try {
            DB::beginTransaction();

            $this->attachmentService->deleteAttachments($task->attachment);
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
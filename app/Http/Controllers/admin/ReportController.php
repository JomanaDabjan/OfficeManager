<?php

namespace App\Http\Controllers\Admin;

// =========================================================================
// IMPORT NECESSARY CLASSES AND PACKAGES
// =========================================================================
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

// Import Barryvdh DomPDF facade for PDF export functionality
use Barryvdh\DomPDF\Facade\Pdf;
// Import custom Excel export classes for projects and tasks
use App\Exports\ProjectsExport;
use App\Exports\TaskExport;
// Import Excel facade from Maatwebsite package for handling spreadsheet downloads
use Maatwebsite\Excel\Facades\Excel;

/**
 * =========================================================================
 * REPORT CONTROLLER (ANALYTICS & REPORTING MANAGEMENT)
 * =========================================================================
 * This controller aggregates system data for projects, tasks, and users
 * to generate comprehensive analytical reports, printable views, and downloadable files.
 */
class ReportController extends Controller
{
    /**
     * =====================================================================
     * DISPLAY REPORT INDEX HUB
     * =====================================================================
     * Render the main report selection dashboard view containing all categories.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        return view('contents.report.Index');
    }

    /**
     * =====================================================================
     * EMPLOYEE PERFORMANCE / TASK REPORT
     * =====================================================================
     * Gathers user statistics, counting their assigned tasks, completed tasks,
     * and pending tasks to measure individual productivity.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function taskreport(Request $request): View
    {
        // Using the Model Local Scope for clean filtering
        $query = Task::with(['project', 'assignedUser'])->reportFilter($request);
        $tasks = $query->paginate(10)->appends($request->query());

        $projects = Project::all();
        $employees = User::all();
        $allTaskTitles = Task::distinct()->pluck('title');

        $totalTasksCount = Task::count();
        $completedTasksCount = Task::where('status', 'completed')->count();
        $inProgressTasksCount = Task::where('status', 'in_progress')->count();
        $pendingTasksCount = Task::where('status', 'pending')->count();

        return view('contents.report.TaskReport', compact(
            'tasks',
            'projects',
            'employees',
            'allTaskTitles',
            'totalTasksCount',
            'completedTasksCount',
            'inProgressTasksCount',
            'pendingTasksCount'
        ));
    }

    /**
     * =====================================================================
     * PROJECT MANAGERS REPORT
     * =====================================================================
     * Aggregates project lists along with their related tasks and completion progress,
     * supporting Live Search and filtering via the Project Model scope.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function projectreport(Request $request)
    {
        // Using the Project Model Local Scope for filtering and eager loading
        $query = Project::with(['manager', 'tasks.assignedUser'])->reportFilter($request);

        // Count tasks for each project while maintaining filters
        $query->withCount('tasks');

        // Apply pagination and append query parameters to links
        $projects = $query->paginate(10)->appends($request->query());

        if ($request->ajax()) {
            return view('contents.report.partials.projects-table', compact('projects'));
        }

        $allTitles = Project::pluck('title')->unique();
        $managers = User::where('role', 'manager')->get();

        return view('contents.report.ProjectReport', compact('projects', 'allTitles', 'managers'));
    }

    /**
     * =====================================================================
     * PRINT REPORTS (DIRECT BROWSER PRINT FOR PROJECTS & TASKS)
     * =====================================================================
     */

    public function printProjectsReport(Request $request): View
    {
        $projects = collect();
        Project::with(['manager', 'tasks.assignedUser'])->reportFilter($request)->chunk(500, function ($chunk) use (&$projects) {
            $projects = $projects->concat($chunk);
        });

        // تقسيم البيانات إلى أجزاء (كل جزء 6 عناصر للطباعة المنظمة)
        $chunks = $projects->values()->chunk(6);

        return view('contents.report.partial.Project_table', compact('chunks'));
    }

    public function printTasksReport(Request $request): View
    {
        $tasks = collect();
        Task::with(['project', 'assignedUser'])->reportFilter($request)->chunk(500, function ($chunk) use (&$tasks) {
            $tasks = $tasks->concat($chunk);
        });

        // تقسيم البيانات إلى أجزاء (كل جزء 6 مهام)
        $chunks = $tasks->values()->chunk(6);

        return view('contents.report.partial.Task_table', compact('chunks'));
    }

    /**
     * =====================================================================
     * EXPORT REPORTS AS PDF (FOR PROJECTS & TASKS)
     * =====================================================================
     */

    public function exportProjectsPdf(Request $request)
    {
        $projects = collect();
        Project::with(['manager', 'tasks.assignedUser'])->reportFilter($request)->chunk(500, function ($chunk) use (&$projects) {
            $projects = $projects->concat($chunk);
        });

        $chunks = $projects->values()->chunk(6);
        $isPdf = true;

        $pdf = Pdf::loadView('contents.report.partial.Project_table', compact('chunks', 'isPdf'));
        return $pdf->download('projects-report.pdf');
    }

    public function exportTasksPdf(Request $request)
    {
        $tasks = collect();
        Task::with(['project', 'assignedUser'])->reportFilter($request)->chunk(500, function ($chunk) use (&$tasks) {
            $tasks = $tasks->concat($chunk);
        });

        $chunks = $tasks->values()->chunk(6);
        $isPdf = true;

        $pdf = Pdf::loadView('contents.report.partial.Task_table', compact('chunks', 'isPdf'));
        return $pdf->download('tasks-report.pdf');
    }

    /**
     * =====================================================================
     * EXPORT REPORTS AS EXCEL (FOR PROJECTS & TASKS)
     * =====================================================================
     */

    public function exportProjectsExcel(Request $request)
    {
        return Excel::download(new ProjectsExport($request), 'projects-report.xlsx');
    }

    public function exportTasksExcel(Request $request)
    {
        return Excel::download(new TaskExport($request), 'tasks-report.xlsx');
    }

    /**
     * =====================================================================
     * SYSTEM OVERVIEW & STATISTICS REPORT
     * =====================================================================
     */
    public function systemOverview(): View
    {
        $totalUsers = User::count();
        $totalProjects = Project::count();
        $totalTasks = Task::count();

        $taskStatusBreakdown = Task::select('status', \DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        return view('contents.report.system-overview', compact(
            'totalUsers',
            'totalProjects',
            'totalTasks',
            'taskStatusBreakdown'
        ));
    }

    public function create()
    {
        abort(404);
    }
    public function store(Request $request)
    {
        abort(404);
    }
    public function show(string $id)
    {
        abort(404);
    }
    public function edit(string $id)
    {
        abort(404);
    }
    public function update(Request $request, string $id)
    {
        abort(404);
    }
    public function destroy(string $id)
    {
        abort(404);
    }
}
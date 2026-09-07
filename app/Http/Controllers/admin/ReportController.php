<?php

namespace App\Http\Controllers\Admin;

// =========================================================================
// IMPORT NECESSARY CLASSES AND PACKAGES
// =========================================================================
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\Team;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * =========================================================================
 * REPORT CONTROLLER (ANALYTICS & REPORTING MANAGEMENT)
 * =========================================================================
 * This controller aggregates system data for projects, tasks, and users
 * to generate comprehensive analytical reports.
 */
class ReportController extends Controller
{

    use AuthorizesRequests;
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
        $this->authorize('viewAny', Report::class);

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
        $this->authorize('viewAny', Report::class);

        // Using the Model Local Scope for clean filtering
        $query = Task::with(['project', 'assignedUser'])->reportFilter($request);
        $tasks = $query->paginate(10)->appends($request->query());

        $projects = Project::all();
        $employees = User::all();
        $allTaskTitles = Task::distinct()->pluck('title');

        $totalTasksCount = Task::count();
        $completedTasksCount = Task::where('status', 'completed')->count();
        $inProgressTasksCount = Task::where('status', 'in_progress')->count();

        // الـ Pending الحقيقي (الذي لـيس اليوم وليس متأخراً)
        $pendingTasksCount = Task::where('status', 'pending')
            ->whereDate('due_date', '>', today())
            ->count();

        // إضافة عدادات Overdue و Due Today لتوحيدها
        $overdueTasksCount = Task::where('status', '!=', 'completed')
            ->whereDate('due_date', '<', today())
            ->count();

        $dueTodayTasksCount = Task::where('status', '!=', 'completed')
            ->whereDate('due_date', today())
            ->count();

        return view('contents.report.TaskReport', compact(
            'tasks',
            'projects',
            'employees',
            'allTaskTitles',
            'totalTasksCount',
            'completedTasksCount',
            'inProgressTasksCount',
            'pendingTasksCount',
            'overdueTasksCount',
            'dueTodayTasksCount'
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
        $this->authorize('viewAny', Report::class);

        // ================================================================
        // GET STATUS FILTER SEPARATELY
        // ================================================================

        $selectedStatus = $request->input('status');

        // ================================================================
        // REMOVE STATUS BEFORE USING reportFilter()
        // ================================================================

        $filterRequest = clone $request;

        $filterRequest->query->remove('status');

        $filterRequest->request->remove('status');

        // ================================================================
        // BASE PROJECT QUERY WITH MODEL SCOPES
        // ================================================================

        $query = Project::with(['manager', 'tasks.assignedUser'])
            ->reportFilter($filterRequest)
            ->statusFilter($selectedStatus)
            ->withCount('tasks');

        // ================================================================
        // APPLY PAGINATION AFTER ALL FILTERS
        // ================================================================

        $projects = $query
            ->paginate(10)
            ->appends($request->query());

        // ================================================================
        // AJAX RESPONSE
        // ================================================================

        if ($request->ajax()) {
            return view(
                'contents.report.partials.projects-table',
                compact('projects')
            );
        }

        // ================================================================
        // FILTER DATA
        // ================================================================

        $allTitles = Project::pluck('title')->unique();

        $managers = User::where(
            'role',
            'manager'
        )->get();

        // ================================================================
        // RETURN PROJECT REPORT
        // ================================================================

        return view(
            'contents.report.ProjectReport',
            compact(
                'projects',
                'allTitles',
                'managers'
            )
        );
    }

    /**
     * =====================================================================
     * USER REPORT
     * =====================================================================
     * Handles user reporting and filtering analytics.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function userreport(Request $request): View
    {
        $this->authorize('viewAny', Report::class);

        $query = User::filter($request->only([
            'search',
            'name',
            'role',
            'position',
            'department',
            'status',
            'date_from',
            'date_to'
        ]));

        $users = $query->paginate(10)->appends($request->query());

        $allNames = User::distinct()->pluck('name')->filter()->values();
        $allPositions = User::distinct()->pluck('position')->filter()->values();
        $allDepartments = User::distinct()->pluck('department')->filter()->values();

        return view(
            'contents.report.UserReport',
            compact(
                'users',
                'allNames',
                'allPositions',
                'allDepartments'
            )
        );
    }

    /**
     * =====================================================================
     * TEAM REPORT
     * =====================================================================
     * Handles team reporting and analytical statistics.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function teamreport(Request $request): View
    {
        $this->authorize('viewAny', Report::class);

        $query = Team::with(['leader', 'members', 'project'])
            ->filter($request->only([
                'team_name',
                'project_id',
                'team_leader_id'
            ]));

        // Apply pagination and append query parameters to links
        $teams = $query->paginate(10)->appends($request->query());

        // Fetch data required for the filtering dropdowns in the UI
        $allTeamNames = Team::pluck('name')->unique();
        $projects = Project::all();
        $leaders = User::all();

        return view(
            'contents.report.TeamReport',
            compact(
                'teams',
                'allTeamNames',
                'projects',
                'leaders'
            )
        );
    }
}
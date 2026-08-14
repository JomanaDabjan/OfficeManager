<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
//use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * =========================================================================
 * DASHBOARD CONTROLLER CLASS
 * =========================================================================
 * This controller handles the main administrative dashboard overview.
 * It gathers and computes statistics (total counts for projects, tasks,
 * users, and status breakdowns) efficiently using optimized database queries.
 */
class DashController extends Controller
{
    /**
     * =====================================================================
     * DISPLAY DASHBOARD STATISTICS
     * =====================================================================
     * Fetch all necessary system metrics and pass them to the dashboard view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // -----------------------------------------------------------------
        // STEP 1: OPTIMIZED TASK STATUS COUNTING (GROUP BY)
        // -----------------------------------------------------------------
        // Instead of executing separate database queries for every single status
        // (which causes performance bottlenecks), we fetch all status counts
        // in a single database query using GROUP BY.
        $taskStatusCounts = Task::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        // -----------------------------------------------------------------
        // STEP 2: GATHER TOTAL COUNTS FOR OTHER ENTITIES
        // -----------------------------------------------------------------
        $totalProjects  = Project::count();                             // Count all projects in database
        $totalTasks     = Task::count();                                // Count all tasks across all statuses
        $totalEmployees = User::where('role', 'employee')->count();     // Count only users with 'employee' role
        $totalManagers  = User::where('role', 'manager')->count();      // Count only users with 'manager' role

        // -----------------------------------------------------------------
        // STEP 3: PREPARE DATA ARRAY FOR THE VIEW
        // -----------------------------------------------------------------
        // Map the grouped status counts safely, defaulting to 0 if a status doesn't exist yet.
        $data = [
            'totalProjects'   => $totalProjects,
            'totalTasks'      => $totalTasks,
            'totalEmployees'  => $totalEmployees,
            'totalManagers'   => $totalManagers,

            // Extract individual status counts from the optimized collection
            'pendingTasks'    => $taskStatusCounts['pending'] ?? 0,
            'inProgressTasks' => $taskStatusCounts['in_progress'] ?? 0,
            'completedTasks'  => $taskStatusCounts['completed'] ?? 0,
            'acceptedTasks'   => $taskStatusCounts['accepted'] ?? 0,
            'rejectedTasks'   => $taskStatusCounts['rejected'] ?? 0,
        ];

        // Return the dashboard view packed with all computed statistics
        return view('contents.dashboard.Index', $data);
    }
}

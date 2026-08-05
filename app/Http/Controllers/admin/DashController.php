<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;

/**
 * =========================================================================
 * DASHBOARD CONTROLLER CLASS
 * =========================================================================
 * This controller handles the main administrative dashboard overview.
 * It gathers and computes statistics (total counts for projects, tasks,
 * users, and status breakdowns) to display on the dashboard view.
 */
class DashController extends Controller
{
    // =========================================================================
    // INDEX METHOD: DISPLAY DASHBOARD STATISTICS
    // =========================================================================

    /**
     * Display a listing of the resource (Dashboard main view with counts).
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // -----------------------------------------------------------------
        // STEP 1: Count total entities in the system database tables
        // -----------------------------------------------------------------
        $totalProjects  = Project::count(); // Count all projects
        $totalTasks     = Task::count();    // Count all tasks
        $totalEmployees = User::where('role', 'employee')->count(); // Count users with employee role
        $totalManagers  = User::where('role', 'manager')->count();  // Count users with manager role

        // -----------------------------------------------------------------
        // STEP 2: Count tasks separated by their current progression status
        // -----------------------------------------------------------------
        $pendingTasks    = Task::where('status', 'pending')->count();     // Count pending tasks
        $inProgressTasks = Task::where('status', 'in_progress')->count(); // Count in-progress tasks
        $completedTasks  = Task::where('status', 'completed')->count();  // Count completed tasks
        $acceptedTasks   = Task::where('status', 'accepted')->count();   // Count accepted tasks
        $rejectedTasks   = Task::where('status', 'rejected')->count();   // Count rejected tasks

        // -----------------------------------------------------------------
        // STEP 3: Pass all gathered statistics variables to the dashboard view
        // -----------------------------------------------------------------
        return view('contents.dashboard.Index', compact(
            'totalProjects',
            'totalTasks',
            'totalEmployees',
            'totalManagers',
            'pendingTasks',
            'inProgressTasks',
            'completedTasks',
            'acceptedTasks',
            'rejectedTasks'
        ));
    }

    // =========================================================================
    // EMPTY RESOURCE METHODS (NOT CURRENTLY USED)
    // =========================================================================

    /**
     * Show the form for creating a new resource.
     * (Unused for dashboard)
     *
     * @return void
     */
    public function create()
    {
        // Left empty intentionally as dashboard does not create resources directly here
    }

    /**
     * Store a newly created resource in storage.
     * (Unused for dashboard)
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function store(Request $request)
    {
        // Left empty intentionally
    }

    /**
     * Display the specified resource.
     * (Unused for dashboard)
     *
     * @param string $id
     * @return void
     */
    public function show(string $id)
    {
        // Left empty intentionally
    }

    /**
     * Show the form for editing the specified resource.
     * (Unused for dashboard)
     *
     * @param string $id
     * @return void
     */
    public function edit(string $id)
    {
        // Left empty intentionally
    }

    /**
     * Update the specified resource in storage.
     * (Unused for dashboard)
     *
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return void
     */
    public function update(Request $request, string $id)
    {
        // Left empty intentionally
    }

    /**
     * Remove the specified resource from storage.
     * (Unused for dashboard)
     *
     * @param string $id
     * @return void
     */
    public function destroy(string $id)
    {
        // Left empty intentionally
    }
}
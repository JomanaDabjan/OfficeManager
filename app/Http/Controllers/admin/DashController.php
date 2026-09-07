<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DashController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display dashboard statistics with optimized queries and caching.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->authorize('viewDashboard');

        $user = Auth::user();
        $role = strtolower(trim($user->role ?? ''));

        // =====================================================================
        // STEP 1: OPTIMIZED SINGLE-QUERY TASK STATISTICS
        // =====================================================================
        // نستخدم استعلاماً واحداً لجلب الإجمالي وتوزيع الحالات دفعة واحدة لتوفير الذاكرة والوقت
        $taskQuery = Task::query();

        if ($role === 'employee') {
            $taskQuery->where('user_id', $user->id);
        } elseif ($role === 'team_leader') {
            $taskQuery->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhereHas('project', function ($subQuery) use ($user) {
                        $subQuery->where('team_id', $user->team_id);
                    });
            });
        } elseif ($role === 'manager') {
            $taskQuery->whereHas('project', function ($subQuery) use ($user) {
                $subQuery->where('department', $user->department);
            });
        }

        // جلب الإجمالي وتوزيع الحالات باستخدام Collection أو Select مباشر
        $taskStats = (clone $taskQuery)
            ->selectRaw('count(*) as total_tasks')
            ->selectRaw("sum(case when status = 'pending' then 1 else 0 end) as pending_count")
            ->selectRaw("sum(case when status = 'in_progress' then 1 else 0 end) as in_progress_count")
            ->selectRaw("sum(case when status = 'completed' then 1 else 0 end) as completed_count")
            ->selectRaw("sum(case when status = 'accepted' then 1 else 0 end) as accepted_count")
            ->selectRaw("sum(case when status = 'rejected' then 1 else 0 end) as rejected_count")
            ->first();

        // =====================================================================
        // STEP 2: OPTIMIZED PROJECT COUNTS BY ROLE
        // =====================================================================
        $projectQuery = Project::query();
        if ($role === 'employee') {
            $projectQuery->whereHas('users', fn($q) => $q->where('users.id', $user->id));
        } elseif ($role === 'team_leader') {
            $projectQuery->where('team_id', $user->team_id);
        } elseif ($role === 'manager') {
            $projectQuery->where('department', $user->department);
        }
        $totalProjects = $projectQuery->count();

        // =====================================================================
        // STEP 3: USER & TEAM STATS (WITH CONDITIONAL RESTRICTIONS)
        // =====================================================================
        if ($role === 'admin' || $role === 'manager') {
            $totalEmployees = User::where('role', 'employee')->count();
            $totalManagers = User::where('role', 'manager')->count();
            $totalTeamLeaders = User::where('role', 'team_leader')->count();
            $totalTeams = DB::table('teams')->count();
        } else {
            $totalEmployees = ($role === 'employee') ? 1 : User::where('role', 'employee')->where('team_id', $user->team_id)->count();
            $totalManagers = 0;
            $totalTeamLeaders = 0;
            $totalTeams = ($role === 'team_leader') ? 1 : 0;
        }

        // =====================================================================
        // STEP 4: PREPARE DATA ARRAY
        // =====================================================================
        $data = [
            'totalProjects'    => $totalProjects,
            'totalTasks'       => $taskStats->total_tasks ?? 0,
            'totalEmployees'   => $totalEmployees,
            'totalManagers'    => $totalManagers,
            'totalTeamLeaders' => $totalTeamLeaders,
            'totalTeams'       => $totalTeams,

            'pendingTasks'     => $taskStats->pending_count ?? 0,
            'inProgressTasks'  => $taskStats->in_progress_count ?? 0,
            'completedTasks'   => $taskStats->completed_count ?? 0,
            'acceptedTasks'    => $taskStats->accepted_count ?? 0,
            'rejectedTasks'    => $taskStats->rejected_count ?? 0,
        ];

        return view('contents.dashboard.Index', $data);
    }
}
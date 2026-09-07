<div class="sidebar" data-color="orange">
    <!--
        Tip 1: You can change the color of the sidebar using: data-color="blue | green | orange | red | yellow"
    -->
    <div class="logo">
        <a href="http://www.creative-tim.com" class="simple-text logo-mini">
            CT
        </a>
        <a href="http://www.creative-tim.com" class="simple-text logo-normal">
            CoreTask
        </a>
    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">
            <!-- Dashboard -->
            @can('viewAny', App\Models\User::class)
            <li class="{{ request()->routeIs('admin.dash*') ? 'active' : '' }}">
                <a href="{{ route('admin.dash.index') }}">
                    <i class="now-ui-icons design_app"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            @endcan

            <!-- User Dropdown (Kept as requested) -->
            @can('viewAny', App\Models\User::class)
            <li class="{{ request()->routeIs('admin.user*') ? 'active' : '' }}">
                <a href="{{ route('admin.user.index') }}">
                    <i class=" now-ui-icons users_single-02"></i>
                    <p>User</p>
                </a>
            </li>
            @endcan


            <!-- Team (Direct Link without Submenu) -->
            @can('viewAny', App\Models\Team::class)
            <li class="{{ request()->routeIs('admin.team*') ? 'active' : '' }}">
                <a href="{{ route('admin.team.index') }}">
                    <i class="now-ui-icons users_circle-08"></i>
                    <p>Team</p>
                </a>
            </li>
            @endcan


            <!-- Project (Direct Link without Submenu) -->
            @can('viewAny', App\Models\Project::class)
            <li class="{{ request()->routeIs('admin.project*') ? 'active' : '' }}">
                <a href="{{ route('admin.project.index') }}">
                    <i class="now-ui-icons business_briefcase-24"></i>
                    <p>Project</p>
                </a>
            </li>
            @endcan

            <!-- Task (Direct Link without Submenu) -->
            @can('viewAny', App\Models\Task::class)
            <li class="{{ request()->routeIs('admin.task*') ? 'active' : '' }}">
                <a href="{{ route('admin.task.index') }}">
                    <i class="now-ui-icons design_bullet-list-67"></i>
                    <p>Task</p>
                </a>
            </li>
            @endcan

            <!-- Report (Direct Link without Submenu) -->
            @can('viewAny', App\Models\Report::class)
            <li class="{{ request()->routeIs('admin.report*') ? 'active' : '' }}">
                <a href="{{ route('admin.report.index') }}">
                    <i class="now-ui-icons files_paper"></i>
                    <p>Report</p>
                </a>
            </li>
            @endcan
        </ul>
    </div>
</div>
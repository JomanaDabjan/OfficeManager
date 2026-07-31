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
            <li class="{{ request()->routeIs('admin.dash*') ? 'active' : '' }}">
                <a href="{{ route('admin.dash.index') }}">
                    <i class="now-ui-icons design_app"></i>
                    <p>Dashboard</p>
                </a>
            </li>

            <!-- Project (Direct Link without Submenu) -->
            <li class="{{ request()->routeIs('admin.project*') ? 'active' : '' }}">
                <a href="{{ route('admin.project.index') }}">
                    <i class="now-ui-icons business_briefcase-24"></i>
                    <p>Project</p>
                </a>
            </li>

            <!-- Task (Direct Link without Submenu) -->
            <li class="{{ request()->routeIs('admin.task*') ? 'active' : '' }}">
                <a href="{{ route('admin.task.index') }}">
                    <i class="now-ui-icons design_bullet-list-67"></i>
                    <p>Task</p>
                </a>
            </li>

            <!-- Team (Direct Link without Submenu) -->
            <li class="{{ request()->routeIs('admin.teams*') ? 'active' : '' }}">
                <a href="#">
                    <i class="now-ui-icons users_circle-08"></i>
                    <p>Team</p>
                </a>
            </li>

            <!-- User Dropdown (Kept as requested) -->
            <li class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <a data-toggle="collapse" href="#userDropdown"
                    aria-expanded="{{ request()->routeIs('admin.users*') ? 'true' : 'false' }}">
                    <i class="now-ui-icons users_single-02"></i>
                    <p>User <b class="caret"></b></p>
                </a>
                <div class="collapse {{ request()->routeIs('admin.users*') ? 'show' : '' }}" id="userDropdown">
                    <ul class="nav">
                        <li class="{{ request()->routeIs('admin.users.manager') ? 'active' : '' }}">
                            <a href="#">Project Manager Profile</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.users.employee') ? 'active' : '' }}">
                            <a href="{{ route('admin.user.employee.Index', ['role' => 'employee']) }}">Employee Profile</a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- ProjectStatus -->
            <li class="{{ request()->routeIs('admin.project-status*') ? 'active' : '' }}">
                <a href="#">
                    <i class="now-ui-icons loader_refresh"></i>
                    <p>Project Status</p>
                </a>
            </li>

            <!-- Report (Direct Link without Submenu) -->
            <li class="{{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                <a href="#">
                    <i class="now-ui-icons files_paper"></i>
                    <p>Report</p>
                </a>
            </li>
        </ul>
    </div>
</div>

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
            <li class="{{ request()->routeIs('dash*') ? 'active' : '' }}">
                <a href="{{ route('/') }}">
                    <i class="now-ui-icons design_app"></i>
                    <p>Dashboard</p>
                </a>
            </li>

            <!-- Project Dropdown -->
            <li class="{{ request()->routeIs('projects*') ? 'active' : '' }}">
                <a data-toggle="collapse" href="#projectDropdown"
                    aria-expanded="{{ request()->routeIs('projects*') ? 'true' : 'false' }}">
                    <i class="now-ui-icons business_briefcase-24"></i>
                    <p>Project <b class="caret"></b></p>
                </a>
                <div class="collapse {{ request()->routeIs('projects*') ? 'show' : '' }}" id="projectDropdown">
                    <ul class="nav">
                        <li class="{{ request()->routeIs('projects.index') ? 'active' : '' }}">
                            <a href="#">Show Projects</a>
                        </li>
                        <li class="{{ request()->routeIs('projects.create') ? 'active' : '' }}">
                            <a href="#">Add Project</a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Task Dropdown -->
            <li class="{{ request()->routeIs('tasks*') ? 'active' : '' }}">
                <a data-toggle="collapse" href="#taskDropdown"
                    aria-expanded="{{ request()->routeIs('tasks*') ? 'true' : 'false' }}">
                    <i class="now-ui-icons design_bullet-list-67"></i>
                    <p>Task <b class="caret"></b></p>
                </a>
                <div class="collapse {{ request()->routeIs('tasks*') ? 'show' : '' }}" id="taskDropdown">
                    <ul class="nav">
                        <li class="{{ request()->routeIs('tasks.index') ? 'active' : '' }}">
                            <a href="#">Show Tasks</a>
                        </li>
                        <li class="{{ request()->routeIs('tasks.create') ? 'active' : '' }}">
                            <a href="#">Add Task</a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Team Dropdown -->
            <li class="{{ request()->routeIs('teams*') ? 'active' : '' }}">
                <a data-toggle="collapse" href="#teamDropdown"
                    aria-expanded="{{ request()->routeIs('teams*') ? 'true' : 'false' }}">
                    <i class="now-ui-icons users_circle-08"></i>
                    <p>Team <b class="caret"></b></p>
                </a>
                <div class="collapse {{ request()->routeIs('teams*') ? 'show' : '' }}" id="teamDropdown">
                    <ul class="nav">
                        <li class="{{ request()->routeIs('teams.index') ? 'active' : '' }}">
                            <a href="#">Show Team</a>
                        </li>
                        <li class="{{ request()->routeIs('teams.create') ? 'active' : '' }}">
                            <a href="#">Add Team</a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- User Dropdown -->
            <li class="{{ request()->routeIs('users*') ? 'active' : '' }}">
                <a data-toggle="collapse" href="#userDropdown"
                    aria-expanded="{{ request()->routeIs('users*') ? 'true' : 'false' }}">
                    <i class="now-ui-icons users_single-02"></i>
                    <p>User <b class="caret"></b></p>
                </a>
                <div class="collapse {{ request()->routeIs('users*') ? 'show' : '' }}" id="userDropdown">
                    <ul class="nav">
                        <li class="{{ request()->routeIs('users.manager') ? 'active' : '' }}">
                            <a href="#">Project Manager Profile</a>
                        </li>
                        <li class="{{ request()->routeIs('users.employee') ? 'active' : '' }}">
                            <a href="#">Employee Profile</a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- ProjectStatus -->
            <li class="{{ request()->routeIs('project-status*') ? 'active' : '' }}">
                <a href="#">
                    <i class="now-ui-icons loader_refresh"></i>
                    <p>Project Status</p>
                </a>
            </li>

            <!-- Report Dropdown -->
            <li class="{{ request()->routeIs('reports*') ? 'active' : '' }}">
                <a data-toggle="collapse" href="#reportDropdown"
                    aria-expanded="{{ request()->routeIs('reports*') ? 'true' : 'false' }}">
                    <i class="now-ui-icons files_paper"></i>
                    <p>Report <b class="caret"></b></p>
                </a>
                <div class="collapse {{ request()->routeIs('reports*') ? 'show' : '' }}" id="reportDropdown">
                    <ul class="nav">
                        <li class="{{ request()->routeIs('reports.index') ? 'active' : '' }}">
                            <a href="#">Show Report</a>
                        </li>
                        <li class="{{ request()->routeIs('reports.create') ? 'active' : '' }}">
                            <a href="#">Add Report</a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</div>

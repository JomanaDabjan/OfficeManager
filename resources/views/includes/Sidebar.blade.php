<!-- ========================================================================= -->
<!-- SIDEBAR                                                                  -->
<!-- ========================================================================= -->

<div class="sidebar" data-color="orange">

    <!--
        Tip 1: You can change the color of the sidebar using:
        data-color="blue | green | orange | red | yellow"
    -->


    <!-- ===================================================================== -->
    <!-- LOGO                                                                  -->
    <!-- ===================================================================== -->

    <div class="logo">

        <a href="http://www.creative-tim.com" class="simple-text logo-mini">
            CT
        </a>

        <a href="http://www.creative-tim.com" class="simple-text logo-normal">
            CoreTask
        </a>


        <!-- ================================================================ -->
        <!-- SIDEBAR COLLAPSE BUTTON                                          -->
        <!-- ================================================================ -->

        <button type="button" id="sidebarCollapseButton" class="sidebar-collapse-btn" aria-label="Collapse sidebar"
            title="Collapse sidebar">
            <i class="now-ui-icons arrows-1_minimal-left"></i>
        </button>

    </div>


    <!-- ===================================================================== -->
    <!-- SIDEBAR WRAPPER                                                       -->
    <!-- ===================================================================== -->

    <div class="sidebar-wrapper">

        <ul class="nav">


            <!-- ============================================================= -->
            <!-- Dashboard                                                     -->
            <!-- ============================================================= -->

            @can('viewAny', App\Models\User::class)

            <li class="{{ request()->routeIs('admin.dash*') ? 'active' : '' }}">

                <a href="{{ route('admin.dash.index') }}" data-title="Dashboard">
                    <i class="now-ui-icons design_app"></i>
                    <p>Dashboard</p>
                </a>

            </li>

            @endcan



            <!-- ============================================================= -->
            <!-- User                                                          -->
            <!-- ============================================================= -->

            @can('viewAny', App\Models\User::class)

            <li class="{{ request()->routeIs('admin.user*') ? 'active' : '' }}">

                <a href="{{ route('admin.user.index') }}" data-title="User">
                    <i class="now-ui-icons users_single-02"></i>
                    <p>User</p>
                </a>

            </li>

            @endcan



            <!-- ============================================================= -->
            <!-- Project                                                       -->
            <!-- ============================================================= -->

            @can('viewAny', App\Models\Project::class)

            <li class="{{ request()->routeIs('admin.project*') ? 'active' : '' }}">

                <a href="{{ route('admin.project.index') }}" data-title="Project">
                    <i class="now-ui-icons business_briefcase-24"></i>
                    <p>Project</p>
                </a>

            </li>

            @endcan



            <!-- ============================================================= -->
            <!-- Team                                                          -->
            <!-- ============================================================= -->

            @can('viewAny', App\Models\Team::class)

            <li class="{{ request()->routeIs('admin.team*') ? 'active' : '' }}">

                <a href="{{ route('admin.team.index') }}" data-title="Team">
                    <i class="now-ui-icons users_circle-08"></i>
                    <p>Team</p>
                </a>

            </li>

            @endcan



            <!-- ============================================================= -->
            <!-- Task                                                          -->
            <!-- ============================================================= -->

            @can('viewAny', App\Models\Task::class)

            <li class="{{ request()->routeIs('admin.task*') ? 'active' : '' }}">

                <a href="{{ route('admin.task.index') }}" data-title="Task">
                    <i class="now-ui-icons design_bullet-list-67"></i>
                    <p>Task</p>
                </a>

            </li>

            @endcan



            <!-- ============================================================= -->
            <!-- Report                                                        -->
            <!-- ============================================================= -->

            @can('viewAny', App\Models\Report::class)

            <li class="{{ request()->routeIs('admin.report*') ? 'active' : '' }}">

                <a href="{{ route('admin.report.index') }}" data-title="Report">
                    <i class="now-ui-icons files_paper"></i>
                    <p>Report</p>
                </a>

            </li>

            @endcan


        </ul>

    </div>

</div>

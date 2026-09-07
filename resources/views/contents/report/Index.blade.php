@extends('layouts.app')

<!-- ================================================================= -->
<!-- PAGE TITLE SECTION                                                -->
<!-- ================================================================= -->
<!-- Set the dynamic title for this specific page shown in browser tab -->
@section('title', 'Reports Hub')

<!-- ================================================================= -->
<!-- MAIN CONTENT SECTION                                              -->
<!-- ================================================================= -->
@section('Main_Content')

@can('viewAny', \App\Models\Report::class)

<div class="content">

    <!-- Header Row for Page Title with Top Padding -->
    <div class="row" style="padding-top: 30px;">
        <div class="col-md-12">
            <div class="card card-plain">
                <div class="card-header">
                    <h4 class="card-title">Choose Report Type</h4>
                    <p class="category" style="font-size: 15px;">Select an analytical report category below to view
                        detailed insights.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Reports Cards Grid Row with Flexbox for Equal Heights -->
    <div class="row d-flex align-items-stretch">


        <!-- ========================================================= -->
        <!-- CARD 1: PROJECT REPORTS                                   -->
        <!-- ========================================================= -->
        <div class="col-lg-6 col-md-6 d-flex mb-4">
            <div class="card card-chart shadow-sm flex-fill d-flex flex-column">
                <div class="card-header text-center pt-4">
                    <!-- Illustration placeholder -->
                    <div class="icon-big text-center icon-warning mb-3" style="height: 140px;">
                        <img src="https://img.icons8.com/color/96/project-setup.png" alt="Project Reports"
                            style="max-height: 100%; max-width: 100%; object-fit: contain;">
                    </div>
                    <h5 class="card-title font-weight-bold">Project Reports</h5>
                </div>
                <div class="card-body flex-grow-1">
                    <p class="card-category text-center pb-3">Analyze ongoing projects, task distribution, and overall
                        progress rates.</p>
                </div>
                <div class="card-footer text-center pb-4">
                    <!-- Action Link using the route defined in web.php -->
                    <a href="{{ route('admin.report.project-report') }}" class="btn btn-primary btn-round btn-block">
                        View Report
                    </a>
                </div>
            </div>
        </div>


        <!-- ========================================================= -->
        <!-- CARD 2: TASK REPORTS                                      -->
        <!-- ========================================================= -->
        <div class="col-lg-6 col-md-6 d-flex mb-4">
            <div class="card card-chart shadow-sm flex-fill d-flex flex-column">
                <div class="card-header text-center pt-4">
                    <!-- Illustration placeholder -->
                    <div class="icon-big text-center icon-warning mb-3" style="height: 140px;">
                        <img src="https://img.icons8.com/color/96/todo-list.png" alt="Task Reports"
                            style="max-height: 100%; max-width: 100%; object-fit: contain;">
                    </div>
                    <h5 class="card-title font-weight-bold">Task Reports</h5>
                </div>
                <div class="card-body flex-grow-1">
                    <p class="card-category text-center pb-3">Track individual task completion, pending workloads, and
                        user metrics.</p>
                </div>
                <div class="card-footer text-center pb-4">
                    <!-- Action Link using the route defined in web.php -->
                    <a href="{{ route('admin.report.task-report') }}" class="btn btn-primary btn-round btn-block">
                        View Report
                    </a>
                </div>
            </div>
        </div>


        <!-- ========================================================= -->
        <!-- CARD 3: TEAM REPORTS                                      -->
        <!-- ========================================================= -->
        <div class="col-lg-6 col-md-6 d-flex mb-4">
            <div class="card card-chart shadow-sm flex-fill d-flex flex-column">
                <div class="card-header text-center pt-4">
                    <!-- Illustration placeholder -->
                    <div class="icon-big text-center icon-warning mb-3" style="height: 140px;">
                        <img src="https://img.icons8.com/color/96/conference.png" alt="Team Reports"
                            style="max-height: 100%; max-width: 100%; object-fit: contain;">
                    </div>
                    <h5 class="card-title font-weight-bold">Team Reports</h5>
                </div>
                <div class="card-body flex-grow-1">
                    <p class="card-category text-center pb-3">Monitor team collaboration, group performances, and
                        collective milestones.</p>
                </div>
                <div class="card-footer text-center pb-4">
                    <!-- Action Link using the route defined in web.php -->
                    <a href="{{ route('admin.report.team-report') }}" class="btn btn-primary btn-round btn-block">
                        View Report
                    </a>
                </div>
            </div>
        </div>


        <!-- ========================================================= -->
        <!-- CARD 4: USER REPORTS                                      -->
        <!-- ========================================================= -->
        <div class="col-lg-6 col-md-6 d-flex mb-4">
            <div class="card card-chart shadow-sm flex-fill d-flex flex-column">
                <div class="card-header text-center pt-4">
                    <!-- Illustration placeholder -->
                    <div class="icon-big text-center icon-warning mb-3" style="height: 140px;">
                        <img src="https://img.icons8.com/color/96/user-male-circle.png" alt="User Reports"
                            style="max-height: 100%; max-width: 100%; object-fit: contain;">
                    </div>
                    <h5 class="card-title font-weight-bold">User Reports</h5>
                </div>
                <div class="card-body flex-grow-1">
                    <p class="card-category text-center pb-3">Evaluate individual user activity, contributions, and
                        productivity status.</p>
                </div>
                <div class="card-footer text-center pb-4">
                    <!-- Action Link using the route defined in web.php -->
                    <a href="{{ route('admin.report.user-report') }}" class="btn btn-primary btn-round btn-block">
                        View Report
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

@endcan

@endsection
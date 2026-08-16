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
<div class="content">

    <!-- Header Row for Page Title -->
    <div class="row">
        <div class="col-md-12">
            <div class="card card-plain">
                <div class="card-header">
                    <h4 class="card-title">Choose Report Type</h4>
                    <p class="category">Select an analytical report category below to view detailed insights.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Reports Cards Grid Row -->
    <div class="row">


        <!-- ========================================================= -->
        <!-- CARD 1: PROJECT REPORTS                                   -->
        <!-- ========================================================= -->
        <div class="col-lg-4 col-md-6">
            <div class="card card-chart shadow-sm">
                <div class="card-header text-center pt-4">
                    <!-- Illustration placeholder -->
                    <div class="icon-big text-center icon-warning mb-3" style="height: 140px;">
                        <img src="https://img.icons8.com/color/96/project-setup.png" alt="Project Reports"
                            style="max-height: 100%; max-width: 100%; object-fit: contain;">
                    </div>
                    <h5 class="card-title font-weight-bold">Project Reports</h5>
                </div>
                <div class="card-body">
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
        <div class="col-lg-4 col-md-6">
            <div class="card card-chart shadow-sm">
                <div class="card-header text-center pt-4">
                    <!-- Illustration placeholder -->
                    <div class="icon-big text-center icon-warning mb-3" style="height: 140px;">
                        <img src="https://img.icons8.com/color/96/todo-list.png" alt="Task Reports"
                            style="max-height: 100%; max-width: 100%; object-fit: contain;">
                    </div>
                    <h5 class="card-title font-weight-bold">Task Reports</h5>
                </div>
                <div class="card-body">
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
        <!-- CARD 3: SYSTEM OVERVIEW REPORT                            -->
        <!-- ========================================================= -->
        <div class="col-lg-4 col-md-6">
            <div class="card card-chart shadow-sm">
                <div class="card-header text-center pt-4">
                    <!-- Illustration placeholder -->
                    <div class="icon-big text-center icon-warning mb-3" style="height: 140px;">
                        <img src="https://img.icons8.com/color/96/data-configuration.png" alt="System Overview"
                            style="max-height: 100%; max-width: 100%; object-fit: contain;">
                    </div>
                    <h5 class="card-title font-weight-bold">System Overview</h5>
                </div>
                <div class="card-body">
                    <p class="card-category text-center pb-3">View global Key Performance Indicators (KPIs) and
                        high-level statistics.</p>
                </div>
                <div class="card-footer text-center pb-4">
                    <!-- Action Link using the route defined in web.php -->
                    <a href="{{ route('admin.report.overview') }}" class="btn btn-primary btn-round btn-block">
                        View Report
                    </a>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

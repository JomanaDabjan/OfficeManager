@extends('layouts.app')

<!-- ================================================================= -->
<!-- PAGE TITLE SECTION                                                -->
<!-- ================================================================= -->
<!-- Set the dynamic title for this specific page shown in browser tab -->
@section('title', 'System Overview Report')

<!-- ================================================================= -->
<!-- MAIN CONTENT SECTION                                              -->
<!-- ================================================================= -->
@section('Main_Content')
<div class="content">

    <!-- Page Header Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card card-plain">
                <div class="card-header">
                    <h4 class="card-title">System Overview Dashboard</h4>
                    <p class="category">High-level key performance indicators (KPIs) and global statistics of the
                        application.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards Row (KPIs) -->
    <div class="row">

        <!-- ========================================================= -->
        <!-- TOTAL USERS CARD                                          -->
        <!-- ========================================================= -->
        <div class="col-lg-4 col-md-6">
            <div class="card card-stats shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <div class="icon-big text-center icon-warning">
                                <i class="now-ui-icons users_circle-08 text-primary"></i>
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="numbers">
                                <p class="card-category">Total Users</p>
                                <h4 class="card-title">{{ $totalUsers }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer textvariable">
                    <hr>
                    <div class="stats">
                        <i class="now-ui-icons arrows-1_refresh-69"></i> Active system accounts
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================================= -->
        <!-- TOTAL PROJECTS CARD                                       -->
        <!-- ========================================================= -->
        <div class="col-lg-4 col-md-6">
            <div class="card card-stats shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <div class="icon-big text-center icon-warning">
                                <i class="now-ui-icons business_bank text-success"></i>
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="numbers">
                                <p class="card-category">Total Projects</p>
                                <h4 class="card-title">{{ $totalProjects }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <hr>
                    <div class="stats">
                        <i class="now-ui-icons ui-1_calendar-60"></i> Managed projects
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================================= -->
        <!-- TOTAL TASKS CARD                                          -->
        <!-- ========================================================= -->
        <div class="col-lg-4 col-md-6">
            <div class="card card-stats shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <div class="icon-big text-center icon-warning">
                                <i class="now-ui-icons ui-1_bell-53 text-danger"></i>
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="numbers">
                                <p class="card-category">Total Tasks</p>
                                <h4 class="card-title">{{ $totalTasks }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <hr>
                    <div class="stats">
                        <i class="now-ui-icons media-2_sound-wave"></i> Registered tasks
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Task Status Breakdown Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title">Task Status Breakdown</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">

                        <!-- Status Breakdown Table -->
                        <table class="table text-center">
                            <thead class="text-primary">
                                <tr>
                                    <th class="text-left">Status Name</th>
                                    <th>Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Loop through the taskStatusBreakdown array passed from controller -->
                                @forelse($taskStatusBreakdown as $status => $count)
                                <tr>
                                    <!-- Status Key Column -->
                                    <td class="text-left font-weight-bold text-uppercase">{{ $status }}</td>

                                    <!-- Status Count Value -->
                                    <td>
                                        <span class="badge badge-neutral px-3 py-2">{{ $count }}</span>
                                    </td>
                                </tr>
                                @empty
                                <!-- Fallback message if no data exists -->
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-4">No status metrics available.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
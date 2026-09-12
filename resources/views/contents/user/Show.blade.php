@extends('layouts.app')

@section('Main_Content')

@can('view', $user)

<div class="content mt-3">
    <div class="row">
        <div class="col-md-12">

            <!-- ======================================================= -->
            <!-- MAIN CARD CONTAINER START                               -->
            <!-- ======================================================= -->
            <div class="card">

                <!-- Card Header with Title and Back Button -->
                <div class="card-header bg-primary text-white p-4 rounded-top d-flex justify-content-between align-items-center"
                    style="background: linear-gradient(0deg, #f96332 0%, #ff8559 100%) !important;">
                    <div class="d-flex align-items-center">
                        <div class="icon icon-shape bg-white text-primary rounded-circle shadow mr-3 d-flex align-items-center justify-content-center"
                            style="width: 48px; height: 48px; min-width: 48px;">
                            <i class="now-ui-icons users_single-02 text-primary" style="font-size: 20px;"></i>
                        </div>
                        <div>
                            <h3 class="card-title text-white mb-0"><strong>{{ $user->name }}</strong></h3>
                            <p class="card-category text-white mb-0"> Detailed view and system
                                overview of the user profile.</p>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('admin.user.index') }}"
                            class="btn btn-neutral btn-round text-primary font-weight-bold btn-sm px-4 shadow-sm"
                            style="height: 36px; display: inline-flex; align-items: center; justify-content: center;">
                            <i class="now-ui-icons arrows-1_minimal-left mr-1"></i> Back
                        </a>

                        @can('update', $user)
                        <a href="{{ route('admin.user.edit', $user->id) }}"
                            class="btn btn-primary btn-round text-white font-weight-bold btn-sm px-4 shadow-sm"
                            style="height: 36px; display: inline-flex; align-items: center; justify-content: center;">
                            <i class="now-ui-icons ui-2_settings-90 mr-1"></i> Edit User
                        </a>
                        @endcan
                    </div>
                </div>

                <!-- Card Body Containing User Information -->
                <div class="card-body p-4">

                    <!-- =================================================== -->
                    <!-- SECTION 1: METRICS & INFO CARDS GRID                -->
                    <!-- =================================================== -->
                    <div class="row">
                        <!-- Department Card -->
                        <div class="col-md-4 mb-4">
                            <div class="card card-stats card-raised h-100 mb-0 border shadow-sm">
                                <div class="card-body">
                                    <p class="text-uppercase text-muted font-weight-bold mb-1" style="font-size: 11px;">
                                        Department</p>
                                    <h6 class="card-title font-weight-bold text-dark mt-2 mb-0">
                                        <i class="now-ui-icons design_app text-primary mr-1"></i>
                                        {{ $user->department ?? 'Not Assigned' }}
                                    </h6>
                                </div>
                            </div>
                        </div>

                        <!-- Phone Number Card -->
                        <div class="col-md-4 mb-4">
                            <div class="card card-stats card-raised h-100 mb-0 border shadow-sm">
                                <div class="card-body">
                                    <p class="text-uppercase text-muted font-weight-bold mb-1" style="font-size: 11px;">
                                        Phone Number</p>
                                    <h6 class="card-title font-weight-bold text-dark mt-2 mb-0">
                                        <i class="now-ui-icons tech_mobile text-primary mr-1"></i>
                                        {{ $user->phone ?? ($user->phone_number ?? 'No Phone Provided') }}
                                    </h6>
                                </div>
                            </div>
                        </div>

                        <!-- Joining Date Card -->
                        <div class="col-md-4 mb-4">
                            <div class="card card-stats card-raised h-100 mb-0 border shadow-sm">
                                <div class="card-body">
                                    <p class="text-uppercase text-muted font-weight-bold mb-1" style="font-size: 11px;">
                                        Joining Date</p>
                                    <h6 class="card-title font-weight-bold text-dark mt-2 mb-0">
                                        <i class="now-ui-icons ui-1_calendar-60 text-primary mr-1"></i>
                                        {{ $user->joining_date ?
                                        \Carbon\Carbon::parse($user->joining_date)->format('Y-m-d') : 'N/A' }}
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECOND ROW: WORKING HOURS & EXTRA DETAILS -->
                    <div class="row">
                        <!-- Working Hours Card -->
                        <div class="col-md-4 mb-4">
                            <div class="card card-stats card-raised h-100 mb-0 border shadow-sm">
                                <div class="card-body">
                                    <p class="text-uppercase text-muted font-weight-bold mb-1" style="font-size: 11px;">
                                        <i class="fas fa-clock text-primary mr-1" style="font-size: 12px;"></i> Working
                                        Hours
                                    </p>
                                    <h6 class="card-title font-weight-bold text-primary mt-2 mb-0"
                                        style="font-size: 14px;">
                                        <span>
                                            {{ $user->working_hours ?? 'Standard Hours' }}
                                        </span>
                                    </h6>
                                </div>
                            </div>
                        </div>

                        <!-- Email Address Card -->
                        <div class="col-md-4 mb-4">
                            <div class="card card-stats card-raised h-100 mb-0 border shadow-sm">
                                <div class="card-body">
                                    <p class="text-uppercase text-muted font-weight-bold mb-1" style="font-size: 11px;">
                                        Email Address</p>
                                    <h6 class="card-title font-weight-bold text-dark mt-2 mb-0 text-break"
                                        style="font-size: 13px;">
                                        <i class="now-ui-icons ui-1_email-85 text-primary mr-1"></i>
                                        {{ $user->email ?? 'No Email' }}
                                    </h6>
                                </div>
                            </div>
                        </div>

                        <!-- Account Status Card -->
                        <div class="col-md-4 mb-4">
                            <div class="card card-stats card-raised h-100 mb-0 border shadow-sm">
                                <div class="card-body">
                                    <p class="text-uppercase text-muted font-weight-bold mb-1" style="font-size: 11px;">
                                        Account Status</p>
                                    <div class="mt-2">
                                        @if(strtolower($user->status ?? 'active') === 'deactivated' ||
                                        strtolower($user->status ?? '') === 'inactive' || ($user->is_active ?? true) ==
                                        false)
                                        <span class="badge badge-danger p-2 px-3 text-uppercase font-weight-bold">
                                            Deactivated
                                        </span>
                                        @else
                                        <span class="badge badge-success p-2 px-3 text-uppercase font-weight-bold">
                                            Active
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- THIRD ROW: PROJECTS & TASKS METRICS WITH ACTION BUTTONS -->
                    <div class="row">
                        <!-- Active Projects Card -->
                        <div class="col-md-3 mb-4">
                            <div class="card card-stats card-raised h-100 mb-0 border shadow-sm">
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <div>
                                        <p class="text-uppercase text-muted font-weight-bold mb-1"
                                            style="font-size: 11px;">
                                            Active Projects</p>
                                        <h6 class="card-title font-weight-bold text-dark mt-2 mb-0">
                                            <i class="now-ui-icons business_briefcase-24 text-primary mr-1"></i>
                                            {{ isset($activeProjectsCount) ? $activeProjectsCount :
                                            ($user->activeProjectsCount ?? 0) }}
                                        </h6>
                                    </div>
                                    <div class="mt-3">
                                        <a href="{{ route('admin.project.index', ['user_id' => $user->id, 'status' => 'active']) }}"
                                            class="btn btn-primary btn-round btn-sm btn-block text-white font-weight-bold">
                                            View Active
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Past Projects Card -->
                        <div class="col-md-3 mb-4">
                            <div class="card card-stats card-raised h-100 mb-0 border shadow-sm">
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <div>
                                        <p class="text-uppercase text-muted font-weight-bold mb-1"
                                            style="font-size: 11px;">
                                            Past Projects</p>
                                        <h6 class="card-title font-weight-bold text-dark mt-2 mb-0">
                                            <template></template><i
                                                class="now-ui-icons education_paper text-primary mr-1"></i>
                                            {{ isset($pastProjectsCount) ? $pastProjectsCount :
                                            ($user->pastProjectsCount ?? 0) }}
                                        </h6>
                                    </div>
                                    <div class="mt-3">
                                        <a href="{{ route('admin.project.index', ['user_id' => $user->id, 'status' => 'completed']) }}"
                                            class="btn btn-primary btn-round btn-sm btn-block text-white font-weight-bold">
                                            View Past
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Assigned Tasks Card -->
                        <div class="col-md-3 mb-4">
                            <div class="card card-stats card-raised h-100 mb-0 border shadow-sm">
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <div>
                                        <p class="text-uppercase text-muted font-weight-bold mb-1"
                                            style="font-size: 11px;">
                                            Assigned Tasks</p>
                                        <h6 class="card-title font-weight-bold text-dark mt-2 mb-0">
                                            <i class="now-ui-icons ui-1_bell-53 text-primary mr-1"></i>
                                            {{ isset($tasksCount) ? $tasksCount : ($user->tasks_count ?? 0) }}
                                        </h6>
                                    </div>
                                    <div class="mt-3">
                                        <a href="{{ route('admin.task.index', ['user_id' => $user->id]) }}"
                                            class="btn btn-primary btn-round btn-sm btn-block text-white font-weight-bold">
                                            View Tasks
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Task Completion Status Card -->
                        <div class="col-md-3 mb-4">
                            <div class="card card-stats card-raised h-100 mb-0 border shadow-sm">
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <div>
                                        <p class="text-uppercase text-muted font-weight-bold mb-1"
                                            style="font-size: 11px;">
                                            Completed Tasks</p>
                                        <h6 class="card-title font-weight-bold text-dark mt-2 mb-0">
                                            <i class="now-ui-icons ui-1_check text-success mr-1"></i>
                                            {{ isset($completedTasksCount) ? $completedTasksCount :
                                            ($user->completed_tasks_count ?? 0) }}
                                        </h6>
                                    </div>
                                    <div class="mt-3">
                                        <a href="{{ route('admin.task.index', ['user_id' => $user->id, 'status' => 'completed']) }}"
                                            class="btn btn-primary btn-round btn-sm btn-block text-white font-weight-bold">
                                            View Completed
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END OF CARD BODY -->

                </div>
                <!-- END OF MAIN CARD CONTAINER -->

            </div>
        </div>
    </div>
</div>

@endcan

@endsection

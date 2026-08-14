@extends('layouts.app')

<!-- Set the dynamic title for this specific page -->
@section('title', 'Task Report')

@section('Main_Content')

<!-- ========================================== -->
<!-- PAGE HEADER AND EXPORT ACTION BUTTON        -->
<!-- ========================================== -->
<div class="row mt-4 mb-4 align-items-center">
    <div class="col-lg-6 col-md-8 col-12 mb-3 mb-lg-0">
        <h3 class="font-weight-bold text-dark mb-0 text-break">Task Report Management</h3>
        <p class="text-muted text-sm mb-0">Comprehensive overview of all system tasks, assigned employees, and statuses.
        </p>
    </div>
    <div class="col-lg-6 col-md-4 col-12 text-lg-right text-md-right text-left">
        <!-- ========================================================================== -->
        <!-- EXPORT & PRINT DROPDOWN ACTIONS SECTION                                    -->
        <!-- ========================================================================== -->
        <div class="dropdown d-inline-block">
            <!-- Dropdown Toggle Button -->
            <button class="btn btn-primary btn-round dropdown-toggle text-white shadow-sm px-4" type="button"
                id="exportDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="now-ui-icons files_single-copy-04"></i> Export & Print
            </button>

            <!-- Dropdown Menu Options -->
            <div class="dropdown-menu dropdown-menu-right shadow-lg border-0 py-2" aria-labelledby="exportDropdown"
                style="border-radius: 12px;">

                <!-- ================================================================== -->
                <!-- DOWNLOAD PDF ACTION LINK                                           -->
                <!-- ================================================================== -->
                <a class="dropdown-item py-2 px-3 text-sm font-weight-bold" id="downloadPdfBtn"
                    href="{{ route('admin.report.task-report.pdf', array_merge(request()->all(), ['search' => request('search')])) }}">
                    <i class="now-ui-icons files_paper text-danger mr-2"></i> Download PDF
                </a>

                <a class="dropdown-item py-2 px-3 text-sm font-weight-bold"
                    href="{{ route('admin.report.task-report.excel') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}">
                    <!-- Icon representing charts/data for the excel action -->
                    <i class="now-ui-icons business_chart-pie-36 text-success mr-2"></i>

                    <!-- Action label displayed to the user -->
                    Download Excel
                </a>

                <!-- Divider line separating file exports from browser actions -->
                <div class="dropdown-divider"></div>

                <a class="dropdown-item py-2 px-3 text-sm font-weight-bold" id="printReportBtn"
                    href="javascript:void(0);" onclick="confirmAndExport('print')">
                    <i class="now-ui-icons media-1_album text-primary mr-2"></i> Print Report
                </a>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- FOUR KEY METRICS CARDS SECTION            -->
<!-- ========================================== -->
<div class="row">

    <!-- Total Tasks Card -->
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
        <div class="card card-stats border-0 shadow-lg position-relative overflow-hidden"
            style="border-radius: 18px; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); transition: transform 0.2s ease;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="card-category text-uppercase text-muted font-weight-bold mb-1"
                            style="font-size: 10px; letter-spacing: 1px;">Total Tasks</p>
                        <h3 class="card-title font-weight-bolder text-dark mb-0">
                            {{ isset($totalTasksCount) ? $totalTasksCount : (method_exists($tasks, 'total') ?
                            $tasks->total() : $tasks->count()) }}
                        </h3>
                    </div>
                    <div class="icon-shape text-white rounded-circle shadow d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width: 48px; height: 48px; background: linear-gradient(135deg, #8965e0 0%, #bc6fe1 100%);">
                        <i class="now-ui-icons design_bullet-list-67" style="font-size: 20px;"></i>
                    </div>
                </div>
            </div>
            <div class="position-absolute w-100"
                style="height: 4px; bottom: 0; left: 0; background: linear-gradient(135deg, #8965e0 0%, #bc6fe1 100%);">
            </div>
        </div>
    </div>

    <!-- Completed Tasks Card -->
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
        <div class="card card-stats border-0 shadow-lg position-relative overflow-hidden"
            style="border-radius: 18px; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); transition: transform 0.2s ease;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="card-category text-uppercase text-muted font-weight-bold mb-1"
                            style="font-size: 10px; letter-spacing: 1px;">Completed</p>
                        <h3 class="card-title font-weight-bolder text-dark mb-0">
                            {{ isset($completedTasksCount) ? $completedTasksCount : $tasks->where('status',
                            'completed')->count() }}
                        </h3>
                    </div>
                    <div class="icon-shape text-white rounded-circle shadow d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width: 48px; height: 48px; background: linear-gradient(135deg, #2dce89 0%, #2ddfc4 100%);">
                        <i class="now-ui-icons ui-1_check" style="font-size: 20px;"></i>
                    </div>
                </div>
            </div>
            <div class="position-absolute w-100"
                style="height: 4px; bottom: 0; left: 0; background: linear-gradient(135deg, #2dce89 0%, #2ddfc4 100%);">
            </div>
        </div>
    </div>

    <!-- In Progress Tasks Card -->
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
        <div class="card card-stats border-0 shadow-lg position-relative overflow-hidden"
            style="border-radius: 18px; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); transition: transform 0.2s ease;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="card-category text-uppercase text-muted font-weight-bold mb-1"
                            style="font-size: 10px; letter-spacing: 1px;">In Progress</p>
                        <h3 class="card-title font-weight-bolder text-dark mb-0">
                            {{ isset($inProgressTasksCount) ? $inProgressTasksCount : $tasks->where('status',
                            'in_progress')->count() }}
                        </h3>
                    </div>
                    <div class="icon-shape text-white rounded-circle shadow d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width: 48px; height: 48px; background: linear-gradient(135deg, #fbb140 0%, #f39c12 100%);">
                        <i class="now-ui-icons loader_refresh" style="font-size: 20px;"></i>
                    </div>
                </div>
            </div>
            <div class="position-absolute w-100"
                style="height: 4px; bottom: 0; left: 0; background: linear-gradient(135deg, #fbb140 0%, #f39c12 100%);">
            </div>
        </div>
    </div>

    <!-- Pending Tasks Card -->
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
        <div class="card card-stats border-0 shadow-lg position-relative overflow-hidden"
            style="border-radius: 18px; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); transition: transform 0.2s ease;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="card-category text-uppercase text-muted font-weight-bold mb-1"
                            style="font-size: 10px; letter-spacing: 1px;">Pending</p>
                        <h3 class="card-title font-weight-bolder text-dark mb-0">
                            {{ isset($pendingTasksCount) ? $pendingTasksCount : $tasks->where('status',
                            'pending')->count() }}
                        </h3>
                    </div>
                    <div class="icon-shape text-white rounded-circle shadow d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width: 48px; height: 48px; background: linear-gradient(135deg, #11cdef 0%, #1171ef 100%);">
                        <i class="now-ui-icons time-support" style="font-size: 20px;"></i>
                    </div>
                </div>
            </div>
            <div class="position-absolute w-100"
                style="height: 4px; bottom: 0; left: 0; background: linear-gradient(135deg, #11cdef 0%, #1171ef 100%);">
            </div>
        </div>
    </div>

</div>

<!-- ========================================== -->
<!-- TASK FILTERS DROPDOWNS SECTION (SERVER)    -->
<!-- ========================================== -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm" style="border-radius: 16px; background: #ffffff; overflow: visible;">
            <div class="card-body p-3" style="overflow: visible;">
                <div class="d-flex flex-wrap align-items-center justify-content-between" style="gap: 12px;">

                    <!-- Filters Grouping Container -->
                    <div class="d-flex flex-wrap align-items-center w-100 w-md-auto" style="gap: 10px;">
                        <!-- Filter Icon Label -->
                        <span class="text-muted font-weight-bold mr-2 d-none d-sm-inline-block"
                            style="font-size: 13px;">
                            <i class="now-ui-icons ui-1_zoom-bold mr-1 text-primary"></i> Filter By:
                        </span>

                        <!-- 1. Task Title Filter Dropdown -->
                        <div class="dropdown flex-fill flex-md-grow-0">
                            <button
                                class="btn btn-light btn-sm dropdown-toggle text-dark shadow-none px-3 py-2 font-weight-bold rounded-pill border w-100 text-left text-md-center d-flex justify-content-between align-items-center"
                                type="button" id="dropdownTaskTitle" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false"
                                style="font-size: 13px; background-color: #f8f9fa; border-color: #e3e6f0 !important;">
                                <span class="text-truncate">
                                    {{ request('title') && request('title') != 'all' ? Str::limit(request('title'), 15)
                                    : 'All Task Titles' }}
                                </span>
                            </button>
                            <div class="dropdown-menu shadow-lg border-0 py-2" aria-labelledby="dropdownTaskTitle"
                                style="border-radius: 12px; min-width: 180px;">
                                <a class="dropdown-item py-2 px-3 text-sm {{ !request('title') || request('title') == 'all' ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.report.task-report', array_merge(request()->except(['title', 'page']), ['title' => 'all'])) }}">
                                    <i class="now-ui-icons ui-1_simple-add mr-2"></i> All Task Titles
                                </a>
                                @if(isset($allTaskTitles))
                                @foreach($allTaskTitles as $taskTitle)
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('title') == $taskTitle ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.report.task-report', array_merge(request()->except(['title', 'page']), ['title' => $taskTitle])) }}">
                                    {{ $taskTitle }}
                                </a>
                                @endforeach
                                @endif
                            </div>
                        </div>

                        <!-- 2. Project Name Filter Dropdown (Relational Context) -->
                        <div class="dropdown flex-fill flex-md-grow-0">
                            <button
                                class="btn btn-light btn-sm dropdown-toggle text-dark shadow-none px-3 py-2 font-weight-bold rounded-pill border w-100 text-left text-md-center d-flex justify-content-between align-items-center"
                                type="button" id="dropdownProject" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false"
                                style="font-size: 13px; background-color: #f8f9fa; border-color: #e3e6f0 !important;">
                                @php
                                $selectedProject = isset($projects) ?$projects->firstWhere('id', request('project_id'))
                                : null;
                                @endphp
                                <span class="text-truncate">
                                    {{ $selectedProject ? $selectedProject->title : 'All Projects' }}
                                </span>
                            </button>
                            <div class="dropdown-menu shadow-lg border-0 py-2" aria-labelledby="dropdownProject"
                                style="border-radius: 12px; min-width: 180px;">
                                <a class="dropdown-item py-2 px-3 text-sm {{ !request('project_id') || request('project_id') == 'all' ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.report.task-report', array_merge(request()->except(['project_id', 'page']), ['project_id' => 'all'])) }}">
                                    All Projects
                                </a>
                                @if(isset($projects))
                                @foreach($projects as $project)
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('project_id') == $project->id ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.report.task-report', array_merge(request()->except(['project_id', 'page']), ['project_id' => $project->id])) }}">
                                    {{ $project->title }}
                                </a>
                                @endforeach
                                @endif
                            </div>
                        </div>

                        <!-- 3. Assigned Employee Filter Dropdown -->
                        <div class="dropdown flex-fill flex-md-grow-0">
                            <button
                                class="btn btn-light btn-sm dropdown-toggle text-dark shadow-none px-3 py-2 font-weight-bold rounded-pill border w-100 text-left text-md-center d-flex justify-content-between align-items-center"
                                type="button" id="dropdownAssignee" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false"
                                style="font-size: 13px; background-color: #f8f9fa; border-color: #e3e6f0 !important;">
                                @php
                                $selectedAssignee = isset($employees) ?$employees->firstWhere('id', request('user_id'))
                                : null;
                                @endphp
                                <span class="text-truncate">
                                    {{ $selectedAssignee ? $selectedAssignee->name : 'Assigned To' }}
                                </span>
                            </button>
                            <div class="dropdown-menu shadow-lg border-0 py-2" aria-labelledby="dropdownAssignee"
                                style="border-radius: 12px; min-width: 180px;">
                                <a class="dropdown-item py-2 px-3 text-sm {{ !request('user_id') || request('user_id') == 'all' ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.report.task-report', array_merge(request()->except(['user_id', 'page']), ['user_id' => 'all'])) }}">
                                    All Employees
                                </a>
                                @if(isset($employees))
                                @foreach($employees as $employee)
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('user_id') == $employee->id ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.report.task-report', array_merge(request()->except(['user_id', 'page']), ['user_id' => $employee->id])) }}">
                                    {{ $employee->name }}
                                </a>
                                @endforeach
                                @endif
                            </div>
                        </div>

                        <!-- 4. Status Filter Dropdown -->
                        <div class="dropdown flex-fill flex-md-grow-0">
                            <button
                                class="btn btn-light btn-sm dropdown-toggle text-dark shadow-none px-3 py-2 font-weight-bold rounded-pill border w-100 text-left text-md-center d-flex justify-content-between align-items-center"
                                type="button" id="dropdownTaskStatus" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false"
                                style="font-size: 13px; background-color: #f8f9fa; border-color: #e3e6f0 !important;">
                                <span class="text-truncate">
                                    {{ request('status') && request('status') != 'all' ? ucfirst(str_replace('_', ' ',
                                    request('status'))) : 'All Statuses' }}
                                </span>
                            </button>
                            <div class="dropdown-menu shadow-lg border-0 py-2" aria-labelledby="dropdownTaskStatus"
                                style="border-radius: 12px; min-width: 160px;">
                                <a class="dropdown-item py-2 px-3 text-sm {{ !request('status') || request('status') == 'all' ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.report.task-report', array_merge(request()->except(['status', 'page']), ['status' => 'all'])) }}">
                                    All Statuses
                                </a>
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('status') == 'pending' ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.report.task-report', array_merge(request()->except(['status', 'page']), ['status' => 'pending'])) }}">
                                    Pending
                                </a>
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('status') == 'in_progress' ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.report.task-report', array_merge(request()->except(['status', 'page']), ['status' => 'in_progress'])) }}">
                                    In Progress
                                </a>
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('status') == 'completed' ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.report.task-report', array_merge(request()->except(['status', 'page']), ['status' => 'completed'])) }}">
                                    Completed
                                </a>
                            </div>
                        </div>

                    </div>

                    <!-- Reset Filters Button (Appears only when a filter is active) -->
                    @if(request()->anyFilled(['title', 'project_id', 'user_id', 'status']))
                    <div class="w-100 w-md-auto text-md-right">
                        <a href="{{ route('admin.report.task-report') }}"
                            class="btn btn-outline-danger btn-sm rounded-pill px-3 py-2 w-100 w-md-auto"
                            style="font-size: 12px;">
                            <i class="now-ui-icons ui-1_simple-remove mr-1"></i> Reset Filters
                        </a>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MAIN REPORT TABLE CARD SECTION            -->
<!-- ========================================== -->
<div class="row">
    <div class="col-md-12">
        <x-alert-message />
        <div class="card shadow-sm border-0" id="printable-report">
            <div class="card-body px-0 pb-0">
                <div class="table-responsive">
                    <table class="table align-items-center table-flush mb-0" id="tasksTable" style="min-width: 900px;">
                        <thead style="background: linear-gradient(135deg, #f96332 0%, #ff8c42 100%); color: white;">
                            <tr>
                                <th class="py-3 font-weight-bold text-white pl-4">Task Title</th>
                                <th class="py-3 font-weight-bold text-white">Description</th>
                                <th class="py-3 font-weight-bold text-white">Project</th>
                                <th class="py-3 font-weight-bold text-white">Assigned Employee</th>
                                <th class="py-3 font-weight-bold text-white">Last Update</th>
                                <th class="py-3 font-weight-bold text-white text-right pr-4">Status & Priority</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tasks as $task)
                            @php
                            $taskStatus = strtolower($task->status ?? 'pending');
                            $priorityPercent = match(strtolower($task->priority ?? 'medium')) {
                            'urgent', 'high' => 100,
                            'medium' => 50,
                            'low' => 20,
                            default => 50
                            };
                            @endphp
                            <tr class="border-bottom task-row" data-status="{{ $taskStatus }}">
                                <!-- Task Title -->
                                <td class="font-weight-bold text-dark pl-4 align-middle">
                                    {{ $task->title ?? $task->name }}
                                </td>

                                <!-- Task Description -->
                                <td class="align-middle text-muted" style="max-width: 200px;">
                                    @php
                                    $fullDescription = $task->description ?? 'No description';
                                    $isLong = mb_strlen($fullDescription) > 50;
                                    @endphp

                                    <span class="desc-short-text">
                                        {{ Str::limit($fullDescription, 50) }}
                                    </span>

                                    @if($isLong)
                                    <!-- This Button Will Open The Modal To Show The Full Description -->
                                    <button type="button"
                                        class="btn btn-link btn-icon btn-sm text-primary p-0 ml-1 d-print-none font-weight-bold"
                                        data-toggle="modal" data-target="#descModal{{ $task->id }}"
                                        style="font-size: 12px; text-decoration: underline;">
                                        More
                                    </button>

                                    <!-- This Modal Will Show The Full Description -->
                                    <div class="modal fade d-print-none" id="descModal{{ $task->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="descModalLabel{{ $task->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content shadow-lg border-0"
                                                style="border-radius: 12px; overflow: hidden;">
                                                <div class="modal-header text-white"
                                                    style="background: linear-gradient(135deg, #f96332 0%, #ff8c42 100%);">
                                                    <h5 class="modal-title font-weight-bold text-white"
                                                        id="descModalLabel{{ $task->id }}">
                                                        <i class="now-ui-icons text_align-left mr-2"></i> Task
                                                        Description
                                                    </h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal"
                                                        aria-label="Close" style="opacity: 1;">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body p-4 bg-white text-dark">
                                                    <h6 class="font-weight-bold text-primary mb-2">{{ $task->title ??
                                                        $task->name }}</h6>
                                                    <hr class="mt-1 mb-3">
                                                    <p class="text-break"
                                                        style="line-height: 1.6; white-space: pre-line;">{{
                                                        $fullDescription }}</p>
                                                </div>
                                                <div class="modal-footer bg-light px-4 py-3">
                                                    <button type="button" class="btn btn-secondary btn-round btn-sm"
                                                        data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- This text will be visible only on print -->
                                    <div class="d-none d-print-block text-dark" style="white-space: normal;">
                                        {{ $fullDescription }}
                                    </div>
                                </td>

                                <!-- Project Name -->
                                <td class="align-middle">
                                    <span class="text-dark font-weight-normal text-break">
                                        {{ optional($task->project)->title ?? 'No Project' }}
                                    </span>
                                </td>

                                <!-- Assigned Employee Name -->
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <span
                                            class="avatar-sm rounded-circle bg-light text-primary font-weight-bold d-flex align-items-center justify-content-center shadow-sm mr-2 flex-shrink-0"
                                            style="width: 32px; height: 32px; font-size: 12px;">
                                            {{ strtoupper(substr(optional($task->assignedUser ?? $task->employee)->name
                                            ?? 'U', 0, 2)) }}
                                        </span>
                                        <span class="text-dark font-weight-normal text-break">
                                            {{ optional($task->assignedUser ?? $task->employee)->name ?? 'Unassigned' }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Last Update (Timestamp) -->
                                <td class="align-middle">
                                    <div class="text-muted" style="font-size: 12px;">
                                        @if($task->updated_at)
                                        <div class="font-weight-bold text-dark">{{ $task->updated_at->format('Y-m-d') }}
                                        </div>
                                        <div style="font-size: 11px;">{{ $task->updated_at->format('h:i A') }}</div>
                                        <div class="text-info" style="font-size: 10px;">{{
                                            $task->updated_at->diffForHumans() }}</div>
                                        @else
                                        <span class="italic">N/A</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Status Badge & Progress Indicator -->
                                <td class="text-center align-middle" style="min-width: 180px;">
                                    @php
                                    $taskStatus = $task->status ?? 'pending';

                                    $priorityPercent = match($taskStatus) {
                                    'completed' => 100,
                                    'in_progress' => 50,
                                    'pending' => 10,
                                    default => 25,
                                    };
                                    @endphp

                                    <!-- Status Badge -->
                                    <span class="badge badge-pill mb-2 px-3 py-1 text-white shadow-sm
                                        @if($taskStatus == 'completed') badge-success
                                        @elseif($taskStatus == 'in_progress') badge-warning
                                        @elseif($taskStatus == 'pending') badge-info
                                        @else badge-secondary @endif">
                                        {{ ucfirst(str_replace('_', ' ', $taskStatus)) }}
                                    </span>

                                    <!-- Progress Bar -->
                                    <div class="d-flex flex-column align-items-center w-100" style="gap: 2px;">
                                        <div class="progress shadow-inner w-100"
                                            style="height: 8px; border-radius: 4px; background-color: #e9ecef; overflow: hidden; max-width: 130px;">
                                            <div class="progress-bar
                                                @if($priorityPercent == 100) bg-success
                                                @elseif($priorityPercent >= 50) bg-warning
                                                @else bg-info @endif" style="width: {{ $priorityPercent }}%;">
                                            </div>
                                        </div>

                                        <!-- Percentage Text Label -->
                                        <span class="text-muted font-weight-bold" style="font-size: 11px;">
                                            {{ $priorityPercent }}%
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <div class="py-4">
                                        <i class="now-ui-icons design_bullet-list-67 mb-3 text-muted"
                                            style="font-size: 28px;"></i>
                                        <p class="font-weight-bold mb-1">No task data available for report.</p>
                                    </div>
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

<!-- PAGINATION CONTROLS SECTION -->
@if(method_exists($tasks, 'hasPages') && $tasks->hasPages())
<div class="card-footer bg-white py-4 d-flex flex-column flex-md-row justify-content-between align-items-center"
    style="gap: 15px;">
    <div class="text-muted text-sm text-center text-md-left">
        Showing <b>{{ $tasks->firstItem() }}</b> to <b>{{ $tasks->lastItem() }}</b> of <b>{{
            $tasks->total() }}</b> entries
    </div>
    <div class="overflow-auto w-100 w-md-auto d-flex justify-content-center justify-content-md-end">
        {{ $tasks->links('pagination::bootstrap-4') }}
    </div>
</div>
@endif

</div>
</div>
</div>
</div>
@endsection
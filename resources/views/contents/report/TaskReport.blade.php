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

    <!-- ========================================== -->
    <!-- EXPORT & PRINT DROPDOWN ACTIONS SECTION    -->
    <!-- ========================================== -->
    <div class="col-lg-6 col-md-4 col-12 text-lg-right text-md-right text-left">
        <div class="dropdown d-inline-block">
            <!-- Dropdown Toggle Button -->
            <button class="btn btn-primary btn-round dropdown-toggle text-white shadow-sm px-4" type="button"
                id="exportDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="now-ui-icons files_single-copy-04"></i> Export & Print
            </button>

            <!-- Dropdown Menu Options -->
            <div class="dropdown-menu dropdown-menu-right shadow-lg border-0 py-2" aria-labelledby="exportDropdown"
                style="border-radius: 12px;">

                <!-- DOWNLOAD PDF ACTION LINK -->
                <a class="dropdown-item py-2 px-3 text-sm font-weight-bold" id="downloadPdfBtn"
                    href="{{ route('admin.report.task-report.pdf', array_merge(request()->all(), ['search' => request('search')])) }}">
                    <i class="now-ui-icons files_paper text-danger mr-2"></i> Download PDF
                </a>

                <!-- DOWNLOAD EXCEL ACTION LINK -->
                <a class="dropdown-item py-2 px-3 text-sm font-weight-bold"
                    href="{{ route('admin.report.task-report.excel') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}">
                    <i class="now-ui-icons business_chart-pie-36 text-success mr-2"></i>
                    Download Excel
                </a>

                <!-- Divider line separating file exports from browser actions -->
                <div class="dropdown-divider"></div>

                <!-- PRINT REPORT ACTION -->
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
    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4">
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
    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4">
        <div class="card card-stats border-0 shadow-lg position-relative overflow-hidden"
            style="border-radius: 18px; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); transition: transform 0.2s ease;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="card-category text-uppercase text-muted font-weight-bold mb-1"
                            style="font-size: 10px; letter-spacing: 1px;">Completed</p>
                        <h3 class="card-title font-weight-bolder text-dark mb-0">
                            {{ isset($completedTasksCount) ? $completedTasksCount : \App\Models\Task::where('status',
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
    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4">
        <div class="card card-stats border-0 shadow-lg position-relative overflow-hidden"
            style="border-radius: 18px; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); transition: transform 0.2s ease;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="card-category text-uppercase text-muted font-weight-bold mb-1"
                            style="font-size: 10px; letter-spacing: 1px;">In Progress</p>
                        <h3 class="card-title font-weight-bolder text-dark mb-0">
                            {{ \App\Models\Task::where('status', 'in_progress')->where('due_date', '>=',
                            now()->toDateString())->count() }}
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
    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4">
        <div class="card card-stats border-0 shadow-lg position-relative overflow-hidden"
            style="border-radius: 18px; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); transition: transform 0.2s ease;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="card-category text-uppercase text-muted font-weight-bold mb-1"
                            style="font-size: 10px; letter-spacing: 1px;">Pending</p>
                        <h3 class="card-title font-weight-bolder text-dark mb-0">
                            {{ \App\Models\Task::where('status', 'pending')->where('due_date', '>=',
                            now()->toDateString())->count() }}
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

    <!-- Overdue Tasks Card -->
    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4">
        <div class="card card-stats border-0 shadow-lg position-relative overflow-hidden"
            style="border-radius: 18px; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); transition: transform 0.2s ease;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="card-category text-uppercase text-muted font-weight-bold mb-1"
                            style="font-size: 10px; letter-spacing: 1px;">Overdue</p>
                        <h3 class="card-title font-weight-bolder text-dark mb-0">
                            {{ isset($overdueTasksCount) ? $overdueTasksCount : \App\Models\Task::where('status', '!=',
                            'completed')->where('due_date', '<', now()->toDateString())->count() }}
                        </h3>
                    </div>
                    <div class="icon-shape text-white rounded-circle shadow d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width: 48px; height: 48px; background: linear-gradient(135deg, #f5365c 0%, #f56036 100%);">
                        <i class="now-ui-icons ui-1_simple-remove" style="font-size: 20px;"></i>
                    </div>
                </div>
            </div>
            <div class="position-absolute w-100"
                style="height: 4px; bottom: 0; left: 0; background: linear-gradient(135deg, #f5365c 0%, #f56036 100%);">
            </div>
        </div>
    </div>

    <!-- Due Today Tasks Card -->
    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4">
        <div class="card card-stats border-0 shadow-lg position-relative overflow-hidden"
            style="border-radius: 18px; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); transition: transform 0.2s ease;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="card-category text-uppercase text-muted font-weight-bold mb-1"
                            style="font-size: 10px; letter-spacing: 1px;">Due Today</p>
                        <h3 class="card-title font-weight-bolder text-dark mb-0">
                            {{ isset($dueTodayTasksCount) ? $dueTodayTasksCount : \App\Models\Task::where('status',
                            '!=', 'completed')->whereDate('due_date', now()->toDateString())->count() }}
                        </h3>
                    </div>
                    <div class="icon-shape text-white rounded-circle shadow d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width: 48px; height: 48px; background: linear-gradient(135deg, #172b4d 0%, #2d3748 100%);">
                        <i class="now-ui-icons ui-2_time-alarm" style="font-size: 20px;"></i>
                    </div>
                </div>
            </div>
            <div class="position-absolute w-100"
                style="height: 4px; bottom: 0; left: 0; background: linear-gradient(135deg, #23b7e5 0%, #5190ef 100%);">
            </div>
        </div>
    </div>

</div>

<!-- ========================================== -->
<!-- COLUMN FILTERS DROPDOWNS SECTION (SERVER)  -->
<!-- ========================================== -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm" style="border-radius: 16px; background: #ffffff; overflow: visible;">
            <div class="card-body p-3" style="overflow: visible;">
                <div class="d-flex flex-wrap align-items-center justify-content-between" style="gap: 12px;">

                    <!-- Filters Grouping -->
                    <div class="d-flex flex-wrap align-items-center flex-grow-1" style="gap: 10px;">
                        <span class="text-muted font-weight-bold mr-1 d-none d-xl-inline-block"
                            style="font-size: 13px;">
                            <i class="now-ui-icons ui-1_zoom-bold mr-1 text-primary"></i> Filter By:
                        </span>

                        <!-- Title Filter Dropdown -->
                        <div class="dropdown flex-fill">
                            <button
                                class="btn btn-light btn-sm dropdown-toggle text-dark shadow-none px-3 py-2 font-weight-bold rounded-pill border w-100 text-truncate"
                                type="button" id="dropdownTitle" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false"
                                style="font-size: 13px; background-color: #f8f9fa; border-color: #e3e6f0 !important; height: 35px; display: flex; align-items: center; justify-content: space-between;">
                                <span>{{ request('title') ? Str::limit(request('title'), 15) : 'All Titles' }}</span>
                            </button>
                            <div class="dropdown-menu shadow-lg border-0 py-2" aria-labelledby="dropdownTitle"
                                style="border-radius: 12px; min-width: 180px;">
                                <a class="dropdown-item py-2 px-3 text-sm {{ !request('title') ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.report.task-report', array_merge(request()->except(['title', 'page']), [])) }}">
                                    <i class="now-ui-icons ui-1_simple-add mr-2"></i> All Titles
                                </a>
                                @foreach($allTaskTitles as $titleItem)
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('title') == $titleItem ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.report.task-report', array_merge(request()->except(['title', 'page']), ['title' => $titleItem])) }}">
                                    {{ $titleItem }}
                                </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Assigned To Filter Dropdown -->
                        <div class="dropdown flex-fill">
                            <button
                                class="btn btn-light btn-sm dropdown-toggle text-dark shadow-none px-3 py-2 font-weight-bold rounded-pill border w-100 text-truncate"
                                type="button" id="dropdownAssigned" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false"
                                style="font-size: 13px; background-color: #f8f9fa; border-color: #e3e6f0 !important; height: 35px; display: flex; align-items: center; justify-content: space-between;">
                                @php
                                $selectedUser = $employees->firstWhere('id', request('assigned_to'));
                                @endphp
                                <span class="text-truncate">{{ $selectedUser ? $selectedUser->name : 'Assigned To'
                                    }}</span>
                            </button>
                            <div class="dropdown-menu shadow-lg border-0 py-2" aria-labelledby="dropdownAssigned"
                                style="border-radius: 12px; min-width: 180px;">
                                <a class="dropdown-item py-2 px-3 text-sm {{ !request('assigned_to') ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.report.task-report', array_merge(request()->except(['assigned_to', 'page']), [])) }}">
                                    All Assignees
                                </a>
                                @foreach($employees as $uItem)
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('assigned_to') == $uItem->id ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.report.task-report', array_merge(request()->except(['assigned_to', 'page']), ['assigned_to' => $uItem->id])) }}">
                                    {{ $uItem->name }}
                                </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Status Filter Dropdown -->
                        <div class="dropdown flex-fill">
                            <button
                                class="btn btn-light btn-sm dropdown-toggle text-dark shadow-none px-3 py-2 font-weight-bold rounded-pill border w-100 text-truncate"
                                type="button" id="dropdownStatus" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false"
                                style="font-size: 13px; background-color: #f8f9fa; border-color: #e3e6f0 !important; height: 35px; display: flex; align-items: center; justify-content: space-between;">
                                <span>{{ request('status') ? ucfirst(str_replace('_', ' ', request('status'))) : 'All
                                    Statuses' }}</span>
                            </button>
                            <div class="dropdown-menu shadow-lg border-0 py-2" aria-labelledby="dropdownStatus"
                                style="border-radius: 12px; min-width: 160px;">
                                <a class="dropdown-item py-2 px-3 text-sm {{ !request('status') || request('status') == 'all' ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.report.task-report', array_merge(request()->except(['status', 'page']), ['status' => 'all'])) }}">All
                                    Statuses</a>
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('status') == 'pending' ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.report.task-report', array_merge(request()->except(['status', 'page']), ['status' => 'pending'])) }}">Pending</a>
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('status') == 'in_progress' ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.report.task-report', array_merge(request()->except(['status', 'page']), ['status' => 'in_progress'])) }}">In
                                    Progress</a>
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('status') == 'overdue' ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.report.task-report', array_merge(request()->except(['status', 'page']), ['status' => 'overdue'])) }}">Overdue</a>
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('status') == 'due_today' ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.report.task-report', array_merge(request()->except(['status', 'page']), ['status' => 'due_today'])) }}">Due
                                    Today</a>
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('status') == 'completed' ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.report.task-report', array_merge(request()->except(['status', 'page']), ['status' => 'completed'])) }}">Completed</a>
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('status') == 'accepted' ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.report.task-report', array_merge(request()->except(['status', 'page']), ['status' => 'accepted'])) }}">Accepted</a>
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('status') == 'rejected' ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.report.task-report', array_merge(request()->except(['status', 'page']), ['status' => 'rejected'])) }}">Rejected</a>
                            </div>
                        </div>

                        <!-- Date From & To Filters Group -->
                        <form method="GET" action="{{ route('admin.report.task-report') }}"
                            class="d-flex align-items-center flex-fill" style="gap: 8px; min-width: 260px;">
                            @foreach(request()->except(['date_from', 'date_to', 'page']) as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                            <div class="d-flex align-items-center flex-fill"
                                style="background-color: #f8f9fa; border: 1px solid #e3e6f0 !important; border-radius: 50rem; padding: 2px 10px; height: 35px;">
                                <span class="text-muted mr-1" style="font-size: 11px; white-space: nowrap;">From:</span>
                                <input type="date" name="date_from" value="{{ request('date_from') }}"
                                    class="form-control form-control-sm border-0 bg-transparent shadow-none px-0 py-0 w-100"
                                    style="font-size: 11px;" onchange="this.form.submit()">
                            </div>
                            <div class="d-flex align-items-center flex-fill"
                                style="background-color: #f8f9fa; border: 1px solid #e3e6f0 !important; border-radius: 50rem; padding: 2px 10px; height: 35px;">
                                <span class="text-muted mr-1" style="font-size: 11px; white-space: nowrap;">To:</span>
                                <input type="date" name="date_to" value="{{ request('date_to') }}"
                                    class="form-control form-control-sm border-0 bg-transparent shadow-none px-0 py-0 w-100"
                                    style="font-size: 11px;" onchange="this.form.submit()">
                            </div>
                        </form>

                    </div>

                    <!-- Reset Filters Button -->
                    @if(request()->anyFilled(['title', 'assigned_to', 'status', 'date_from', 'date_to', 'search']))
                    <div>
                        <a href="{{ route('admin.report.task-report') }}"
                            class="btn btn-outline-danger btn-sm rounded-pill px-3 py-2"
                            style="font-size: 12px; white-space: nowrap; height: 35px;">
                            <i class="now-ui-icons ui-1_simple-remove mr-1"></i> Reset
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
                                <th class="py-3 font-weight-bold text-white">Start Date</th>
                                <th class="py-3 font-weight-bold text-white">End Date</th>
                                <th class="py-3 font-weight-bold text-white">Last Update</th>
                                <th class="py-3 font-weight-bold text-white text-right pr-4">Status & Priority</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tasks as $task)
                            @php
                            // تحديد الحالة الديناميكية (overdue أو due_today أو الحالة الأصلية)
                            $status = $task->status ?? 'pending';
                            $dueDate = $task->due_date ?? null;
                            $today = \Carbon\Carbon::today();

                            $taskStatus = $status;
                            if ($dueDate && !in_array($status, ['completed', 'accepted'])) {
                            $taskDate = \Carbon\Carbon::parse($dueDate)->startOfDay();
                            if ($taskDate->lt($today)) {
                            $taskStatus = 'overdue';
                            } elseif ($taskDate->eq($today)) {
                            $taskStatus = 'due_today';
                            }
                            }
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

                                <!-- Start Date -->
                                <td class="align-middle">
                                    <div class="text-muted" style="font-size: 12px;">
                                        @if($task->started_at)
                                        <div class="font-weight-bold text-dark">{{ $task->started_at->format('Y-m-d') }}
                                        </div>
                                        <div style="font-size: 11px;">{{ $task->started_at->format('h:i A') }}</div>
                                        @else
                                        <span class="italic">N/A</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- End Date -->
                                <td class="align-middle">
                                    <div class="text-muted" style="font-size: 12px;">
                                        @if($task->due_date)
                                        <div class="font-weight-bold text-dark">{{ $task->due_date->format('Y-m-d') }}
                                        </div>
                                        <div style="font-size: 11px;">{{ $task->due_date->format('h:i A') }}</div>
                                        @else
                                        <span class="italic">N/A</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Last Update (Timestamp) -->
                                <td class="align-middle">
                                    <div class="text-muted" style="font-size: filter; font-size: 12px;">
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

                                <!-- Status Badge Only -->
                                <td class="text-center align-middle" style="min-width: 150px;">
                                    <span class="badge badge-pill mb-2 px-3 py-1 text-white shadow-sm
                                        @if($taskStatus == 'completed') badge-success
                                        @elseif($taskStatus == 'in_progress') badge-warning
                                        @elseif($taskStatus == 'pending') badge-info
                                        @elseif($taskStatus == 'overdue') badge-danger
                                        @elseif($taskStatus == 'due_today') badge-primary
                                        @else badge-secondary @endif">
                                        {{ ucfirst(str_replace('_', ' ', $taskStatus)) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
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

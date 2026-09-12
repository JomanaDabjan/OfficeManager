@extends('layouts.app')

<!-- Set the dynamic title for this specific page -->
@section('title', 'Task Report')

@section('Main_Content')

@can('viewAny', \App\Models\Report::class)

<!-- ========================================== -->
<!-- PAGE HEADER AND EXPORT ACTION BUTTON        -->
<!-- ========================================== -->
<div class="row mt-4 mb-4 align-items-center">

    <div class="col-lg-6 col-md-8 col-12 mb-3 mb-lg-0">

        <h3 class="font-weight-bold text-dark mb-0 text-break">Task Report Management</h3>

        <p class="text-muted text-sm mb-0">Comprehensive overview of all system tasks, assigned employees, and statuses.
        </p>

    </div>

    {{-- ========================================== --}}
    {{-- EXPORT & PRINT DROPDOWN ACTIONS SECTION --}}
    {{-- ========================================== --}}

    <div class="col-lg-6 col-md-4 col-12 text-lg-right text-md-right text-left">

        <div class="dropdown d-inline-block">

            {{-- Dropdown Toggle Button --}}

            <button class="btn btn-primary btn-round dropdown-toggle text-white shadow-sm px-4" type="button"
                id="exportDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                <i class="now-ui-icons files_single-copy-04"></i>

                Export & Print

            </button>

            {{-- Dropdown Menu Options --}}

            <div class="dropdown-menu dropdown-menu-right shadow-lg border-0 py-2" aria-labelledby="exportDropdown"
                style="border-radius: 12px;">

                {{-- DOWNLOAD PDF ACTION --}}

                <a class="dropdown-item py-2 px-3 text-sm font-weight-bold" href="javascript:void(0);"
                    id="downloadPdfBtn">

                    <i class="now-ui-icons files_paper text-danger mr-2"></i>

                    Download PDF

                </a>

                {{-- DOWNLOAD EXCEL ACTION --}}

                <a class="dropdown-item py-2 px-3 text-sm font-weight-bold" href="javascript:void(0);"
                    id="downloadExcelBtn">

                    <i class="now-ui-icons business_chart-pie-36 text-success mr-2"></i>

                    Download Excel

                </a>

                {{-- Divider line separating file exports from browser actions --}}

                <div class="dropdown-divider"></div>

                {{-- PRINT REPORT ACTION --}}

                <a class="dropdown-item py-2 px-3 text-sm font-weight-bold" href="javascript:void(0);"
                    id="printReportBtn">

                    <i class="now-ui-icons media-1_album text-primary mr-2"></i>

                    Print Report

                </a>

            </div>

        </div>

    </div>

</div>

<!-- ========================================== -->
<!-- FOUR KEY METRICS CARDS SECTION             -->
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

                            {{ $pendingTasksCount ?? 0 }}

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

    <!-- Accepted Tasks Card -->

    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4">

        <div class="card card-stats border-0 shadow-lg position-relative overflow-hidden"
            style="border-radius: 18px; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); transition: transform 0.2s ease;">

            <div class="card-body p-4">

                <div class="d-flex align-items-center justify-content-between">

                    <div>

                        <p class="card-category text-uppercase text-muted font-weight-bold mb-1"
                            style="font-size: 10px; letter-spacing: 1px;">Accepted</p>

                        <h3 class="card-title font-weight-bolder text-dark mb-0">

                            {{ isset($acceptedTasksCount) ? $acceptedTasksCount : \App\Models\Task::where('status',
                            'accepted')->count() }}

                        </h3>

                    </div>

                    <div class="icon-shape text-white rounded-circle shadow d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width: 48px; height: 48px; background: linear-gradient(135deg, #20c997 0%, #198754 100%);">

                        <i class="now-ui-icons ui-1_check" style="font-size: 20px;"></i>

                    </div>

                </div>

            </div>

            <div class="position-absolute w-100"
                style="height: 4px; bottom: 0; left: 0; background: linear-gradient(135deg, #20c997 0%, #198754 100%);">

            </div>

        </div>

    </div>

    <!-- Rejected Tasks Card -->

    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4">

        <div class="card card-stats border-0 shadow-lg position-relative overflow-hidden"
            style="border-radius: 18px; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); transition: transform 0.2s ease;">

            <div class="card-body p-4">

                <div class="d-flex align-items-center justify-content-between">

                    <div>

                        <p class="card-category text-uppercase text-muted font-weight-bold mb-1"
                            style="font-size: 10px; letter-spacing: 1px;">Rejected</p>

                        <h3 class="card-title font-weight-bolder text-dark mb-0">

                            {{ isset($rejectedTasksCount) ? $rejectedTasksCount : \App\Models\Task::where('status',
                            'rejected')->count() }}

                        </h3>

                    </div>

                    <div class="icon-shape text-white rounded-circle shadow d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width: 48px; height: 48px; background: linear-gradient(135deg, #dc3545 0%, #a71d2a 100%);">

                        <i class="now-ui-icons ui-1_simple-remove" style="font-size: 20px;"></i>

                    </div>

                </div>

            </div>

            <div class="position-absolute w-100"
                style="height: 4px; bottom: 0; left: 0; background: linear-gradient(135deg, #dc3545 0%, #a71d2a 100%);">

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

                <div class="d-flex flex-column" style="gap: 12px;">

                    <!-- ========================================== -->
                    <!-- FILTER BY SECTION                         -->
                    <!-- ========================================== -->

                    <div class="d-flex flex-wrap align-items-center" style="gap: 10px;">

                        <span class="text-muted font-weight-bold d-flex align-items-center mr-1"
                            style="font-size: 13px; min-width: 75px;">

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

                                <!-- Live Search -->

                                <div class="px-3 pb-2">

                                    <input type="text" id="titleLiveSearch"
                                        class="form-control form-control-sm shadow-none" placeholder="Search titles..."
                                        autocomplete="off" style="border-radius: 8px; font-size: 13px;">

                                </div>

                                <div id="titleList">

                                    <a class="dropdown-item py-2 px-3 text-sm title-option {{ !request('title') ? 'active font-weight-bold text-primary' : '' }}"
                                        href="{{ route('admin.task.index', array_merge(request()->except(['title', 'page']), [])) }}"
                                        data-title="All Titles">

                                        <i class="now-ui-icons ui-1_simple-add mr-2"></i> All Titles

                                    </a>

                                    @foreach($allTaskTitles as $titleItem)

                                    <a class="dropdown-item py-2 px-3 text-sm title-option {{ request('title') == $titleItem ? 'active font-weight-bold text-primary' : '' }}"
                                        href="{{ route('admin.report.task-report', array_merge(request()->except(['title', 'page']), ['title' => $titleItem])) }}"
                                        data-title="{{ $titleItem }}">

                                        {{ $titleItem }}

                                    </a>

                                    @endforeach

                                </div>

                                <!-- No Results -->

                                <div id="noTitleResults" class="text-center text-muted py-2 px-3"
                                    style="display: none; font-size: 12px;">

                                    No titles found

                                </div>

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
                                $selectedUser = $employees
                                ->where('role', 'employee')
                                ->firstWhere('id', request('user_id'));
                                @endphp

                                <span class="text-truncate">

                                    {{ $selectedUser ? $selectedUser->name : 'Assigned To' }}

                                </span>

                            </button>

                            <div class="dropdown-menu shadow-lg border-0 py-2" aria-labelledby="dropdownAssigned"
                                style="border-radius: 12px; min-width: 220px; max-height: 320px; overflow-y: auto;">

                                <!-- Live Search -->

                                <div class="px-3 pb-2">

                                    <input type="text" id="UserLiveSearch"
                                        class="form-control form-control-sm shadow-none"
                                        placeholder="Search employees..." autocomplete="off"
                                        style="border-radius: 8px; font-size: 13px;">

                                </div>

                                <div class="dropdown-divider mt-1 mb-1"></div>

                                <!-- All Assignees -->

                                <a class="dropdown-item py-2 px-3 text-sm {{ !request('user_id') ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.report.task-report', array_merge(request()->except(['user_id', 'page']), [])) }}">

                                    All Assignees

                                </a>

                                <!-- Employees -->

                                <div id="assignedUsersList">

                                    @foreach($employees->where('role', 'employee') as $uItem)

                                    <a class="dropdown-item py-2 px-3 text-sm assigned-user-item {{ request('user_id') == $uItem->id ? 'active font-weight-bold text-primary' : '' }}"
                                        href="{{ route('admin.report.task-report', array_merge(request()->except(['user_id', 'page']), ['user_id' => $uItem->id])) }}"
                                        data-user-name="{{ strtolower($uItem->name) }}">

                                        {{ $uItem->name }}

                                    </a>

                                    @endforeach

                                </div>

                                <!-- No Results -->

                                <div id="assignedNoResults" class="text-center text-muted py-2 px-3"
                                    style="display: none; font-size: 12px;">

                                    No employees found

                                </div>

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

                    </div>

                    <!-- ========================================== -->
                    <!-- CREATION DATE SECTION                      -->
                    <!-- ========================================== -->

                    <div class="d-flex align-items-center" style="gap: 12px;">

                        <span class="text-muted font-weight-bold d-flex align-items-center"
                            style="font-size: 13px; min-width: 110px;">

                            <i class="now-ui-icons ui-1_calendar-60 mr-1 text-primary" style="font-size: 14px;"></i>

                            Creation Date:

                        </span>

                        <form method="GET" action="{{ route('admin.report.task-report') }}"
                            class="d-flex align-items-center flex-grow-1" style="gap: 8px;">

                            @foreach(request()->except(['date_from', 'date_to', 'page']) as $key => $value)

                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">

                            @endforeach

                            <div class="d-flex align-items-center flex-fill"
                                style="background-color: #f8f9fa; border: 1px solid #e3e6f0 !important; border-radius: 50rem; padding: 2px 10px; height: 35px;">

                                <span class="text-muted font-weight-bold mr-1"
                                    style="font-size: 11px; white-space: nowrap;">

                                    From:

                                </span>

                                <input type="date" name="date_from" value="{{ request('date_from') }}"
                                    class="form-control form-control-sm border-0 bg-transparent shadow-none px-0 py-0 w-100"
                                    style="font-size: 11px;" onchange="this.form.submit()">

                            </div>

                            <div class="d-flex align-items-center flex-fill"
                                style="background-color: #f8f9fa; border: 1px solid #e3e6f0 !important; border-radius: 50rem; padding: 2px 10px; height: 35px;">

                                <span class="text-muted font-weight-bold mr-1"
                                    style="font-size: 11px; white-space: nowrap;">

                                    To:

                                </span>

                                <input type="date" name="date_to" value="{{ request('date_to') }}"
                                    class="form-control form-control-sm border-0 bg-transparent shadow-none px-0 py-0 w-100"
                                    style="font-size: 11px;" onchange="this.form.submit()">

                            </div>

                        </form>

                    </div>

                    <!-- Reset Filters Button -->

                    @if(request()->anyFilled(['title', 'user_id', 'status', 'date_from', 'date_to', 'search']))

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
<!-- MAIN REPORT TABLE CARD SECTION             -->
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

                            $status = strtolower(trim($task->status ?? 'pending'));

                            $dueDate = $task->due_date ?? null;

                            $today = \Carbon\Carbon::today();

                            $taskStatus = $status;

                            if ($dueDate && !in_array($status, ['completed', 'accepted', 'rejected'])) {

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

                                        @else

                                        <span class="italic">N/A</span>

                                        @endif

                                    </div>

                                </td>

                                <!-- Last Update (Date Only) -->

                                <td class="align-middle">

                                    <div class="text-muted" style="font-size: 12px;">

                                        @if($task->updated_at)

                                        <div class="font-weight-bold text-dark">
                                            {{ $task->updated_at->format('Y-m-d') }}
                                        </div>

                                        @else

                                        <span class="italic">N/A</span>

                                        @endif

                                    </div>

                                </td>

                                <!-- Status Badge Only -->

                                <td class="text-center align-middle" style="min-width: 150px;">

                                    @if($taskStatus == 'accepted')

                                    <span class="badge badge-pill mb-2 px-3 py-1 shadow-sm"
                                        style="background-color: rgba(40, 167, 69, 0.12) !important; color: #28a745 !important; border: 1px solid rgba(40, 167, 69, 0.25) !important;">

                                        Accepted

                                    </span>

                                    @elseif($taskStatus == 'rejected')

                                    <span class="badge badge-pill mb-2 px-3 py-1 shadow-sm"
                                        style="background-color: rgba(220, 53, 69, 0.12) !important; color: #dc3545 !important; border: 1px solid rgba(220, 53, 69, 0.25) !important;">

                                        Rejected

                                    </span>

                                    @elseif($taskStatus == 'completed')

                                    <span class="badge badge-pill mb-2 px-3 py-1 text-white shadow-sm badge-success">

                                        Completed

                                    </span>

                                    @elseif($taskStatus == 'in_progress')

                                    <span class="badge badge-pill mb-2 px-3 py-1 text-white shadow-sm badge-warning">

                                        In Progress

                                    </span>

                                    @elseif($taskStatus == 'pending')

                                    <span class="badge badge-pill mb-2 px-3 py-1 text-white shadow-sm badge-info">

                                        Pending

                                    </span>

                                    @elseif($taskStatus == 'overdue')

                                    <span class="badge badge-pill mb-2 px-3 py-1 text-white shadow-sm badge-danger">

                                        Overdue

                                    </span>

                                    @elseif($taskStatus == 'due_today')

                                    <span class="badge badge-pill mb-2 px-3 py-1 text-white shadow-sm"
                                        style="background-color: #8965e0 !important;">

                                        Due Today

                                    </span>

                                    @else

                                    <span class="badge badge-pill mb-2 px-3 py-1 text-white shadow-sm badge-secondary">

                                        {{ ucfirst(str_replace('_', ' ', $taskStatus)) }}

                                    </span>

                                    @endif

                                </td>

                            </tr>

                            @empty

                            <tr>
                                <td colspan="8" class="p-0">
                                    <div class="datatable-empty-state"
                                        style="padding: 45px 20px; text-align: center; width: 100%;">

                                        <div
                                            style="width: 64px; height: 64px; margin: 0 auto 16px auto; border-radius: 50%; background: linear-gradient(135deg, #fff1eb 0%, #ffe4d8 100%); display: flex; align-items: center; justify-content: center; box-shadow: 0 6px 18px rgba(249, 99, 50, 0.12);">

                                            <i class="now-ui-icons design_bullet-list-67"
                                                style="font-size: 28px; color: #f96332;"></i>

                                        </div>

                                        <div
                                            style="font-size: 16px; font-weight: 700; color: #32325d; margin-bottom: 6px;">

                                            No tasks available

                                        </div>

                                        <div
                                            style="font-size: 13px; color: #8898aa; max-width: 420px; margin: 0 auto; line-height: 1.6;">

                                            There is no task data to display at the moment.

                                        </div>

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

@endcan

@endsection
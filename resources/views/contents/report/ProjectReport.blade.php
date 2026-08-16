@extends('layouts.app')

<!-- Set the dynamic title for this specific page -->
@section('title', 'Project Report')

@section('Main_Content')

<!-- ========================================== -->
<!-- PAGE HEADER AND EXPORT ACTION BUTTON        -->
<!-- ========================================== -->
<div class="row mt-4 mb-4 align-items-center">
    <div class="col-lg-6 col-md-8 col-12 mb-3 mb-lg-0">
        <h3 class="font-weight-bold text-dark mb-0 text-break">Project Report Management</h3>
        <p class="text-muted text-sm mb-0">Comprehensive overview of all system projects, assigned managers, and task
            counts.</p>
    </div>
    <div class="col-lg-6 col-md-4 col-12 text-lg-right text-md-right text-left">
        <!-- ========================================================================== -->
        <!-- EXPORT & PRINT DROPDOWN ACTIONS SECTION                                    -->
        <!-- This dropdown menu provides options for the user to export the report      -->
        <!-- data into different formats (PDF, Excel) or trigger a direct print.        -->
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
                <!-- Updated to link directly to the named route for PDF generation     -->
                <!-- ================================================================== -->
                <a class="dropdown-item py-2 px-3 text-sm font-weight-bold" id="downloadPdfBtn"
                    href="{{ route('admin.report.project-report.pdf', array_merge(request()->all(), ['search' => request('search')])) }}">
                    <i class="now-ui-icons files_paper text-danger mr-2"></i> Download PDF
                </a>

                <a class="dropdown-item py-2 px-3 text-sm font-weight-bold"
                    href="{{ route('admin.report.project-report.excel') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}">
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
<!-- FIVE KEY METRICS CARDS SECTION            -->
<!-- ========================================== -->
<div class="row">

    <!-- Total Projects Card -->
    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-4">
        <div class="card card-stats border-0 shadow-lg position-relative overflow-hidden"
            style="border-radius: 18px; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); transition: transform 0.2s ease;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="card-category text-uppercase text-muted font-weight-bold mb-1"
                            style="font-size: 10px; letter-spacing: 1px;">Total Projects</p>
                        <h3 class="card-title font-weight-bolder text-dark mb-0">{{ isset($totalProjects) ?
                            $totalProjects : (method_exists($projects, 'total') ? $projects->total() :
                            $projects->count()) }}</h3>
                    </div>
                    <div class="icon-shape text-white rounded-circle shadow d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width: 48px; height: 48px; background: linear-gradient(135deg, #f96332 0%, #ff8c42 100%);">
                        <i class="now-ui-icons business_briefcase-24"
                            style="font-size: 20px; color: #1a1a1a; text-shadow: 0px 1px 2px rgba(255, 255, 255, 0.4);"></i>
                    </div>
                </div>
            </div>
            <div class="position-absolute w-100"
                style="height: 4px; bottom: 0; left: 0; background: linear-gradient(135deg, #f96332 0%, #ff8c42 100%);">
            </div>
        </div>
    </div>

    <!-- Completed Projects Card -->
    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-4">
        <div class="card card-stats border-0 shadow-lg position-relative overflow-hidden"
            style="border-radius: 18px; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); transition: transform 0.2s ease;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="card-category text-uppercase text-muted font-weight-bold mb-1"
                            style="font-size: 10px; letter-spacing: 1px;">Completed</p>
                        <h3 class="card-title font-weight-bolder text-dark mb-0">
                            {{ isset($completedProjectsCount) ? $completedProjectsCount : $projects->where('status',
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

    <!-- In Progress Projects Card -->
    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-4">
        <div class="card card-stats border-0 shadow-lg position-relative overflow-hidden"
            style="border-radius: 18px; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); transition: transform 0.2s ease;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="card-category text-uppercase text-muted font-weight-bold mb-1"
                            style="font-size: 10px; letter-spacing: 1px;">In Progress</p>
                        <h3 class="card-title font-weight-bolder text-dark mb-0">
                            {{ isset($inProgressProjectsCount) ? $inProgressProjectsCount : $projects->where('status',
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

    <!-- Pending Projects Card -->
    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-4">
        <div class="card card-stats border-0 shadow-lg position-relative overflow-hidden"
            style="border-radius: 18px; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); transition: transform 0.2s ease;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="card-category text-uppercase text-muted font-weight-bold mb-1"
                            style="font-size: 10px; letter-spacing: 1px;">Pending</p>
                        <h3 class="card-title font-weight-bolder text-dark mb-0">
                            {{ isset($pendingProjectsCount) ? $pendingProjectsCount : $projects->where('status',
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

    <!-- Total Tasks Card -->
    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-4">
        <div class="card card-stats border-0 shadow-lg position-relative overflow-hidden"
            style="border-radius: 18px; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); transition: transform 0.2s ease;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="card-category text-uppercase text-muted font-weight-bold mb-1"
                            style="font-size: 10px; letter-spacing: 1px;">Total Tasks</p>
                        <h3 class="card-title font-weight-bolder text-dark mb-0">
                            {{ isset($totalTasksCount) ? $totalTasksCount : $projects->sum(function($proj) { return
                            $proj->tasks_count ?? ($proj->tasks ? $proj->tasks->count() : 0); }) }}
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

</div>

<!-- ========================================== -->
<!-- COLUMN FILTERS DROPDOWNS SECTION (SERVER)  -->
<!-- ========================================== -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm" style="border-radius: 16px; background: #ffffff; overflow: visible;">
            <div class="card-body p-3 p-md-4" style="overflow: visible;">
                <div class="d-flex flex-wrap align-items-center justify-content-between" style="gap: 12px;">

                    <!-- Filters Grouping -->
                    <div class="d-flex flex-wrap align-items-center flex-grow-1" style="gap: 10px;">

                        <!-- Title Filter Dropdown -->
                        <div class="dropdown flex-fill">
                            <button
                                class="btn btn-light btn-sm dropdown-toggle text-dark shadow-none px-3 py-2 font-weight-bold rounded-pill border w-100 text-truncate"
                                type="button" id="dropdownTitle" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false"
                                style="font-size: 13px; background-color: #f8f9fa; border-color: #e3e6f0 !important; height: 35px; display: flex; align-items: center; justify-content: space-between;">
                                <span>{{ request('title') && request('title') != 'all' ? Str::limit(request('title'),
                                    15) : 'All Titles' }}</span>
                            </button>
                            <div class="dropdown-menu shadow-lg border-0 py-2" aria-labelledby="dropdownTitle"
                                style="border-radius: 12px; min-width: 180px;">
                                <a class="dropdown-item py-2 px-3 text-sm {{ !request('title') || request('title') == 'all' ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.report.project-report', array_merge(request()->except(['title', 'page']), [])) }}">
                                    <i class="now-ui-icons ui-1_simple-add mr-2"></i> All Titles
                                </a>
                                @foreach($allTitles as $projTitle)
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('title') == $projTitle ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.report.project-report', array_merge(request()->except(['title', 'page']), ['title' => $projTitle])) }}">
                                    {{ $projTitle }}
                                </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Manager Filter Dropdown -->
                        <div class="dropdown flex-fill">
                            <button
                                class="btn btn-light btn-sm dropdown-toggle text-dark shadow-none px-3 py-2 font-weight-bold rounded-pill border w-100 text-truncate"
                                type="button" id="dropdownManager" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false"
                                style="font-size: 13px; background-color: #f8f9fa; border-color: #e3e6f0 !important; height: 35px; display: flex; align-items: center; justify-content: space-between;">
                                @php
                                $selectedManager = $managers->firstWhere('id', request('manager_id'));
                                @endphp
                                <span class="text-truncate">{{ $selectedManager ? $selectedManager->name : 'Assigned
                                    Manager' }}</span>
                            </button>
                            <div class="dropdown-menu shadow-lg border-0 py-2" aria-labelledby="dropdownManager"
                                style="border-radius: 12px; min-width: 180px;">
                                <a class="dropdown-item py-2 px-3 text-sm {{ !request('manager_id') || request('manager_id') == 'all' ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.report.project-report', array_merge(request()->except(['manager_id', 'page']), [])) }}">
                                    All Managers
                                </a>
                                @foreach($managers as $manager)
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('manager_id') == $manager->id ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.report.project-report', array_merge(request()->except(['manager_id', 'page']), ['manager_id' => $manager->id])) }}">
                                    {{ $manager->name }}
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
                                <span>{{ request('status') && request('status') != 'all' ? ucfirst(str_replace('_', ' ',
                                    request('status'))) : 'All Statuses' }}</span>
                            </button>
                            <div class="dropdown-menu shadow-lg border-0 py-2" aria-labelledby="dropdownStatus"
                                style="border-radius: 12px; min-width: 160px;">
                                <a class="dropdown-item py-2 px-3 text-sm {{ !request('status') || request('status') == 'all' ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.report.project-report', array_merge(request()->except(['status', 'page']), ['status' => 'all'])) }}">All
                                    Statuses</a>
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('status') == 'pending' ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.report.project-report', array_merge(request()->except(['status', 'page']), ['status' => 'pending'])) }}">Pending</a>
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('status') == 'in_progress' ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.report.project-report', array_merge(request()->except(['status', 'page']), ['status' => 'in_progress'])) }}">In
                                    Progress</a>
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('status') == 'completed' ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.report.project-report', array_merge(request()->except(['status', 'page']), ['status' => 'completed'])) }}">Completed</a>
                            </div>
                        </div>

                        <!-- Price Filter Dropdown -->
                        <div class="dropdown flex-fill">
                            <button
                                class="btn btn-light btn-sm dropdown-toggle text-dark shadow-none px-3 py-2 font-weight-bold rounded-pill border w-100 text-truncate"
                                type="button" id="dropdownPrice" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false"
                                style="font-size: 13px; background-color: #f8f9fa; border-color: #e3e6f0 !important; height: 35px; display: flex; align-items: center; justify-content: space-between;">
                                <span>{{ request('price') && request('price') != 'all' ? 'Price: ' . request('price') :
                                    'All Prices' }}</span>
                            </button>
                            <div class="dropdown-menu shadow-lg border-0 py-2" aria-labelledby="dropdownPrice"
                                style="border-radius: 12px; min-width: 160px;">
                                <a class="dropdown-item py-2 px-3 text-sm {{ !request('price') || request('price') == 'all' ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.report.project-report', array_merge(request()->except(['price', 'page']), ['price' => 'all'])) }}">All
                                    Prices</a>
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('price') == 'low' ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.report.project-report', array_merge(request()->except(['price', 'page']), ['price' => 'low'])) }}">Low
                                    Price</a>
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('price') == 'medium' ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.report.project-report', array_merge(request()->except(['price', 'page']), ['price' => 'medium'])) }}">Medium
                                    Price</a>
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('price') == 'high' ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.report.project-report', array_merge(request()->except(['price', 'page']), ['price' => 'high'])) }}">High
                                    Price</a>
                            </div>
                        </div>

                        <!-- Date From & To Filters Group -->
                        <form method="GET" action="{{ route('admin.report.project-report') }}"
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
                    @if(request()->anyFilled(['title', 'manager_id', 'status', 'price', 'date_from', 'date_to',
                    'search']))
                    <div>
                        <a href="{{ route('admin.report.project-report') }}"
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
                    <table class="table align-items-center table-flush mb-0" id="projectsTable"
                        style="min-width: 1000px;">
                        <thead style="background: linear-gradient(135deg, #f96332 0%, #ff8c42 100%); color: white;">
                            <tr>
                                <th class="py-3 font-weight-bold text-white pl-4">Project Title</th>
                                <th class="py-3 font-weight-bold text-white">Description</th>
                                <th class="py-3 font-weight-bold text-white">Project Manager</th>
                                <th class="py-3 font-weight-bold text-white">Start Date</th>
                                <th class="py-3 font-weight-bold text-white">End Date</th>
                                <th class="py-3 font-weight-bold text-white">Budget</th>
                                <th class="py-3 font-weight-bold text-white text-center">Total Tasks</th>
                                <th class="py-3 font-weight-bold text-white">Project Tasks & Assigned Employees</th>
                                <th class="py-3 font-weight-bold text-white text-right pr-4">Status & Progress</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($projects as $project)
                            @php
                            $projectStatus = strtolower($project->status ?? 'in_progress');

                            $progressPercent = $project->progress ?? match($projectStatus) {
                            'completed' => 100,
                            'in_progress' => 50,
                            'pending' => 10,
                            default => 0
                            };
                            @endphp
                            <tr class="border-bottom project-row" data-status="{{ $projectStatus }}">
                                <!-- Project Title -->
                                <td class="font-weight-bold text-dark pl-4 align-middle">
                                    {{ $project->title }}
                                </td>

                                <!-- Project Description -->
                                <td class="align-middle text-muted" style="max-width: 200px;">
                                    @php
                                    $fullDescription = $project->description ?? 'No description';
                                    $isLong = mb_strlen($fullDescription) > 50;
                                    @endphp

                                    <span class="desc-short-text">
                                        {{ Str::limit($fullDescription, 50) }}
                                    </span>

                                    @if($isLong)
                                    <!-- This Button Will Open The Modal To Show The Full Description -->
                                    <button type="button"
                                        class="btn btn-link btn-icon btn-sm text-primary p-0 ml-1 d-print-none font-weight-bold"
                                        data-toggle="modal" data-target="#descModal{{ $project->id }}"
                                        style="font-size: 12px; text-decoration: underline;">
                                        More
                                    </button>

                                    <!-- This Modal Will Show The Full Description -->
                                    <div class="modal fade d-print-none" id="descModal{{ $project->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="descModalLabel{{ $project->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content shadow-lg border-0"
                                                style="border-radius: 12px; overflow: hidden;">
                                                <div class="modal-header text-white"
                                                    style="background: linear-gradient(135deg, #f96332 0%, #ff8c42 100%);">
                                                    <h5 class="modal-title font-weight-bold text-white"
                                                        id="descModalLabel{{ $project->id }}">
                                                        <i class="now-ui-icons text_align-left mr-2"></i> Project
                                                        Description
                                                    </h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal"
                                                        aria-label="Close" style="opacity: 1;">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body p-4 bg-white text-dark">
                                                    <h6 class="font-weight-bold text-primary mb-2">{{ $project->title }}
                                                    </h6>
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

                                <!-- Manager Name -->
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <span
                                            class="avatar-sm rounded-circle bg-light text-primary font-weight-bold d-flex align-items-center justify-content-center shadow-sm mr-2 flex-shrink-0"
                                            style="width: 32px; height: 32px; font-size: 12px;">
                                            {{ strtoupper(substr(optional($project->manager)->name ?? 'U', 0, 2)) }}
                                        </span>
                                        <span class="text-dark font-weight-normal text-break">
                                            {{ optional($project->manager)->name ?? 'No Manager' }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Start Date -->
                                <td class="align-middle text-muted">
                                    {{ $project->start_date ?
                                    \Carbon\Carbon::parse($project->start_date)->format('Y-m-d') : 'N/A' }}
                                </td>

                                <!-- End Date -->
                                <td class="align-middle text-muted">
                                    {{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('Y-m-d') :
                                    'N/A' }}
                                </td>

                                <!-- Budget -->
                                <td class="align-middle font-weight-bold text-success">
                                    {{ $project->budget ? '$' . number_format($project->budget, 2) : 'N/A' }}
                                </td>

                                <!-- Total Tasks Count -->
                                <td class="align-middle text-center">
                                    <span
                                        class="badge badge-pill badge-neutral font-weight-bold px-3 py-2 border shadow-sm text-dark">
                                        <i class="now-ui-icons design_bullet-list-67 mr-1 text-primary"></i>
                                        {{ $project->tasks_count ?? ($project->tasks ? $project->tasks->count() : 0) }}
                                        Tasks
                                    </span>
                                </td>

                                <!-- Project Tasks & Assigned Employees Column -->
                                <td class="align-middle" style="max-width: 220px;">
                                    @if(isset($project->tasks) && $project->tasks->count() > 0)
                                    @php
                                    $hasManyTasks = $project->tasks->count() > 2;
                                    @endphp

                                    <div class="{{ $hasManyTasks ? '' : '' }}">
                                        <ul class="list-unstyled mb-1" style="font-size: 12px;">
                                            @foreach($project->tasks->take(2) as $task)
                                            <li class="mb-1 pb-1 border-bottom border-light">
                                                <span class="font-weight-bold text-dark">{{ $task->title ?? $task->name
                                                    }}</span>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="now-ui-icons users_single-02 mr-1"></i>
                                                    {{ optional($task->assignedUser ?? $task->employee)->name ??
                                                    'Unassigned' }}
                                                </small>
                                            </li>
                                            @endforeach
                                        </ul>

                                        @if($hasManyTasks)
                                        <!-- Button to open Tasks Modal -->
                                        <button type="button"
                                            class="btn btn-link btn-icon btn-sm text-primary p-0 d-print-none font-weight-bold"
                                            data-toggle="modal" data-target="#tasksModal{{ $project->id }}"
                                            style="font-size: 12px; text-decoration: underline;">
                                            More ({{ $project->tasks->count() }} Tasks)
                                        </button>

                                        <!-- Modal for All Tasks -->
                                        <div class="modal fade d-print-none" id="tasksModal{{ $project->id }}"
                                            tabindex="-1" role="dialog"
                                            aria-labelledby="tasksModalLabel{{ $project->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
                                                role="document">
                                                <div class="modal-content shadow-lg border-0"
                                                    style="border-radius: 12px; overflow: hidden;">
                                                    <div class="modal-header text-white"
                                                        style="background: linear-gradient(135deg, #f96332 0%, #ff8c42 100%);">
                                                        <h5 class="modal-title font-weight-bold text-white"
                                                            id="tasksModalLabel{{ $project->id }}">
                                                            <i class="now-ui-icons design_bullet-list-67 mr-2"></i>
                                                            Project Tasks & Employees
                                                        </h5>
                                                        <button type="button" class="close text-white"
                                                            data-dismiss="modal" aria-label="Close" style="opacity: 1;">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body p-4 bg-white text-dark">
                                                        <h6 class="font-weight-bold text-primary mb-2">{{
                                                            $project->title }}</h6>
                                                        <hr class="mt-1 mb-3">
                                                        <ul class="list-unstyled mb-0">
                                                            @foreach($project->tasks as $task)
                                                            <li class="mb-3 pb-2 border-bottom">
                                                                <span class="font-weight-bold text-dark"
                                                                    style="font-size: 14px;">{{ $task->title ??
                                                                    $task->name }}</span>
                                                                <br>
                                                                <small class="text-muted" style="font-size: 12px;">
                                                                    <i class="now-ui-icons users_single-02 mr-1"></i>
                                                                    Assigned to: <span
                                                                        class="text-primary font-weight-bold">{{
                                                                        optional($task->assignedUser ??
                                                                        $task->employee)->name ?? 'Unassigned' }}</span>
                                                                </small>
                                                            </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                    <div class="modal-footer bg-light px-4 py-3">
                                                        <button type="button" class="btn btn-secondary btn-round btn-sm"
                                                            data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    @else
                                    <span class="text-muted font-italic" style="font-size: 12px;">No tasks
                                        assigned</span>
                                    @endif
                                </td>

                                <!-- Project Status Badge & Battery Style Progress Bar -->
                                <td class="text-right pr-4 align-middle" style="min-width: 180px;">
                                    <!-- Project Status Badge Display -->
                                    <span class="badge badge-pill mb-2
                                        @if($projectStatus == 'completed') badge-success
                                        @elseif($projectStatus == 'in_progress') badge-warning
                                        @elseif($projectStatus == 'pending') badge-info
                                        @else badge-secondary @endif px-3 py-1 text-white shadow-sm">
                                        {{ ucfirst(str_replace('_', ' ', $projectStatus)) }}
                                    </span>

                                    <!-- Battery Style Progress Bar with Percentage -->
                                    <div class="d-flex flex-column align-items-end" style="gap: 4px;">

                                        <!-- Percentage Text Label -->
                                        <span class="text-muted font-weight-bold" style="font-size: 11px;">
                                            {{ $progressPercent }}%
                                        </span>

                                        <div class="d-flex align-items-center justify-content-end w-100">
                                            <!-- Battery Body Container -->
                                            <div class="progress shadow-inner"
                                                style="height: 14px; border-radius: 6px; width: 125px; background-color: #f1f3f5; border: 2px solid #dcdcdc; overflow: hidden; padding: 2px;">

                                                <!-- Dynamic Progress Fill Bar with Gradients -->
                                                <div class="progress-bar" role="progressbar"
                                                    style="width: {{ $progressPercent }}%; border-radius: 3px; background: @if($projectStatus == 'completed') linear-gradient(135deg, #2dce89 0%, #2ddfc4 100%) @elseif($projectStatus == 'in_progress') linear-gradient(135deg, #fbb140 0%, #f39c12 100%) @else linear-gradient(135deg, #11cdef 0%, #1171ef 100%) @endif;"
                                                    aria-valuenow="{{ $progressPercent }}" aria-valuemin="0"
                                                    aria-valuemax="100">
                                                </div>
                                            </div>

                                            <!-- Battery Tip Element -->
                                            <div
                                                style="width: 4px; height: 7px; background-color: #dcdcdc; border-top-right-radius: 2px; border-bottom-right-radius: 2px; margin-left: 1px;">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-5">
                                    <div class="py-4">
                                        <i class="now-ui-icons business_briefcase-24 mb-3 text-muted"
                                            style="font-size: 28px;"></i>
                                        <p class="font-weight-bold mb-1">No project data available for report.</p>
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
@if(method_exists($projects, 'hasPages') && $projects->hasPages())
<div class="card-footer bg-white py-4 d-flex flex-column flex-md-row justify-content-between align-items-center"
    style="gap: 15px;">
    <div class="text-muted text-sm text-center text-md-left">
        Showing <b>{{ $projects->firstItem() }}</b> to <b>{{ $projects->lastItem() }}</b> of <b>{{
            $projects->total() }}</b> entries
    </div>
    <div class="overflow-auto w-100 w-md-auto d-flex justify-content-center justify-content-md-end">
        {{ $projects->links('pagination::bootstrap-4') }}
    </div>
</div>
@endif

</div>
</div>
</div>
</div>

@endsection

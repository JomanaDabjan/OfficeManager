@extends('layouts.app')

<!-- Set the dynamic title for this specific page -->
@section('title', 'Project Table View')

@section('Main_Content')

<!-- ========================================== -->
<!-- PAGE HEADER AND CREATION ACTION BUTTON     -->
<!-- ========================================== -->
<div class="row mt-4 mb-4 align-items-center">
    <div class="col-md-6">
        <h3 class="font-weight-bold text-dark mb-0">Projects Management</h3>
        <p class="text-muted text-sm mb-0">
            Manage all system projects, assign managers, and track statuses. (Drag table
            headers to reorder columns)
        </p>
    </div>

    <div class="col-md-6 text-right">
        <!-- Render the 'Add New Project' button for administrators -->
        @can('create', \App\Models\Project::class)
        <a href="{{ route('admin.project.create') }}" class="btn btn-primary btn-round text-white shadow-sm px-4">
            <i class="now-ui-icons ui-1_simple-add"></i> Add New Project
        </a>
        @endcan
    </div>
</div>


<!-- ========================================== -->
<!-- COLUMN FILTERS DROPDOWNS SECTION (SERVER)  -->
<!-- ========================================== -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm" style="border-radius: 16px; background: #ffffff; overflow: visible;">

            <div class="card-body p-3 p-md-4" style="overflow: visible;">

                <div class="d-flex flex-column" style="gap: 16px;">

                    <div class="d-flex flex-wrap align-items-center justify-content-between" style="gap: 12px;">

                        <!-- Filters Grouping -->
                        <div class="d-flex flex-wrap align-items-center flex-grow-1" style="gap: 10px;">

                            <!-- Filter By Label with Icon -->
                            <span class="text-muted font-weight-bold d-flex align-items-center mr-1"
                                style="font-size: 13px; min-width: 80px;">

                                <i class="now-ui-icons ui-1_zoom-bold mr-1 text-primary" style="font-size: 14px;"></i>

                                Filter By:
                            </span>

                            <!-- Title Filter Dropdown -->
                            <div class="dropdown flex-fill">
                                <button
                                    class="btn btn-light btn-sm dropdown-toggle text-dark shadow-none px-3 py-2 font-weight-bold rounded-pill border w-100 text-truncate"
                                    type="button" id="dropdownTitle" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false"
                                    style="font-size: 13px; background-color: #f8f9fa; border-color: #e3e6f0 !important; height: 35px; display: flex; align-items: center; justify-content: space-between;">
                                    <span>{{ request('title') ? Str::limit(request('title'), 15) : 'All Titles'
                                        }}</span>
                                </button>

                                <div class="dropdown-menu shadow-lg border-0 py-2" aria-labelledby="dropdownTitle"
                                    style="border-radius: 12px; min-width: 180px;">

                                    <!-- Live Search -->
                                    <div class="px-3 pb-2">
                                        <input type="text" id="titleLiveSearch"
                                            class="form-control form-control-sm shadow-none"
                                            placeholder="Search titles..." autocomplete="off"
                                            style="border-radius: 8px; font-size: 13px;">
                                    </div>

                                    <div id="titleList">

                                        <a class="dropdown-item py-2 px-3 text-sm title-option {{ !request('name') ? 'active font-weight-bold text-primary' : '' }}"
                                            href="{{ route('admin.user.index', array_merge(request()->except(['name', 'page']), [])) }}"
                                            data-title="All Names">
                                            <i class="now-ui-icons ui-1_simple-add mr-2"></i> All Names
                                        </a>

                                        @foreach($allTitles as $nameItem)
                                        <a class="dropdown-item py-2 px-3 text-sm title-option {{ request('name') == $nameItem ? 'active font-weight-bold text-primary' : '' }}"
                                            href="{{ route('admin.task.index', array_merge(request()->except(['name', 'page']), ['name' => $nameItem])) }}"
                                            data-title="{{ $nameItem }}">
                                            {{ $nameItem }}
                                        </a>
                                        @endforeach

                                    </div>

                                    <!-- No Results -->
                                    <div id="noTitleResults" class="text-center text-muted py-2 px-3"
                                        style="display: none; font-size: 12px;">
                                        No names found
                                    </div>

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

                                    <span class="text-truncate">
                                        {{ $selectedManager ? $selectedManager->name : 'Assigned Manager' }}
                                    </span>

                                </button>

                                <div class="dropdown-menu shadow-lg border-0 py-2" aria-labelledby="dropdownManager"
                                    style="border-radius: 12px; min-width: 180px;">

                                    <a class="dropdown-item py-2 px-3 text-sm {{ !request('manager_id') || request('manager_id') == 'all' ? 'active font-weight-bold text-primary' : '' }}"
                                        href="{{ route('admin.project.index', array_merge(request()->except(['manager_id', 'page']), [])) }}">

                                        All Managers

                                    </a>

                                    @foreach($managers as $manager)

                                    <a class="dropdown-item py-2 px-3 text-sm {{ request('manager_id') == $manager->id ? 'active font-weight-bold text-primary' : '' }}"
                                        href="{{ route('admin.project.index', array_merge(request()->except(['manager_id', 'page']), ['manager_id' => $manager->id])) }}">

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

                                    <span>
                                        {{ request('status') && request('status') != 'all'
                                        ? ucfirst(str_replace('_', ' ', request('status')))
                                        : 'All Statuses' }}
                                    </span>

                                </button>

                                <div class="dropdown-menu shadow-lg border-0 py-2" aria-labelledby="dropdownStatus"
                                    style="border-radius: 12px; min-width: 160px;">

                                    <a class="dropdown-item py-2 px-3 text-sm {{ !request('status') || request('status') == 'all' ? 'active font-weight-bold text-primary' : '' }}"
                                        href="{{ route('admin.project.index', array_merge(request()->except(['status', 'page']), ['status' => 'all'])) }}">
                                        All Statuses
                                    </a>

                                    <a class="dropdown-item py-2 px-3 text-sm {{ request('status') == 'pending' ? 'active font-weight-bold text-primary' : '' }}"
                                        href="{{ route('admin.project.index', array_merge(request()->except(['status', 'page']), ['status' => 'pending'])) }}">
                                        Pending
                                    </a>

                                    <a class="dropdown-item py-2 px-3 text-sm {{ request('status') == 'in_progress' ? 'active font-weight-bold text-primary' : '' }}"
                                        href="{{ route('admin.project.index', array_merge(request()->except(['status', 'page']), ['status' => 'in_progress'])) }}">
                                        In Progress
                                    </a>

                                    <a class="dropdown-item py-2 px-3 text-sm {{ request('status') == 'completed' ? 'active font-weight-bold text-primary' : '' }}"
                                        href="{{ route('admin.project.index', array_merge(request()->except(['status', 'page']), ['status' => 'completed'])) }}">
                                        Completed
                                    </a>

                                    <!-- خيار الفلترة حسب المتأخرة -->
                                    <a class="dropdown-item py-2 px-3 text-sm {{ request('status') == 'overdue' ? 'active font-weight-bold text-primary' : '' }}"
                                        href="{{ route('admin.project.index', array_merge(request()->except(['status', 'page']), ['status' => 'overdue'])) }}">
                                        Overdue
                                    </a>

                                    <!-- خيار الفلترة حسب المستحقة اليوم -->
                                    <a class="dropdown-item py-2 px-3 text-sm {{ request('status') == 'due_today' ? 'active font-weight-bold text-primary' : '' }}"
                                        href="{{ route('admin.project.index', array_merge(request()->except(['status', 'page']), ['status' => 'due_today'])) }}">
                                        Due Today
                                    </a>

                                </div>
                            </div>


                            <!-- Price Filter Dropdown -->
                            <div class="dropdown flex-fill">

                                <button
                                    class="btn btn-light btn-sm dropdown-toggle text-dark shadow-none px-3 py-2 font-weight-bold rounded-pill border w-100 text-truncate"
                                    type="button" id="dropdownPrice" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false"
                                    style="font-size: 13px; background-color: #f8f9fa; border-color: #e3e6f0 !important; height: 35px; display: flex; align-items: center; justify-content: space-between;">

                                    <span>
                                        {{ request('price') && request('price') != 'all'
                                        ? 'Price: ' . request('price')
                                        : 'All Prices' }}
                                    </span>

                                </button>

                                <div class="dropdown-menu shadow-lg border-0 py-2" aria-labelledby="dropdownPrice"
                                    style="border-radius: 12px; min-width: 160px;">

                                    <a class="dropdown-item py-2 px-3 text-sm {{ !request('price') || request('price') == 'all' ? 'active font-weight-bold text-primary' : '' }}"
                                        href="{{ route('admin.project.index', array_merge(request()->except(['price', 'page']), ['price' => 'all'])) }}">
                                        All Prices
                                    </a>

                                    <a class="dropdown-item py-2 px-3 text-sm {{ request('price') == 'low' ? 'active font-weight-bold text-primary' : '' }}"
                                        href="{{ route('admin.project.index', array_merge(request()->except(['price', 'page']), ['price' => 'low'])) }}">
                                        Low Price
                                    </a>

                                    <a class="dropdown-item py-2 px-3 text-sm {{ request('price') == 'medium' ? 'active font-weight-bold text-primary' : '' }}"
                                        href="{{ route('admin.project.index', array_merge(request()->except(['price', 'page']), ['price' => 'medium'])) }}">
                                        Medium Price
                                    </a>

                                    <a class="dropdown-item py-2 px-3 text-sm {{ request('price') == 'high' ? 'active font-weight-bold text-primary' : '' }}"
                                        href="{{ route('admin.project.index', array_merge(request()->except(['price', 'page']), ['price' => 'high'])) }}">
                                        High Price
                                    </a>

                                </div>
                            </div>

                        </div>


                        <!-- Reset Filters Button -->
                        @if(request()->anyFilled([
                        'title',
                        'manager_id',
                        'status',
                        'price',
                        'date_from',
                        'date_to',
                        'search'
                        ]))

                        <div>

                            <a href="{{ route('admin.project.index') }}"
                                class="btn btn-outline-danger btn-sm rounded-pill px-3 py-2"
                                style="font-size: 12px; white-space: nowrap; height: 35px;">

                                <i class="now-ui-icons ui-1_simple-remove mr-1"></i>
                                Reset

                            </a>

                        </div>

                        @endif

                    </div>


                    <!-- Date From & To Filters Group -->
                    <div class="d-flex align-items-center" style="gap: 12px;">

                        <span class="text-muted font-weight-bold d-flex align-items-center"
                            style="font-size: 13px; min-width: 110px;">

                            <i class="now-ui-icons ui-1_calendar-60 mr-1 text-primary" style="font-size: 14px;"></i>

                            Creation Date:

                        </span>


                        <form method="GET" action="{{ route('admin.project.index') }}"
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

                </div>
            </div>
        </div>
    </div>
</div>


<!-- ========================================== -->
<!-- LIVE SEARCH SECTION                        -->
<!-- ========================================== -->
<div class="row mb-3 align-items-center">

    <div class="col-md-5 mb-2 mb-md-0">

        <div class="search-container">

            <i class="now-ui-icons ui-1_zoom-bold search-icon"></i>

            <input type="text" id="taskSearchInput" class="form-control border rounded-pill shadow-sm"
                placeholder="Search projects..." value="{{ request('search') }}" style="background-color: #f9fbfd;">

        </div>

    </div>

</div>


<!-- ========================================== -->
<!-- MAIN PROJECTS TABLE CARD SECTION          -->
<!-- ========================================== -->
<div class="row mx-0">

    <div class="col-md-12 px-4">

        <x-alert-message />

        <div class="card shadow-sm border" style="border: 1px solid #dee2e6 !important;">

            <div class="card-body px-0 pb-0">

                <div class="table-responsive" style="overflow-x: auto; width: 100%;">

                    @can('viewAny', \App\Models\Project::class)
                    <table class="table align-items-center table-flush mb-0 border" id="projectsTable"
                        style="border: 1px solid #dee2e6; table-layout: auto;">

                        <!-- ========================================== -->
                        <!-- TABLE HEADER                                -->
                        <!-- SAME DESIGN AS TEAM TABLE                  -->
                        <!-- ========================================== -->
                        <thead style="background: linear-gradient(135deg, #f96332 0%, #ff8c42 100%); color: white;">

                            <tr id="tableHeaders">

                                <!-- ========================================== -->
                                <!-- TITLE                                     -->
                                <!-- ========================================== -->
                                <th class="py-3 font-weight-bold text-white pl-4 draggable-header draggable-th text-center align-middle"
                                    draggable="true" data-column="0"
                                    style="cursor: grab; font-size: 13px; font-weight: 700 !important; color: #ffffff !important; border: 1px solid rgba(255,255,255,0.2) !important; white-space: nowrap;">

                                    Title

                                    <i class="now-ui-icons arrows-1_move-horizontal ml-1"
                                        style="font-size: 10px; opacity: 0.7;"></i>

                                </th>


                                <!-- ========================================== -->
                                <!-- DESCRIPTION                                -->
                                <!-- ========================================== -->
                                <th class="py-3 font-weight-bold text-white pl-4 draggable-header draggable-th text-center align-middle"
                                    draggable="true" data-column="1"
                                    style="cursor: grab; font-size: 13px; font-weight: 700 !important; color: #ffffff !important; border: 1px solid rgba(255,255,255,0.2) !important; white-space: nowrap;">

                                    Description

                                    <i class="now-ui-icons arrows-1_move-horizontal ml-1"
                                        style="font-size: 10px; opacity: 0.7;"></i>

                                </th>


                                <!-- ========================================== -->
                                <!-- MANAGER                                    -->
                                <!-- ========================================== -->
                                <th class="py-3 font-weight-bold text-white pl-4 draggable-header draggable-th text-center align-middle"
                                    draggable="true" data-column="2"
                                    style="cursor: grab; font-size: 13px; font-weight: 700 !important; color: #ffffff !important; border: 1px solid rgba(255,255,255,0.2) !important; white-space: nowrap;">

                                    Manager

                                    <i class="now-ui-icons arrows-1_move-horizontal ml-1"
                                        style="font-size: 10px; opacity: 0.7;"></i>

                                </th>


                                <!-- ========================================== -->
                                <!-- STATUS                                     -->
                                <!-- ========================================== -->
                                <th class="py-3 font-weight-bold text-white pl-4 draggable-header draggable-th text-center align-middle"
                                    draggable="true" data-column="3"
                                    style="cursor: grab; font-size: 13px; font-weight: 700 !important; color: #ffffff !important; border: 1px solid rgba(255,255,255,0.2) !important; white-space: nowrap;">

                                    Status

                                    <i class="now-ui-icons arrows-1_move-horizontal ml-1"
                                        style="font-size: 10px; opacity: 0.7;"></i>

                                </th>


                                <!-- ========================================== -->
                                <!-- ACTIONS                                    -->
                                <!-- ========================================== -->
                                <th class="py-3 font-weight-bold text-white pl-4 draggable-header draggable-th text-center align-middle"
                                    draggable="true" data-column="4"
                                    style="cursor: grab; font-size: 13px; font-weight: 700 !important; color: #ffffff !important; border: 1px solid rgba(255,255,255,0.2) !important; white-space: nowrap;">

                                    Actions

                                    <i class="now-ui-icons arrows-1_move-horizontal ml-1"
                                        style="font-size: 10px; opacity: 0.7;"></i>

                                </th>

                            </tr>

                        </thead>


                        <!-- ========================================== -->
                        <!-- TABLE BODY                                  -->
                        <!-- ========================================== -->
                        <tbody id="tableBody">

                            <!-- Loop through each project record -->
                            @forelse($projects as $project)

                            @php

                            $today = \Carbon\Carbon::today();

                            $endDate = $project->end_date
                            ? \Carbon\Carbon::parse($project->end_date)
                            : null;

                            $startDate = $project->start_date
                            ? \Carbon\Carbon::parse($project->start_date)
                            : null;


                            $tasks = $project->tasks;

                            $hasTasks = $tasks->count() > 0;


                            $allTasksCompleted = $hasTasks
                            ? $tasks->every(function($task) {
                            return strtolower(trim($task->status)) === 'complete'
                            || strtolower(trim($task->status)) === 'completed';
                            })
                            : false;


                            $rawStatus = strtolower(trim($project->status));

                            $currentStatus = $rawStatus;


                            if ($rawStatus !== 'completed' && $rawStatus !== 'complete') {

                            if ($endDate) {

                            if ($today->greaterThan($endDate)
                            && (!$hasTasks || !$allTasksCompleted)) {

                            $currentStatus = 'overdue';

                            } elseif ($today->isSameDay($endDate)
                            && (!$hasTasks || !$allTasksCompleted)) {

                            $currentStatus = 'due_today';

                            } else {

                            $currentStatus = $rawStatus;

                            }

                            } else {

                            $currentStatus = $rawStatus;

                            }

                            } else {

                            $currentStatus = 'completed';

                            }


                            $statusClass = match($currentStatus) {

                            'completed', 'complete' => 'badge-success',

                            'in_progress' => 'badge-warning',

                            'pending' => 'badge-info',

                            'overdue', 'rejected' => 'badge-danger',

                            'due_today' => 'badge-purple',

                            default => 'badge-secondary',

                            };

                            @endphp


                            <!-- ========================================== -->
                            <!-- PROJECT ROW                                 -->
                            <!-- ========================================== -->
                            <tr class="border-bottom project-row" data-status="{{ $currentStatus }}">


                                <!-- ========================================== -->
                                <!-- PROJECT TITLE                              -->
                                <!-- ========================================== -->
                                <td class="font-weight-bold text-dark pl-4 align-middle project-title border-right text-center"
                                    data-column="0" data-col-index="0"
                                    style="border: 1px solid #dee2e6 !important; word-break: break-word; white-space: normal; max-width: 180px; font-size: 13px;">

                                    {{ $project->title }}

                                </td>


                                <!-- ========================================== -->
                                <!-- PROJECT DESCRIPTION                        -->
                                <!-- ========================================== -->
                                <td class="text-muted align-middle project-desc border-right text-center"
                                    data-column="1" data-col-index="1"
                                    style="border: 1px solid #dee2e6 !important; max-width: 200px; font-size: 13px;">

                                    <span
                                        style="display: inline-block; max-width: 130px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; vertical-align: middle;">

                                        {{ Str::limit($project->description, 40) }}

                                    </span>

                                    @if(strlen($project->description) > 40)

                                    <button type="button"
                                        class="btn btn-link btn-sm p-0 ml-1 text-primary font-weight-bold"
                                        data-toggle="modal" data-target="#descModal-{{ $project->id }}"
                                        style="font-size: 12px; text-decoration: underline; vertical-align: baseline;">

                                        More

                                    </button>

                                    @endif

                                </td>


                                <!-- ========================================== -->
                                <!-- ASSIGNED MANAGER                           -->
                                <!-- ========================================== -->
                                <td class="align-middle project-manager border-right text-center" data-column="2"
                                    data-col-index="2"
                                    style="border: 1px solid #dee2e6 !important; word-break: break-word; white-space: normal; max-width: 180px; font-size: 13px;">

                                    <div class="d-flex flex-column align-items-center justify-content-center">

                                        <span
                                            class="avatar-sm rounded-circle bg-light text-primary font-weight-bold d-flex align-items-center justify-content-center shadow-sm mb-1"
                                            style="width: 32px; height: 32px; font-size: 12px; flex-shrink: 0;">

                                            {{ strtoupper(substr(optional($project->manager)->name ?? 'U', 0, 2)) }}

                                        </span>

                                        <span class="text-dark font-weight-normal text-center">

                                            {{ optional($project->manager)->name ?? 'No Manager' }}

                                        </span>

                                    </div>

                                </td>


                                <!-- ========================================== -->
                                <!-- PROJECT STATUS                             -->
                                <!-- ========================================== -->
                                <td class="align-middle project-status border-right text-center" data-column="3"
                                    data-col-index="3"
                                    style="border: 1px solid #dee2e6 !important; white-space: nowrap; font-size: 13px;">

                                    <span class="badge badge-pill {{ $statusClass }} px-3 py-2 text-white shadow-sm"
                                        @if($currentStatus==='due_today' )
                                        style="background-color: #6f42c1; font-size: 12px;" @else
                                        style="font-size: 12px;" @endif>

                                        {{ ucfirst(str_replace('_', ' ', $currentStatus)) }}

                                    </span>

                                </td>


                                <!-- ========================================== -->
                                <!-- ACTIONS                                     -->
                                <!-- ========================================== -->
                                <td class="text-center align-middle" data-column="4" data-col-index="4"
                                    style="border: 1px solid #dee2e6 !important; white-space: nowrap;">

                                    <div class="d-flex justify-content-center align-items-center" role="group"
                                        aria-label="Project Actions">


                                        <!-- ========================================== -->
                                        <!-- VIEW PROJECT                              -->
                                        <!-- ========================================== -->
                                        @can('view', $project)
                                        <a href="{{ route('admin.project.show', $project->id) }}"
                                            class="btn btn-info btn-sm btn-icon shadow-sm mx-1 rounded"
                                            title="View Project Details"
                                            style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">

                                            <i class="now-ui-icons business_bulb-63" style="font-size: 13px;"></i>

                                        </a>
                                        @endcan


                                        <!-- ========================================== -->
                                        <!-- EDIT PROJECT                              -->
                                        <!-- ========================================== -->
                                        @can('update', $project)
                                        <a href="{{ route('admin.project.edit', $project->id) }}"
                                            class="btn btn-warning btn-sm btn-icon shadow-sm mx-1 rounded"
                                            title="Edit Project"
                                            style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">

                                            <i class="now-ui-icons ui-2_settings-90" style="font-size: 13px;"></i>

                                        </a>
                                        @endcan


                                        <!-- ========================================== -->
                                        <!-- DELETE PROJECT                            -->
                                        <!-- ========================================== -->
                                        @can('delete', $project)
                                        <form action="{{ route('admin.project.destroy', $project->id) }}" method="POST"
                                            style="display: inline-block;" id="delete-form-project-{{ $project->id }}">

                                            @csrf

                                            @method('DELETE')


                                            <button type="button"
                                                class="btn btn-danger btn-sm btn-icon shadow-sm mx-1 rounded"
                                                title="Delete Project"
                                                style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;"
                                                onclick="confirmDelete('project', {{ $project->id }})">

                                                <i class="now-ui-icons ui-1_simple-remove" style="font-size: 13px;"></i>

                                            </button>

                                        </form>
                                        @endcan

                                    </div>

                                </td>

                            </tr>


                            @empty

                            @endforelse

                        </tbody>

                    </table>
                    @endcan

                </div>


                <!-- ========================================== -->
                <!-- PAGINATION CONTROLS SECTION                -->
                <!-- ========================================== -->
                @if($projects->hasPages())

                <div class="card-footer bg-white py-4 d-flex justify-content-between align-items-center">

                    <div class="text-muted text-sm">

                        Showing

                        <b>{{ $projects->firstItem() }}</b>

                        to

                        <b>{{ $projects->lastItem() }}</b>

                        of

                        <b>{{ $projects->total() }}</b>

                        entries

                    </div>


                    <div>

                        {{ $projects->links('pagination::bootstrap-4') }}

                    </div>

                </div>

                @endif

            </div>

        </div>

    </div>

</div>


<!-- ========================================== -->
<!-- MODALS FOR PROJECT DESCRIPTIONS            -->
<!-- ========================================== -->
@foreach($projects as $project)

@if(strlen($project->description) > 40)

<div class="modal fade" id="descModal-{{ $project->id }}" tabindex="-1" role="dialog"
    aria-labelledby="descModalLabel-{{ $project->id }}" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">

        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;">


            <!-- ========================================== -->
            <!-- MODAL HEADER                               -->
            <!-- ========================================== -->
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #f96332 0%, #ff8c42 100%);">

                <h5 class="modal-title font-weight-bold" id="descModalLabel-{{ $project->id }}">

                    <i class="now-ui-icons business_briefcase-24 mr-2"></i>

                    Description

                </h5>


                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"
                    style="opacity: 0.9;">

                    <span aria-hidden="true">

                        &times;

                    </span>

                </button>

            </div>


            <!-- ========================================== -->
            <!-- MODAL BODY                                 -->
            <!-- ========================================== -->
            <div class="modal-body p-4 text-dark" style="background-color: #f9fbfd; line-height: 1.6;">

                <p class="mb-0" style="white-space: pre-line;">

                    {{ $project->description }}

                </p>

            </div>


            <!-- ========================================== -->
            <!-- MODAL FOOTER                               -->
            <!-- ========================================== -->
            <div class="modal-footer bg-white border-0 py-3">

                <button type="button" class="btn btn-secondary btn-round px-4 shadow-sm" data-dismiss="modal">

                    Close

                </button>

            </div>

        </div>

    </div>

</div>

@endif

@endforeach

@endsection
@extends('layouts.app')

<!-- Set the dynamic title for this specific page -->
@section('title', 'Task Table View')

@section('Main_Content')

<!-- ========================================== -->
<!-- PAGE HEADER AND CREATION ACTION BUTTON     -->
<!-- ========================================== -->
<div class="row mt-4 mb-4 align-items-center">
    <div class="col-md-6">
        <h3 class="font-weight-bold text-dark mb-0">Tasks Management</h3>
        <p class="text-muted text-sm mb-0">Manage all project tasks, assign employees, and track statuses.</p>
    </div>
    <div class="col-md-6 text-right">
        <!-- Render the 'Add New Task' button according to TaskPolicy -->
        @can('create', \App\Models\Task::class)
        <a href="{{ route('admin.task.create') }}" class="btn btn-primary btn-round text-white shadow-sm px-4">
            <i class="now-ui-icons ui-1_simple-add"></i> Add New Task
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
            <div class="card-body p-3" style="overflow: visible;">
                <div class="d-flex flex-column" style="gap: 12px;">

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

                                        <a class="dropdown-item py-2 px-3 text-sm title-option {{ !request('title') ? 'active font-weight-bold text-primary' : '' }}"
                                            href="{{ route('admin.task.index', array_merge(request()->except(['title', 'page']), [])) }}"
                                            data-title="All Titles">
                                            <i class="now-ui-icons ui-1_simple-add mr-2"></i> All Titles
                                        </a>

                                        @foreach($allTitles as $titleItem)
                                        <a class="dropdown-item py-2 px-3 text-sm title-option {{ request('title') == $titleItem ? 'active font-weight-bold text-primary' : '' }}"
                                            href="{{ route('admin.task.index', array_merge(request()->except(['title', 'page']), ['title' => $titleItem])) }}"
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
                                    $selectedUser = $allUsers
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
                                        href="{{ route('admin.task.index', array_merge(request()->except(['user_id', 'page']), [])) }}">
                                        All Assignees
                                    </a>

                                    <!-- Employees -->
                                    <div id="assignedUsersList">

                                        @foreach($allUsers->where('role', 'employee') as $uItem)

                                        <a class="dropdown-item py-2 px-3 text-sm assigned-user-item {{ request('user_id') == $uItem->id ? 'active font-weight-bold text-primary' : '' }}"
                                            href="{{ route('admin.task.index', array_merge(request()->except(['user_id', 'page']), ['user_id' => $uItem->id])) }}"
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

                            <!-- Attachment Filter Dropdown -->
                            <div class="dropdown flex-fill">
                                <button
                                    class="btn btn-light btn-sm dropdown-toggle text-dark shadow-none px-3 py-2 font-weight-bold rounded-pill border w-100 text-truncate"
                                    type="button" id="dropdownAttachment" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false"
                                    style="font-size: 13px; background-color: #f8f9fa; border-color: #e3e6f0 !important; height: 35px; display: flex; align-items: center; justify-content: space-between;">
                                    <span>
                                        @if(request('has_attachment') == 'yes') With Attachment
                                        @elseif(request('has_attachment') == 'no') No File
                                        @else Attachment @endif
                                    </span>
                                </button>
                                <div class="dropdown-menu shadow-lg border-0 py-2" aria-labelledby="dropdownAttachment"
                                    style="border-radius: 12px; min-width: 160px;">
                                    <a class="dropdown-item py-2 px-3 text-sm {{ !request('has_attachment') ? 'active font-weight-bold text-primary' : '' }}"
                                        href="{{ route('admin.task.index', array_merge(request()->except(['has_attachment', 'page']), [])) }}">All
                                        Files</a>
                                    <a class="dropdown-item py-2 px-3 text-sm {{ request('has_attachment') == 'yes' ? 'active font-weight-bold text-primary' : '' }}"
                                        href="{{ route('admin.task.index', array_merge(request()->except(['has_attachment', 'page']), ['has_attachment' => 'yes'])) }}">With
                                        Attachment</a>
                                    <a class="dropdown-item py-2 px-3 text-sm {{ request('has_attachment') == 'no' ? 'active font-weight-bold text-primary' : '' }}"
                                        href="{{ route('admin.task.index', array_merge(request()->except(['has_attachment', 'page']), ['has_attachment' => 'no'])) }}">No
                                        File</a>
                                </div>
                            </div>

                            <!-- Status Filter Dropdown -->
                            <div class="dropdown flex-fill">
                                <button
                                    class="btn btn-light btn-sm dropdown-toggle text-dark shadow-none px-3 py-2 font-weight-bold rounded-pill border w-100 text-truncate"
                                    type="button" id="dropdownStatus" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false"
                                    style="font-size: 13px; background-color: #f8f9fa; border-color: #e3e6f0 !important; height: 35px; display: flex; align-items: center; justify-content: space-between;">
                                    <span>{{ request('filter') ? ucfirst(str_replace('_', ' ', request('filter'))) :
                                        'All Statuses' }}</span>
                                </button>
                                <div class="dropdown-menu shadow-lg border-0 py-2" aria-labelledby="dropdownStatus"
                                    style="border-radius: 12px; min-width: 160px;">
                                    <a class="dropdown-item py-2 px-3 text-sm {{ !request('filter') || request('filter') == 'all' ? 'active font-weight-bold text-primary' : '' }}"
                                        href="{{ route('admin.task.index', array_merge(request()->except(['filter', 'page']), ['filter' => 'all'])) }}">All
                                        Statuses</a>
                                    <a class="dropdown-item py-2 px-3 text-sm {{ request('filter') == 'pending' ? 'active font-weight-bold text-primary' : '' }}"
                                        href="{{ route('admin.task.index', array_merge(request()->except(['filter', 'page']), ['filter' => 'pending'])) }}">Pending</a>
                                    <a class="dropdown-item py-2 px-3 text-sm {{ request('filter') == 'in_progress' ? 'active font-weight-bold text-primary' : '' }}"
                                        href="{{ route('admin.task.index', array_merge(request()->except(['filter', 'page']), ['filter' => 'in_progress'])) }}">In
                                        Progress</a>
                                    <a class="dropdown-item py-2 px-3 text-sm {{ request('filter') == 'completed' ? 'active font-weight-bold text-primary' : '' }}"
                                        href="{{ route('admin.task.index', array_merge(request()->except(['filter', 'page']), ['filter' => 'completed'])) }}">Completed</a>
                                    <a class="dropdown-item py-2 px-3 text-sm {{ request('filter') == 'accepted' ? 'active font-weight-bold text-primary' : '' }}"
                                        href="{{ route('admin.task.index', array_merge(request()->except(['filter', 'page']), ['filter' => 'accepted'])) }}">Accepted</a>
                                    <a class="dropdown-item py-2 px-3 text-sm {{ request('filter') == 'rejected' ? 'active font-weight-bold text-primary' : '' }}"
                                        href="{{ route('admin.task.index', array_merge(request()->except(['filter', 'page']), ['filter' => 'rejected'])) }}">Rejected</a>
                                    <a class="dropdown-item py-2 px-3 text-sm {{ request('filter') == 'overdue' ? 'active font-weight-bold text-primary' : '' }}"
                                        href="{{ route('admin.task.index', array_merge(request()->except(['filter', 'page']), ['filter' => 'overdue'])) }}">Overdue</a>
                                    <a class="dropdown-item py-2 px-3 text-sm {{ request('filter') == 'due_today' ? 'status-filter-link active font-weight-bold text-primary' : '' }}"
                                        href="{{ route('admin.task.index', array_merge(request()->except(['filter', 'page']), ['filter' => 'due_today'])) }}">Due
                                        Today</a>
                                </div>
                            </div>

                            <!-- Reset Filters Button (Appears only if a filter is active) -->
                            @if(request()->anyFilled(['title', 'user_id', 'has_attachment', 'filter', 'date_from',
                            'date_to', 'search']))
                            <div>
                                <a href="{{ route('admin.task.index') }}"
                                    class="btn btn-outline-danger btn-sm rounded-pill px-3 py-2"
                                    style="font-size: 12px; white-space: nowrap; height: 35px;">
                                    <i class="now-ui-icons ui-1_simple-remove mr-1"></i> Reset
                                </a>
                            </div>
                            @endif

                        </div>

                    </div>

                    <!-- Date From & To Filters Group (Moved to a new line) -->
                    <div class="d-flex align-items-center" style="gap: 12px;">
                        <span class="text-muted font-weight-bold d-flex align-items-center"
                            style="font-size: 13px; min-width: 110px;">
                            <i class="now-ui-icons ui-1_calendar-60 mr-1 text-primary" style="font-size: 14px;"></i>
                            Creation Date:
                        </span>
                        <form method="GET" action="{{ route('admin.task.index') }}"
                            class="d-flex align-items-center flex-grow-1" style="gap: 8px;">
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
        <div class="search-container" style="position: relative;">
            <i class="now-ui-icons ui-1_zoom-bold search-icon"
                style="position: absolute; top: 50%; transform: translateY(-50%); left: 15px; color: #888;"></i>
            <input type="text" id="taskSearchInput" name="search" class="form-control border rounded-pill shadow-sm"
                placeholder="Search projects..." value="{{ request('search') }}"
                style="background-color: #f9fbfd; padding-left: 40px; height: 40px;">
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MAIN TASKS TABLE CARD SECTION            -->
<!-- ========================================== -->
<div class="row">
    <div class="col-md-12">
        <x-alert-message />
        <div class="card shadow-sm border-0">
            <div class="card-body px-0 pb-0">

                @can('viewAny', \App\Models\Task::class)

                <div class="table-responsive" style="overflow-x: auto; width: 100%;">
                    <table class="table align-items-center table-flush mb-0" id="tasksTable">
                        <!-- Table Headings with Gradient Style Matching Projects -->
                        <thead style="background: linear-gradient(135deg, #f96332 0%, #ff8c42 100%); color: white;">
                            <tr id="tableHeaders">
                                <th class="py-3 font-weight-bold text-white pl-4 draggable-header" draggable="true"
                                    data-column="title" style="cursor: grab;">Title</th>
                                <th class="py-3 font-weight-bold text-white draggable-header" draggable="true"
                                    data-column="description" style="cursor: grab;">Description</th>
                                <th class="py-3 font-weight-bold text-white draggable-header" draggable="true"
                                    data-column="user_id" style="cursor: grab;">Assigned To</th>
                                <th class="py-3 font-weight-bold text-white draggable-header" draggable="true"
                                    data-column="attachment" style="cursor: grab;">Attachment</th>
                                <th class="py-3 font-weight-bold text-white draggable-header" draggable="true"
                                    data-column="status" style="cursor: grab;">Status</th>
                                <th class="py-3 font-weight-bold text-white draggable-header" draggable="true"
                                    data-column="review_action" style="cursor: grab;">Review Action</th>
                                <th class="py-3 font-weight-bold text-white text-right pr-4 draggable-header"
                                    draggable="true" data-column="actions" style="cursor: grab;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Loop through each task record using Laravel forelse directive -->
                            @forelse($tasks as $task)

                            <tr class="border-bottom task-row" data-status="{{ $task->status_data }}">

                                <!-- Task Title Column -->
                                <td class="font-weight-bold text-dark pl-4 align-middle task-title" data-column="title">
                                    @if(!empty($task->title))
                                    <span
                                        style="display: inline-block; max-width: 130px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; vertical-align: middle;">
                                        {{ Str::limit($task->title, 40) }}
                                    </span>
                                    @if(strlen($task->title) > 15)
                                    <button type="button"
                                        class="btn btn-link btn-sm p-0 ml-1 text-primary font-weight-bold"
                                        data-toggle="modal" data-target="#taskTitleModal-{{ $task->id }}"
                                        style="font-size: 12px; text-decoration: underline; vertical-align: baseline;">
                                        More
                                    </button>
                                    @endif
                                    @else
                                    <span class="text-muted" style="font-style: italic;">
                                        No Title
                                    </span>
                                    @endif
                                </td>

                                <!-- Task Description Column (Clickable to open Modal) -->
                                <td class="text-muted align-middle task-desc" data-column="description">
                                    @if(!empty($task->description))
                                    <span
                                        style="display: inline-block; max-width: 130px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; vertical-align: middle;">
                                        {{ Str::limit($task->description, 40) }}
                                    </span>
                                    @if(strlen($task->description) > 15)
                                    <button type="button"
                                        class="btn btn-link btn-sm p-0 ml-1 text-primary font-weight-bold"
                                        data-toggle="modal" data-target="#taskDescModal-{{ $task->id }}"
                                        style="font-size: 12px; text-decoration: underline; vertical-align: baseline;">
                                        More
                                    </button>
                                    @endif
                                    @else
                                    <span class="text-muted" style="font-style: italic;">
                                        No Description
                                    </span>
                                    @endif
                                </td>

                                <!-- Assigned User Column with Avatar Initials Matching Project Manager Style -->
                                <td class="align-middle task-user" data-column="user_id">
                                    <div class="d-flex align-items-center">
                                        @if($task->assignedUser)
                                        <span
                                            class="avatar-sm rounded-circle bg-light text-primary font-weight-bold d-flex align-items-center justify-content-center shadow-sm mr-2"
                                            style="width: 32px; height: 32px; font-size: 12px;">
                                            {{ strtoupper(substr($task->assignedUser->name, 0, 2)) }}
                                        </span>
                                        <span class="text-dark font-weight-normal">
                                            {{ $task->assignedUser->name }}
                                        </span>
                                        @else
                                        <span class="text-muted font-italic" style="font-size: 12px;">No
                                            Assignee</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Attachment Column (Supports Single/Multiple Files via Collection or Array) -->
                                <td class="align-middle task-attachment" data-column="attachment">

                                    @if($task->file_count > 0)
                                    <button type="button"
                                        class="btn btn-sm btn-outline-primary btn-round px-3 py-1 shadow-sm"
                                        data-toggle="modal" data-target="#taskFilesModal-{{ $task->id }}"
                                        style="font-size: 11px;">
                                        <i class="now-ui-icons files_single-copy-04 mr-1"></i> View Files ({{
                                        $task->file_count }})
                                    </button>
                                    @else
                                    <span class="text-muted font-italic" style="font-size: 12px;">No File</span>
                                    @endif

                                </td>

                                <!-- Task Status Column -->
                                <td class="align-middle task-status" data-column="status">

                                    <span class="badge badge-pill {{ $task->status_class }} px-3 py-2 shadow-sm"
                                        style="display: inline-block !important; width: auto !important; min-width: 0 !important; height: auto !important; line-height: 1.2 !important; white-space: nowrap !important; border-radius: 50rem !important; {{ $task->status_style }}">
                                        {{ $task->display_status_label }}
                                    </span>

                                </td>

                                <!-- REVIEW / ACTION COLUMN -->
                                <td class="align-middle" data-column="review_action">
                                    @if($task->status == 'rejected')
                                    <!-- If task is rejected, hide accept/reject buttons and show view reason button -->
                                    @if(!empty($task->rejection_reason))
                                    <button type="button"
                                        class="btn btn-outline-danger btn-sm rounded-pill px-3 py-1 shadow-sm font-weight-bold"
                                        data-toggle="modal" data-target="#rejectionReasonModal-{{ $task->id }}"
                                        style="font-size: 11px;">
                                        <i class="now-ui-icons info_circle mr-1"></i> View Reason
                                    </button>
                                    @else
                                    <span class="text-muted font-italic" style="font-size: 12px;">No Reason
                                        Provided</span>
                                    @endif
                                    @else
                                    <!-- If task is not rejected, show standard accept and reject review actions -->
                                    <div class="btn-group" role="group" aria-label="Review Actions">

                                        @can('modifyStatus', $task)

                                        <!-- Accept Button -->
                                        <form action="{{ route('admin.task.accept', $task->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="btn btn-success btn-sm btn-icon shadow-sm mx-1 rounded"
                                                title="Accept Task"
                                                style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                                <i class="now-ui-icons ui-1_check" style="font-size: 13px;"></i>
                                            </button>
                                        </form>

                                        <!-- Reject Button (Triggers Rejection Modal) -->
                                        <button type="button"
                                            class="btn btn-danger btn-sm btn-icon shadow-sm mx-1 rounded"
                                            title="Reject Task" data-toggle="modal"
                                            data-target="#rejectModal-{{ $task->id }}"
                                            style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                            <i class="now-ui-icons ui-1_simple-remove" style="font-size: 13px;"></i>
                                        </button>

                                        @else

                                        <span class="text-muted font-weight-bold px-3 py-2 rounded-pill"
                                            style="background-color: #f8f9fa; border: 1px solid #e3e6f0; font-size: 11px; display: inline-block; white-space: nowrap;">
                                            No Review Action
                                        </span>

                                        @endcan

                                    </div>
                                    @endif
                                </td>

                                <!-- STANDARD ACTIONS COLUMN (SHOW, EDIT, DELETE WITH SWEETALERT) -->
                                <td class="text-right pr-4 align-middle" data-column="actions">
                                    <div class="btn-group" role="group" aria-label="Task Actions">

                                        <!-- View Task Details Button -->
                                        @can('view', $task)
                                        <a href="{{ route('admin.task.show', $task->id) }}"
                                            class="btn btn-info btn-sm btn-icon shadow-sm mx-1 rounded"
                                            title="View Task Details"
                                            style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                            <i class="now-ui-icons business_bulb-63" style="font-size: 13px;"></i>
                                        </a>
                                        @endcan

                                        <!-- Edit Task Button -->
                                        @can('update', $task)
                                        <a href="{{ route('admin.task.edit', $task->id) }}"
                                            class="btn btn-warning btn-sm btn-icon shadow-sm mx-1 rounded"
                                            title="Edit Task"
                                            style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                            <i class="now-ui-icons ui-2_settings-90" style="font-size: 13px;"></i>
                                        </a>
                                        @endcan

                                        <!-- Delete Form with SweetAlert2 Integration -->
                                        @can('delete', $task)
                                        <form action="{{ route('admin.task.destroy', $task->id) }}" method="POST"
                                            style="display: inline-block;" id="delete-form-task-{{ $task->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="btn btn-danger btn-sm btn-icon shadow-sm mx-1 rounded"
                                                title="Delete Task"
                                                style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;"
                                                onclick="confirmDelete('task', {{ $task->id }})">
                                                <i class="now-ui-icons ui-1_simple-remove" style="font-size: 13px;"></i>
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>

                            @empty
                            <tr>
                                <td colspan="7" class="p-0">
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

                @endcan

            </div>
        </div>
    </div>
</div>

<!-- PAGINATION CONTROLS SECTION -->
@if(method_exists($tasks, 'hasPages') && $tasks->hasPages())
<div class="card-footer bg-white py-4 d-flex justify-content-between align-items-center">
    <div class="text-muted text-sm">
        Showing <b>{{ $tasks->firstItem() }}</b> to <b>{{ $tasks->lastItem() }}</b> of <b>{{
            $tasks->total() }}</b> entries
    </div>
    <div>
        {{ $tasks->appends(request()->query())->links('pagination::bootstrap-4') }}
    </div>
</div>
@endif

</div>
</div>
</div>
</div>

<!-- ========================================== -->
<!-- MODALS FOR TASK ATTACHMENTS FILES          -->
<!-- ========================================== -->
@foreach($tasks as $task)
@php
$attachmentService = app(\App\Services\TaskAttachmentService::class);
$attachments = $attachmentService->getFormattedAttachments($task);
@endphp

@if(count($attachments) > 0)
<div class="modal fade" id="taskFilesModal-{{ $task->id }}" tabindex="-1" role="dialog"
    aria-labelledby="taskFilesModalLabel-{{ $task->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #f96332 0%, #ff8c42 100%);">
                <h5 class="modal-title font-weight-bold" id="taskFilesModalLabel-{{ $task->id }}">
                    <i class="now-ui-icons files_single-copy-04 mr-2"></i> Task Attachments ({{ count($attachments) }})
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"
                    style="opacity: 0.9;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4" style="background-color: #f9fbfd;">
                <div class="list-group shadow-sm" style="border-radius: 10px; overflow: hidden;">
                    @foreach($attachments as $file)
                    <div
                        class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-3 border-bottom">
                        <div class="d-flex align-items-center text-truncate mr-3">
                            @if($file['is_image'])
                            <div class="mr-3 shadow-sm rounded overflow-hidden d-flex align-items-center justify-content-center"
                                style="min-width: 65px; width: 65px; height: 65px; background-color: #fee2e2;">
                                <img src="{{ asset('storage/' . $file['path']) }}" alt="img"
                                    style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            @else
                            <div class="rounded mr-3 shadow-sm text-white d-flex align-items-center justify-content-center"
                                style="min-width: 65px; width: 65px; height: 65px; background: linear-gradient(135deg,
                                @if($file['is_video']) #ef4444, #dc2626
                                @elseif($file['is_pdf']) #f59e0b, #d97706
                                @elseif($file['is_doc']) #2563eb, #1d4ed8
                                @elseif($file['is_archive']) #8b5cf6, #7c3aed
                                @else #f96332, #ff8c42 @endif);">

                                @if($file['is_video'])
                                <i class="now-ui-icons media-2_sound-wave" style="font-size: 24px;"></i>
                                @elseif($file['is_pdf'])
                                <i class="now-ui-icons files_paper" style="font-size: 24px;"></i>
                                @elseif($file['is_doc'])
                                <i class="now-ui-icons text_align-left" style="font-size: 24px;"></i>
                                @elseif($file['is_archive'])
                                <i class="now-ui-icons files_archive" style="font-size: 24px;"></i>
                                @else
                                <i class="now-ui-icons files_single-copy-04" style="font-size: 24px;"></i>
                                @endif
                            </div>
                            @endif

                            <div class="text-truncate">
                                <span class="font-weight-bold text-dark d-block text-truncate" style="font-size: 14px;"
                                    title="{{ $file['name'] }}">{{ $file['name'] }}</span>
                                <small class="text-muted text-uppercase font-weight-bold" style="font-size: 10px;">
                                    {{ $file['extension'] ?: 'File' }} Document
                                </small>
                            </div>
                        </div>
                        <div class="btn-group flex-shrink-0" role="group">
                            {{-- إذا كان الملف صورة، يفتح صفحة المعاينة المخصصة التي أنشأناها --}}
                            @if($file['is_image'])
                            <a href="{{ route('admin.task.view-attachment', ['path' => $file['path']]) }}"
                                target="_blank" class="btn btn-sm btn-info btn-round px-3 mr-2 shadow-sm text-white"
                                style="font-size: 11px;">
                                <i class="now-ui-icons design_image mr-1"></i> View Image
                            </a>
                            @else
                            {{-- إذا لم يكن صورة (مثل PDF أو مستند)، نجعل زر الـ View يوجهه للتحميل أو العرض المباشر
                            المتاح --}}
                            <a href="{{ asset('storage/' . $file['path']) }}" target="_blank"
                                class="btn btn-sm btn-info btn-round px-3 mr-2 shadow-sm text-white"
                                style="font-size: 11px;">
                                <i class="now-ui-icons files_paper mr-1"></i> Open File
                            </a>
                            @endif

                            {{-- زر التحميل الثابت لجميع الملفات --}}
                            <a href="{{ asset('storage/' . $file['path']) }}" download
                                class="btn btn-sm btn-primary btn-round px-3 shadow-sm text-white"
                                style="font-size: 11px; background-color: #ff8c42; border-color: #ff8c42;">
                                <i class="now-ui-icons arrows-1_cloud-download-93 mr-1"></i> Download
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer bg-white border-0 py-3">
                <button type="button" class="btn btn-secondary btn-round px-4 shadow-sm"
                    data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endif
@endforeach


<!-- ========================================== -->
<!-- MODAL FOR REJECTING TASK (WITH REASON)     -->
<!-- ========================================== -->
@foreach($tasks as $task)
@can('modifyStatus', $task)
<div class="modal fade" id="rejectModal-{{ $task->id }}" tabindex="-1" role="dialog"
    aria-labelledby="rejectModalLabel-{{ $task->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;">
            <form action="{{ route('admin.task.reject', $task->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header text-white"
                    style="background: linear-gradient(135deg, #f96332 0%, #ff8c42 100%);">
                    <h5 class="modal-title font-weight-bold" id="rejectModalLabel-{{ $task->id }}">
                        <i class="now-ui-icons ui-1_simple-remove mr-2"></i> Reject Task
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"
                        style="opacity: 0.9;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4" style="background-color: #f9fbfd;">
                    <div class="form-group">
                        <label for="rejection_reason" class="font-weight-bold text-dark">Please provide a reason for
                            rejection:</label>
                        <textarea name="rejection_reason" id="rejection_reason" rows="4"
                            class="form-control border rounded p-3" placeholder="Enter rejection reason here..."
                            required style="background-color: #ffffff;"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-white border-0 py-3">
                    <button type="button" class="btn btn-secondary btn-round px-4 shadow-sm"
                        data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger btn-round px-4 shadow-sm">Confirm Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan
@endforeach

<!-- ========================================== -->
<!-- MODAL TO VIEW REJECTION REASON             -->
<!-- ========================================== -->
@foreach($tasks as $task)
@if($task->status == 'rejected' && !empty($task->rejection_reason))
<div class="modal fade" id="rejectionReasonModal-{{ $task->id }}" tabindex="-1" role="dialog"
    aria-labelledby="rejectionReasonModalLabel-{{ $task->id }}" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">

        <div class="modal-content shadow-lg border-0"
            style="border-radius: 16px; overflow: hidden; background: #ffffff;">

            <div class="modal-header border-0 pb-3 pt-4 px-4"
                style="background: linear-gradient(135deg, #f96332 0%, #ff8559 100%); color: white;">

                <h5 class="modal-title font-weight-bold text-white d-flex align-items-center m-0"
                    id="rejectionReasonModalLabel-{{ $task->id }}" style="font-size: 1.1rem;">

                    <div style="background: rgba(255, 255, 255, 0.2); width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center;"
                        class="mr-3">
                        <i class="now-ui-icons travel_info text-white" style="font-size: 18px; line-height: 0;"></i>
                    </div>

                    <span>
                        Rejection Reason
                    </span>

                </h5>

                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"
                    style="opacity: 0.8; text-shadow: none; transition: opacity 0.2s;"
                    onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.8'">

                    <span aria-hidden="true" style="font-size: 1.5rem;">
                        &times;
                    </span>

                </button>

            </div>


            <div class="modal-body p-4 text-left" style="background-color: #fcfcfc;">

                <div
                    style="background: rgba(255, 112, 67, 0.04); border: 1px solid rgba(249, 99, 50, 0.12); border-radius: 12px; padding: 20px; box-shadow: inset 0 1px 3px rgba(0,0,0,0.02);">
                    <p class="text-dark m-0" style="white-space: pre-line; line-height: 1.8; font-size: 0.95rem;">

                        {{ $task->rejection_reason }}

                    </p>
                </div>

            </div>


            <div class="modal-footer border-0 pt-0 pb-4 px-4 justify-content-end" style="background-color: #fcfcfc;">

                <button type="button" class="btn btn-secondary btn-round px-4 py-2" data-dismiss="modal"
                    style="text-transform: none; font-weight: 600; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">

                    Close

                </button>

            </div>

        </div>

    </div>

</div>
@endif
@endforeach




<!-- ========================================== -->
<!-- MODALS FOR TASK TITLES                     -->
<!-- ========================================== -->
@foreach($tasks as $task)

@if(strlen($task->title) > 15)

<div class="modal fade" id="taskTitleModal-{{ $task->id }}" tabindex="-1" role="dialog"
    aria-labelledby="taskTitleModalLabel-{{ $task->id }}" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">

        <div class="modal-content shadow-lg border-0"
            style="border-radius: 16px; overflow: hidden; background: #ffffff;">

            <div class="modal-header border-0 pb-3 pt-4 px-4"
                style="background: linear-gradient(135deg, #f96332 0%, #ff8c42 100%); color: white;">

                <h5 class="modal-title font-weight-bold text-white d-flex align-items-center m-0"
                    id="taskTitleModalLabel-{{ $task->id }}" style="font-size: 1.1rem;">

                    <div style="background: rgba(255, 255, 255, 0.2); width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center;"
                        class="mr-3">
                        <i class="now-ui-icons text_caps-small text-white" style="font-size: 18px; line-height: 0;"></i>
                    </div>

                    <span>
                        Task Title
                    </span>

                </h5>

                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"
                    style="opacity: 0.8; text-shadow: none; transition: opacity 0.2s;"
                    onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.8'">

                    <span aria-hidden="true" style="font-size: 1.5rem;">
                        &times;
                    </span>

                </button>

            </div>


            <div class="modal-body p-4 text-left" style="background-color: #fcfcfc;">

                <div
                    style="background: rgba(249, 99, 50, 0.04); border: 1px solid rgba(249, 99, 50, 0.12); border-radius: 12px; padding: 20px; box-shadow: inset 0 1px 3px rgba(0,0,0,0.02);">
                    <p class="text-dark m-0" style="white-space: pre-line; line-height: 1.8; font-size: 0.95rem;">

                        {{ $task->title }}

                    </p>
                </div>

            </div>


            <div class="modal-footer border-0 pt-0 pb-4 px-4 justify-content-end" style="background-color: #fcfcfc;">

                <button type="button" class="btn btn-secondary btn-round px-4 py-2" data-dismiss="modal"
                    style="text-transform: none; font-weight: 600; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">

                    Close

                </button>

            </div>

        </div>

    </div>

</div>

@endif

@endforeach



<!-- ========================================== -->
<!-- MODALS FOR TASK DESCRIPTIONS            -->
<!-- ========================================== -->
@foreach($tasks as $task)

@if(strlen($task->description) > 15)

<div class="modal fade" id="taskDescModal-{{ $task->id }}" tabindex="-1" role="dialog"
    aria-labelledby="taskDescModalLabel-{{ $task->id }}" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">

        <div class="modal-content shadow-lg border-0"
            style="border-radius: 16px; overflow: hidden; background: #ffffff;">

            <div class="modal-header border-0 pb-3 pt-4 px-4"
                style="background: linear-gradient(135deg, #f96332 0%, #ff8c42 100%); color: white;">

                <h5 class="modal-title font-weight-bold text-white d-flex align-items-center m-0"
                    id="taskDescModalLabel-{{ $task->id }}" style="font-size: 1.1rem;">

                    <div style="background: rgba(255, 255, 255, 0.2); width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center;"
                        class="mr-3">
                        <i class="now-ui-icons business_briefcase-24 text-white"
                            style="font-size: 18px; line-height: 0;"></i>
                    </div>

                    <span>
                        Description
                    </span>

                </h5>

                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"
                    style="opacity: 0.8; text-shadow: none; transition: opacity 0.2s;"
                    onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.8'">

                    <span aria-hidden="true" style="font-size: 1.5rem;">
                        &times;
                    </span>

                </button>

            </div>


            <div class="modal-body p-4 text-left" style="background-color: #fcfcfc;">

                <div
                    style="background: rgba(249, 99, 50, 0.04); border: 1px solid rgba(249, 99, 50, 0.12); border-radius: 12px; padding: 20px; box-shadow: inset 0 1px 3px rgba(0,0,0,0.02);">
                    <p class="text-dark m-0" style="white-space: pre-line; line-height: 1.8; font-size: 0.95rem;">

                        {{ $task->description }}

                    </p>
                </div>

            </div>


            <div class="modal-footer border-0 pt-0 pb-4 px-4 justify-content-end" style="background-color: #fcfcfc;">

                <button type="button" class="btn btn-secondary btn-round px-4 py-2" data-dismiss="modal"
                    style="text-transform: none; font-weight: 600; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">

                    Close

                </button>

            </div>

        </div>

    </div>

</div>

@endif

@endforeach

@endsection

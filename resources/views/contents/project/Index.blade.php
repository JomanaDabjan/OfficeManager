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
        <p class="text-muted text-sm mb-0">Manage all system projects, assign managers, and track statuses. (Drag table
            headers to reorder columns)</p>
    </div>
    <div class="col-md-6 text-right">
        <!-- Render the 'Add New Project' button for administrators -->
        <a href="{{ route('admin.project.create') }}" class="btn btn-primary btn-round text-white shadow-sm px-4">
            <i class="now-ui-icons ui-1_simple-add"></i> Add New Project
        </a>
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
                    <div class="d-flex flex-wrap align-items-center" style="gap: 12px;">
                        <span class="text-muted font-weight-bold mr-2" style="font-size: 13px;">
                            <i class="now-ui-icons ui-1_zoom-bold mr-1 text-primary"></i> Filter By:
                        </span>

                        <!-- Title Filter Dropdown -->
                        <div class="dropdown">
                            <button
                                class="btn btn-light btn-sm dropdown-toggle text-dark shadow-none px-3 py-2 font-weight-bold rounded-pill border"
                                type="button" id="dropdownTitle" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false"
                                style="font-size: 13px; background-color: #f8f9fa; border-color: #e3e6f0 !important;">
                                {{ request('title') && request('title') != 'all' ? Str::limit(request('title'), 15) :
                                'All Titles' }}
                            </button>
                            <div class="dropdown-menu shadow-lg border-0 py-2" aria-labelledby="dropdownTitle"
                                style="border-radius: 12px; min-width: 180px;">
                                <a class="dropdown-item py-2 px-3 text-sm {{ !request('title') || request('title') == 'all' ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.project.index', array_merge(request()->except(['title', 'page']), [])) }}">
                                    <i class="now-ui-icons ui-1_simple-add mr-2"></i> All Titles
                                </a>
                                @foreach($allTitles as $projTitle)
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('title') == $projTitle ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.project.index', array_merge(request()->except(['title', 'page']), ['title' => $projTitle])) }}">
                                    {{ $projTitle }}
                                </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Manager Filter Dropdown -->
                        <div class="dropdown">
                            <button
                                class="btn btn-light btn-sm dropdown-toggle text-dark shadow-none px-3 py-2 font-weight-bold rounded-pill border"
                                type="button" id="dropdownManager" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false"
                                style="font-size: 13px; background-color: #f8f9fa; border-color: #e3e6f0 !important;">
                                @php
                                $selectedManager = $managers->firstWhere('id', request('manager_id'));
                                @endphp
                                {{ $selectedManager ? $selectedManager->name : 'Assigned Manager' }}
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
                        <div class="dropdown">
                            <button
                                class="btn btn-light btn-sm dropdown-toggle text-dark shadow-none px-3 py-2 font-weight-bold rounded-pill border"
                                type="button" id="dropdownStatus" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false"
                                style="font-size: 13px; background-color: #f8f9fa; border-color: #e3e6f0 !important;">
                                {{ request('status') && request('status') != 'all' ? ucfirst(str_replace('_', ' ',
                                request('status'))) : 'All Statuses' }}
                            </button>
                            <div class="dropdown-menu shadow-lg border-0 py-2" aria-labelledby="dropdownStatus"
                                style="border-radius: 12px; min-width: 160px;">
                                <a class="dropdown-item py-2 px-3 text-sm {{ !request('status') || request('status') == 'all' ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.project.index', array_merge(request()->except(['status', 'page']), ['status' => 'all'])) }}">All
                                    Statuses</a>
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('status') == 'pending' ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.project.index', array_merge(request()->except(['status', 'page']), ['status' => 'pending'])) }}">Pending</a>
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('status') == 'in_progress' ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.project.index', array_merge(request()->except(['status', 'page']), ['status' => 'in_progress'])) }}">In
                                    Progress</a>
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('status') == 'completed' ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.project.index', array_merge(request()->except(['status', 'page']), ['status' => 'completed'])) }}">Completed</a>
                            </div>
                        </div>

                    </div>

                    <!-- Reset Filters Button (Appears only if a filter is active) -->
                    @if(request()->anyFilled(['title', 'manager_id', 'status', 'search']))
                    <div>
                        <a href="{{ route('admin.project.index') }}"
                            class="btn btn-outline-danger btn-sm rounded-pill px-3 py-2" style="font-size: 12px;">
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
<!-- LIVE SEARCH & STATUS FILTER SECTION        -->
<!-- ========================================== -->
<div class="row mb-3 align-items-center">
    <!-- Search Input -->
    <div class="col-md-5 mb-2 mb-md-0">
        <div class="search-container">
            <i class="now-ui-icons ui-1_zoom-bold search-icon"></i>
            <input type="text" id="projectSearchInput" class="form-control border rounded-pill shadow-sm"
                placeholder="Search projects..." value="{{ request('search') }}" style="background-color: #f9fbfd;">
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MAIN PROJECTS TABLE CARD SECTION          -->
<!-- ========================================== -->
<div class="row">
    <div class="col-md-12">
        <x-alert-message />
        <div class="card shadow-sm border-0">
            <div class="card-body px-0 pb-0">
                <div class="table-responsive">
                    <table class="table align-items-center table-flush mb-0" id="projectsTable">
                        <!-- Table Headings with Gradient Style -->
                        <thead style="background: linear-gradient(135deg, #f96332 0%, #ff8c42 100%); color: white;">
                            <tr>
                                <th class="py-3 font-weight-bold text-white pl-4 draggable-th" draggable="true"
                                    data-column="0" style="cursor: grab;">Title <i
                                        class="now-ui-icons arrows-1_move-horizontal ml-1"
                                        style="font-size: 10px; opacity: 0.7;"></i></th>
                                <th class="py-3 font-weight-bold text-white draggable-th" draggable="true"
                                    data-column="1" style="cursor: grab;">Description <i
                                        class="now-ui-icons arrows-1_move-horizontal ml-1"
                                        style="font-size: 10px; opacity: 0.7;"></i></th>
                                <th class="py-3 font-weight-bold text-white draggable-th" draggable="true"
                                    data-column="2" style="cursor: grab;">Manager <i
                                        class="now-ui-icons arrows-1_move-horizontal ml-1"
                                        style="font-size: 10px; opacity: 0.7;"></i></th>
                                <th class="py-3 font-weight-bold text-white draggable-th" draggable="true"
                                    data-column="3" style="cursor: grab;">Status <i
                                        class="now-ui-icons arrows-1_move-horizontal ml-1"
                                        style="font-size: 10px; opacity: 0.7;"></i></th>
                                <th class="py-3 font-weight-bold text-white text-right pr-4 draggable-th"
                                    draggable="true" data-column="4" style="cursor: grab;">Actions <i
                                        class="now-ui-icons arrows-1_move-horizontal ml-1"
                                        style="font-size: 10px; opacity: 0.7;"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Loop through each project record using Laravel forelse directive -->
                            @forelse($projects as $project)
                            @php
                            $projectStatus = strtolower($project->status ?? 'in_progress');
                            @endphp
                            <tr class="border-bottom project-row" data-status="{{ $projectStatus }}">
                                <!-- Project Title Column -->
                                <td class="font-weight-bold text-dark pl-4 align-middle project-title"
                                    data-col-index="0">
                                    {{ $project->title }}
                                </td>

                                <!-- Project Description Column (Clickable to open Modal) -->
                                <td class="text-muted align-middle project-desc" data-col-index="1">
                                    {{ Str::limit($project->description, 40) }}
                                    @if(strlen($project->description) > 40)
                                    <button type="button"
                                        class="btn btn-link btn-sm p-0 ml-1 text-primary font-weight-bold"
                                        data-toggle="modal" data-target="#descModal-{{ $project->id }}"
                                        style="font-size: 12px; text-decoration: underline;">
                                        More
                                    </button>
                                    @endif
                                </td>

                                <!-- Assigned Manager Column with Avatar Initials -->
                                <td class="align-middle project-manager" data-col-index="2">
                                    <div class="d-flex align-items-center">
                                        <span
                                            class="avatar-sm rounded-circle bg-light text-primary font-weight-bold d-flex align-items-center justify-content-center shadow-sm mr-2"
                                            style="width: 32px; height: 32px; font-size: 12px;">
                                            {{ strtoupper(substr(optional($project->manager)->name ?? 'U', 0, 2)) }}
                                        </span>
                                        <span class="text-dark font-weight-normal">
                                            {{ optional($project->manager)->name ?? 'No Manager' }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Project Status Column with Dynamic Color Badges -->
                                <td class="align-middle project-status" data-col-index="3">
                                    <span class="badge badge-pill
                                        @if($projectStatus == 'completed') badge-success
                                        @elseif($projectStatus == 'in_progress') badge-warning
                                        @elseif($projectStatus == 'pending') badge-info
                                        @else badge-secondary @endif px-3 py-2 text-white shadow-sm">
                                        {{ ucfirst(str_replace('_', ' ', $project->status ?? 'in_progress')) }}
                                    </span>
                                </td>

                                <!-- ACTIONS COLUMN (VIEW, EDIT, DELETE) -->
                                <td class="text-right pr-4 align-middle" data-col-index="4">
                                    <div class="btn-group" role="group" aria-label="Project Actions">
                                        <!-- View Project Details Button -->
                                        <a href="{{ route('admin.project.show', $project->id) }}"
                                            class="btn btn-info btn-sm btn-icon shadow-sm mx-1 rounded"
                                            title="View Details"
                                            style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                            <i class="now-ui-icons business_bulb-63" style="font-size: 13px;"></i>
                                        </a>

                                        <!-- Edit Project Button -->
                                        <a href="{{ route('admin.project.edit', $project->id) }}"
                                            class="btn btn-warning btn-sm btn-icon shadow-sm mx-1 rounded"
                                            title="Edit Project"
                                            style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                            <i class="now-ui-icons ui-2_settings-90" style="font-size: 13px;"></i>
                                        </a>

                                        <form action="{{ route('admin.project.destroy', $project->id) }}" method="POST"
                                            style="display: inline-block;" id="delete-form-project-{{ $project->id }}">
                                            <!--  CSRF Token and Method -->
                                            @csrf
                                            @method('DELETE')

                                            <button type="button"
                                                class="btn btn-danger btn-sm btn-icon shadow-sm mx-1 rounded"
                                                title="Delete Project"
                                                onclick="confirmDelete('project', {{ $project->id }})">
                                                <!-- The "Delete" icon -->
                                                <i class="now-ui-icons ui-1_simple-remove" style="font-size: 13px;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <!-- Empty State Row when no projects exist -->
                            <tr id="noProjectsDefault">
                                <td colspan="5" class="text-center text-muted py-5">
                                    <div class="py-4">
                                        <i class="now-ui-icons business_briefcase-24 mb-3 text-muted"
                                            style="font-size: 28px;"></i>
                                        <p class="font-weight-bold mb-1">No projects found.</p>
                                        <p class="text-sm text-muted">Click "Add New Project" to create one.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION CONTROLS SECTION -->
                @if($projects->hasPages())
                <div class="card-footer bg-white py-4 d-flex justify-content-between align-items-center">
                    <div class="text-muted text-sm">
                        Showing <b>{{ $projects->firstItem() }}</b> to <b>{{ $projects->lastItem() }}</b> of <b>{{
                            $projects->total() }}</b> entries
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
<!-- MODALS FOR PROJECT DESCRIPTIONS          -->
<!-- ========================================== -->
@foreach($projects as $project)
@if(strlen($project->description) > 40)
<div class="modal fade" id="descModal-{{ $project->id }}" tabindex="-1" role="dialog"
    aria-labelledby="descModalLabel-{{ $project->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #f96332 0%, #ff8c42 100%);">
                <h5 class="modal-title font-weight-bold" id="descModalLabel-{{ $project->id }}">
                    <i class="now-ui-icons business_briefcase-24 mr-2"></i> Description
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"
                    style="opacity: 0.9;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4 text-dark" style="background-color: #f9fbfd; line-height: 1.6;">
                <p class="mb-0" style="white-space: pre-line;">{{ $project->description }}</p>
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

@endsection

@extends('layouts.app')

<!-- Set the dynamic title for this specific page -->
@section('title', 'Team Table View')

@section('Main_Content')
<div class="content container-fluid px-4">
    <div class="row">
        <div class="col-md-12 px-0">
            <!-- Alert message component -->
            <x-alert-message />

            <div class="card shadow-sm border-0 w-100">
                <!-- Card Header matching Task Management layout and styling with top padding -->
                <div class="card-header d-flex justify-content-between align-items-center pt-5 pb-4 px-4">
                    <div>
                        <h4 class="card-title font-weight-bold">Teams Management</h4>
                        <p class="card-category text-muted">Manage all project teams, assign projects, and track member
                            counts.</p>
                    </div>
                    <div class="col-md-6 text-right">
                        <!-- Render the 'Add New Task' button for administrators -->
                        <a href="{{ route('admin.team.create') }}"
                            class="btn btn-primary btn-round text-white shadow-sm px-4">
                            <i class="now-ui-icons ui-1_simple-add"></i> Add New Team
                        </a>
                    </div>
                </div>



                <!-- ========================================== -->
                <!-- COLUMN FILTERS DROPDOWNS SECTION (TEAMS)  -->
                <!-- ========================================== -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card border-0 shadow-sm"
                            style="border-radius: 16px; background: #ffffff; overflow: visible;">
                            <div class="card-body p-3 p-md-4" style="overflow: visible;">
                                <div class="d-flex flex-wrap align-items-center justify-content-between"
                                    style="gap: 12px;">

                                    <!-- Filters Grouping -->
                                    <div class="d-flex flex-wrap align-items-center flex-grow-1" style="gap: 10px;">

                                        <!-- Team Name Filter Dropdown -->
                                        <div class="dropdown flex-fill">
                                            <button
                                                class="btn btn-light btn-sm dropdown-toggle text-dark shadow-none px-3 py-2 font-weight-bold rounded-pill border w-100 text-truncate"
                                                type="button" id="dropdownTeamName" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false"
                                                style="font-size: 13px; background-color: #f8f9fa; border-color: #e3e6f0 !important; height: 35px; display: flex; align-items: center; justify-content: space-between;">
                                                <span>{{ request('team_name') && request('team_name') != 'all' ?
                                                    Str::limit(request('team_name'), 15) : 'All Teams' }}</span>
                                            </button>
                                            <div class="dropdown-menu shadow-lg border-0 py-2"
                                                aria-labelledby="dropdownTeamName"
                                                style="border-radius: 12px; min-width: 180px;">
                                                <a class="dropdown-item py-2 px-3 text-sm {{ !request('team_name') || request('team_name') == 'all' ? 'active font-weight-bold text-primary' : '' }}"
                                                    href="{{ route('admin.team.index', array_merge(request()->except(['team_name', 'page']), [])) }}">
                                                    <i class="now-ui-icons ui-1_simple-add mr-2"></i> All Teams
                                                </a>
                                                @foreach($allTeamNames as $teamName)
                                                <a class="dropdown-item py-2 px-3 text-sm {{ request('team_name') == $teamName ? 'active font-weight-bold text-primary' : '' }}"
                                                    href="{{ route('admin.team.index', array_merge(request()->except(['team_name', 'page']), ['team_name' => $teamName])) }}">
                                                    {{ $teamName }}
                                                </a>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- Project Filter Dropdown -->
                                        <div class="dropdown flex-fill">
                                            <button
                                                class="btn btn-light btn-sm dropdown-toggle text-dark shadow-none px-3 py-2 font-weight-bold rounded-pill border w-100 text-truncate"
                                                type="button" id="dropdownProject" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false"
                                                style="font-size: 13px; background-color: #f8f9fa; border-color: #e3e6f0 !important; height: 35px; display: flex; align-items: center; justify-content: space-between;">
                                                <span>{{ request('project_id') && request('project_id') != 'all' ?
                                                    optional($projects->firstWhere('id', request('project_id')))->title
                                                    ?? 'Project' : 'All Projects' }}</span>
                                            </button>
                                            <div class="dropdown-menu shadow-lg border-0 py-2"
                                                aria-labelledby="dropdownProject"
                                                style="border-radius: 12px; min-width: 180px;">
                                                <a class="dropdown-item py-2 px-3 text-sm {{ !request('project_id') || request('project_id') == 'all' ? 'active font-weight-bold text-primary' : '' }}"
                                                    href="{{ route('admin.team.index', array_merge(request()->except(['project_id', 'page']), [])) }}">
                                                    All Projects
                                                </a>
                                                @foreach($projects as $project)
                                                <a class="dropdown-item py-2 px-3 text-sm {{ request('project_id') == $project->id ? 'active font-weight-bold text-primary' : '' }}"
                                                    href="{{ route('admin.team.index', array_merge(request()->except(['project_id', 'page']), ['project_id' => $project->id])) }}">
                                                    {{ $project->title }}
                                                </a>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- Team Leader Filter Dropdown -->
                                        <div class="dropdown flex-fill">
                                            <button
                                                class="btn btn-light btn-sm dropdown-toggle text-dark shadow-none px-3 py-2 font-weight-bold rounded-pill border w-100 text-truncate"
                                                type="button" id="dropdownLeader" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false"
                                                style="font-size: 13px; background-color: #f8f9fa; border-color: #e3e6f0 !important; height: 35px; display: flex; align-items: center; justify-content: space-between;">
                                                @php
                                                $selectedLeader = $leaders->firstWhere('id', request('leader_id'));
                                                @endphp
                                                <span class="text-truncate">{{ $selectedLeader ? $selectedLeader->name :
                                                    'Team Leader' }}</span>
                                            </button>
                                            <div class="dropdown-menu shadow-lg border-0 py-2"
                                                aria-labelledby="dropdownLeader"
                                                style="border-radius: 12px; min-width: 180px;">
                                                <a class="dropdown-item py-2 px-3 text-sm {{ !request('leader_id') || request('leader_id') == 'all' ? 'active font-weight-bold text-primary' : '' }}"
                                                    href="{{ route('admin.team.index', array_merge(request()->except(['leader_id', 'page']), [])) }}">
                                                    All Leaders
                                                </a>
                                                @foreach($leaders as $leader)
                                                <a class="dropdown-item py-2 px-3 text-sm {{ request('leader_id') == $leader->id ? 'active font-weight-bold text-primary' : '' }}"
                                                    href="{{ route('admin.team.index', array_merge(request()->except(['leader_id', 'page']), ['leader_id' => $leader->id])) }}">
                                                    {{ $leader->name }}
                                                </a>
                                                @endforeach
                                            </div>
                                        </div>

                                    </div>

                                    <!-- Reset Filters Button -->
                                    @if(request()->anyFilled(['team_name', 'project_id', 'leader_id', 'search']))
                                    <div>
                                        <a href="{{ route('admin.team.index') }}"
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
                <!-- LIVE SEARCH SECTION                        -->
                <!-- ========================================== -->
                <div class="row mb-3 align-items-center">
                    <div class="col-md-5 mb-2 mb-md-0">
                        <div class="search-container">
                            <i class="now-ui-icons ui-1_zoom-bold search-icon"></i>
                            <input type="text" id="taskSearchInput" class="form-control border rounded-pill shadow-sm"
                                placeholder="Search teams..." value="{{ request('search') }}"
                                style="background-color: #f9fbfd;">
                        </div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- MAIN TEAMS TABLE CARD SECTION      -->
                <!-- ========================================== -->
                <div class="row mx-0">
                    <div class="col-md-12 px-4">
                        <x-alert-message />
                        <div class="card shadow-sm border" style="border: 1px solid #dee2e6 !important;">
                            <div class="card-body px-0 pb-0">
                                <div class="table-responsive" style="overflow-x: auto; width: 100%;">
                                    <!-- Corrected ID from teamsTable to match JS selector or updated JS -->
                                    <table class="table align-items-center table-flush mb-0 border" id="teamsTable"
                                        style="border: 1px solid #dee2e6; table-layout: auto;">
                                        <!-- Table Headings with Gradient Style Matching Projects -->
                                        <thead
                                            style="background: linear-gradient(135deg, #f96332 0%, #ff8c42 100%); color: white;">
                                            <tr id="tableHeaders">
                                                <th class="py-3 font-weight-bold text-white pl-4 draggable-header draggable-th text-center align-middle"
                                                    draggable="true" data-column="0"
                                                    style="cursor: grab; font-size: 13px; border: 1px solid rgba(255,255,255,0.2) !important; white-space: nowrap;">
                                                    Team Name <i class="now-ui-icons arrows-1_move-horizontal ml-1"
                                                        style="font-size: 10px; opacity: 0.7;"></i>
                                                </th>
                                                <th class="py-3 font-weight-bold text-white pl-4 draggable-header draggable-th text-center align-middle"
                                                    draggable="true" data-column="1"
                                                    style="cursor: grab; font-size: 13px; border: 1px solid rgba(255,255,255,0.2) !important; white-space: nowrap;">
                                                    Description <i class="now-ui-icons arrows-1_move-horizontal ml-1"
                                                        style="font-size: 10px; opacity: 0.7;"></i>
                                                </th>
                                                <th class="py-3 font-weight-bold text-white pl-4 draggable-header draggable-th text-center align-middle"
                                                    draggable="true" data-column="2"
                                                    style="cursor: grab; font-size: 13px; border: 1px solid rgba(255,255,255,0.2) !important; white-space: nowrap;">
                                                    Project Name <i class="now-ui-icons arrows-1_move-horizontal ml-1"
                                                        style="font-size: 10px; opacity: 0.7;"></i>
                                                </th>
                                                <th class="py-3 font-weight-bold text-white pl-4 draggable-header draggable-th text-center align-middle"
                                                    draggable="true" data-column="3"
                                                    style="cursor: grab; font-size: 13px; border: 1px solid rgba(255,255,255,0.2) !important; white-space: nowrap;">
                                                    Team Leader <i class="now-ui-icons arrows-1_move-horizontal ml-1"
                                                        style="font-size: 10px; opacity: 0.7;"></i>
                                                </th>
                                                <th class="py-3 font-weight-bold text-white pl-4 draggable-header draggable-th text-center align-middle"
                                                    draggable="true" data-column="4"
                                                    style="cursor: grab; font-size: 13px; border: 1px solid rgba(255,255,255,0.2) !important; white-space: nowrap;">
                                                    Members Count <i class="now-ui-icons arrows-1_move-horizontal ml-1"
                                                        style="font-size: 10px; opacity: 0.7;"></i>
                                                </th>
                                                <th class="py-3 font-weight-bold text-white pl-4 draggable-header draggable-th text-center align-middle"
                                                    draggable="true" data-column="5"
                                                    style="cursor: grab; font-size: 13px; border: 1px solid rgba(255,255,255,0.2) !important; white-space: nowrap;">
                                                    Actions <i class="now-ui-icons arrows-1_move-horizontal ml-1"
                                                        style="font-size: 10px; opacity: 0.7;"></i>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableBody">
                                            <!-- Loop through each team record using Laravel forelse directive -->
                                            @forelse($teams as $team)
                                            <tr class="border-bottom team-row">
                                                <!-- Team Name Column (Allows text wrapping downwards) -->
                                                <td class="font-weight-bold text-dark pl-4 align-middle team-name border-right text-center"
                                                    data-column="0"
                                                    style="border: 1px solid #dee2e6 !important; word-break: break-word; white-space: normal; max-width: 180px;">
                                                    {{ $team->name }}
                                                </td>

                                                <!-- Team Description Column (Clickable to open Modal) -->
                                                <td class="text-muted align-middle team-desc border-right text-center"
                                                    data-column="1"
                                                    style="border: 1px solid #dee2e6 !important; max-width: 200px;">
                                                    <span
                                                        style="display: inline-block; max-width: 130px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; vertical-align: middle;">
                                                        {{ Str::limit($team->description, 40) }}
                                                    </span>
                                                    <button type="button"
                                                        class="btn btn-link btn-sm p-0 ml-1 text-primary font-weight-bold"
                                                        data-toggle="modal" data-target="#teamDescModal-{{ $team->id }}"
                                                        style="font-size: 12px; text-decoration: underline; vertical-align: baseline;">
                                                        More
                                                    </button>
                                                </td>

                                                <!-- Project Name Column -->
                                                <td class="align-middle team-project border-right text-center"
                                                    data-column="2"
                                                    style="border: 1px solid #dee2e6 !important; max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                    <span class="text-dark font-weight-normal"
                                                        style="display: inline-block; max-width: 90px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; vertical-align: middle;">
                                                        {{ $team->project->title ?? 'No Project' }}
                                                    </span>
                                                    <button type="button"
                                                        class="btn btn-link btn-sm p-0 ml-1 text-primary font-weight-bold"
                                                        data-toggle="modal"
                                                        data-target="#teamProjectModal-{{ $team->id }}"
                                                        style="font-size: 12px; text-decoration: underline; vertical-align: baseline;">
                                                        More
                                                    </button>
                                                </td>

                                                <!-- Team Leader Column (Allows text wrapping downwards) -->
                                                <td class="align-middle team-manager border-right text-center"
                                                    data-column="3"
                                                    style="border: 1px solid #dee2e6 !important; word-break: break-word; white-space: normal; max-width: 180px;">
                                                    <div
                                                        class="d-flex flex-column align-items-center justify-content-center">
                                                        @if($team->leader)
                                                        <span
                                                            class="avatar-sm rounded-circle bg-light text-primary font-weight-bold d-flex align-items-center justify-content-center shadow-sm mb-1"
                                                            style="width: 32px; height: 32px; font-size: 12px; flex-shrink: 0;">
                                                            {{ strtoupper(substr($team->leader->name, 0, 2)) }}
                                                        </span>
                                                        <span class="text-dark font-weight-normal text-center">
                                                            {{ $team->leader->name }}
                                                        </span>
                                                        @else
                                                        <span class="text-muted font-italic" style="font-size: 12px;">No
                                                            Leader</span>
                                                        @endif
                                                    </div>
                                                </td>

                                                <!-- Members Count Column (Removed More button and modal) -->
                                                <td class="align-middle team-members-count border-right text-center"
                                                    data-column="4"
                                                    style="border: 1px solid #dee2e6 !important; white-space: nowrap;">
                                                    <span
                                                        class="badge badge-pill badge-primary px-3 py-2 text-white shadow-sm">
                                                        {{ $team->members_count ?? $team->users_count ?? 0 }} Members
                                                    </span>
                                                </td>

                                                <!-- STANDARD ACTIONS COLUMN (SHOW, EDIT, DELETE WITH SWEETALERT) -->
                                                <td class="text-center align-middle" data-column="5"
                                                    style="border: 1px solid #dee2e6 !important; white-space: nowrap;">
                                                    <div class="d-flex justify-content-center align-items-center"
                                                        role="group" aria-label="Team Actions">

                                                        <!-- View Team Details Button -->
                                                        <a href="{{ route('admin.team.show', $team->id) }}"
                                                            class="btn btn-info btn-sm btn-icon shadow-sm mx-1 rounded"
                                                            title="View Team Details"
                                                            style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                                            <i class="now-ui-icons business_bulb-63"
                                                                style="font-size: 13px;"></i>
                                                        </a>

                                                        <!-- Edit Team Button -->
                                                        <a href="{{ route('admin.team.edit', $team->id) }}"
                                                            class="btn btn-warning btn-sm btn-icon shadow-sm mx-1 rounded"
                                                            title="Edit Team"
                                                            style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                                            <i class="now-ui-icons ui-2_settings-90"
                                                                style="font-size: 13px;"></i>
                                                        </a>

                                                        <!-- Delete Form with SweetAlert2 Integration -->
                                                        <form action="{{ route('admin.team.destroy', $team->id) }}"
                                                            method="POST" style="display: inline-block;"
                                                            id="delete-form-team-{{ $team->id }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm btn-icon shadow-sm mx-1 rounded"
                                                                title="Delete Team"
                                                                style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;"
                                                                onclick="confirmDelete('team', {{ $team->id }})">
                                                                <i class="now-ui-icons ui-1_simple-remove"
                                                                    style="font-size: 13px;"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- Description Modal for Team -->
                                            <div class="modal fade" id="teamDescModal-{{ $team->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="teamDescModalLabel-{{ $team->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content shadow border-0">
                                                        <div class="modal-header"
                                                            style="background: linear-gradient(135deg, #f96332 0%, #ff8c42 100%); color: white;">
                                                            <h5 class="modal-title font-weight-bold text-white d-flex align-items-center"
                                                                id="teamDescModalLabel-{{ $team->id }}">
                                                                <i class="now-ui-icons users_circle-08 mr-2"
                                                                    style="font-size: 18px; line-height: 0;"></i>
                                                                <span>{{ $team->name }} - Description</span>
                                                            </h5>
                                                            <button type="button" class="close text-white"
                                                                data-dismiss="modal" aria-label="Close"
                                                                style="opacity: 1;">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body p-4 text-left">
                                                            <p class="text-dark"
                                                                style="white-space: pre-line; line-height: 1.6;">{{
                                                                $team->description }}</p>
                                                        </div>
                                                        <div class="modal-footer border-0 pt-0">
                                                            <button type="button"
                                                                class="btn btn-secondary btn-round px-4"
                                                                data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Project Modal for Team -->
                                            <div class="modal fade" id="teamProjectModal-{{ $team->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="teamProjectModalLabel-{{ $team->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content shadow border-0">
                                                        <div class="modal-header"
                                                            style="background: linear-gradient(135deg, #f96332 0%, #ff8c42 100%); color: white;">
                                                            <h5 class="modal-title font-weight-bold text-white d-flex align-items-center"
                                                                id="teamProjectModalLabel-{{ $team->id }}">
                                                                <i class="now-ui-icons users_circle-08 mr-2"
                                                                    style="font-size: 18px; line-height: 0;"></i>
                                                                <span>Project Name</span>
                                                            </h5>
                                                            <button type="button" class="close text-white"
                                                                data-dismiss="modal" aria-label="Close"
                                                                style="opacity: 1;">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body p-4 text-left">
                                                            <p class="text-dark"
                                                                style="white-space: pre-line; line-height: 1.6;">{{
                                                                $team->project->title ?? 'No Project' }}</p>
                                                        </div>
                                                        <div class="modal-footer border-0 pt-0">
                                                            <button type="button"
                                                                class="btn btn-secondary btn-round px-4"
                                                                data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @empty
                                            <!-- Empty State Row when no teams match filter criteria -->
                                            <tr id="noTeamsDefault">
                                                <td colspan="6" class="text-center text-muted py-5"
                                                    style="border: 1px solid #dee2e6 !important;">
                                                    <div class="py-4">
                                                        <i class="now-ui-icons design_bullet-list-67 fa-3x mb-3 text-muted"
                                                            style="font-size: 28px;"></i>
                                                        <p class="font-weight-bold mb-1">No teams found.</p>
                                                        <p class="text-sm text-muted">Click "Add New Team" to create
                                                            one.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <!-- PAGINATION CONTROLS SECTION -->
                                @if($teams->hasPages())
                                <div
                                    class="card-footer bg-white py-4 d-flex justify-content-between align-items-center">
                                    <div class="text-muted text-sm">
                                        Showing <b>{{ $teams->firstItem() }}</b> to <b>{{ $teams->lastItem()
                                            }}</b> of <b>{{
                                            $teams->total() }}</b> entries
                                    </div>
                                    <div>
                                        {{ $teams->links('pagination::bootstrap-4') }}
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

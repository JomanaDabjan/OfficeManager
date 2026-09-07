@extends('layouts.app')

@section('title', 'Team Table View')

@section('Main_Content')

<div class="content container-fluid px-4">

    <!-- ========================================== -->
    <!-- PAGE HEADER AND CREATION ACTION BUTTON     -->
    <!-- ========================================== -->
    <div class="row mt-4 mb-4 align-items-center" style="padding-top: 20px;">
        <div class="col-md-6">
            <h3 class="font-weight-bold text-dark mb-0">
                Teams Management
            </h3>

            <p class="text-muted text-sm mb-0">
                Manage all project teams, assign projects, and track member counts.
            </p>
        </div>

        <div class="col-md-6 text-right">
            <!-- Render the 'Add New Team' button according to TeamPolicy -->
            @can('create', \App\Models\Team::class)
            <a href="{{ route('admin.team.create') }}" class="btn btn-primary btn-round text-white shadow-sm px-4">
                <i class="now-ui-icons ui-1_simple-add"></i>
                Add New Team
            </a>
            @endcan
        </div>
    </div>


    <!-- ========================================== -->
    <!-- COLUMN FILTERS DROPDOWNS SECTION (TEAMS)  -->
    <!-- ========================================== -->
    <div class="row mb-4">

        <div class="col-md-12">

            <!-- Info Alert Notice -->
            <div class="alert alert-warning border-0 shadow-sm mb-3 text-white"
                style="background: linear-gradient(135deg, #f96332 0%, #ff8559 100%); border-radius: 12px; font-size: 13px;">

                <div class="d-flex align-items-center">
                    <i class="now-ui-icons travel_info mr-2" style="font-size: 18px;"></i>

                    <div>
                        <strong>Note:</strong>
                        Adding team members can be done directly from the team's details page.
                    </div>
                </div>

            </div>


            <!-- ========================================== -->
            <!-- FILTER CARD - SAME DESIGN AS USER PAGE     -->
            <!-- ========================================== -->
            <div class="card border-0 shadow-sm" style="border-radius: 16px; background: #ffffff; overflow: visible;">

                <div class="card-body p-3 p-md-4" style="overflow: visible;">

                    <div class="d-flex flex-wrap align-items-center justify-content-between" style="gap: 12px;">

                        <!-- Filters Grouping -->
                        <div class="d-flex flex-wrap align-items-center flex-grow-1" style="gap: 10px;">


                            <!-- ========================================== -->
                            <!-- TEAM NAME FILTER                           -->
                            <!-- ========================================== -->
                            <div class="dropdown flex-fill">

                                <button
                                    class="btn btn-light btn-sm dropdown-toggle text-dark shadow-none px-3 py-2 font-weight-bold rounded-pill border w-100 text-truncate"
                                    type="button" id="dropdownTeamName" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false"
                                    style="font-size: 13px; background-color: #f8f9fa; border-color: #e3e6f0 !important; height: 35px; display: flex; align-items: center; justify-content: space-between;">

                                    <span>
                                        {{ request('team_name') && request('team_name') != 'all'
                                        ? Str::limit(request('team_name'), 15)
                                        : 'All Teams' }}
                                    </span>

                                </button>

                                <div class="dropdown-menu shadow-lg border-0 py-2" aria-labelledby="dropdownTeamName"
                                    style="border-radius: 12px; min-width: 180px;">

                                    <a class="dropdown-item py-2 px-3 text-sm {{ !request('team_name') || request('team_name') == 'all' ? 'active font-weight-bold text-primary' : '' }}"
                                        href="{{ route('admin.team.index', array_merge(request()->except(['team_name', 'page']), [])) }}">

                                        <i class="now-ui-icons ui-1_simple-add mr-2"></i>
                                        All Teams

                                    </a>

                                    @foreach($allTeamNames as $teamName)

                                    <a class="dropdown-item py-2 px-3 text-sm {{ request('team_name') == $teamName ? 'active font-weight-bold text-primary' : '' }}"
                                        href="{{ route('admin.team.index', array_merge(request()->except(['team_name', 'page']), ['team_name' => $teamName])) }}">

                                        {{ $teamName }}

                                    </a>

                                    @endforeach

                                </div>

                            </div>


                            <!-- ========================================== -->
                            <!-- PROJECT FILTER                             -->
                            <!-- ========================================== -->
                            <div class="dropdown flex-fill">

                                <button
                                    class="btn btn-light btn-sm dropdown-toggle text-dark shadow-none px-3 py-2 font-weight-bold rounded-pill border w-100 text-truncate"
                                    type="button" id="dropdownProject" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false"
                                    style="font-size: 13px; background-color: #f8f9fa; border-color: #e3e6f0 !important; height: 35px; display: flex; align-items: center; justify-content: space-between;">

                                    <span>
                                        {{ request('project_id') && request('project_id') != 'all'
                                        ? optional($projects->firstWhere('id', request('project_id')))->title
                                        ?? 'Project'
                                        : 'All Projects' }}
                                    </span>

                                </button>

                                <div class="dropdown-menu shadow-lg border-0 py-2" aria-labelledby="dropdownProject"
                                    style="border-radius: 12px; min-width: 220px; max-height: 300px; overflow-y: auto;"
                                    onclick="event.stopPropagation();">

                                    <!-- Search Input Inside Dropdown -->
                                    <div class="px-3 pb-2 pt-1 sticky-top bg-white border-bottom mb-1">

                                        <input type="text" id="projectSearchInput" class="form-control form-control-sm"
                                            placeholder="Search project..." autocomplete="off"
                                            onkeyup="filterProjects()" style="border-radius: 20px;">

                                    </div>


                                    <div id="projectListContainer">

                                        <a class="dropdown-item py-2 px-3 text-sm project-item {{ !request('project_id') || request('project_id') == 'all' ? 'active font-weight-bold text-primary' : '' }}"
                                            href="{{ route('admin.team.index', array_merge(request()->except(['project_id', 'page']), [])) }}">

                                            All Projects

                                        </a>

                                        @foreach($projects as $project)

                                        <a class="dropdown-item py-2 px-3 text-sm project-item {{ request('project_id') == $project->id ? 'active font-weight-bold text-primary' : '' }}"
                                            href="{{ route('admin.team.index', array_merge(request()->except(['project_id', 'page']), ['project_id' => $project->id])) }}"
                                            data-title="{{ strtolower($project->title) }}">

                                            {{ $project->title }}

                                        </a>

                                        @endforeach

                                    </div>

                                </div>

                            </div>


                            <!-- ========================================== -->
                            <!-- TEAM LEADER FILTER                         -->
                            <!-- ========================================== -->
                            <div class="dropdown flex-fill">

                                <button
                                    class="btn btn-light btn-sm dropdown-toggle text-dark shadow-none px-3 py-2 font-weight-bold rounded-pill border w-100 text-truncate"
                                    type="button" id="dropdownLeader" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false"
                                    style="font-size: 13px; background-color: #f8f9fa; border-color: #e3e6f0 !important; height: 35px; display: flex; align-items: center; justify-content: space-between;">

                                    @php
                                    // جلب قادة الفريق الذين لديهم الدور team_leader فقط
                                    $teamLeaders = isset($leaders) ? $leaders->where('role', 'team_leader') :
                                    \App\Models\User::where('role', 'team_leader')->get();
                                    $selectedLeader = $teamLeaders->firstWhere('id', request('team_leader_id'));
                                    @endphp

                                    <span class="text-truncate">
                                        {{ $selectedLeader ? $selectedLeader->name : 'Team Leader' }}
                                    </span>

                                </button>

                                <div class="dropdown-menu shadow-lg border-0 py-2" aria-labelledby="dropdownLeader"
                                    style="border-radius: 12px; min-width: 180px;">

                                    <a class="dropdown-item py-2 px-3 text-sm {{ !request('team_leader_id') || request('team_leader_id') == 'all' ? 'active font-weight-bold text-primary' : '' }}"
                                        href="{{ route('admin.team.index', array_merge(request()->except(['team_leader_id', 'page']), [])) }}">

                                        All Leaders

                                    </a>

                                    @foreach($teamLeaders as $leader)

                                    <a class="dropdown-item py-2 px-3 text-sm {{ request('team_leader_id') == $leader->id ? 'active font-weight-bold text-primary' : '' }}"
                                        href="{{ route('admin.team.index', array_merge(request()->except(['team_leader_id', 'page']), ['team_leader_id' => $leader->id])) }}">

                                        {{ $leader->name }}

                                    </a>

                                    @endforeach

                                </div>

                            </div>


                            <!-- ========================================== -->
                            <!-- RESET FILTERS BUTTON                        -->
                            <!-- ========================================== -->
                            @if(request()->anyFilled(['team_name', 'project_id', 'team_leader_id', 'search']))

                            <div>

                                <a href="{{ route('admin.team.index') }}"
                                    class="btn btn-outline-danger btn-sm rounded-pill px-3 py-2"
                                    style="font-size: 12px; white-space: nowrap; height: 35px;">

                                    <i class="now-ui-icons ui-1_simple-remove mr-1"></i>
                                    Reset

                                </a>

                            </div>

                            @endif

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

                <input type="text" id="taskSearchInput" name="search"
                    class="form-control border rounded-pill shadow-sm" placeholder="Search teams..."
                    value="{{ request('search') }}"
                    style="background-color: #f9fbfd; padding-left: 40px; height: 40px;">
            </div>
        </div>
    </div>


    <!-- ========================================== -->
    <!-- MAIN TEAMS TABLE CARD SECTION              -->
    <!-- ========================================== -->
    <div class="row">

        <div class="col-md-12">

            <x-alert-message />

            <div class="card border-0 shadow-sm"
                style="border-radius: 16px; background: #ffffff; overflow: visible;">

                <div class="card-body px-0 pb-0">

                    <div class="table-responsive" style="overflow-x: auto; width: 100%;">

                        @can('viewAny', \App\Models\Team::class)

                        <table class="table align-items-center table-flush mb-0 border" id="teamsTable"
                            style="border: 1px solid #dee2e6; table-layout: auto;">

                            <!-- ========================================== -->
                            <!-- TABLE HEADER - SAME USER DESIGN            -->
                            <!-- ========================================== -->
                            <thead
                                style="background: linear-gradient(135deg, #f96332 0%, #ff8c42 100%); color: white;">

                                <tr id="tableHeaders">

                                    <!-- TEAM NAME -->
                                    <th class="py-3 font-weight-bold text-white pl-4 draggable-header draggable-th text-center align-middle"
                                        draggable="true" data-column="0"
                                        style="cursor: grab; font-size: 13px; font-weight: 700 !important; color: #ffffff !important; border: 1px solid rgba(255,255,255,0.2) !important; white-space: nowrap;">

                                        Team Name

                                        <i class="now-ui-icons arrows-1_move-horizontal ml-1"
                                            style="font-size: 10px; opacity: 0.7;"></i>

                                    </th>


                                    <!-- DESCRIPTION -->
                                    <th class="py-3 font-weight-bold text-white pl-4 draggable-header draggable-th text-center align-middle"
                                        draggable="true" data-column="1"
                                        style="cursor: grab; font-size: 13px; font-weight: 700 !important; color: #ffffff !important; border: 1px solid rgba(255,255,255,0.2) !important; white-space: nowrap;">

                                        Description

                                        <i class="now-ui-icons arrows-1_move-horizontal ml-1"
                                            style="font-size: 10px; opacity: 0.7;"></i>

                                    </th>


                                    <!-- PROJECT NAME -->
                                    <th class="py-3 font-weight-bold text-white pl-4 draggable-header draggable-th text-center align-middle"
                                        draggable="true" data-column="2"
                                        style="cursor: grab; font-size: 13px; font-weight: 700 !important; color: #ffffff !important; border: 1px solid rgba(255,255,255,0.2) !important; white-space: nowrap;">

                                        Project Name

                                        <i class="now-ui-icons arrows-1_move-horizontal ml-1"
                                            style="font-size: 10px; opacity: 0.7;"></i>

                                    </th>


                                    <!-- TEAM LEADER -->
                                    <th class="py-3 font-weight-bold text-white pl-4 draggable-header draggable-th text-center align-middle"
                                        draggable="true" data-column="3"
                                        style="cursor: grab; font-size: 13px; font-weight: 700 !important; color: #ffffff !important; border: 1px solid rgba(255,255,255,0.2) !important; white-space: nowrap;">

                                        Team Leader

                                        <i class="now-ui-icons arrows-1_move-horizontal ml-1"
                                            style="font-size: 10px; opacity: 0.7;"></i>

                                    </th>


                                    <!-- MEMBERS COUNT -->
                                    <th class="py-3 font-weight-bold text-white pl-4 draggable-header draggable-th text-center align-middle"
                                        draggable="true" data-column="4"
                                        style="cursor: grab; font-size: 13px; font-weight: 700 !important; color: #ffffff !important; border: 1px solid rgba(255,255,255,0.2) !important; white-space: nowrap;">

                                        Members Count

                                        <i class="now-ui-icons arrows-1_move-horizontal ml-1"
                                            style="font-size: 10px; opacity: 0.7;"></i>

                                    </th>


                                    <!-- ACTIONS -->
                                    <th class="py-3 font-weight-bold text-white pl-4 draggable-header draggable-th text-center align-middle"
                                        draggable="true" data-column="5"
                                        style="cursor: grab; font-size: 13px; font-weight: 700 !important; color: #ffffff !important; border: 1px solid rgba(255,255,255,0.2) !important; white-space: nowrap;">

                                        Actions

                                        <i class="now-ui-icons arrows-1_move-horizontal ml-1"
                                            style="font-size: 10px; opacity: 0.7;"></i>

                                    </th>

                                </tr>

                            </thead>


                            <tbody id="tableBody">

                                <!-- Loop through each team record using Laravel forelse directive -->
                                @forelse($teams as $team)

                                <tr class="border-bottom team-row">

                                    <!-- ========================================== -->
                                    <!-- TEAM NAME                                  -->
                                    <!-- ========================================== -->
                                    <td class="font-weight-bold text-dark pl-4 align-middle team-name border-right text-center"
                                        data-column="0"
                                        style="border: 1px solid #dee2e6 !important; word-break: break-word; white-space: normal; max-width: 180px;">

                                        {{ $team->name }}

                                    </td>


                                    <!-- ========================================== -->
                                    <!-- TEAM DESCRIPTION                           -->
                                    <!-- ========================================== -->
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


                                    <!-- ========================================== -->
                                    <!-- PROJECT NAME                               -->
                                    <!-- ========================================== -->
                                    <td class="align-middle team-project border-right text-center" data-column="2"
                                        style="border: 1px solid #dee2e6 !important; max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">

                                        <span class="text-dark font-weight-normal"
                                            style="display: inline-block; max-width: 90px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; vertical-align: middle;">

                                            {{ $team->project->title ?? 'No Project' }}

                                        </span>

                                        <button type="button"
                                            class="btn btn-link btn-sm p-0 ml-1 text-primary font-weight-bold"
                                            data-toggle="modal" data-target="#teamProjectModal-{{ $team->id }}"
                                            style="font-size: 12px; text-decoration: underline; vertical-align: baseline;">

                                            More

                                        </button>

                                    </td>


                                    <!-- ========================================== -->
                                    <!-- TEAM LEADER                                -->
                                    <!-- ========================================== -->
                                    <td class="align-middle team-manager border-right text-center" data-column="3"
                                        style="border: 1px solid #dee2e6 !important; word-break: break-word; white-space: normal; max-width: 180px;">

                                        <div class="d-flex flex-column align-items-center justify-content-center">

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

                                            <span class="text-muted font-italic" style="font-size: 12px;">

                                                No Leader

                                            </span>

                                            @endif

                                        </div>

                                    </td>


                                    <!-- ========================================== -->
                                    <!-- MEMBERS COUNT                              -->
                                    <!-- ========================================== -->
                                    <td class="align-middle team-members-count border-right text-center"
                                        data-column="4"
                                        style="border: 1px solid #dee2e6 !important; white-space: nowrap;">

                                        <span class="badge badge-pill badge-primary px-3 py-2 text-white shadow-sm">

                                            {{ $team->members_count ?? $team->users_count ?? 0 }}
                                            Members

                                        </span>

                                    </td>


                                    <!-- ========================================== -->
                                    <!-- STANDARD ACTIONS COLUMN                    -->
                                    <!-- ========================================== -->
                                    <td class="text-center align-middle" data-column="5"
                                        style="border: 1px solid #dee2e6 !important; white-space: nowrap;">

                                        <div class="d-flex justify-content-center align-items-center" role="group"
                                            aria-label="Team Actions">


                                            <!-- View Team Details Button -->
                                            @can('view', $team)
                                            <a href="{{ route('admin.team.show', $team->id) }}"
                                                class="btn btn-info btn-sm btn-icon shadow-sm mx-1 rounded"
                                                title="View Team Details"
                                                style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">

                                                <i class="now-ui-icons business_bulb-63"
                                                    style="font-size: 13px;"></i>

                                            </a>
                                            @endcan


                                            <!-- Edit Team Button -->
                                            @can('update', $team)
                                            <a href="{{ route('admin.team.edit', $team->id) }}"
                                                class="btn btn-warning btn-sm btn-icon shadow-sm mx-1 rounded"
                                                title="Edit Team"
                                                style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">

                                                <i class="now-ui-icons ui-2_settings-90"
                                                    style="font-size: 13px;"></i>

                                            </a>
                                            @endcan


                                            <!-- Delete Form with SweetAlert2 Integration -->
                                            @can('delete', $team)
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
                                            @endcan

                                        </div>

                                    </td>

                                </tr>


                                <!-- ========================================== -->
                                <!-- DESCRIPTION MODAL                          -->
                                <!-- ========================================== -->
                                <div class="modal fade" id="teamDescModal-{{ $team->id }}" tabindex="-1"
                                    role="dialog" aria-labelledby="teamDescModalLabel-{{ $team->id }}"
                                    aria-hidden="true">

                                    <div class="modal-dialog modal-dialog-centered" role="document">

                                        <div class="modal-content shadow border-0">

                                            <div class="modal-header"
                                                style="background: linear-gradient(135deg, #f96332 0%, #ff8559 100%); color: white;">

                                                <h5 class="modal-title font-weight-bold text-white d-flex align-items-center"
                                                    id="teamDescModalLabel-{{ $team->id }}">

                                                    <i class="now-ui-icons users_circle-08 mr-2"
                                                        style="font-size: 18px; line-height: 0;"></i>

                                                    <span>
                                                        {{ $team->name }} - Description
                                                    </span>

                                                </h5>

                                                <button type="button" class="close text-white" data-dismiss="modal"
                                                    aria-label="Close" style="opacity: 1;">

                                                    <span aria-hidden="true">
                                                        &times;
                                                    </span>

                                                </button>

                                            </div>


                                            <div class="modal-body p-4 text-left">

                                                <p class="text-dark"
                                                    style="white-space: pre-line; line-height: 1.6;">

                                                    {{ $team->description }}

                                                </p>

                                            </div>


                                            <div class="modal-footer border-0 pt-0">

                                                <button type="button" class="btn btn-secondary btn-round px-4"
                                                    data-dismiss="modal">

                                                    Close

                                                </button>

                                            </div>

                                        </div>

                                    </div>

                                </div>


                                <!-- ========================================== -->
                                <!-- PROJECT MODAL                              -->
                                <!-- ========================================== -->
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

                                                    <span>
                                                        Project Name
                                                    </span>

                                                </h5>

                                                <button type="button" class="close text-white" data-dismiss="modal"
                                                    aria-label="Close" style="opacity: 1;">

                                                    <span aria-hidden="true">
                                                        &times;
                                                    </span>

                                                </button>

                                            </div>


                                            <div class="modal-body p-4 text-left">

                                                <p class="text-dark"
                                                    style="white-space: pre-line; line-height: 1.6;">

                                                    {{ $team->project->title ?? 'No Project' }}

                                                </p>

                                            </div>


                                            <div class="modal-footer border-0 pt-0">

                                                <button type="button" class="btn btn-secondary btn-round px-4"
                                                    data-dismiss="modal">

                                                    Close

                                                </button>

                                            </div>

                                        </div>

                                    </div>

                                </div>


                                @empty
                                @endforelse

                            </tbody>

                        </table>

                        @endcan

                    </div>


                    <!-- ========================================== -->
                    <!-- PAGINATION CONTROLS SECTION                -->
                    <!-- ========================================== -->
                    @if($teams->hasPages())

                    <div class="card-footer bg-white py-4 d-flex justify-content-between align-items-center">

                        <div class="text-muted text-sm">

                            Showing
                            <b>{{ $teams->firstItem() }}</b>
                            to
                            <b>{{ $teams->lastItem() }}</b>
                            of
                            <b>{{ $teams->total() }}</b>
                            entries

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

@endsection

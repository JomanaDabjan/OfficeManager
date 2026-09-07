@extends('layouts.app')

@section('title', 'Show Team Details')

@section('Main_Content')

<!-- ========================================================================= -->
<!-- MAIN TEAM SHOW WRAPPER SECTION                                            -->
<!-- ========================================================================= -->

@can('view', $team)

<div class="row justify-content-center">
    <div class="col-lg-12 col-md-12">

        <!-- ===================================================== -->
        <!-- MAIN TEAM OVERVIEW CARD CONTAINER                     -->
        <!-- ===================================================== -->
        <div class="card shadow-lg border-0 project-show-card mb-4 rounded-lg overflow-hidden">

            <!-- CARD HEADER WITH GRADIENT AND ACTION BUTTONS -->
            <div
                class="card-header custom-card-header text-white d-flex flex-wrap justify-content-between align-items-center py-3 px-4 bg-gradient-primary">
                <div class="d-flex align-items-center mb-2 mb-md-0">

                    <!-- Icon shape container -->
                    <div class="icon icon-shape icon-lg bg-white text-primary rounded-circle shadow-sm mr-3">
                        <i class="now-ui-icons users_circle-08" style="font-size: 20px;"></i>
                    </div>

                    <!-- Team Title and Subtitle Info -->
                    <div>
                        <h4 class="font-weight-bold text-white mb-0">{{ $team->name }}</h4>
                        <p class="text-white-50 text-sm mb-0">Detailed view and system overview of the team.</p>
                    </div>
                </div>

                <!-- Action Buttons: Back & Edit -->
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.team.index') }}"
                        class="btn btn-neutral btn-round btn-sm px-3 shadow-sm mr-2 mb-1">
                        <i class="now-ui-icons arrows-1_minimal-left"></i> Back
                    </a>

                    @can('update', $team)
                    <a href="{{ route('admin.team.edit', $team->id) }}"
                        class="btn btn-primary btn-round btn-sm px-3 shadow-sm mb-1">
                        <i class="now-ui-icons ui-2_settings-90"></i> Edit Team
                    </a>
                    @endcan
                </div>
            </div>

            <!-- CARD BODY DETAILS SECTION -->
            <div class="card-body px-4 py-4 bg-white">
                <!-- Info Boxes Row with Flex Wrapping & Equal Height Stretching -->
                <div class="row flex-wrap">

                    <!-- Team Leader Information Box -->
                    <div class="col-xl-4 col-md-6 col-12 mb-4 d-flex align-items-stretch">
                        <div
                            class="p-3 bg-light border-0 rounded-lg shadow-sm w-100 d-flex flex-column justify-content-between">
                            <span class="d-block text-muted text-uppercase text-xs font-weight-bold mb-2">Team
                                Leader</span>
                            <span class="text-dark font-weight-bold text-md text-truncate"
                                title="{{ $team->leader->name ?? 'Not Assigned' }}">
                                <i class="now-ui-icons users_single-02 mr-1 text-primary"></i>
                                {{ $team->leader->name ?? 'Not Assigned' }}
                            </span>
                        </div>
                    </div>

                    <!-- Associated Project Information Box (Project Name styled as a Button) -->
                    <div class="col-xl-4 col-md-6 col-12 mb-4 d-flex align-items-stretch">
                        <div
                            class="p-3 bg-light border-0 rounded-lg shadow-sm w-100 d-flex flex-column justify-content-between">
                            <span class="d-block text-muted text-uppercase text-xs font-weight-bold mb-2">Associated
                                Project</span>
                            <div>
                                @if($team->project)
                                <a href="{{ route('admin.project.show', $team->project->id) }}"
                                    class="btn btn-sm btn-round font-weight-bold w-100 m-0 shadow-none d-flex align-items-center justify-content-center text-white py-2 px-3"
                                    style="background: #ff5722; border: none; text-align: center; white-space: normal;"
                                    title="{{ $team->project->title }}">
                                    <i class="now-ui-icons business_briefcase-24 mr-1"></i>
                                    <span class="text-truncate">{{ $team->project->title }}</span>
                                </a>
                                @else
                                <span
                                    class="badge badge-pill badge-neutral text-muted border px-3 py-2 w-100 font-weight-normal">No
                                    Project Linked</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Creation Date Information Box -->
                    <div class="col-xl-4 col-md-6 col-12 mb-4 d-flex align-items-stretch">
                        <div
                            class="p-3 bg-light border-0 rounded-lg shadow-sm w-100 d-flex flex-column justify-content-between">
                            <span class="d-block text-muted text-uppercase text-xs font-weight-bold mb-2">Created
                                At</span>
                            <span class="text-dark font-weight-bold text-md">
                                <i class="now-ui-icons ui-1_calendar-60 mr-1 text-primary"></i>
                                {{ $team->created_at ? $team->created_at->format('Y-m-d') : 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- TEAM DESCRIPTION SUB-SECTION -->
                <div class="row">
                    <div class="col-lg-12 mb-4">
                        <div class="p-4 bg-light border-0 rounded-lg shadow-sm h-100">
                            <span class="d-block text-muted text-uppercase text-xs font-weight-bold mb-2">Team
                                Description</span>
                            <p class="text-dark mb-0" style="font-size: 15px; line-height: 1.7;">
                                {{ $team->description ?? 'No description provided for this team.' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- MAIN TEAMS MEMBERS TABLE CARD SECTION  -->
                <!-- ========================================== -->
                <div class="card shadow-sm border-0 project-tasks-card mb-0">
                    <div
                        class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center flex-wrap">
                        <h5 class="font-weight-bold text-dark mb-0">
                            <i class="now-ui-icons users_single-02 text-primary mr-2"></i> Team Members &
                            Positions
                        </h5>
                        @can('update', $team)
                        <a href="{{ route('admin.team.members.create', $team->id) }}"
                            class="btn btn-sm btn-primary btn-round px-3 shadow-sm mb-0">
                            <i class="now-ui-icons ui-1_simple-add"></i> Add New Members
                        </a>
                        @endcan
                    </div>

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
                                                        Member Name <i
                                                            class="now-ui-icons arrows-1_move-horizontal ml-1"
                                                            style="font-size: 10px; opacity: 0.7;"></i>
                                                    </th>
                                                    <th class="py-3 font-weight-bold text-white pl-4 draggable-header draggable-th text-center align-middle"
                                                        draggable="true" data-column="1"
                                                        style="cursor: grab; font-size: 13px; border: 1px solid rgba(255,255,255,0.2) !important; white-space: nowrap;">
                                                        Position <i class="now-ui-icons arrows-1_move-horizontal ml-1"
                                                            style="font-size: 10px; opacity: 0.7;"></i>
                                                    </th>
                                                    <th class="py-3 font-weight-bold text-white pl-4 draggable-header draggable-th text-center align-middle"
                                                        draggable="true" data-column="2"
                                                        style="cursor: grab; font-size: 13px; border: 1px solid rgba(255,255,255,0.2) !important; white-space: nowrap;">
                                                        Email <i class="now-ui-icons arrows-1_move-horizontal ml-1"
                                                            style="font-size: 10px; opacity: 0.7;"></i>
                                                    </th>
                                                    <th class="py-3 font-weight-bold text-white pl-4 draggable-header draggable-th text-center align-middle"
                                                        draggable="true" data-column="3"
                                                        style="cursor: grab; font-size: 13px; border: 1px solid rgba(255,255,255,0.2) !important; white-space: nowrap;">
                                                        Joined Date & Time <i
                                                            class="now-ui-icons arrows-1_move-horizontal ml-1"
                                                            style="font-size: 10px; opacity: 0.7;"></i>
                                                    </th>
                                                    <th class="py-3 font-weight-bold text-white pl-4 draggable-header draggable-th text-center align-middle"
                                                        draggable="true" data-column="4"
                                                        style="cursor: grab; font-size: 13px; border: 1px solid rgba(255,255,255,0.2) !important; white-space: nowrap;">
                                                        Profile <i class="now-ui-icons arrows-1_move-horizontal ml-1"
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
                                                <!-- Loop through each team member record using Laravel forelse directive -->
                                                @forelse($team->members ?? $team->users ?? [] as $member)
                                                <tr class="border-bottom team-row">
                                                    <!-- Member Name Column -->
                                                    <td class="font-weight-bold text-dark pl-4 align-middle team-name border-right text-center"
                                                        data-column="0"
                                                        style="border: 1px solid #dee2e6 !important; word-break: break-word; white-space: normal; max-width: 180px;">
                                                        <div class="d-flex align-items-center justify-content-center">
                                                            <span
                                                                class="avatar-sm rounded-circle bg-light text-primary font-weight-bold d-flex align-items-center justify-content-center shadow-sm mr-2"
                                                                style="width: 32px; height: 32px; font-size: 12px; flex-shrink: 0;">
                                                                {{ strtoupper(substr($member->name, 0, 2)) }}
                                                            </span>
                                                            <span>{{ $member->name }}</span>
                                                        </div>
                                                    </td>

                                                    <!-- Position Column -->
                                                    <td class="text-muted align-middle team-desc border-right text-center"
                                                        data-column="1"
                                                        style="border: 1px solid #dee2e6 !important; max-width: 200px;">
                                                        <span
                                                            class="badge badge-pill badge-outline-primary px-3 py-1 font-weight-bold"
                                                            style="border: 1px solid #f96332; color: #f96332;">
                                                            {{ $member->position ?? 'Member' }}
                                                        </span>
                                                    </td>

                                                    <!-- Email Column -->
                                                    <td class="align-middle team-project border-right text-center"
                                                        data-column="2"
                                                        style="border: 1px solid #dee2e6 !important; max-width: 200px; word-break: break-word; white-space: normal;">
                                                        <span class="text-dark font-weight-normal">
                                                            {{ $member->email ?? 'N/A' }}
                                                        </span>
                                                    </td>

                                                    <!-- Joined Date & Time Column -->
                                                    <td class="align-middle team-manager border-right text-center"
                                                        data-column="3"
                                                        style="border: 1px solid #dee2e6 !important; white-space: nowrap;">
                                                        <span class="text-muted">
                                                            <i class="now-ui-icons ui-1_calendar-60 mr-1"></i>
                                                            {{ $member->pivot->created_at ??
                                                            $member->created_at?->format('Y-m-d H:i') ?? 'N/A' }}
                                                        </span>
                                                    </td>

                                                    <!-- Profile Column -->
                                                    <td class="align-middle team-members-count border-right text-center"
                                                        data-column="4"
                                                        style="border: 1px solid #dee2e6 !important; white-space: nowrap;">
                                                        <a href="{{ route('admin.user.show', $member->id) }}"
                                                            class="btn btn-info btn-sm btn-icon shadow-sm rounded"
                                                            title="View Profile"
                                                            style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                                            <i class="now-ui-icons users_single-02"
                                                                style="font-size: 13px;"></i>
                                                        </a>
                                                    </td>

                                                    <!-- STANDARD ACTIONS COLUMN (REMOVE MEMBER) -->
                                                    <td class="text-center align-middle" data-column="5"
                                                        style="border: 1px solid #dee2e6 !important; white-space: nowrap;">
                                                        <div class="d-flex justify-content-center align-items-center"
                                                            role="group" aria-label="Member Actions">

                                                            <!-- Delete Form with SweetAlert2 Integration -->
                                                            <form
                                                                action="{{ route('admin.team.members.destroy', [$team->id, $member->id]) }}"
                                                                method="POST" style="display: inline-block;"
                                                                id="delete-form-member-{{ $member->id }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button"
                                                                    class="btn btn-danger btn-sm btn-icon shadow-sm mx-1 rounded"
                                                                    title="Remove Member"
                                                                    style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;"
                                                                    onclick="confirmDelete('member', {{ $member->id }})">
                                                                    <i class="now-ui-icons ui-1_simple-remove"
                                                                        style="font-size: 13px;"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @empty
                                                <!-- Empty State Row when no team members exist -->
                                                <tr id="noTeamsDefault">
                                                    <td colspan="6" class="text-center text-muted py-5"
                                                        style="border: 1px solid #dee2e6 !important;">
                                                        <div class="py-4">
                                                            <i class="now-ui-icons users_circle-08 fa-3x mb-3 text-muted"
                                                                style="font-size: 28px;"></i>
                                                            <p class="font-weight-bold mb-1">No team members found.</p>
                                                            <p class="text-sm text-muted">Click "Add New Members" to add
                                                                one.</p>
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

                    @endcan

                    @endsection
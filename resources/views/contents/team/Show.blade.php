@extends('layouts.app')

@section('title', 'Show Team Details')

@section('Main_Content')

<!-- ========================================================================= -->
<!-- MAIN TEAM SHOW WRAPPER SECTION                                            -->
<!-- ========================================================================= -->

<div class="row justify-content-center">
    <div class="col-lg-12 col-md-12">

        <!-- Include Session Alert Message Component for Feedback -->
        <x-alert-message />

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

                    @if(Auth::user()->role !== 'employee')
                    <a href="{{ route('admin.team.edit', $team->id) }}"
                        class="btn btn-primary btn-round btn-sm px-3 shadow-sm mb-1">
                        <i class="now-ui-icons ui-2_settings-90"></i> Edit Team
                    </a>
                    @endif
                </div>
            </div>

            <!-- CARD BODY DETAILS SECTION -->
            <div class="card-body px-4 py-4 bg-white">
                <!-- Info Boxes Row with Flex Wrapping & Equal Height Stretching -->
                <div class="row flex-wrap">

                    <!-- Team Leader Information Box -->
                    <div class="col-xl-3 col-md-6 col-12 mb-4 d-flex align-items-stretch">
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
                    <div class="col-xl-3 col-md-6 col-12 mb-4 d-flex align-items-stretch">
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

                    <!-- Total Members Information Box -->
                    <div class="col-xl-3 col-md-6 col-12 mb-4 d-flex align-items-stretch">
                        <div
                            class="p-3 bg-light border-0 rounded-lg shadow-sm w-100 d-flex flex-column justify-content-between">
                            <span class="d-block text-muted text-uppercase text-xs font-weight-bold mb-2">Members
                                Count</span>
                            <span class="text-dark font-weight-bold text-md">
                                <i class="now-ui-icons users_multiple-08 mr-1 text-primary"></i>
                                {{ $team->members_count ?? $team->users_count ?? 0 }} Members
                            </span>
                        </div>
                    </div>

                    <!-- Creation Date Information Box -->
                    <div class="col-xl-3 col-md-6 col-12 mb-4 d-flex align-items-stretch">
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

                <!-- ASSOCIATED TEAM MEMBERS SECTION -->
                <div class="card shadow-sm border-0 project-tasks-card mb-0">
                    <div
                        class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center flex-wrap">
                        <h5 class="font-weight-bold text-dark mb-0">
                            <i class="now-ui-icons users_single-02 text-primary mr-2"></i> Team Members &
                            Specializations
                        </h5>
                        @if(Auth::user()->role !== 'employee')
                        <a href="{{ route('admin.team.members.create', $team->id) }}"
                            class="btn btn-sm btn-primary btn-round px-3 shadow-sm mb-0">
                            <i class="now-ui-icons ui-1_simple-add"></i> Add New Members
                        </a>
                        @endif
                    </div>

                    <div class="card-body px-4 py-3">
                        @if(isset($team->users) && $team->users->count() > 0)
                        <div class="table-responsive">
                            <table class="table align-items-center table-bordered table-flush mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col" class="border">Member Name</th>
                                        <th scope="col" class="border">Specialization / Role</th>
                                        <th scope="col" class="border">Email</th>
                                        <th scope="col" class="border">Joined Date & Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($team->users as $member)
                                    <tr>
                                        <td class="font-weight-bold text-dark border">
                                            <div class="d-flex align-items-center">
                                                <span
                                                    class="avatar-sm rounded-circle bg-light text-primary font-weight-bold d-flex align-items-center justify-content-center shadow-sm mr-2"
                                                    style="width: 34px; height: 34px; min-width: 34px; font-size: 13px;">
                                                    {{ strtoupper(substr($member->name, 0, 2)) }}
                                                </span>
                                                {{ $member->name }}
                                            </div>
                                        </td>
                                        <td class="align-middle border">
                                            <span
                                                class="badge badge-pill badge-neutral text-primary border border-primary px-3 py-1 font-weight-bold">
                                                {{ $member->position ?? $member->role ?? 'Front-end' }}
                                            </span>
                                        </td>
                                        <td class="text-muted border">
                                            {{ $member->email ?? 'N/A' }}
                                        </td>
                                        <td class="border">
                                            <span class="text-muted">
                                                <i class="now-ui-icons ui-1_calendar-60 mr-1 text-primary"></i>
                                                {{ $member->pivot && $member->pivot->created_at ?
                                                $member->pivot->created_at->format('Y-m-d h:i A') : 'N/A' }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="now-ui-icons users_single-02 text-muted mb-3" style="font-size: 48px;"></i>
                            <p class="text-muted font-weight-bold mb-0">No members found assigned to this team yet.</p>
                        </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection

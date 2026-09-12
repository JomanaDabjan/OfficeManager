@extends('layouts.app')

{{-- Set the dynamic title for this specific page --}}
@section('title', 'Show Project Details')

@section('Main_Content')

<!-- ========================================================================= -->
<!-- MAIN PROJECT SHOW WRAPPER SECTION                                         -->
<!-- ========================================================================= -->

@can('view', $project)

<div class="row justify-content-center">
    <div class="col-lg-12 col-md-12">


        <!-- ===================================================== -->
        <!-- MAIN PROJECT OVERVIEW CARD CONTAINER                  -->
        <!-- ===================================================== -->
        <div class="card shadow-sm border-0 project-show-card mb-4">

            <!-- CARD HEADER WITH GRADIENT AND ACTION BUTTONS -->
            <div
                class="card-header custom-card-header text-white d-flex justify-content-between align-items-center py-3 px-4">
                <div class="d-flex align-items-center">

                    <!-- Icon shape container -->
                    <div class="icon icon-shape icon-lg bg-white text-primary rounded-circle shadow-sm mr-3">
                        <i class="now-ui-icons business_briefcase-24" style="font-size: 20px;"></i>
                    </div>

                    <!-- Project Title and Subtitle Info -->
                    <div>
                        <h4 class="font-weight-bold text-white mb-0">{{ $project->title }}</h4>
                        <p class="text-white-50 text-sm mb-0">Detailed view and system overview of the project.</p>
                    </div>
                </div>

                <!-- Action Buttons: Back & Edit -->
                <!-- Action Buttons: Back & Edit -->
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.project.index') }}"
                        class="btn btn-neutral btn-round text-primary font-weight-bold btn-sm px-4 shadow-sm mr-2 mb-1"
                        style="height: 36px; min-width: 120px; display: inline-flex; align-items: center; justify-content: center; box-sizing: border-box;">
                        <i class="now-ui-icons arrows-1_minimal-left mr-1"></i> Back
                    </a>

                    @can('update', $project)
                    <a href="{{ route('admin.project.edit', $project->id) }}"
                        class="btn btn-primary btn-round text-white font-weight-bold btn-sm px-4 shadow-sm mb-1"
                        style="height: 36px; min-width: 120px; display: inline-flex; align-items: center; justify-content: center; box-sizing: border-box;">
                        <i class="now-ui-icons ui-2_settings-90 mr-1"></i> Edit Project
                    </a>
                    @endcan
                </div>
            </div>

            <!-- CARD BODY DETAILS SECTION -->
            <div class="card-body px-4 py-4">
                <div class="row">

                    <!-- Project Status Information Box -->
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="p-3 bg-light rounded shadow-sm h-100 d-flex flex-column justify-content-between">
                            <span class="d-block text-muted text-uppercase text-xs font-weight-bold mb-2">Project
                                Status</span>

                            @php
                            $today = \Carbon\Carbon::today();
                            $endDate = $project->end_date ? \Carbon\Carbon::parse($project->end_date) : null;
                            $startDate = $project->start_date ? \Carbon\Carbon::parse($project->start_date) : null;

                            $tasks = $project->tasks;
                            $hasTasks = $tasks->count() > 0;

                            $allTasksCompleted = $hasTasks ? $tasks->every(function($task) {
                            return strtolower(trim($task->status)) === 'complete' || strtolower(trim($task->status)) ===
                            'completed';
                            }) : false;

                            $rawStatus = strtolower(trim($project->status));
                            $currentStatus = $rawStatus;

                            if ($rawStatus !== 'completed' && $rawStatus !== 'complete') {
                            if ($endDate) {
                            if ($today->greaterThan($endDate) && (!$hasTasks || !$allTasksCompleted)) {
                            $currentStatus = 'overdue';
                            } elseif ($today->isSameDay($endDate) && (!$hasTasks || !$allTasksCompleted)) {
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
                            'due_today' => 'badge-purple text-white',
                            default => 'badge-secondary',
                            };
                            @endphp
                            <div>
                                <span class="badge {{ $statusClass }} px-3 py-2 text-uppercase font-weight-bold"
                                    @if($currentStatus==='due_today' ) style="background-color: #6f42c1; color: #fff;"
                                    @endif>
                                    {{ str_replace('_', ' ', $currentStatus) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Assigned Manager Information Box -->
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="p-3 bg-light rounded shadow-sm h-100 d-flex flex-column justify-content-between">
                            <span class="d-block text-muted text-uppercase text-xs font-weight-bold mb-2">Assigned
                                Manager</span>
                            <span class="text-dark font-weight-bold text-md text-truncate"
                                title="{{ $project->manager->name ?? 'Not Assigned' }}">
                                <i class="now-ui-icons users_circle-08 mr-1 text-primary"></i>
                                {{ $project->manager->name ?? 'Not Assigned' }}
                            </span>
                        </div>
                    </div>

                    <!-- Project Budget / Price Information Box -->
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="p-3 bg-light rounded shadow-sm h-100 d-flex flex-column justify-content-between">
                            <span class="d-block text-muted text-uppercase text-xs font-weight-bold mb-2">Project
                                Budget</span>
                            <span class="text-success font-weight-bold text-md">
                                <i class="now-ui-icons business_money-coins mr-1 text-success"></i>
                                {{ isset($project->budget) && $project->budget !== null ?
                                number_format($project->budget, 2) . ' $' : 'Not Specified' }}
                            </span>
                        </div>
                    </div>

                    <!-- Creation Date Information Box -->
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="p-3 bg-light rounded shadow-sm h-100 d-flex flex-column justify-content-between">
                            <span class="d-block text-muted text-uppercase text-xs font-weight-bold mb-2">Created
                                At</span>
                            <span class="text-dark font-weight-bold text-md">
                                <i class="now-ui-icons ui-1_calendar-60 mr-1 text-primary"></i>
                                {{ $project->created_at ? $project->created_at->format('Y-m-d') : 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- PROJECT TIMELINE SUB-SECTION (START & END WITH REMAINING TIME) -->
                <div class="row">
                    <!-- Start Date Box -->
                    <div class="col-lg-6 mb-3">
                        <div class="p-3 bg-light rounded shadow-sm h-100 d-flex flex-column justify-content-between">
                            <span class="d-block text-muted text-uppercase text-xs font-weight-bold mb-2">Start
                                Date</span>
                            <span class="text-dark font-weight-bold text-md">
                                <i class="now-ui-icons ui-1_calendar-60 mr-1 text-primary"></i>
                                {{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('Y-m-d') :
                                'Not Specified' }}
                            </span>
                        </div>
                    </div>

                    <!-- End Date & Remaining Time Box -->
                    <div class="col-lg-6 mb-3">
                        <div class="p-3 bg-light rounded shadow-sm h-100 d-flex flex-column justify-content-between">
                            <span class="d-block text-muted text-uppercase text-xs font-weight-bold mb-2">End Date &
                                Remaining Time</span>

                            <div class="d-flex flex-wrap align-items-center justify-content-between mt-1">
                                <!-- End Date -->
                                <span class="text-dark font-weight-bold text-md mb-1 mb-sm-0">
                                    <i class="now-ui-icons ui-1_calendar-60 mr-1 text-primary"></i>
                                    {{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('Y-m-d') :
                                    'Not Specified' }}
                                </span>

                                <!-- Remaining Time Badge -->
                                <span
                                    class="badge badge-neutral text-primary border px-2 py-2 shadow-sm text-wrap text-left @if(isset($todayDate) && $todayDate->isSameDay($endDate)) text-white @endif"
                                    style="font-size: 80%; line-height: 1.4; @if(isset($todayDate) && $todayDate->isSameDay($endDate)) background-color: #6f42c1; border-color: #6f42c1 !important; @endif">
                                    <i class="now-ui-icons ui-2_time-alarm mr-1"></i>
                                    @php
                                    $remainingText = 'N/A';
                                    if ($project->status === 'completed') {
                                    $remainingText = 'Project Completed';
                                    } elseif ($endDate) {
                                    $todayDate = \Carbon\Carbon::today();

                                    if ($todayDate->isSameDay($endDate)) {
                                    $remainingText = 'Today is the last day';
                                    } elseif ($todayDate->greaterThan($endDate)) {
                                    $daysOverdue = $endDate->diffInDays($todayDate);
                                    $remainingText = 'OVERDUE BY ' . $daysOverdue . ' DAYS';
                                    } elseif ($startDate && $todayDate->lessThan($startDate)) {
                                    $daysRemaining = $startDate->diffInDays($endDate);
                                    $remainingText = $daysRemaining . ' Days Total (Not Started)';
                                    } else {
                                    $daysRemaining = $todayDate->diffInDays($endDate);
                                    $remainingText = $daysRemaining . ' Days Remaining';
                                    }
                                    }
                                    @endphp
                                    {{ $remainingText }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ASSOCIATED TEAMS SECTION -->
                <div class="card shadow-sm border-0 project-teams-card mb-4">
                    <div
                        class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="font-weight-bold text-dark mb-0">
                            <i class="now-ui-icons users_circle-08 text-primary mr-2"></i> Associated Teams
                        </h5>
                        <button type="button" class="btn btn-primary btn-round btn-sm px-3 shadow-sm"
                            data-toggle="modal" data-target="#projectTeamsModal">
                            <i class="now-ui-icons ui-1_zoom-bold mr-1"></i> View Teams
                        </button>
                    </div>
                </div>

                <!-- Modal for Associated Teams -->
                <div class="modal fade" id="projectTeamsModal" tabindex="-1" role="dialog"
                    aria-labelledby="projectTeamsModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title font-weight-bold text-white" id="projectTeamsModalLabel">
                                    <i class="now-ui-icons users_circle-08 mr-2"></i> Teams for: {{ $project->title }}
                                </h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body p-4">
                                @php
                                $currentUser = auth()->user();
                                $role = strtolower(trim($currentUser->role ?? ''));
                                $isTeamLeader = ($role === 'team_leader');
                                $isEmployee = ($role === 'employee');

                                // جلب الفرق المرتبطة بالمشروع مع تصفيتها بناءً على دور المستخدم (قائد فريق أو موظف)
                                $associatedTeams = isset($project->teams) ? $project->teams->when($isTeamLeader,
                                function($collection) use ($currentUser) {
                                return $collection->filter(function($team) use ($currentUser) {
                                return $team->team_leader_id === $currentUser->id;
                                });
                                })->when($isEmployee, function($collection) use ($currentUser) {
                                return $collection->filter(function($team) use ($currentUser) {
                                return $team->members()->where('users.id', $currentUser->id)->exists();
                                });
                                }) : collect();
                                @endphp

                                @if($associatedTeams->count() > 0)
                                <div class="list-group">
                                    @foreach($associatedTeams as $team)
                                    <div
                                        class="list-group-item list-group-item-action d-flex align-items-center justify-content-between border-0 mb-2 rounded bg-light shadow-sm">
                                        <div class="d-flex align-items-center">
                                            <div class="icon icon-shape icon-sm bg-primary text-white rounded-circle shadow-sm mr-3 d-flex align-items-center justify-content-center"
                                                style="width: 35px; height: 35px;">
                                                <i class="now-ui-icons business_badge"></i>
                                            </div>
                                            <div>
                                                <h6 class="font-weight-bold text-dark mb-0">{{ $team->name }}</h6>
                                                <small class="text-muted">Leader: {{ $team->leader->name ?? 'Not
                                                    Assigned' }}</small>
                                            </div>
                                        </div>
                                        <a href="{{ route('admin.team.show', $team->id) }}"
                                            class="btn btn-primary btn-round btn-sm px-3 shadow-sm">
                                            <i class="now-ui-icons design_image mr-1"></i> View Team
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <div class="text-center py-5">
                                    <i class="now-ui-icons objects_support-17 text-muted" style="font-size: 30px;"></i>
                                    <p class="text-muted mt-2 mb-0">No teams associated with this project yet.</p>
                                </div>
                                @endif
                            </div>
                            <div class="modal-footer bg-light">
                                <button type="button" class="btn btn-secondary btn-round btn-sm px-4"
                                    data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- MAIN TASKS MEMBERS TABLE CARD SECTION  -->
                <!-- ========================================== -->
                <div class="card shadow-sm border-0 project-tasks-card">
                    <div class="card-header bg-white py-3 px-4 border-bottom">
                        <h5 class="font-weight-bold text-dark mb-0">
                            <i class="now-ui-icons design_bullet-list-67 text-primary mr-2"></i> Associated Tasks
                        </h5>
                    </div>

                    <div class="row mx-0">
                        <div class="col-md-12 px-4">
                            <x-alert-message />
                            <div class="card shadow-sm border" style="border: 1px solid #dee2e6 !important;">
                                <div class="card-body px-0 pb-0">
                                    <div class="table-responsive" style="overflow-x: auto; width: 100%;">
                                        @php
                                        $user = auth()->user();
                                        $isEmployee = strtolower(trim($user->role ?? '')) === 'employee';
                                        @endphp

                                        <table class="table align-items-center table-flush mb-0 border" id="tasksTable"
                                            style="border: 1px solid #dee2e6; table-layout: fixed; width: 100%;">
                                            <thead
                                                style="background: linear-gradient(135deg, #f96332 0%, #ff8c42 100%); color: white;">
                                                <tr id="tableHeaders">
                                                    <th class="py-3 font-weight-bold text-white pl-4 draggable-header draggable-th text-center align-middle"
                                                        draggable="true" data-column="0"
                                                        style="cursor: grab; font-size: 13px; border: 1px solid rgba(255,255,255,0.2) !important; width: {{ $isEmployee ? '35%' : '25%' }}; white-space: nowrap;">
                                                        Task Title <i class="now-ui-icons arrows-1_move-horizontal ml-1"
                                                            style="font-size: 10px; opacity: 0.7;"></i>
                                                    </th>
                                                    <th class="py-3 font-weight-bold text-white pl-4 draggable-header draggable-th text-center align-middle"
                                                        draggable="true" data-column="1"
                                                        style="cursor: grab; font-size: 13px; border: 1px solid rgba(255,255,255,0.2) !important; width: {{ $isEmployee ? '25%' : '15%' }}; white-space: nowrap;">
                                                        Status <i class="now-ui-icons arrows-1_move-horizontal ml-1"
                                                            style="font-size: 10px; opacity: 0.7;"></i>
                                                    </th>
                                                    @if(!$isEmployee)
                                                    <th class="py-3 font-weight-bold text-white pl-4 draggable-header draggable-th text-center align-middle"
                                                        draggable="true" data-column="2"
                                                        style="cursor: grab; font-size: 13px; border: 1px solid rgba(255,255,255,0.2) !important; width: 20%; white-space: nowrap;">
                                                        Assigned Users <i
                                                            class="now-ui-icons arrows-1_move-horizontal ml-1"
                                                            style="font-size: 10px; opacity: 0.7;"></i>
                                                    </th>
                                                    @endif
                                                    <th class="py-3 font-weight-bold text-white pl-4 draggable-header draggable-th text-center align-middle"
                                                        draggable="true" data-column="3"
                                                        style="cursor: grab; font-size: 13px; border: 1px solid rgba(255,255,255,0.2) !important; width: {{ $isEmployee ? '40%' : '22%' }}; white-space: nowrap;">
                                                        Last Status Update Date <i
                                                            class="now-ui-icons arrows-1_move-horizontal ml-1"
                                                            style="font-size: 10px; opacity: 0.7;"></i>
                                                    </th>
                                                    @if(!$isEmployee)
                                                    <th class="py-3 font-weight-bold text-white pl-4 draggable-header draggable-th text-center align-middle"
                                                        draggable="true" data-column="4"
                                                        style="cursor: grab; font-size: 13px; border: 1px solid rgba(255,255,255,0.2) !important; width: 18%; white-space: nowrap;">
                                                        Actions <i class="now-ui-icons arrows-1_move-horizontal ml-1"
                                                            style="font-size: 10px; opacity: 0.7;"></i>
                                                    </th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody id="tableBody">
                                                {{-- إزالة شرط إخفاء الصفوف، لعرض كل مهام المشروع للموظف --}}
                                                @forelse($project->tasks ?? [] as $task)
                                                <tr class="border-bottom task-row">
                                                    <!-- Task Title Column -->
                                                    <td class="font-weight-bold text-dark px-2 align-middle border-right text-center"
                                                        data-column="0"
                                                        style="border: 1px solid #dee2e6 !important; word-break: break-word; white-space: normal; font-size: 14px;">
                                                        <div class="d-flex align-items-center justify-content-center">
                                                            <span>{{ $task->title ?? $task->name }}</span>
                                                        </div>
                                                    </td>

                                                    <!-- Status Column -->
                                                    <td class="text-muted px-2 align-middle team-desc border-right text-center"
                                                        data-column="1"
                                                        style="border: 1px solid #dee2e6 !important; word-break: break-word; font-size: 13px;">
                                                        @php
                                                        $rawStatus = strtolower($task->status ?? 'pending');

                                                        if (!in_array($rawStatus, ['completed', 'complete',
                                                        'rejected'])) {
                                                        if (isset($task->due_date)) {
                                                        $today = now()->toDateString();
                                                        $dueDate =
                                                        \Carbon\Carbon::parse($task->due_date)->toDateString();

                                                        if ($dueDate < $today) { $rawStatus='overdue' ; } elseif
                                                            ($dueDate===$today) { $rawStatus='due_today' ; } } }
                                                            $status=$rawStatus; $badgeClass=match ($status)
                                                            { 'completed' , 'complete'=> 'badge-success',
                                                            'in_progress' => 'badge-warning text-dark',
                                                            'pending' => 'badge-info',
                                                            'overdue', 'rejected' => 'badge-danger',
                                                            'due_today' => 'badge-purple text-white',
                                                            default => 'badge-secondary',
                                                            };

                                                            $bgColor = match ($status) {
                                                            'completed', 'complete' => '#28a745',
                                                            'in_progress' => '#ffc107',
                                                            'pending' => '#17a2b8',
                                                            'overdue', 'rejected' => '#dc3545',
                                                            'due_today' => '#6f42c1',
                                                            default => '#6c757d',
                                                            };
                                                            @endphp
                                                            <span
                                                                class="badge badge-pill px-2 py-1 font-weight-bold text-white {{ $badgeClass }}"
                                                                style="background-color: {{ $bgColor }}; font-size: 12px;">
                                                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                                                            </span>
                                                    </td>

                                                    <!-- Assigned Users Column (يختفي تماماً عن الموظف بناءً على طلبك) -->
                                                    @if(!$isEmployee)
                                                    <td class="align-middle border-right text-center px-2"
                                                        data-column="2"
                                                        style="border: 1px solid #dee2e6 !important; font-size: 13px;">
                                                        <a href="{{ isset($task->user_id) ? route('admin.user.show', $task->user_id) : '#' }}"
                                                            class="btn btn-primary btn-sm btn-icon shadow-sm rounded px-2"
                                                            title="View User"
                                                            style="height: 30px; font-size: 12px; display: inline-flex; align-items: center; justify-content: center; background-color: #1dc7ea; border-color: #1dc7ea; white-space: nowrap;">
                                                            <i class="now-ui-icons users_single-02"
                                                                style="font-size: 13px;"></i>
                                                        </a>
                                                    </td>
                                                    @endif

                                                    <!-- Last Status Update Date Column -->
                                                    <td class="align-middle border-right text-center px-2"
                                                        data-column="3"
                                                        style="border: 1px solid #dee2e6 !important; font-size: 13px;">
                                                        <span class="text-muted"
                                                            style="font-size: 13px; white-space: nowrap;">
                                                            <i class="now-ui-icons ui-1_calendar-60 mr-1"></i>
                                                            {{ $task->updated_at?->format('Y-m-d') ?? 'N/A' }}
                                                        </span>
                                                    </td>

                                                    <!-- Actions Column (يختفي تماماً عن الموظف) -->
                                                    @if(!$isEmployee)
                                                    <td class="text-center align-middle px-2" data-column="4"
                                                        style="border: 1px solid #dee2e6 !important; font-size: 13px;">
                                                        <div class="d-flex justify-content-center align-items-center"
                                                            role="group">
                                                            <a href="{{ route('admin.task.show', $task->id) }}"
                                                                class="btn btn-info btn-sm btn-icon shadow-sm mx-1 rounded"
                                                                title="View Task Details"
                                                                style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">
                                                                <i class="now-ui-icons design_image"
                                                                    style="font-size: 12px;"></i>
                                                            </a>

                                                            <form action="{{ route('admin.task.destroy', $task->id) }}"
                                                                method="POST" style="display: inline-block;"
                                                                id="delete-form-task-{{ $task->id }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button"
                                                                    class="btn btn-danger btn-sm btn-icon shadow-sm mx-1 rounded"
                                                                    title="Delete Task"
                                                                    style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;"
                                                                    onclick="confirmDelete('task', {{ $task->id }})">
                                                                    <i class="now-ui-icons ui-1_simple-remove"
                                                                        style="font-size: 12px;"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                    @endif
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="{{ $isEmployee ? '3' : '5' }}"
                                                        class="text-center text-muted py-5"
                                                        style="border: 1px solid #dee2e6 !important;">
                                                        <div class="py-4">
                                                            <i class="now-ui-icons design_bullet-list-67 fa-3x mb-3 text-muted"
                                                                style="font-size: 28px;"></i>
                                                            <p class="font-weight-bold mb-1" style="font-size: 15px;">No
                                                                associated tasks found.
                                                            </p>
                                                            <p class="text-sm text-muted" style="font-size: 13px;">Click
                                                                "Add Task In Task Section" to assign one
                                                                to this project.</p>
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
                </div>

            </div>
        </div>

        @endcan

        @endsection
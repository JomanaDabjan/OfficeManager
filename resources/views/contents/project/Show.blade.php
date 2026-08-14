@extends('layouts.app')

{{-- Set the dynamic title for this specific page --}}
@section('title', 'Show Project Details')

@section('Main_Content')

<!-- ========================================================================= -->
<!-- MAIN PROJECT SHOW WRAPPER SECTION                                         -->
<!-- ========================================================================= -->

<div class="row justify-content-center">
    <div class="col-lg-10 col-md-12">

        <!-- Include Session Alert Message Component for Feedback -->
        <x-alert-message />

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
                <div>
                    <a href="{{ route('admin.project.index') }}"
                        class="btn btn-neutral btn-round btn-sm px-3 shadow-sm mr-2">
                        <i class="now-ui-icons arrows-1_minimal-left"></i> Back
                    </a>

                    @if(Auth::user()->role !== 'employee')
                    <a href="{{ route('admin.project.edit', $project->id) }}"
                        class="btn btn-primary btn-round btn-sm px-3 shadow-sm">
                        <i class="now-ui-icons ui-2_settings-90"></i> Edit Project
                    </a>
                    @endif
                </div>
            </div>

            <!-- CARD BODY DETAILS SECTION -->
            <div class="card-body px-5 py-4">
                <div class="row">

                    <!-- Project Status Information Box -->
                    <div class="col-md-4 mb-3">
                        <div class="p-3 bg-light rounded shadow-sm h-100">
                            <span class="d-block text-muted text-uppercase text-xs font-weight-bold mb-1">Project
                                Status</span>

                            @php
                            $today = \Carbon\Carbon::today();
                            $endDate = $project->end_date ? \Carbon\Carbon::parse($project->end_date) : null;
                            $startDate = $project->start_date ? \Carbon\Carbon::parse($project->start_date) : null;

                            $tasks = $project->tasks;
                            $hasTasks = $tasks->count() > 0;

                            // Check if all tasks are completed
                            $allTasksCompleted = $hasTasks ? $tasks->every(function($task) {
                            return strtolower(trim($task->status)) === 'complete';
                            }) : true;

                            $currentStatus = $project->status;

                            // Logic to change project status to overdue if time has passed and tasks remain incomplete
                            if ($endDate && $today->greaterThan($endDate)) {
                            if (strtolower($currentStatus) !== 'complete' && !$allTasksCompleted) {
                            $currentStatus = 'overdue';
                            }
                            }

                            // Dynamic status class based on database status values
                            $statusClass = match(strtolower($currentStatus)) {
                            'completed', 'complete' => 'badge-success',
                            'in_progress' => 'badge-info',
                            'pending' => 'badge-warning',
                            'overdue', 'rejected' => 'badge-danger',
                            default => 'badge-secondary',
                            };
                            @endphp

                            <span class="badge {{ $statusClass }} px-3 py-2 text-uppercase font-weight-bold">
                                {{ str_replace('_', ' ', $currentStatus) }}
                            </span>
                        </div>
                    </div>

                    <!-- Assigned Manager Information Box -->
                    <div class="col-md-4 mb-3">
                        <div class="p-3 bg-light rounded shadow-sm h-100">
                            <span class="d-block text-muted text-uppercase text-xs font-weight-bold mb-1">Assigned
                                Manager</span>
                            <span class="text-dark font-weight-bold text-md">
                                <i class="now-ui-icons users_circle-08 mr-1 text-primary"></i>
                                {{ $project->manager->name ?? 'Not Assigned' }}
                            </span>
                        </div>
                    </div>

                    <!-- Creation Date Information Box -->
                    <div class="col-md-4 mb-3">
                        <div class="p-3 bg-light rounded shadow-sm h-100">
                            <span class="d-block text-muted text-uppercase text-xs font-weight-bold mb-1">Created
                                At</span>
                            <span class="text-dark font-weight-bold text-md">
                                <i class="now-ui-icons ui-1_calendar-60 mr-1 text-primary"></i>
                                {{ $project->created_at ? $project->created_at->format('Y-m-d') : 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- PROJECT TIMELINE SUB-SECTION (START & END WITH CONSUMED TIME) -->
                <div class="row">
                    <!-- Start Date Box -->
                    <div class="col-md-6 mb-3">
                        <div class="p-3 bg-light rounded shadow-sm h-100">
                            <span class="d-block text-muted text-uppercase text-xs font-weight-bold mb-1">Start
                                Date</span>
                            <span class="text-dark font-weight-bold text-md">
                                <i class="now-ui-icons ui-1_calendar-60 mr-1 text-primary"></i>
                                {{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('Y-m-d') :
                                'Not Specified' }}
                            </span>
                        </div>
                    </div>

                    <!-- End Date & Consumed Time Box -->
                    <div class="col-md-6 mb-3">
                        <div class="p-3 bg-light rounded shadow-sm h-100">
                            <span class="d-block text-muted text-uppercase text-xs font-weight-bold mb-1">End Date &
                                Consumed Time</span>
                            <span
                                class="text-dark font-weight-bold text-md d-flex justify-content-between align-items-center">
                                <span>
                                    <i class="now-ui-icons ui-1_calendar-60 mr-1 text-primary"></i>
                                    {{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('Y-m-d') :
                                    'Not Specified' }}
                                </span>
                                <span class="badge badge-neutral text-primary border px-2 py-1 shadow-sm"
                                    style="font-size: 85%;">
                                    <i class="now-ui-icons ui-2_time-alarm mr-1"></i>
                                    @php
                                    $consumedText = 'N/A';
                                    if ($startDate && $endDate) {
                                    $totalDays = $startDate->diffInDays($endDate);

                                    if ($today->greaterThan($endDate)) {
                                    $consumedText = $totalDays . ' Days Elapsed / ' . $totalDays . ' Total Days
                                    (Ended)';
                                    } elseif ($today->lessThan($startDate)) {
                                    $consumedText = '0 Days Elapsed / ' . $totalDays . ' Total Days (Not Started)';
                                    } else {
                                    $daysPassed = $startDate->diffInDays($today);
                                    $consumedText = $daysPassed . ' Days Elapsed / ' . $totalDays . ' Total Days';
                                    }
                                    } elseif ($startDate) {
                                    $daysPassed = $startDate->diffInDays($today);
                                    $consumedText = $daysPassed . ' Days Elapsed / N/A Total Days';
                                    }
                                    @endphp
                                    {{ $consumedText }}
                                </span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- PROJECT DESCRIPTION SUB-SECTION -->
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-control-label font-weight-bold text-dark">Project Description &
                                Objectives</label>
                            <div class="p-3 bg-light rounded text-muted shadow-sm"
                                style="min-height: 100px; line-height: 1.6;">
                                {!! nl2br(e($project->description)) ?: 'No description provided for this project.' !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ASSOCIATED TASKS SECTION -->
        <div class="card shadow-sm border-0 project-tasks-card">
            <div class="card-header bg-white py-3 px-4 border-bottom">
                <h5 class="font-weight-bold text-dark mb-0">
                    <i class="now-ui-icons design_bullet-list-67 text-primary mr-2"></i> Associated Tasks
                </h5>
            </div>

            <div class="card-body px-4 py-3">
                @if(isset($project->tasks) && $project->tasks->count() > 0)
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Task Title</th>
                                <th scope="col">Status</th>
                                <th scope="col">Assigned Users</th>
                                <th scope="col">Last Status Update Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($project->tasks as $task)
                            <tr>
                                <td class="font-weight-bold text-dark">{{ $task->title }}</td>
                                <td class="align-middle project-status">
                                    @php
                                    $taskStatus = strtolower($task->status);
                                    @endphp
                                    <span class="badge badge-pill
                                        @if($taskStatus == 'complete' || $taskStatus == 'completed') badge-success
                                        @elseif($taskStatus == 'in_progress') badge-info
                                        @elseif($taskStatus == 'pending') badge-warning
                                        @else badge-secondary @endif px-3 py-2 text-white shadow-sm font-weight-bold">
                                        {{ ucfirst(str_replace('_', ' ', $task->status ?? 'in_progress')) }}
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-round btn-sm px-3 shadow-sm"
                                        data-toggle="modal" data-target="#assignedUsersModal-{{ $task->id }}">
                                        <i class="now-ui-icons users_single-02 mr-1"></i> View Users
                                    </button>
                                </td>
                                <td>
                                    <span class="text-muted">
                                        <i class="now-ui-icons ui-1_calendar-60 mr-1 text-primary"></i>
                                        {{ $task->updated_at ? $task->updated_at->format('Y-m-d H:i') :
                                        ($task->created_at ? $task->created_at->format('Y-m-d H:i') : 'N/A') }}
                                    </span>
                                </td>
                            </tr>

                            <!-- Modal for Assigned Users -->
                            <div class="modal fade" id="assignedUsersModal-{{ $task->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="assignedUsersModalLabel-{{ $task->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content border-0 shadow-lg">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title font-weight-bold text-white"
                                                id="assignedUsersModalLabel-{{ $task->id }}">
                                                <i class="now-ui-icons users_single-02 mr-2"></i> Users for: {{
                                                $task->title }}
                                            </h5>
                                            <button type="button" class="close text-white" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body p-4">
                                            @php
                                            $assignedUsers = collect();
                                            if (isset($task->assignedUsers) && $task->assignedUsers->count() > 0) {
                                            $assignedUsers = $task->assignedUsers;
                                            } elseif (isset($task->user) && $task->user) {
                                            $assignedUsers = collect([$task->user]);
                                            }
                                            @endphp

                                            @if($assignedUsers->count() > 0)
                                            <div class="list-group">
                                                @foreach($assignedUsers as $assignedUser)
                                                <div
                                                    class="list-group-item list-group-item-action d-flex align-items-center border-0 mb-2 rounded bg-light shadow-sm">
                                                    <div class="icon icon-shape icon-sm bg-primary text-white rounded-circle shadow-sm mr-3 d-flex align-items-center justify-content-center"
                                                        style="width: 35px; height: 35px;">
                                                        <i class="now-ui-icons users_circle-08"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="font-weight-bold text-dark mb-0">{{
                                                            $assignedUser->name }}</h6>
                                                        <small class="text-muted">{{ $assignedUser->email ?? 'No email
                                                            provided' }}</small>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                            @else
                                            <div class="text-center py-4">
                                                <i class="now-ui-icons objects_support-17 text-muted"
                                                    style="font-size: 30px;"></i>
                                                <p class="text-muted mt-2 mb-0">No users assigned to this task yet.</p>
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
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-muted text-center py-3 mb-0">No tasks found associated with this project.</p>
                @endif
            </div>
        </div>

    </div>
</div>

@endsection

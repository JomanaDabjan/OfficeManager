@extends('layouts.app') {{-- Extends the main admin dashboard layout --}}

@section('Main_Content')
<div class="content mt-3">
    <div class="row">
        <div class="col-md-12">

            <!-- ======================================================= -->
            <!-- MAIN CARD CONTAINER START                               -->
            <!-- ======================================================= -->
            <div class="card">

                <!-- Card Header with Title and Back Button -->
                <div class="card-header bg-primary text-white p-4 rounded-top d-flex justify-content-between align-items-center"
                    style="background: linear-gradient(0deg, #f96332 0%, #ff8559 100%) !important;">
                    <div>
                        <h3 class="card-title text-white mb-0"><strong>{{ $task->title }}</strong></h3>
                        <p class="card-category text-white mb-0"> Detailed view and system
                            overview of the task.</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.task.index') }}"
                            class="btn btn-neutral btn-round text-primary font-weight-bold">
                            <i class="now-ui-icons arrows-1_minimal-left"></i> Back
                        </a>
                    </div>
                </div>

                <!-- Card Body Containing Task Information -->
                <div class="card-body p-4">

                    <!-- =================================================== -->
                    <!-- SECTION 1: METRICS & INFO CARDS GRID                -->
                    <!-- =================================================== -->
                    <div class="row">
                        <!-- Task Status Card -->
                        <div class="col-md-4 mb-4">
                            <div class="card card-stats card-raised h-100 mb-0 border shadow-sm">
                                <div class="card-body">
                                    <p class="text-uppercase text-muted font-weight-bold mb-1" style="font-size: 11px;">
                                        Task Status</p>
                                    <div class="mt-2">
                                        @if($task->status == 'pending')
                                        <span class="badge badge-warning p-2 px-3">Pending</span>
                                        @elseif($task->status == 'in_progress')
                                        <span class="badge badge-info p-2 px-3">In Progress</span>
                                        @elseif($task->status == 'completed')
                                        <span class="badge badge-success p-2 px-3">Completed</span>
                                        @elseif($task->status == 'accepted')
                                        <span class="badge badge-success p-2 px-3">Accepted</span>
                                        @elseif($task->status == 'rejected')
                                        <span class="badge badge-danger p-2 px-3">Rejected</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Associated Project Card (Project Name Displayed Directly) -->
                        <div class="col-md-4 mb-4">
                            <div class="card card-stats card-raised h-100 mb-0 border shadow-sm">
                                <div class="card-body">
                                    <p class="text-uppercase text-muted font-weight-bold mb-1" style="font-size: 11px;">
                                        Associated Project : Check In</p>
                                    <div class="mt-2">
                                        @if($task->project)
                                        <a href="{{ route('admin.project.show', $task->project->id) }}"
                                            class="btn btn-primary btn-round btn-sm w-100 text-white font-weight-bold d-flex align-items-center justify-content-center text-truncate px-3"
                                            style="background: linear-gradient(0deg, #f96332 0%, #ff8559 100%) !important; border: none; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"
                                            title="{{ $task->project->title }}">
                                            <span class="text-truncate">{{ $task->project->title }}</span>
                                        </a>
                                        @else
                                        <span class="text-muted font-weight-bold">No Project Assigned</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Assigned Employee Card -->
                        <div class="col-md-4 mb-4">
                            <div class="card card-stats card-raised h-100 mb-0 border shadow-sm">
                                <div class="card-body">
                                    <p class="text-uppercase text-muted font-weight-bold mb-1" style="font-size: 11px;">
                                        Assigned To</p>
                                    <h6 class="card-title font-weight-bold text-dark mt-2 mb-0">
                                        <i class="now-ui-icons users_single-02 text-primary mr-1"></i>
                                        {{ $task->assignedUser->name ?? ($task->user->name ?? 'Unassigned') }}
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECOND ROW: DATES & HOURS -->
                    <div class="row">
                        <!-- Start Date Card -->
                        <div class="col-md-4 mb-4">
                            <div class="card card-stats card-raised h-100 mb-0 border shadow-sm">
                                <div class="card-body">
                                    <p class="text-uppercase text-muted font-weight-bold mb-1" style="font-size: 11px;">
                                        Start Date</p>
                                    <h6 class="card-title font-weight-bold text-dark mt-2 mb-0">
                                        <i class="now-ui-icons ui-1_calendar-60 text-primary mr-1"></i>
                                        {{ $task->started_at ? \Carbon\Carbon::parse($task->started_at)->format('Y-m-d')
                                        : ($task->start_date ? \Carbon\Carbon::parse($task->start_date)->format('Y-m-d')
                                        : 'Not Started Yet') }}
                                    </h6>
                                </div>
                            </div>
                        </div>

                        <!-- End Date Card -->
                        <div class="col-md-4 mb-4">
                            <div class="card card-stats card-raised h-100 mb-0 border shadow-sm">
                                <div class="card-body">
                                    <p class="text-uppercase text-muted font-weight-bold mb-1" style="font-size: 11px;">
                                        End Date</p>
                                    <h6 class="card-title font-weight-bold text-dark mt-2 mb-0">
                                        <i class="now-ui-icons ui-1_calendar-60 text-danger mr-1"></i>
                                        {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') :
                                        'No Deadline' }}
                                    </h6>
                                </div>
                            </div>
                        </div>

                        <!-- Total Duration Card (Countdown Days Remaining) -->
                        <div class="col-md-4 mb-4">
                            <div class="card card-stats card-raised h-100 mb-0 border shadow-sm">
                                <div class="card-body">
                                    <!-- Card Title Label with FontAwesome Clock Icon -->
                                    <p class="text-uppercase text-muted font-weight-bold mb-1" style="font-size: 11px;">
                                        <i class="fas fa-clock text-primary mr-1" style="font-size: 12px;"></i> Days
                                        Remaining
                                    </p>
                                    <h6 class="card-title font-weight-bold text-primary mt-2 mb-0"
                                        style="font-size: 14px;">
                                        <span>
                                            @php
                                            $startDate = isset($task) ? ($task->start_date ?? null) :
                                            ($project->start_date ?? null);
                                            $targetDate = isset($task) ? ($task->due_date ?? null) : ($project->end_date
                                            ?? null);
                                            @endphp

                                            @if(!$targetDate)
                                            No Deadline
                                            @else
                                            @php
                                            $today = \Carbon\Carbon::today();
                                            $start = $startDate ? \Carbon\Carbon::parse($startDate) : null;
                                            $due = \Carbon\Carbon::parse($targetDate);

                                            if ($start) {
                                            $diff = round($start->floatDiffInDays($due, false));
                                            } else {
                                            $diff = round($today->floatDiffInDays($due, false));
                                            }
                                            @endphp

                                            @if($diff > 1)
                                            {{ $diff }} DAYS REMAINING
                                            @elseif($diff === 1)
                                            1 DAY REMAINING
                                            @elseif($diff === 0)
                                            DUE TODAY
                                            @else
                                            OVERDUE BY {{ abs($diff) }} DAYS
                                            @endif

                                            @if($start && $today->lt($start))
                                            <span class="text-danger" style="font-size: 12px;">(Not Started)</span>
                                            @endif
                                            @endif
                                        </span>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END OF METRICS & INFO CARDS -->

                    <!-- =================================================== -->
                    <!-- SECTION 2: DESCRIPTION & REJECTION REASON           -->
                    <!-- =================================================== -->
                    <div class="row mt-2">
                        <!-- Task Description Box -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-uppercase text-muted font-weight-bold"
                                    style="font-size: 11px;"><strong>Task Description & Objectives</strong></label>
                                <div class="p-3 border rounded bg-light text-dark" style="min-height: 100px;">
                                    {!! nl2br(e($task->description)) ?? 'No description provided.' !!}
                                </div>
                            </div>
                        </div>

                        <!-- Rejection Reason Box -->
                        @if($task->status === 'rejected' && $task->rejection_reason)
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-uppercase text-danger font-weight-bold"
                                    style="font-size: 11px;"><strong>Rejection Reason</strong></label>
                                <div class="p-3 border border-danger rounded bg-light text-danger"
                                    style="min-height: 100px;">
                                    {{ $task->rejection_reason }}
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <!-- END OF DESCRIPTION SECTION -->

                </div>
                <!-- END OF CARD BODY -->

            </div>
            <!-- END OF MAIN CARD CONTAINER -->

        </div>
    </div>
</div>
@endsection

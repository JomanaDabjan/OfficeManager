@extends('layouts.app')

@section('Main_Content')

<!-- ========================================================================= -->
<!-- MAIN PROJECT SHOW WRAPPER SECTION                                         -->
<!-- ========================================================================= -->

<!-- Main Row Container Centered -->
<div class="row justify-content-center">
    <div class="col-lg-10 col-md-12">

        <!-- Include Session Alert Message Component for Feedback -->
        <x-alert-message />

        <!-- ===================================================== -->
        <!-- MAIN PROJECT OVERVIEW CARD CONTAINER                  -->
        <!-- ===================================================== -->
        <div class="card shadow-sm border-0 project-show-card mb-4">

            <!-- ================================================= -->
            <!-- CARD HEADER WITH GRADIENT AND ACTION BUTTONS      -->
            <!-- ================================================= -->
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
                    <!-- Back button routing to project index listing -->
                    <a href="{{ route('admin.project.index') }}"
                        class="btn btn-neutral btn-round btn-sm px-3 shadow-sm mr-2">
                        <i class="now-ui-icons arrows-1_minimal-left"></i> Back
                    </a>

                    <!-- Restrict edit button access for employee role -->
                    @if(Auth::user()->role !== 'employee')
                    <a href="{{ route('admin.project.edit', $project->id) }}"
                        class="btn btn-primary btn-round btn-sm px-3 shadow-sm">
                        <i class="now-ui-icons ui-2_settings-90"></i> Edit Project
                    </a>
                    @endif
                </div>
            </div>

            <!-- ================================================= -->
            <!-- CARD BODY DETAILS SECTION                         -->
            <!-- ================================================= -->
            <div class="card-body px-5 py-4">
                <div class="row">

                    <!-- Project Status Information Box -->
                    <div class="col-md-4 mb-3">
                        <div class="p-3 bg-light rounded shadow-sm h-100">
                            <span class="d-block text-muted text-uppercase text-xs font-weight-bold mb-1">Project
                                Status</span>

                            <!-- Determine status badge color scheme dynamically -->
                            @php
                            $statusClass = match($project->status) {
                            'completed' => 'badge-success',
                            'in_progress' => 'badge-info',
                            default => 'badge-warning',
                            };
                            @endphp

                            <!-- Render formatted status badge -->
                            <span class="badge {{ $statusClass }} px-3 py-2 text-uppercase font-weight-bold">
                                {{ str_replace('_', ' ', $project->status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Assigned Manager Information Box -->
                    <div class="col-md-4 mb-3">
                        <div class="p-3 bg-light rounded shadow-sm h-100">
                            <span class="d-block text-muted text-uppercase text-xs font-weight-bold mb-1">Assigned
                                Manager</span>

                            <!-- Display manager name or fallback text -->
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

                            <!-- Format and display creation timestamp -->
                            <span class="text-dark font-weight-bold text-md">
                                <i class="now-ui-icons ui-1_calendar-60 mr-1 text-primary"></i>
                                {{ $project->created_at ? $project->created_at->format('Y-m-d') : 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- ============================================= -->
                <!-- PROJECT DESCRIPTION SUB-SECTION               -->
                <!-- ============================================= -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-control-label font-weight-bold text-dark">Project Description &
                                Objectives</label>

                            <!-- Box displaying sanitized long text description with line breaks -->
                            <div class="p-3 bg-light rounded text-muted shadow-sm"
                                style="min-height: 100px; line-height: 1.6;">
                                {!! nl2br(e($project->description)) ?: 'No description provided for this project.' !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- ASSOCIATED TASKS SECTION                                                  -->
        <!-- ========================================================================= -->
        <div class="card shadow-sm border-0 project-tasks-card">

            <!-- Tasks Card Header -->
            <div class="card-header bg-white py-3 px-4 border-bottom">
                <h5 class="font-weight-bold text-dark mb-0">
                    <i class="now-ui-icons design_bullet-list-67 text-primary mr-2"></i> Associated Tasks
                </h5>
            </div>

            <!-- Tasks Card Body Content -->
            <div class="card-body px-4 py-3">
                <!-- Check if tasks relation has items -->
                @if(isset($project->tasks) && $project->tasks->count() > 0)
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Task Title</th>
                                <th scope="col">Status</th>
                                <th scope="col">Due Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Loop through each associated task record -->
                            @foreach($project->tasks as $task)
                            <tr>
                                <td class="font-weight-bold text-dark">{{ $task->title }}</td>
                                <td>
                                    <span class="badge badge-pill badge-neutral font-weight-bold">
                                        {{ ucfirst($task->status) }}
                                    </span>
                                </td>
                                <td>{{ $task->due_date ?? 'N/A' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <!-- Fallback text when no tasks exist -->
                <p class="text-muted text-center py-3 mb-0">No tasks found associated with this project.</p>
                @endif
            </div>
        </div>

    </div>
</div>

@endsection

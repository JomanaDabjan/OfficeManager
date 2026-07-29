@extends('layouts.app')

@section('Main_Content')

<!-- ========================================== -->
<!-- PAGE HEADER AND CREATION ACTION BUTTON     -->
<!-- ========================================== -->
<div class="row mt-4 mb-4 align-items-center">
    <div class="col-md-6">
        <h3 class="font-weight-bold text-dark mb-0">Tasks Management</h3>
        <p class="text-muted text-sm mb-0">Manage all system tasks, assign team members, and track statuses.</p>
    </div>
    <div class="col-md-6 text-right">
        <!-- Render the 'Add New Task' button only for administrators and managers -->
        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'manager')
        <a href="{{ route('admin.task.create') }}" class="btn btn-primary btn-round text-white shadow-sm px-4">
            <i class="now-ui-icons ui-1_simple-add"></i> Add New Task
        </a>
        @endif
    </div>
</div>

<!-- ========================================== -->
<!-- LIVE SEARCH INPUT CONTAINER                -->
<!-- ========================================== -->
<div class="row mb-3">
    <div class="col-md-12">
        <div class="search-container">
            <i class="now-ui-icons ui-1_zoom-bold search-icon"></i>
            <input type="text" id="taskSearchInput" class="form-control border rounded-pill shadow-sm"
                placeholder="Search tasks..." style="background-color: #f9fbfd;">
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MAIN TASKS TABLE CARD SECTION              -->
<!-- ========================================== -->
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-body px-0 pb-0">
                <div class="table-responsive">
                    <table class="table align-items-center table-flush mb-0" id="tasksTable">
                        <!-- Table Headings with Gradient Style -->
                        <thead style="background: linear-gradient(135deg, #f96332 0%, #ff8c42 100%); color: white;">
                            <tr>
                                <th class="py-3 font-weight-bold text-white pl-4">Title</th>
                                <th class="py-3 font-weight-bold text-white">Description</th>
                                <th class="py-3 font-weight-bold text-white">Project</th>

                                <!-- Conditionally hide 'Assigned To' column if the authenticated user is an employee -->
                                @if(auth()->user()->role !== 'employee')
                                <th class="py-3 font-weight-bold text-white">Assigned To</th>
                                @endif

                                <th class="py-3 font-weight-bold text-white">Status</th>
                                <th class="py-3 font-weight-bold text-white">Attachments</th>
                                <th class="py-3 font-weight-bold text-white text-right pr-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Loop through each task item using Laravel forelse directive -->
                            @forelse($tasks as $task)
                            <tr class="border-bottom task-row">
                                <!-- Task Title Column -->
                                <td class="font-weight-bold text-dark pl-4 align-middle task-title">
                                    {{ $task->title }}
                                </td>

                                <!-- Task Description Column (Truncated for clean view) -->
                                <td class="text-muted align-middle task-desc">
                                    {{ Str::limit($task->description, 45) }}
                                </td>

                                <!-- Associated Project Column -->
                                <td class="text-muted align-middle task-project">
                                    {{ optional($task->project)->title ?? 'N/A' }}
                                </td>

                                <!-- Conditionally display Assigned User cell for non-employee roles -->
                                @if(auth()->user()->role !== 'employee')
                                <td class="align-middle task-user">
                                    <div class="d-flex align-items-center">
                                        <span
                                            class="avatar-sm rounded-circle bg-light text-primary font-weight-bold d-flex align-items-center justify-content-center shadow-sm mr-2"
                                            style="width: 32px; height: 32px; font-size: 12px;">
                                            {{ strtoupper(substr(optional($task->assignedUser)->name ?? 'U', 0, 2)) }}
                                        </span>
                                        <span class="text-dark font-weight-normal">
                                            {{ optional($task->assignedUser)->name ?? 'Unassigned' }}
                                        </span>
                                    </div>
                                </td>
                                @endif

                                <!-- Task Status Column with Dynamic Badges -->
                                <td class="align-middle task-status">
                                    <span class="badge badge-pill
                                        @if($task->status == 'completed') badge-success
                                        @elseif($task->status == 'accepted' || $task->status == 'in_progress') badge-warning
                                        @elseif($task->status == 'rejected') badge-danger
                                        @else badge-secondary @endif px-3 py-2 text-white shadow-sm">
                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                    </span>
                                    <!-- Display rejection reason tooltip or subtext if status is rejected -->
                                    @if($task->status == 'rejected' && $task->rejection_reason)
                                    <div class="text-xs text-danger mt-1" title="{{ $task->rejection_reason }}">
                                        Reason: {{ Str::limit($task->rejection_reason, 25) }}
                                    </div>
                                    @endif
                                </td>

                                <!-- ========================================== -->
                                <!-- ATTACHMENTS COLUMN & MODAL INTEGRATION     -->
                                <!-- ========================================== -->
                                <td class="align-middle">
                                    @php
                                    // Decode JSON attachment column into a PHP array
                                    $attachments = json_decode($task->attachment, true);
                                    $filesCount = (!empty($attachments) && is_array($attachments)) ? count($attachments)
                                    : 0;
                                    @endphp

                                    @if($filesCount > 0)
                                    <!-- Button to trigger the unique modal for this task's files -->
                                    <button type="button"
                                        class="btn btn-sm btn-outline-primary btn-round px-3 py-1 d-inline-flex align-items-center shadow-sm"
                                        data-toggle="modal" data-target="#attachmentsModal{{ $task->id }}">
                                        <i class="now-ui-icons files_single-copy-04 mr-1"></i>
                                        <span>View Files ({{ $filesCount }})</span>
                                    </button>

                                    <!-- Clean and responsive modal displaying file listings -->
                                    <div class="modal fade text-left" id="attachmentsModal{{ $task->id }}" tabindex="-1"
                                        role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content border-0 shadow-lg"
                                                style="border-radius: 12px; overflow: hidden;">
                                                <div class="modal-header bg-light border-bottom-0 py-3 px-4">
                                                    <h5 class="modal-title font-weight-bold text-dark">
                                                        <i
                                                            class="now-ui-icons files_single-copy-04 text-primary mr-2"></i>
                                                        Task Attachments
                                                        <span class="badge badge-primary badge-pill ml-2"
                                                            style="font-size: 11px;">{{ $filesCount }}</span>
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body px-4 py-3"
                                                    style="max-height: 350px; overflow-y: auto;">
                                                    <p class="text-muted text-sm mb-3">List of attached files for:
                                                        <strong>{{ $task->title }}</strong></p>

                                                    <div class="list-group">
                                                        <!-- Iterate through each file inside the decoded array -->
                                                        @foreach($attachments as $index => $file)
                                                        @php
                                                        $fileName = basename($file);
                                                        $fileExtension = pathinfo($file, PATHINFO_EXTENSION);
                                                        @endphp
                                                        <div class="list-group-item list-group-item-action d-flex align-items-center justify-content-between border rounded mb-2 py-2 px-3 shadow-sm"
                                                            style="border-color: #eee !important;">
                                                            <div class="d-flex align-items-center overflow-hidden mr-3">
                                                                <div class="icon-shape bg-light text-primary rounded-circle p-2 mr-3 d-flex align-items-center justify-content-center"
                                                                    style="width: 38px; height: 38px; flex-shrink: 0;">
                                                                    <i
                                                                        class="now-ui-icons files_paper text-primary"></i>
                                                                </div>
                                                                <div class="text-truncate">
                                                                    <h6 class="mb-0 text-dark text-sm font-weight-bold text-truncate"
                                                                        title="{{ $fileName }}">
                                                                        File_{{ $index + 1 }}.{{ $fileExtension }}
                                                                    </h6>
                                                                    <small class="text-muted text-xs">Click download to
                                                                        view file</small>
                                                                </div>
                                                            </div>
                                                            <!-- Secure direct link to storage asset -->
                                                            <a href="{{ asset('storage/' . $file) }}" target="_blank"
                                                                class="btn btn-sm btn-primary btn-round px-3 py-1 flex-shrink-0">
                                                                <i
                                                                    class="now-ui-icons arrows-1_cloud-download-93 mr-1"></i>
                                                                Download
                                                            </a>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <div class="modal-footer bg-light border-top-0 py-3 px-4">
                                                    <button type="button" class="btn btn-secondary btn-round px-4 py-2"
                                                        data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                    <!-- Fallback text when no attachments exist -->
                                    <span class="text-muted font-italic text-sm">No attachments</span>
                                    @endif
                                </td>

                                <!-- ========================================== -->
                                <!-- ACTIONS COLUMN (ROLE-BASED PERMISSIONS)    -->
                                <!-- ========================================== -->
                                <td class="text-right pr-4 align-middle">
                                    @if(auth()->user()->role === 'employee' && $task->user_id === auth()->id())
                                    <!-- Employee Actions: Accept or Reject pending tasks -->
                                    @if($task->status === 'pending')
                                    <!-- Accept Form -->
                                    <form action="{{ route('employee.task.accept', $task->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success btn-round"
                                            title="Accept Task">
                                            Accept
                                        </button>
                                    </form>

                                    <!-- Reject Trigger Button -->
                                    <button type="button" class="btn btn-sm btn-danger btn-round" data-toggle="modal"
                                        data-target="#rejectModal{{ $task->id }}">
                                        Reject
                                    </button>

                                    <!-- Rejection Reason Modal Form -->
                                    <div class="modal fade text-left" id="rejectModal{{ $task->id }}" tabindex="-1"
                                        role="dialog" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form action="{{ route('employee.task.reject', $task->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title font-weight-bold">Reason for Rejection
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label class="form-control-label">Please explain why you are
                                                                rejecting this task:</label>
                                                            <textarea name="rejection_reason" class="form-control"
                                                                rows="3" required
                                                                placeholder="Enter rejection reason..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary btn-round"
                                                            data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger btn-round">Confirm
                                                            Rejection</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                    <span class="text-muted text-sm">No Actions</span>
                                    @endif

                                    @elseif(auth()->user()->role === 'admin' || auth()->user()->role === 'manager')
                                    <!-- Administrative Management Actions (Edit / Delete) -->
                                    <a href="{{ route('admin.task.edit', $task->id) }}"
                                        class="btn btn-success btn-round btn-icon btn-sm" title="Edit">
                                        <i class="now-ui-icons ui-2_settings-90"></i>
                                    </a>

                                    <form action="{{ route('admin.task.destroy', $task->id) }}" method="POST"
                                        style="display: inline-block;"
                                        onsubmit="return confirm('Are you sure you want to delete this task?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-round btn-icon btn-sm"
                                            title="Delete">
                                            <i class="now-ui-icons ui-1_simple-remove"></i>
                                        </button>
                                    </form>
                                    @else
                                    <span class="text-muted text-sm">View Only</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <!-- Empty State Row when no tasks match query criteria -->
                            <tr id="noTasksDefault">
                                <td colspan="{{ auth()->user()->role === 'employee' ? '6' : '7' }}"
                                    class="text-center text-muted py-5">
                                    <div class="py-4">
                                        <i class="now-ui-icons design_bullet-list-67 fa-3x mb-3 text-muted"
                                            style="font-size: 28px;"></i>
                                        <p class="font-weight-bold mb-1">No tasks found.</p>
                                        <p class="text-sm text-muted">Click "Add New Task" to create one.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- ========================================== -->
                <!-- PAGINATION CONTROLS SECTION                -->
                <!-- ========================================== -->
                @if($tasks->hasPages())
                <div class="card-footer bg-white py-4 d-flex justify-content-between align-items-center">
                    <div class="text-muted text-sm">
                        Showing <b>{{ $tasks->firstItem() }}</b> to <b>{{ $tasks->lastItem() }}</b> of <b>{{
                            $tasks->total() }}</b> entries
                    </div>
                    <div>
                        {{ $tasks->links('pagination::bootstrap-4') }}
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

@endsection

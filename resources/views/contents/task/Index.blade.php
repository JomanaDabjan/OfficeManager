@extends('layouts.app')

@section('Main_Content')

<!-- ========================================== -->
<!-- PAGE HEADER AND CREATION ACTION BUTTON     -->
<!-- ========================================== -->
<div class="row mt-4 mb-4 align-items-center">
    <div class="col-md-6">
        <h3 class="font-weight-bold text-dark mb-0">Tasks Management</h3>
        <p class="text-muted text-sm mb-0">Manage all project tasks, assign employees, and track statuses.</p>
    </div>
    <div class="col-md-6 text-right">
        <!-- Render the 'Add New Task' button for administrators -->
        <a href="{{ route('admin.task.create') }}" class="btn btn-primary btn-round text-white shadow-sm px-4">
            <i class="now-ui-icons ui-1_simple-add"></i> Add New Task
        </a>
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
        <x-alert-message />
        <div class="card shadow-sm border-0">
            <div class="card-body px-0 pb-0">
                <div class="table-responsive">
                    <table class="table align-items-center table-flush mb-0" id="tasksTable">
                        <!-- Table Headings with Gradient Style Matching Projects -->
                        <thead style="background: linear-gradient(135deg, #f96332 0%, #ff8c42 100%); color: white;">
                            <tr>
                                <th class="py-3 font-weight-bold text-white pl-4">Title</th>
                                <th class="py-3 font-weight-bold text-white">Description</th>
                                <th class="py-3 font-weight-bold text-white">Assigned To</th>
                                <th class="py-3 font-weight-bold text-white">Attachment</th>
                                <th class="py-3 font-weight-bold text-white">Status</th>
                                <th class="py-3 font-weight-bold text-white">Review Action</th>
                                <!-- New column for accept/reject buttons -->
                                <th class="py-3 font-weight-bold text-white text-right pr-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Loop through each task record using Laravel forelse directive -->
                            @forelse($tasks as $task)
                            <tr class="border-bottom task-row">
                                <!-- Task Title Column -->
                                <td class="font-weight-bold text-dark pl-4 align-middle task-title">
                                    {{ $task->title }}
                                </td>

                                <!-- Task Description Column (Clickable to open Modal) -->
                                <td class="text-muted align-middle task-desc">
                                    {{ Str::limit($task->description, 40) }}
                                    @if(strlen($task->description) > 40)
                                    <button type="button"
                                        class="btn btn-link btn-sm p-0 ml-1 text-primary font-weight-bold"
                                        data-toggle="modal" data-target="#taskDescModal-{{ $task->id }}"
                                        style="font-size: 12px; text-decoration: underline;">
                                        More
                                    </button>
                                    @endif
                                </td>

                                <!-- Assigned User Column with Avatar Initials Matching Project Manager Style -->
                                <td class="align-middle task-user">
                                    <div class="d-flex align-items-center">
                                        <span
                                            class="avatar-sm rounded-circle bg-light text-primary font-weight-bold d-flex align-items-center justify-content-center shadow-sm mr-2"
                                            style="width: 32px; height: 32px; font-size: 12px;">
                                            {{ strtoupper(substr(optional($task->user)->name ?? 'U', 0, 2)) }}
                                        </span>
                                        <span class="text-dark font-weight-normal">
                                            {{ optional($task->user)->name ?? 'No Assignee' }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Attachment Column (Supports Single/Multiple Files via Collection or Array) -->
                                <td class="align-middle task-attachment">
                                    @php
                                    $attachments = [];
                                    if (!empty($task->attachments)) {
                                    $attachments = is_string($task->attachments) ? json_decode($task->attachments, true)
                                    ?? [$task->attachments] : $task->attachments;
                                    } elseif (!empty($task->attachment)) {
                                    $attachments = is_array($task->attachment) ? $task->attachment :
                                    [$task->attachment];
                                    }
                                    $fileCount = count($attachments);
                                    @endphp

                                    @if($fileCount > 0)
                                    <button type="button"
                                        class="btn btn-sm btn-outline-primary btn-round px-3 py-1 shadow-sm"
                                        data-toggle="modal" data-target="#taskFilesModal-{{ $task->id }}"
                                        style="font-size: 11px;">
                                        <i class="now-ui-icons files_single-copy-04 mr-1"></i> View Files ({{ $fileCount
                                        }})
                                    </button>
                                    @else
                                    <span class="text-muted font-italic" style="font-size: 12px;">No File</span>
                                    @endif
                                </td>

                                <!-- Task Status Column with Matching Dynamic Colors & Rejection Reason Tooltip/Modal Trigger -->
                                <td class="align-middle task-status">
                                    <span class="badge badge-pill
                                        @if($task->status == 'completed') badge-success
                                        @elseif($task->status == 'accepted') badge-primary
                                        @elseif($task->status == 'in_progress') badge-warning
                                        @elseif($task->status == 'pending') badge-info
                                        @elseif($task->status == 'rejected') badge-danger
                                        @else badge-secondary @endif px-3 py-2 text-white shadow-sm">
                                        {{ ucfirst(str_replace('_', ' ', $task->status ?? 'pending')) }}
                                    </span>

                                    <!-- Show reason button if status is rejected and rejection_reason exists -->
                                    @if($task->status == 'rejected' && !empty($task->rejection_reason))
                                    <button type="button" class="btn btn-link btn-sm text-danger p-0 ml-1"
                                        data-toggle="modal" data-target="#rejectionReasonModal-{{ $task->id }}"
                                        style="font-size: 11px;">
                                        <i class="now-ui-icons info_circle"></i> Reason
                                    </button>
                                    @endif
                                </td>

                                <!-- REVIEW / ACTION COLUMN (Accept & Reject Buttons) -->
                                <td class="align-middle">
                                    <div class="btn-group" role="group" aria-label="Review Actions">
                                        <!-- Accept Button -->
                                        <form action="{{ route('admin.task.accept', $task->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="btn btn-success btn-sm btn-icon shadow-sm mx-1 rounded"
                                                title="Accept Task"
                                                style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                                <i class="now-ui-icons ui-1_check" style="font-size: 13px;"></i>
                                            </button>
                                        </form>

                                        <!-- Reject Button (Triggers Rejection Modal) -->
                                        <button type="button"
                                            class="btn btn-danger btn-sm btn-icon shadow-sm mx-1 rounded"
                                            title="Reject Task" data-toggle="modal"
                                            data-target="#rejectModal-{{ $task->id }}"
                                            style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                            <i class="now-ui-icons ui-1_simple-remove" style="font-size: 13px;"></i>
                                        </button>
                                    </div>
                                </td>

                                <!-- STANDARD ACTIONS COLUMN (EDIT, DELETE WITH SWEETALERT) -->
                                <td class="text-right pr-4 align-middle">
                                    <div class="btn-group" role="group" aria-label="Task Actions">
                                        <!-- Edit Task Button -->
                                        <a href="{{ route('admin.task.edit', $task->id) }}"
                                            class="btn btn-warning btn-sm btn-icon shadow-sm mx-1 rounded"
                                            title="Edit Task"
                                            style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; background-color: #ff8c42; border-color: #ff8c42;">
                                            <i class="now-ui-icons ui-2_settings-90" style="font-size: 13px;"></i>
                                        </a>

                                        <!-- Delete Form with SweetAlert2 Integration -->
                                        <form action="{{ route('admin.task.destroy', $task->id) }}" method="POST"
                                            style="display: inline-block;" id="delete-form-task-{{ $task->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="btn btn-danger btn-sm btn-icon shadow-sm mx-1 rounded"
                                                title="Delete Task"
                                                style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;"
                                                onclick="confirmDelete('task', {{ $task->id }})">
                                                <i class="now-ui-icons ui-1_simple-remove" style="font-size: 13px;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <!-- Empty State Row when no tasks match filter criteria -->
                            <tr id="noTasksDefault">
                                <td colspan="7" class="text-center text-muted py-5">
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

                <!-- PAGINATION CONTROLS SECTION -->
                @if(method_exists($tasks, 'hasPages') && $tasks->hasPages())
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

<!-- ========================================== -->
<!-- MODALS FOR TASK DESCRIPTIONS               -->
<!-- ========================================== -->
@foreach($tasks as $task)
@if(strlen($task->description) > 40)
<div class="modal fade" id="taskDescModal-{{ $task->id }}" tabindex="-1" role="dialog"
    aria-labelledby="taskDescModalLabel-{{ $task->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #f96332 0%, #ff8c42 100%);">
                <h5 class="modal-title font-weight-bold" id="taskDescModalLabel-{{ $task->id }}">
                    <i class="now-ui-icons design_bullet-list-67 mr-2"></i> Task Description
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"
                    style="opacity: 0.9;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4 text-dark" style="background-color: #f9fbfd; line-height: 1.6;">
                <p class="mb-0" style="white-space: pre-line;">{{ $task->description }}</p>
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

<!-- ========================================== -->
<!-- MODALS FOR TASK ATTACHMENTS FILES          -->
<!-- ========================================== -->
@foreach($tasks as $task)
@php
$attachments = [];
if (!empty($task->attachments)) {
$attachments = is_string($task->attachments) ? json_decode($task->attachments, true) ?? [$task->attachments] :
$task->attachments;
} elseif (!empty($task->attachment)) {
$attachments = is_array($task->attachment) ? $task->attachment : [$task->attachment];
}
@endphp

@if(count($attachments) > 0)
<div class="modal fade" id="taskFilesModal-{{ $task->id }}" tabindex="-1" role="dialog"
    aria-labelledby="taskFilesModalLabel-{{ $task->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #f96332 0%, #ff8c42 100%);">
                <h5 class="modal-title font-weight-bold" id="taskFilesModalLabel-{{ $task->id }}">
                    <i class="now-ui-icons files_single-copy-04 mr-2"></i> Task Attachments ({{ count($attachments) }})
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"
                    style="opacity: 0.9;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4" style="background-color: #f9fbfd;">
                <div class="list-group shadow-sm" style="border-radius: 10px; overflow: hidden;">
                    @foreach($attachments as $file)
                    @php
                    $filePath = is_array($file) ? ($file['path'] ?? $file['file'] ?? '') : $file;
                    $filePath = trim($filePath, '"[] ');
                    $fileName = basename($filePath);
                    $extension = strtolower(pathinfo(parse_url($fileName, PHP_URL_PATH), PATHINFO_EXTENSION));
                    $extension = rtrim($extension, '"]');

                    $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
                    $isVideo = in_array($extension, ['mp4', 'mov', 'avi', 'mkv', 'webm']);
                    $isPdf = $extension === 'pdf';
                    $isDoc = in_array($extension, ['doc', 'docx', 'txt', 'rtf']);
                    $isArchive = in_array($extension, ['zip', 'rar', 'tar', 'gz']);
                    @endphp
                    <div
                        class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-3 border-bottom">
                        <div class="d-flex align-items-center text-truncate mr-3">
                            @if($isImage)
                            <div class="mr-3 shadow-sm rounded-circle overflow-hidden d-flex align-items-center justify-content-center"
                                style="min-width: 40px; width: 40px; height: 40px; background-color: #fee2e2;">
                                <img src="{{ asset('storage/' . $filePath) }}" alt="img"
                                    style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            @else
                            <!-- الأيقونة تظهر هنا بناءً على نوع الملف -->
                            <div class="rounded-circle mr-3 shadow-sm text-white d-flex align-items-center justify-content-center"
                                style="min-width: 40px; width: 40px; height: 40px; background: linear-gradient(135deg,
                                @if($isVideo) #ef4444, #dc2626
                                @elseif($isPdf) #f59e0b, #d97706
                                @elseif($isDoc) #2563eb, #1d4ed8
                                @elseif($isArchive) #8b5cf6, #7c3aed
                                @else #f96332, #ff8c42 @endif);">

                                @if($isVideo)
                                <i class="now-ui-icons media-2_sound-wave" style="font-size: 16px;"></i>
                                @elseif($isPdf)
                                <i class="now-ui-icons files_paper" style="font-size: 16px;"></i>
                                @elseif($isDoc)
                                <i class="now-ui-icons text_align-left" style="font-size: 16px;"></i>
                                @elseif($isArchive)
                                <i class="now-ui-icons files_archive" style="font-size: 16px;"></i>
                                @else
                                <i class="now-ui-icons files_single-copy-04" style="font-size: 16px;"></i>
                                @endif
                            </div>
                            @endif

                            <div class="text-truncate">
                                <span class="font-weight-bold text-dark d-block text-truncate" style="font-size: 14px;"
                                    title="{{ $fileName }}">{{ $fileName }}</span>
                                <small class="text-muted text-uppercase font-weight-bold" style="font-size: 10px;">
                                    {{ $extension ?: 'File' }} Document
                                </small>
                            </div>
                        </div>
                        <div class="btn-group flex-shrink-0" role="group">
                            <a href="{{ asset('storage/' . $filePath) }}" target="_blank"
                                class="btn btn-sm btn-info btn-round px-3 mr-2 shadow-sm text-white"
                                style="font-size: 11px;">
                                <i class="now-ui-icons design_image mr-1"></i> View
                            </a>
                            <a href="{{ asset('storage/' . $filePath) }}" download
                                class="btn btn-sm btn-primary btn-round px-3 shadow-sm text-white"
                                style="font-size: 11px; background-color: #ff8c42; border-color: #ff8c42;">
                                <i class="now-ui-icons arrows-1_cloud-download-93 mr-1"></i> Download
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
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

<!-- ========================================== -->
<!-- MODAL FOR REJECTING TASK (WITH REASON)     -->
<!-- ========================================== -->
@foreach($tasks as $task)
<div class="modal fade" id="rejectModal-{{ $task->id }}" tabindex="-1" role="dialog"
    aria-labelledby="rejectModalLabel-{{ $task->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;">
            <form action="{{ route('admin.task.reject', $task->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header text-white"
                    style="background: linear-gradient(135deg, #f96332 0%, #ff8c42 100%);">
                    <h5 class="modal-title font-weight-bold" id="rejectModalLabel-{{ $task->id }}">
                        <i class="now-ui-icons ui-1_simple-remove mr-2"></i> Reject Task
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"
                        style="opacity: 0.9;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4" style="background-color: #f9fbfd;">
                    <div class="form-group">
                        <label for="rejection_reason" class="font-weight-bold text-dark">Please provide a reason for
                            rejection:</label>
                        <textarea name="rejection_reason" id="rejection_reason" rows="4"
                            class="form-control border rounded p-3" placeholder="Enter rejection reason here..."
                            required style="background-color: #ffffff;"></TD></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-white border-0 py-3">
                    <button type="button" class="btn btn-secondary btn-round px-4 shadow-sm"
                        data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger btn-round px-4 shadow-sm">Confirm Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- ========================================== -->
<!-- MODAL TO VIEW REJECTION REASON             -->
<!-- ========================================== -->
@foreach($tasks as $task)
@if($task->status == 'rejected' && !empty($task->rejection_reason))
<div class="modal fade" id="rejectionReasonModal-{{ $task->id }}" tabindex="-1" role="dialog"
    aria-labelledby="rejectionReasonModalLabel-{{ $task->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;">
            <div class="modal-header text-white bg-danger">
                <h5 class="modal-title font-weight-bold" id="rejectionReasonModalLabel-{{ $task->id }}">
                    <i class="now-ui-icons info_circle mr-2"></i> Rejection Reason
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"
                    style="opacity: 0.9;">
                    <span aria-hidden="true">&times;—</span>
                </button>
            </div>
            <div class="Modal-body p-4 text-dark" style="background-color: #f9fbfd; line-height: 1.6;">
                <p class="mb-0" style="white-space: pre-line;">{{ $task->rejection_reason }}</p>
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

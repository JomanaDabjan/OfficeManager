@extends('layouts.app')

<!-- Set the dynamic title for this specific page -->
@section('title', 'Edit Task')

@section('Main_Content')

<!-- ========================================================================= -->
<!-- MAIN EDIT FORM CONTAINER SECTION                                          -->
<!-- ========================================================================= -->

<!-- Main Form Card Container Centered -->
<div class="row justify-content-center mt-4 mb-4">
    <div class="col-lg-9 col-md-10">

        <!-- Include Session Alert Message Component -->
        <x-alert-message />

        <div class="card shadow-sm border-0 project-form-card">

            <!-- Styled Card Header with Gradient and Centered/Aligned Content (Modified color for Edit context if desired) -->
            <div class="card-header custom-card-header text-white d-flex align-items-center py-3 px-4"
                style="background: linear-gradient(135deg, #2ca8ff 0%, #1572e8 100%);">
                <!-- Header Icon Wrapper -->
                <div class="icon icon-shape bg-white text-primary rounded-circle shadow-sm mr-3 d-flex align-items-center justify-content-center"
                    style="width: 48px; height: 48px;">
                    <i class="now-ui-icons ui-2_settings-90 text-primary" style="font-size: 20px;"></i>
                </div>
                <!-- Header Title and Subtitle -->
                <div>
                    <h4 class="font-weight-bold text-white mb-0">Edit Task: {{ $task->title }}</h4>
                    <p class="text-white-50 text-sm mb-0">Update the details below to modify the existing task.</p>
                </div>
            </div>

            <div class="card-body px-5 py-4">
                <!-- Form with PUT method override and multipart/form-data for file uploads -->
                <form action="{{ route('admin.task.update', $task->id) }}" method="POST" enctype="multipart/form-data">
                    <!-- CSRF Security Token Protection -->
                    @csrf
                    <!-- HTTP Method Spoofing for Update -->
                    @method('PUT')

                    <!-- ========================================================= -->
                    <!-- ROW 1: TITLE AND RELATED PROJECT SELECTION                -->
                    <!-- ========================================================= -->
                    <div class="row">
                        <!-- Task Title Field -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold text-dark">Task Title</label>
                                <!-- Text input with fallback to old value or current model value -->
                                <input type="text" name="title" class="form-control" placeholder="Enter task title..."
                                    value="{{ old('title', $task->title) }}" required>
                                <!-- Display validation error message for title if any -->
                                @error('title')
                                <span class="text-danger text-sm mt-1 d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Assign Project Field -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold text-dark">Related Project</label>
                                <!-- Dropdown list populated dynamically with current selection matching -->
                                <select name="project_id" class="form-control" required>
                                    <option value="" disabled>Select project...</option>
                                    @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ old('project_id', $task->project_id) ==
                                        $project->id ? 'selected' : '' }}>
                                        {{ $project->title }}
                                    </option>
                                    @endforeach
                                </select>
                                <!-- Display validation error message for project_id if any -->
                                @error('project_id')
                                <span class="text-danger text-sm mt-1 d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- ========================================================= -->
                    <!-- ROW 2: EMPLOYEE ASSIGNMENT AND TASK STATUS                -->
                    <!-- ========================================================= -->
                    <div class="row mt-3">
                        <!-- Assign User Field -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold text-dark">Assign To Employee</label>
                                <!-- Dropdown list for employee assignment matching current task user -->
                                <select name="user_id" class="form-control" required>
                                    <option value="" disabled>Select employee...</option>
                                    @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id', $task->user_id) == $user->id ?
                                        'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                    @endforeach
                                </select>
                                <!-- Display validation error message for user_id if any -->
                                @error('user_id')
                                <span class="text-danger text-sm mt-1 d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Task Status Field -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold text-dark">Task Status</label>
                                <!-- Dropdown selection for task progression state matching current status -->
                                <select name="status" class="form-control">
                                    <option value="pending" {{ old('status', $task->status) == 'pending' ? 'selected' :
                                        '' }}>Pending</option>
                                    <option value="in_progress" {{ old('status', $task->status) == 'in_progress' ?
                                        'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ old('status', $task->status) == 'completed' ?
                                        'selected' : '' }}>Completed</option>
                                </select>
                                <!-- Display validation error message for status if any -->
                                @error('status')
                                <span class="text-danger text-sm mt-1 d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- ========================================================= -->
                    <!-- ROW NEW: STARTED AT AND DUE DATE FIELDS                   -->
                    <!-- ========================================================= -->
                    <div class="row mt-3">
                        <!-- Started At Field -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold text-dark">Started At</label>
                                <input type="date" name="started_at" class="form-control"
                                    value="{{ old('started_at', optional($task->started_at)->format('Y-m-d')) }}">
                                @error('started_at')
                                <span class="text-danger text-sm mt-1 d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Due Date Field -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold text-dark">Due Date</label>
                                <input type="date" name="due_date" class="form-control"
                                    value="{{ old('due_date', optional($task->due_date)->format('Y-m-d')) }}">
                                @error('due_date')
                                <span class="text-danger text-sm mt-1 d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- ========================================================= -->
                    <!-- ROW 3: TASK DESCRIPTION TEXTAREA                          -->
                    <!-- ========================================================= -->
                    <div class="row mt-3">
                        <!-- Task Description Field -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold text-dark">Description</label>
                                <!-- Textarea input populated with existing task description -->
                                <textarea name="description" rows="4" class="form-control"
                                    placeholder="Enter task description and objectives...">{{ old('description', $task->description) }}</textarea>
                                <!-- Display validation error message for description if any -->
                                @error('description')
                                <span class="text-danger text-sm mt-1 d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- ========================================================= -->
                    <!-- ROW 4: MULTIPLE FILE ATTACHMENTS UPLOAD BOX & EXISTING    -->
                    <!-- ========================================================= -->
                    <div class="row mt-3">
                        <!-- Task Attachments Field -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold text-dark">Task Attachments
                                    (Optional)</label>

                                <!-- Display Existing Attachments if available -->
                                @if(isset($task->attachments) && count($task->attachments) > 0)
                                <div class="mb-3 p-3 bg-light rounded border">
                                    <span class="font-weight-bold text-sm text-secondary d-mb-2">Current
                                        Attachments:</span>
                                    <ul class="list-unstyled mb-0 mt-1">
                                        @foreach($task->attachments as $attachment)
                                        <li class="d-flex align-items-center justify-content-between py-1">
                                            <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank"
                                                class="text-primary text-sm">
                                                <i class="now-ui-icons files_paper mr-1"></i> {{
                                                basename($attachment->file_path) }}
                                            </a>
                                            <!-- Optional delete individual attachment checkbox or button -->
                                            <div class="form-check mt-0">
                                                <label class="form-check-label text-danger text-xs">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="remove_attachments[]" value="{{ $attachment->id }}">
                                                    <span class="form-check-sign"></span> Delete
                                                </label>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif

                                <!-- Custom Dropzone / Upload Box Style for New Files -->
                                <div class="custom-file-upload-box text-center p-4 border rounded"
                                    style="border: 2px dashed #ced4da !important; background: #fdfdfd; cursor: pointer; transition: all 0.3s ease;"
                                    onclick="document.getElementById('attachmentInput').click();"
                                    onmouseover="this.style.borderColor='#2ca8ff';"
                                    onmouseout="this.style.borderColor='#ced4da';">

                                    <!-- Initial upload instruction prompt -->
                                    <div id="uploadPrompt">
                                        <i class="now-ui-icons arrows-1_cloud-upload-94 text-primary"
                                            style="font-size: 32px; color: #2ca8ff !important;"></i>
                                        <p class="mb-1 font-weight-bold text-secondary mt-2">Click to browse or drag new
                                            files here</p>
                                        <small class="text-muted">Supported formats: PDF, Images (JPG, PNG), DOCX, ZIP
                                            (Max: 2MB each)</small>
                                    </div>

                                    <!-- Live File Preview Container (Hidden by default) -->
                                    <div id="filePreviewContainer"
                                        class="d-none align-items-center justify-content-between p-2 bg-white border rounded shadow-sm">
                                        <div class="d-flex align-items-center">
                                            <div id="previewIconWrapper" class="mr-3 text-primary"
                                                style="font-size: 28px;">📂</div>
                                            <div class="text-left">
                                                <h6 id="fileNameDisplay" class="mb-0 font-weight-bold text-dark text-sm"
                                                    style="word-break: break-all;"></h6>
                                                <small id="fileSizeDisplay" class="text-muted"></small>
                                            </div>
                                        </div>
                                        <!-- Button to clear selected files -->
                                        <button type="button" class="btn btn-sm btn-danger btn-round p-2 mb-0"
                                            id="removeFileBtn" title="Remove files" style="line-key: 1;">
                                            <i class="now-ui-icons ui-1_simple-remove text-white"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Hidden File Input -->
                                <input type="file" name="attachments[]" id="attachmentInput" class="d-none" multiple
                                    accept=".pdf,.jpg,.jpeg,.png,.docx,.zip">

                                <!-- Validation Errors -->
                                @error('attachments')
                                <span class="text-danger text-sm mt-1 d-block">{{ $message }}</span>
                                @enderror
                                @error('attachments.*')
                                <span class="text-danger text-sm mt-1 d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- ========================================================= -->
                    <!-- FORM SUBMISSION BUTTONS SECTION                           -->
                    <!-- ========================================================= -->
                    <div class="row mt-4">
                        <div class="col-md-12 text-right">
                            <!-- Cancel button linking back to tasks index list -->
                            <a href="{{ route('admin.task.edit', $task->id) }}"
                                class="btn btn-secondary btn-round px-4 mr-2 shadow-sm">
                                Cancel
                            </a>
                            <!-- Submit button to trigger task update process -->
                            <button type="submit" class="btn btn-primary btn-round px-4 shadow-sm">
                                Update Task
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection
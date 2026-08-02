@extends('layouts.app')

@section('Main_Content')

<!-- ========================================================================= -->
<!-- MAIN FORM CONTAINER SECTION                                               -->
<!-- ========================================================================= -->

<!-- Main Form Card Container Centered -->
<div class="row justify-content-center mt-4 mb-4">
    <div class="col-lg-9 col-md-10">

        <!-- Include Session Alert Message Component -->
        <x-alert-message />

        <div class="card shadow-sm border-0 project-form-card">

            <!-- Styled Card Header with Gradient and Centered/Aligned Content -->
            <div class="card-header custom-card-header text-white d-flex align-items-center py-3 px-4"
                style="background: linear-gradient(135deg, #f96332 0%, #ff8c42 100%);">
                <!-- Header Icon Wrapper -->
                <div class="icon icon-shape bg-white text-primary rounded-circle shadow-sm mr-3 d-flex align-items-center justify-content-center"
                    style="width: 48px; height: 48px;">
                    <i class="now-ui-icons ui-1_simple-add text-primary" style="font-size: 20px;"></i>
                </div>
                <!-- Header Title and Subtitle -->
                <div>
                    <h4 class="font-weight-bold text-white mb-0">Create New Task</h4>
                    <p class="text-white-50 text-sm mb-0">Fill in the details below to assign a new system task.</p>
                </div>
            </div>

            <div class="card-body px-5 py-4">
                <!-- Form with multipart/form-data to support file and document uploads -->
                <form action="{{ route('admin.task.store') }}" method="POST" enctype="multipart/form-data">
                    <!-- CSRF Security Token Protection -->
                    @csrf

                    <!-- ========================================================= -->
                    <!-- ROW 1: TITLE AND RELATED PROJECT SELECTION                -->
                    <!-- ========================================================= -->
                    <div class="row">
                        <!-- Task Title Field -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold text-dark">Task Title</label>
                                <!-- Text input with old value retention and required validation -->
                                <input type="text" name="title" class="form-control" placeholder="Enter task title..."
                                    value="{{ old('title') }}" required>
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
                                <!-- Dropdown list populated dynamically from database projects -->
                                <select name="project_id" class="form-control" required>
                                    <option value="" selected disabled>Select project...</option>
                                    @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ old('project_id')==$project->id ? 'selected' :
                                        '' }}>
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
                                <!-- Dropdown list filtered to display employee roles only -->
                                <select name="user_id" class="form-control" required>
                                    <option value="" selected disabled>Select employee...</option>
                                    @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id')==$user->id ? 'selected' : '' }}>
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
                                <!-- Dropdown selection for task progression state -->
                                <select name="status" class="form-control">
                                    <option value="pending" {{ old('status')=='pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="in_progress" {{ old('status')=='in_progress' ? 'selected' : '' }}>In
                                        Progress</option>
                                    <option value="completed" {{ old('status')=='completed' ? 'selected' : '' }}>
                                        Completed</option>
                                </select>
                                <!-- Display validation error message for status if any -->
                                @error('status')
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
                                <!-- Textarea input for detailed task objectives -->
                                <textarea name="description" rows="4" class="form-control"
                                    placeholder="Enter task description and objectives...">{{ old('description') }}</textarea>
                                <!-- Display validation error message for description if any -->
                                @error('description')
                                <span class="text-danger text-sm mt-1 d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- ========================================================= -->
                    <!-- ROW 4: MULTIPLE FILE ATTACHMENTS UPLOAD BOX               -->
                    <!-- ========================================================= -->
                    <div class="row mt-3">
                        <!-- Styled Task Attachments Field with Live Preview for Multiple Files -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold text-dark">Task Attachments
                                    (Optional)</label>

                                <!-- Custom Dropzone / Upload Box Style -->
                                <div class="custom-file-upload-box text-center p-4 border rounded"
                                    style="border: 2px dashed #ced4da !important; background: #fdfdfd; cursor: pointer; transition: all 0.3s ease;"
                                    onclick="document.getElementById('attachmentInput').click();"
                                    onmouseover="this.style.borderColor='#f96332';"
                                    onmouseout="this.style.borderColor='#ced4da';">

                                    <!-- Initial upload instruction prompt -->
                                    <div id="uploadPrompt">
                                        <i class="now-ui-icons arrows-1_cloud-upload-94 text-primary"
                                            style="font-size: 32px; color: #f96332 !important;"></i>
                                        <p class="mb-1 font-weight-bold text-secondary mt-2">Click to browse or drag
                                            files here (Multiple allowed)</p>
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
                                        <!-- Button to clear or remove selected files -->
                                        <button type="button" class="btn btn-sm btn-danger btn-round p-2 mb-0"
                                            id="removeFileBtn" title="Remove files" style="line-height: 1;">
                                            <i class="now-ui-icons ui-1_simple-remove text-white"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Actual Hidden File Input (Note the attachments[] name and multiple attribute) -->
                                <input type="file" name="attachments[]" id="attachmentInput" class="d-none" multiple
                                    accept=".pdf,.jpg,.jpeg,.png,.docx,.zip">

                                <!-- Display validation error messages for attachments -->
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
                            <a href="{{ route('admin.task.create') }}"
                                class="btn btn-secondary btn-round px-4 mr-2 shadow-sm">
                                Cancel
                            </a>
                            <!-- Submit button to trigger task creation store process -->
                            <button type="submit" class="btn btn-primary btn-round px-4 shadow-sm text-white">
                                Save Task
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection

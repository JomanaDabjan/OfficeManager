@extends('layouts.app')

<!-- Set the dynamic title for this specific page -->
@section('title', 'Create Task')

@section('Main_Content')

<!-- ========================================================================= -->
<!-- MAIN FORM CONTAINER SECTION                                               -->
<!-- ========================================================================= -->

@can('create', \App\Models\Task::class)

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

                    <h4 class="font-weight-bold text-white mb-0">
                        Create New Task
                    </h4>

                    <p class="text-white-50 text-sm mb-0">
                        Fill in the details below to assign a new system task.
                    </p>

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

                                <label class="form-control-label font-weight-bold text-dark">
                                    Task Title
                                </label>

                                <!-- Text input with old value retention and required validation -->
                                <input type="text" name="title" class="form-control" placeholder="Enter task title..."
                                    value="{{ old('title') }}" required>

                                <!-- Display validation error message for title if any -->
                                @error('title')
                                <span class="text-danger text-sm mt-1 d-block">
                                    {{ $message }}
                                </span>
                                @enderror

                            </div>

                        </div>


                        <!-- ========================================================= -->
                        <!-- ASSIGN PROJECT FIELD WITH LIVE SEARCH                     -->
                        <!-- ========================================================= -->

                        <div class="col-md-6">

                            <div class="form-group">

                                <label class="form-control-label font-weight-bold text-dark">
                                    Related Project
                                </label>


                                <!-- Project Dropdown -->
                                <div class="dropdown w-100">

                                    <!-- Selected Project Button -->
                                    <button type="button"
                                        class="form-control text-left d-flex align-items-center justify-content-between"
                                        id="projectDropdownButton" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false" style="
                                            background-color: #f9fbfd !important;
                                            border: 1.5px solid #ced4da !important;
                                            border-radius: 8px !important;
                                            padding: 10px 15px !important;
                                            font-size: 14px !important;
                                            color: #495057 !important;
                                            height: auto !important;
                                            min-height: 40px;
                                            box-shadow: none !important;
                                        ">

                                        <span id="selectedProjectText">

                                            @if(old('project_id'))
                                            {{ optional($projects->firstWhere('id', old('project_id')))->title ??
                                            'Select project...' }}
                                            @else
                                            Select project...
                                            @endif

                                        </span>


                                        <i class="now-ui-icons arrows-1_minimal-down"
                                            style="font-size: 11px; color: #8898aa;"></i>

                                    </button>


                                    <!-- Hidden Project ID Input -->
                                    <input type="hidden" name="project_id" id="project_id"
                                        value="{{ old('project_id') }}" required>


                                    <!-- ================================================= -->
                                    <!-- PROJECT DROPDOWN MENU                          -->
                                    <!-- ================================================= -->

                                    <div class="dropdown-menu shadow-lg border-0 p-2"
                                        aria-labelledby="projectDropdownButton" style="
                                            width: 100%;
                                            max-height: 300px;
                                            overflow-y: auto;
                                            border-radius: 8px;
                                            margin-top: 4px;
                                            background-color: #ffffff;
                                        " onclick="event.stopPropagation();">

                                        <!-- ========================================= -->
                                        <!-- LIVE SEARCH                               -->
                                        <!-- ========================================= -->

                                        <div class="px-1 pb-2" style="
                                                position: sticky;
                                                top: 0;
                                                background: #ffffff;
                                                z-index: 10;
                                            ">

                                            <div class="position-relative">

                                                <i class="now-ui-icons ui-1_zoom-bold" style="
                                                        position: absolute;
                                                        left: 13px;
                                                        top: 50%;
                                                        transform: translateY(-50%);
                                                        color: #8898aa;
                                                        font-size: 13px;
                                                        z-index: 2;
                                                    "></i>


                                                <input type="text" id="projectLiveSearch" class="form-control"
                                                    placeholder="Search project..." autocomplete="off" style="
                                                        padding-left: 38px !important;
                                                        border-radius: 20px !important;
                                                        height: 36px !important;
                                                        font-size: 13px !important;
                                                        border: 1px solid #ced4da !important;
                                                        background-color: #f9fbfd !important;
                                                        box-shadow: none !important;
                                                    ">

                                            </div>

                                        </div>


                                        <!-- ========================================= -->
                                        <!-- PROJECTS LIST                             -->
                                        <!-- ========================================= -->

                                        <div id="projectList">

                                            @foreach($projects as $project)

                                            <button type="button" class="dropdown-item project-option py-2 px-3"
                                                data-id="{{ $project->id }}"
                                                data-title="{{ strtolower($project->title) }}" style="
                                                    border-radius: 6px;
                                                    font-size: 13px;
                                                    color: #495057;
                                                    white-space: normal;
                                                    text-align: left;
                                                    width: 100%;
                                                    border: none;
                                                    background: transparent;
                                                ">

                                                {{ $project->title }}

                                            </button>

                                            @endforeach


                                            <!-- No Results Message -->
                                            <div id="noProjectResults" class="text-muted text-center py-3" style="
                                                    display: none;
                                                    font-size: 13px;
                                                ">

                                                No projects found.

                                            </div>

                                        </div>

                                    </div>

                                </div>


                                <!-- Display validation error message for project_id if any -->
                                @error('project_id')
                                <span class="text-danger text-sm mt-1 d-block">
                                    {{ $message }}
                                </span>
                                @enderror

                            </div>

                        </div>

                    </div>


                    <!-- ========================================================= -->
                    <!-- ROW 2: EMPLOYEE ASSIGNMENT AND TASK STATUS                -->
                    <!-- ========================================================= -->

                    <div class="row mt-3">

                        <!-- Assign User Field (Filtered to Employees Only) -->
                        <div class="col-md-6">

                            <div class="form-group">

                                <label class="form-control-label font-weight-bold text-dark">
                                    Assign To Employee
                                </label>

                                <!-- Dropdown list filtered to display employee roles only -->
                                <select name="user_id" class="form-control" required>

                                    <option value="" selected disabled>
                                        Select employee...
                                    </option>

                                    @foreach($users as $user)

                                    <option value="{{ $user->id }}" {{ old('user_id')==$user->id ? 'selected' : '' }}>

                                        {{ $user->name }}

                                    </option>

                                    @endforeach

                                </select>

                                <!-- Display validation error message for user_id if any -->
                                @error('user_id')
                                <span class="text-danger text-sm mt-1 d-block">
                                    {{ $message }}
                                </span>
                                @enderror

                            </div>

                        </div>


                        <!-- Task Status Field -->
                        <div class="col-md-6">

                            <div class="form-group">

                                <label class="form-control-label font-weight-bold text-dark">
                                    Task Status
                                </label>

                                <!-- Dropdown selection for task progression state -->
                                <select name="status" class="form-control">

                                    <option value="pending" {{ old('status')=='pending' ? 'selected' : '' }}>
                                        Pending
                                    </option>

                                    <option value="in_progress" {{ old('status')=='in_progress' ? 'selected' : '' }}>
                                        In Progress
                                    </option>

                                    <option value="completed" {{ old('status')=='completed' ? 'selected' : '' }}>
                                        Completed
                                    </option>

                                </select>

                                <!-- Display validation error message for status if any -->
                                @error('status')
                                <span class="text-danger text-sm mt-1 d-block">
                                    {{ $message }}
                                </span>
                                @enderror

                            </div>

                        </div>

                    </div>


                    <!-- ========================================================= -->
                    <!-- ROW 3: STARTED AT & DUE DATE                              -->
                    <!-- ========================================================= -->

                    <div class="row mt-3">

                        <!-- Started At Field -->
                        <div class="col-md-6">

                            <div class="form-group">

                                <label class="form-control-label font-weight-bold text-dark">
                                    Started At
                                </label>

                                <input type="date" name="started_at" class="form-control"
                                    value="{{ old('started_at') }}">

                                @error('started_at')
                                <span class="text-danger text-sm mt-1 d-block">
                                    {{ $message }}
                                </span>
                                @enderror

                            </div>

                        </div>


                        <!-- Due Date Field -->
                        <div class="col-md-6">

                            <div class="form-group">

                                <label class="form-control-label font-weight-bold text-dark">
                                    Due Date
                                </label>

                                <input type="date" name="due_date" class="form-control" value="{{ old('due_date') }}">

                                @error('due_date')
                                <span class="text-danger text-sm mt-1 d-block">
                                    {{ $message }}
                                </span>
                                @enderror

                            </div>

                        </div>

                    </div>


                    <!-- ========================================================= -->
                    <!-- ROW 4: TASK DESCRIPTION TEXTAREA                          -->
                    <!-- ========================================================= -->

                    <div class="row mt-3">

                        <!-- Task Description Field -->
                        <div class="col-md-12">

                            <div class="form-group">

                                <label class="form-control-label font-weight-bold text-dark">
                                    Description
                                </label>

                                <!-- Textarea input for detailed task objectives -->
                                <textarea name="description" rows="4" class="form-control"
                                    placeholder="Enter task description and objectives...">{{ old('description') }}</textarea>

                                <!-- Display validation error message for description if any -->
                                @error('description')
                                <span class="text-danger text-sm mt-1 d-block">
                                    {{ $message }}
                                </span>
                                @enderror

                            </div>

                        </div>

                    </div>


                    <!-- ========================================================= -->
                    <!-- ROW 5: MULTIPLE FILE ATTACHMENTS UPLOAD BOX               -->
                    <!-- ========================================================= -->

                    <div class="row mt-3">

                        <div class="col-md-12">

                            <div class="form-group">

                                <label class="form-control-label font-weight-bold text-dark">
                                    Task Attachments (Optional)
                                </label>


                                <!-- Custom Dropzone / Upload Box Style -->
                                <div class="custom-file-upload-box text-center p-4 border rounded" style="
                                        border: 2px dashed #ced4da !important;
                                        background: #fdfdfd;
                                        cursor: pointer;
                                        transition: all 0.3s ease;
                                    " onclick="document.getElementById('attachmentInput').click();"
                                    onmouseover="this.style.borderColor='#f96332';"
                                    onmouseout="this.style.borderColor='#ced4da';">

                                    <!-- Initial upload instruction prompt -->
                                    <div id="uploadPrompt">

                                        <i class="now-ui-icons arrows-1_cloud-upload-94 text-primary"
                                            style="font-size: 32px; color: #f96332 !important;"></i>

                                        <p class="mb-1 font-weight-bold text-secondary mt-2">
                                            Click to browse or drag files here (Multiple allowed)
                                        </p>

                                        <small class="text-muted">
                                            Supported formats: PDF, Images (JPG, PNG), DOCX, ZIP (Max: 2MB each)
                                        </small>

                                    </div>


                                    <!-- Live File Preview Container (Hidden by default) -->
                                    <div id="filePreviewContainer"
                                        class="d-none align-items-center justify-content-between p-2 bg-white border rounded shadow-sm">

                                        <div class="d-flex align-items-center">

                                            <div id="previewIconWrapper" class="mr-3 text-primary"
                                                style="font-size: 28px;">
                                                📂
                                            </div>

                                            <div class="text-left">

                                                <h6 id="fileNameDisplay" class="mb-0 font-weight-bold text-dark text-sm"
                                                    style="word-break: break-all;"></h6>

                                                <small id="fileSizeDisplay" class="text-muted"></small>

                                            </div>

                                        </div>


                                        <!-- Button to clear or remove selected files -->
                                        <button type="button" class="btn btn-sm btn-danger btn-round p-2 mb-0"
                                            id="removeFileBtn" title="Remove files" style="line-height: 1;"
                                            onclick="event.stopPropagation(); clearFiles();">

                                            <i class="now-ui-icons ui-1_simple-remove text-white"></i>

                                        </button>

                                    </div>


                                    <!-- Multiple Files Names Display -->
                                    <div id="selectedFilesList" class="mt-3 text-left d-none">

                                        <div class="d-flex align-items-center justify-content-between mb-2 px-2">

                                            <div class="font-weight-bold text-dark text-sm">
                                                <i class="now-ui-icons ui-1_check text-success mr-1"></i>
                                                <span id="selectedFilesCount">0</span> files selected
                                            </div>

                                        </div>

                                        <div id="filesNamesContainer" style="
                                                max-height: 180px;
                                                overflow-y: auto;
                                                background: #ffffff;
                                                border: 1px solid #e9ecef;
                                                border-radius: 8px;
                                                padding: 6px;
                                            ">

                                        </div>

                                    </div>

                                </div>


                                <!-- Actual Hidden File Input -->
                                <input type="file" name="attachments[]" id="attachmentInput" class="d-none" multiple
                                    accept=".pdf,.jpg,.jpeg,.png,.docx,.zip">


                                <!-- Display validation error messages for attachments -->
                                @error('attachments')
                                <span class="text-danger text-sm mt-1 d-block">
                                    {{ $message }}
                                </span>
                                @enderror

                                @error('attachments.*')
                                <span class="text-danger text-sm mt-1 d-block">
                                    {{ $message }}
                                </span>
                                @enderror

                            </div>

                        </div>

                    </div>


                    <!-- ========================================================= -->
                    <!-- FORM SUBMISSION BUTTONS SECTION                           -->
                    <!-- ========================================================= -->

                    <div class="row mt-4">

                        <div class="col-md-12 text-right">

                            <!-- Cancel button linking back to previous page or index -->
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

@endcan

@endsection
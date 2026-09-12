@extends('layouts.app')

<!-- Set the dynamic title for this specific page -->
@section('title', 'Create Task')

@section('Main_Content')

@can('create', \App\Models\Task::class)

@php
$role = strtolower(trim(Auth::user()->role ?? ''));
$isTeamLeader = $role === 'team_leader';
$isProjectManager = $role === 'manager';
@endphp

<!-- ========================================================================= -->
<!-- CREATE TASK FORM                                                         -->
<!-- ========================================================================= -->

<div class="row justify-content-center mt-4 mb-4">
    <div class="col-lg-10 col-xl-9">

        <div class="card shadow-sm border-0 project-form-card">

            <!-- ============================================================= -->
            <!-- HEADER                                                         -->
            <!-- ============================================================= -->

            <div class="card-header custom-card-header text-white d-flex align-items-center py-3 px-4"
                style="background: linear-gradient(135deg, #f96332 0%, #ff8c42 100%);">

                <div class="icon icon-shape bg-white text-primary rounded-circle shadow-sm mr-3 d-flex align-items-center justify-content-center"
                    style="width: 48px; height: 48px; flex-shrink: 0;">

                    <i class="now-ui-icons ui-1_simple-add text-primary" style="font-size: 20px;"></i>

                </div>

                <div>
                    <h4 class="font-weight-bold text-white mb-0">
                        Create New Task
                    </h4>

                    <p class="text-white-50 text-sm mb-0">
                        Follow the steps below to create and assign a new task.
                    </p>
                </div>

            </div>


            <div class="card-body px-5 py-4">

                <!-- ========================================================= -->
                <!-- STEP INDICATOR                                             -->
                <!-- ========================================================= -->

                <div class="mb-4">

                    <div class="d-flex align-items-center justify-content-center flex-wrap" style="gap: 8px;">

                        <div class="d-flex align-items-center">
                            <span
                                class="badge badge-primary rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 30px; height: 30px;">
                                1
                            </span>

                            <span class="font-weight-bold text-dark ml-2">
                                Task
                            </span>
                        </div>

                        <div class="mx-2 text-muted">
                            <i class="now-ui-icons arrows-1_minimal-right"></i>
                        </div>

                        <div class="d-flex align-items-center">
                            <span
                                class="badge badge-primary rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 30px; height: 30px;">
                                2
                            </span>

                            <span class="font-weight-bold text-dark ml-2">
                                Project
                            </span>
                        </div>

                        <div class="mx-2 text-muted">
                            <i class="now-ui-icons arrows-1_minimal-right"></i>
                        </div>

                        <div class="d-flex align-items-center">
                            <span
                                class="badge badge-primary rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 30px; height: 30px;">
                                3
                            </span>

                            <span class="font-weight-bold text-dark ml-2">
                                Team
                            </span>
                        </div>

                        <div class="mx-2 text-muted">
                            <i class="now-ui-icons arrows-1_minimal-right"></i>
                        </div>

                        <div class="d-flex align-items-center">
                            <span
                                class="badge badge-primary rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 30px; height: 30px;">
                                4
                            </span>

                            <span class="font-weight-bold text-dark ml-2">
                                Employee
                            </span>
                        </div>

                    </div>

                </div>


                <form action="{{ route('admin.task.store') }}" method="POST" enctype="multipart/form-data">

                    @csrf

                    <!-- Session / General Alert Message -->
                    <x-alert-message />


                    <!-- ===================================================== -->
                    <!-- SECTION 1: TASK INFORMATION                            -->
                    <!-- ===================================================== -->

                    <div class="border rounded-lg p-4 mb-4"
                        style="background-color: #fbfcfe; border-color: #e9ecef !important;">

                        <div class="d-flex align-items-center mb-4">

                            <div class="d-flex align-items-center justify-content-center rounded-circle mr-3"
                                style="width: 38px; height: 38px; background-color: #fff1eb;">

                                <i class="now-ui-icons ui-1_bold-add text-primary" style="font-size: 16px;"></i>

                            </div>

                            <div>
                                <h5 class="font-weight-bold text-dark mb-0">
                                    1. Task Information
                                </h5>

                                <small class="text-muted">
                                    Enter the basic information for this task.
                                </small>
                            </div>

                        </div>


                        <div class="row">

                            <!-- Task Title -->
                            <div class="col-md-6">

                                <div class="form-group">

                                    <label class="form-control-label font-weight-bold text-dark">
                                        Task Title
                                    </label>

                                    <input type="text" name="title" class="form-control"
                                        placeholder="Enter task title..." value="{{ old('title') }}" required>

                                    @error('title')
                                    <span class="text-danger text-sm mt-1 d-block">
                                        {{ $message }}
                                    </span>
                                    @enderror

                                </div>

                            </div>


                            <!-- Task Status -->
                            <div class="col-md-6">

                                <div class="form-group">

                                    <label class="form-control-label font-weight-bold text-dark">
                                        Task Status
                                    </label>

                                    <select name="status" class="form-control">

                                        <option value="pending" {{ old('status', 'pending' )=='pending' ? 'selected'
                                            : '' }}>
                                            Pending
                                        </option>

                                        <option value="in_progress" {{ old('status')=='in_progress' ? 'selected' : ''
                                            }}>
                                            In Progress
                                        </option>

                                        <option value="completed" {{ old('status')=='completed' ? 'selected' : '' }}>
                                            Completed
                                        </option>

                                    </select>

                                    @error('status')
                                    <span class="text-danger text-sm mt-1 d-block">
                                        {{ $message }}
                                    </span>
                                    @enderror

                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- ===================================================== -->
                    <!-- SECTION 2: PROJECT                                     -->
                    <!-- ===================================================== -->

                    <div class="border rounded-lg p-4 mb-4"
                        style="background-color: #fbfcfe; border-color: #e9ecef !important;">

                        <div class="d-flex align-items-center mb-4">

                            <div class="d-flex align-items-center justify-content-center rounded-circle mr-3"
                                style="width: 38px; height: 38px; background-color: #fff1eb;">

                                <i class="now-ui-icons location_pin text-primary" style="font-size: 16px;"></i>

                            </div>

                            <div>
                                <h5 class="font-weight-bold text-dark mb-0">
                                    2. Select Project
                                </h5>

                                <small class="text-muted">
                                    Select the project this task belongs to.
                                </small>
                            </div>

                        </div>


                        <div class="form-group mb-0">

                            <label class="form-control-label font-weight-bold text-dark">
                                Related Project
                            </label>


                            <div class="dropdown w-100">

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
                                        {{ optional($projects->firstWhere('id', old('project_id')))->title ?? 'Select
                                        project...' }}
                                        @else
                                        Select project...
                                        @endif

                                    </span>

                                    <i class="now-ui-icons arrows-1_minimal-down"
                                        style="font-size: 11px; color: #8898aa;"></i>

                                </button>


                                <!-- Hidden Project ID -->
                                <input type="hidden" name="project_id" id="project_id" value="{{ old('project_id') }}"
                                    required>


                                <div class="dropdown-menu shadow-lg border-0 p-2"
                                    aria-labelledby="projectDropdownButton" style="
                                        width: 100%;
                                        max-height: 300px;
                                        overflow-y: auto;
                                        border-radius: 8px;
                                        margin-top: 4px;
                                        background-color: #ffffff;
                                    " onclick="event.stopPropagation();">


                                    <!-- Project Search -->

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


                                    <!-- Projects List -->

                                    <div id="projectList">

                                        @foreach($projects as $project)

                                        <button type="button" class="dropdown-item project-option py-2 px-3"
                                            data-id="{{ $project->id }}" data-title="{{ strtolower($project->title) }}"
                                            style="
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


                                        <div id="noProjectResults" class="text-muted text-center py-3" style="
                                                display: none;
                                                font-size: 13px;
                                            ">

                                            No projects found.

                                        </div>

                                    </div>

                                </div>

                            </div>


                            @error('project_id')
                            <span class="text-danger text-sm mt-1 d-block">
                                {{ $message }}
                            </span>
                            @enderror

                        </div>

                    </div>


                    <!-- ===================================================== -->
                    <!-- SECTION 3: TEAM                                        -->
                    <!-- ===================================================== -->

                    <div class="border rounded-lg p-4 mb-4"
                        style="background-color: #fbfcfe; border-color: #e9ecef !important;">

                        <div class="d-flex align-items-center mb-4">

                            <div class="d-flex align-items-center justify-content-center rounded-circle mr-3"
                                style="width: 38px; height: 38px; background-color: #fff1eb;">

                                <i class="now-ui-icons users_single-02 text-primary" style="font-size: 16px;"></i>

                            </div>

                            <div>
                                <h5 class="font-weight-bold text-dark mb-0">
                                    3. Select Team
                                </h5>

                                <small class="text-muted">
                                    Teams are loaded automatically from the selected project.
                                </small>
                            </div>

                        </div>


                        <div class="form-group mb-0">

                            <label class="form-control-label font-weight-bold text-dark">
                                Team
                            </label>

                            <select name="team_id" id="team_id" class="form-control" disabled>

                                <option value="" selected disabled>
                                    Select project first...
                                </option>

                                @foreach($teams as $team)

                                <option value="{{ $team->id }}" {{ old('team_id')==$team->id ? 'selected' : '' }}>

                                    {{ $team->name }}

                                </option>

                                @endforeach

                            </select>

                            @error('team_id')
                            <span class="text-danger text-sm mt-1 d-block">
                                {{ $message }}
                            </span>
                            @enderror

                        </div>

                    </div>


                    <!-- ===================================================== -->
                    <!-- SECTION 4: EMPLOYEE                                    -->
                    <!-- ===================================================== -->

                    <div class="border rounded-lg p-4 mb-4"
                        style="background-color: #fbfcfe; border-color: #e9ecef !important;">

                        <div class="d-flex align-items-center mb-4">

                            <div class="d-flex align-items-center justify-content-center rounded-circle mr-3"
                                style="width: 38px; height: 38px; background-color: #fff1eb;">

                                <i class="now-ui-icons users_circle-08 text-primary" style="font-size: 16px;"></i>

                            </div>

                            <div>
                                <h5 class="font-weight-bold text-dark mb-0">
                                    4. Assign Employee
                                </h5>

                                <small class="text-muted">
                                    Employees are filtered automatically according to the selected team.
                                </small>
                            </div>

                        </div>


                        <div class="form-group mb-0">

                            <label class="form-control-label font-weight-bold text-dark">
                                Assign To Employee
                            </label>

                            <select name="user_id" id="user_id" class="form-control" required disabled>

                                <option value="" selected disabled>
                                    Select team first...
                                </option>

                                @foreach($users as $user)

                                <option value="{{ $user->id }}" {{ old('user_id')==$user->id ? 'selected' : '' }}>

                                    {{ $user->name }}

                                </option>

                                @endforeach

                            </select>

                            @error('user_id')
                            <span class="text-danger text-sm mt-1 d-block">
                                {{ $message }}
                            </span>
                            @enderror

                        </div>

                    </div>


                    <!-- ===================================================== -->
                    <!-- SECTION 5: DATES                                       -->
                    <!-- ===================================================== -->

                    <div class="border rounded-lg p-4 mb-4"
                        style="background-color: #fbfcfe; border-color: #e9ecef !important;">

                        <div class="d-flex align-items-center mb-4">

                            <div class="d-flex align-items-center justify-content-center rounded-circle mr-3"
                                style="width: 38px; height: 38px; background-color: #fff1eb;">

                                <i class="now-ui-icons ui-1_calendar-60 text-primary" style="font-size: 16px;"></i>

                            </div>

                            <div>
                                <h5 class="font-weight-bold text-dark mb-0">
                                    5. Task Schedule
                                </h5>

                                <small class="text-muted">
                                    Define when the task starts and when it should be completed.
                                </small>
                            </div>

                        </div>


                        <div class="row">

                            <!-- Started At -->

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


                            <!-- Due Date -->

                            <div class="col-md-6">

                                <div class="form-group">

                                    <label class="form-control-label font-weight-bold text-dark">
                                        Due Date
                                    </label>

                                    <input type="date" name="due_date" class="form-control"
                                        value="{{ old('due_date') }}">

                                    @error('due_date')
                                    <span class="text-danger text-sm mt-1 d-block">
                                        {{ $message }}
                                    </span>
                                    @enderror

                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- ===================================================== -->
                    <!-- SECTION 6: DESCRIPTION                                 -->
                    <!-- ===================================================== -->

                    <div class="border rounded-lg p-4 mb-4"
                        style="background-color: #fbfcfe; border-color: #e9ecef !important;">

                        <div class="d-flex align-items-center mb-4">

                            <div class="d-flex align-items-center justify-content-center rounded-circle mr-3"
                                style="width: 38px; height: 38px; background-color: #fff1eb;">

                                <i class="now-ui-icons text-primary now-ui-icons"></i>

                            </div>

                            <div>
                                <h5 class="font-weight-bold text-dark mb-0">
                                    6. Description
                                </h5>

                                <small class="text-muted">
                                    Add details and objectives for the task.
                                </small>
                            </div>

                        </div>


                        <div class="form-group mb-0">

                            <label class="form-control-label font-weight-bold text-dark">
                                Description
                            </label>

                            <textarea name="description" rows="5" class="form-control"
                                placeholder="Enter task description and objectives...">{{ old('description') }}</textarea>

                            @error('description')
                            <span class="text-danger text-sm mt-1 d-block">
                                {{ $message }}
                            </span>
                            @enderror

                        </div>

                    </div>


                    <!-- ===================================================== -->
                    <!-- SECTION 7: ATTACHMENTS                                  -->
                    <!-- ===================================================== -->

                    <div class="border rounded-lg p-4 mb-4"
                        style="background-color: #fbfcfe; border-color: #e9ecef !important;">

                        <div class="d-flex align-items-center mb-4">

                            <div class="d-flex align-items-center justify-content-center rounded-circle mr-3"
                                style="width: 38px; height: 38px; background-color: #fff1eb;">

                                <i class="now-ui-icons arrows-1_cloud-upload-94 text-primary"
                                    style="font-size: 16px;"></i>

                            </div>

                            <div>
                                <h5 class="font-weight-bold text-dark mb-0">
                                    7. Attachments
                                </h5>

                                <small class="text-muted">
                                    Attach supporting files to this task.
                                </small>

                            </div>

                        </div>


                        <div class="form-group mb-0">

                            <label class="form-control-label font-weight-bold text-dark">
                                Task Attachments
                                <span class="text-muted font-weight-normal">
                                    (Optional)
                                </span>
                            </label>


                            <div class="custom-file-upload-box text-center p-4 border rounded" style="
                                    border: 2px dashed #ced4da !important;
                                    background: #fdfdfd;
                                    cursor: pointer;
                                    transition: all 0.3s ease;
                                " onclick="document.getElementById('attachmentInput').click();"
                                onmouseover="this.style.borderColor='#f96332';"
                                onmouseout="this.style.borderColor='#ced4da';">


                                <div id="uploadPrompt">

                                    <i class="now-ui-icons arrows-1_cloud-upload-94 text-primary"
                                        style="font-size: 32px; color: #f96332 !important;"></i>

                                    <p class="mb-1 font-weight-bold text-secondary mt-2">
                                        Click to browse or drag files here
                                    </p>

                                    <small class="text-muted">
                                        Multiple files allowed
                                    </small>

                                    <br>

                                    <small class="text-muted">
                                        PDF, JPG, PNG, DOCX, XLS, XLSX, ZIP — Max 2MB each
                                    </small>

                                </div>


                                <div id="filePreviewContainer"
                                    class="d-none align-items-center justify-content-between p-2 bg-white border rounded shadow-sm">

                                    <div class="d-flex align-items-center">

                                        <div id="previewIconWrapper" class="mr-3 text-primary" style="font-size: 28px;">
                                            📂
                                        </div>

                                        <div class="text-left">

                                            <h6 id="fileNameDisplay" class="mb-0 font-weight-bold text-dark text-sm"
                                                style="word-break: break-all;">
                                            </h6>

                                            <small id="fileSizeDisplay" class="text-muted">
                                            </small>

                                        </div>

                                    </div>


                                    <button type="button" class="btn btn-sm btn-danger btn-round p-2 mb-0"
                                        id="removeFileBtn" title="Remove files" style="line-height: 1;"
                                        onclick="event.stopPropagation(); clearFiles();">

                                        <i class="now-ui-icons ui-1_simple-remove text-white"></i>

                                    </button>

                                </div>


                                <div id="selectedFilesList" class="mt-3 text-left d-none">

                                    <div class="d-flex align-items-center justify-content-between mb-2 px-2">

                                        <div class="font-weight-bold text-dark text-sm">

                                            <i class="now-ui-icons ui-1_check text-success mr-1"></i>

                                            <span id="selectedFilesCount">
                                                0
                                            </span>

                                            files selected

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


                            <input type="file" name="attachments[]" id="attachmentInput" class="d-none" multiple
                                accept=".pdf,.jpg,.jpeg,.png,.docx,.xlsx,.xls,.zip">


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


                    <!-- ===================================================== -->
                    <!-- FORM ACTIONS                                            -->
                    <!-- ===================================================== -->

                    <div class="d-flex align-items-center justify-content-between pt-2">

                        <a href="{{ route('admin.task.create') }}" class="btn btn-secondary btn-round px-4 shadow-sm">
                            Cancel
                        </a>


                        <button type="submit" class="btn btn-primary btn-round px-4 shadow-sm text-white">

                            <i class="now-ui-icons ui-1_check mr-1"></i>

                            Save Task

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>
</div>

@endcan

@endsection

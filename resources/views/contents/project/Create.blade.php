@extends('layouts.app')

<!-- Set the dynamic title for this specific page -->
@section('title', 'Create Project')

@section('Main_Content')

<!-- ========================================================================= -->
<!-- MAIN FORM WRAPPER SECTION                                                 -->
<!-- ========================================================================= -->

<!-- Main Form Card Container Centered -->
<div class="row justify-content-center">
    <div class="col-lg-9 col-md-10">

        <!-- Include Centralized Alert Message Component -->
        <x-alert-message />

        <div class="card shadow-sm border-0 project-form-card">

            <!-- ===================================================== -->
            <!-- CARD HEADER WITH GRADIENT AND ICON                    -->
            <!-- ===================================================== -->
            <div class="card-header custom-card-header text-white d-flex align-items-center py-3 px-4">
                <!-- Icon container with shadow -->
                <div class="icon icon-shape bg-white text-primary rounded-circle shadow-sm mr-3">
                    <i class="now-ui-icons ui-1_simple-add"></i>
                </div>
                <!-- Title and subtitle description -->
                <div>
                    <h4 class="font-weight-bold text-white mb-0">Create New Project</h4>
                    <p class="text-white-50 text-sm mb-0">Fill in the details below to add a new system project.</p>
                </div>
            </div>

            <!-- ===================================================== -->
            <!-- CARD BODY FORM CONTAINER                              -->
            <!-- ===================================================== -->
            <div class="card-body px-5 py-4">
                <form action="{{ route('admin.project.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <!-- Project Title Field -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold text-dark">Project Title</label>
                                <!-- Input field retaining old values with error indicator styling -->
                                <input type="text" name="title"
                                    class="form-control @error('title') is-invalid @enderror"
                                    placeholder="Enter project title..." value="{{ old('title') }}" required>
                            </div>
                        </div>

                        <!-- Project Manager Field -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold text-dark">Assign Manager</label>
                                <select name="manager_id" class="form-control @error('manager_id') is-invalid @enderror"
                                    required>
                                    <option value="" selected disabled>Select project manager...</option>
                                    @foreach($managers as $manager)
                                    <option value="{{ $manager->id }}" {{ old('manager_id')==$manager->id ? 'selected' :
                                        '' }}>
                                        {{ $manager->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <!-- Project Status Field -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold text-dark">Project Status</label>
                                <!-- Status dropdown using lower case values matching backend enums -->
                                <select name="status" class="form-control @error('status') is-invalid @enderror">
                                    <option value="in_progress" {{ old('status')=='in_progress' ? 'selected' : '' }}>In
                                        Progress</option>
                                    <option value="pending" {{ old('status')=='pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="completed" {{ old('status')=='completed' ? 'selected' : '' }}>
                                        Completed</option>
                                </select>
                            </div>
                        </div>

                        <!-- Project Start Date Field -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold text-dark">Start Date</label>
                                <input type="date" name="start_date"
                                    class="form-control @error('start_date') is-invalid @enderror"
                                    value="{{ old('start_date') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <!-- Project End Date Field -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold text-dark">End Date</label>
                                <input type="date" name="end_date"
                                    class="form-control @error('end_date') is-invalid @enderror"
                                    value="{{ old('end_date') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <!-- Project Description Field -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold text-dark">Description</label>
                                <!-- Textarea preserving long description input text -->
                                <textarea name="description" rows="4"
                                    class="form-control @error('description') is-invalid @enderror"
                                    placeholder="Enter project description and objectives...">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- ===================================================== -->
                    <!-- FORM ACTION BUTTONS SECTION                           -->
                    <!-- ===================================================== -->
                    <div class="row mt-4">
                        <div class="col-md-12 text-right">
                            <!-- Cancel button routing back to index list -->
                            <a href="{{ route('admin.project.create') }}"
                                class="btn btn-secondary btn-round px-4 mr-2 shadow-sm">
                                Cancel
                            </a>
                            <!-- Submit button to trigger store method -->
                            <button type="submit" class="btn btn-primary btn-round px-4 shadow-sm">
                                Save Project
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection

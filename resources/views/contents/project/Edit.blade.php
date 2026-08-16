@extends('layouts.app')

<!-- Set the dynamic title for this specific page -->
@section('title', 'Edit Project')

@section('Main_Content')

<!--
  ====================================================================
  PROJECT EDIT FORM CONTAINER
  ====================================================================
  Main wrapper centering the edit form card within the administrative layout.
  ====================================================================
-->
<div class="row justify-content-center">
    <div class="col-lg-9 col-md-10">
        <x-alert-message />
        <div class="card shadow-sm border-0 project-form-card">

            <!-- Card Header with Gradient Background and Icon -->
            <div class="card-header custom-card-header text-white d-flex align-items-center py-3 px-4"
                style="background: linear-gradient(135deg, #f96332 0%, #ff8c42 100%);">
                <div class="icon icon-shape bg-white text-warning rounded-circle shadow-sm mr-3 d-flex align-items-center justify-content-center"
                    style="width: 48px; height: 48px;">
                    <i class="now-ui-icons ui-2_settings-90" style="font-size: 20px;"></i>
                </div>
                <div>
                    <h4 class="font-weight-bold text-white mb-0">Edit Project</h4>
                    <p class="text-white-50 text-sm mb-0">Update the project details below.</p>
                </div>
            </div>

            <!-- Card Body containing the update form -->
            <div class="card-body px-5 py-4">
                <form action="{{ route('admin.project.update', $project->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Project Title Input Field -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold text-dark">Project Title</label>
                                <input type="text" name="title"
                                    class="form-control @error('title') is-invalid @enderror"
                                    placeholder="Enter project title..." value="{{ old('title', $project->title) }}"
                                    required>
                            </div>
                        </div>

                        <!-- Project Manager Field -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold text-dark">Assign Manager</label>
                                <select name="manager_id" class="form-control @error('manager_id') is-invalid @enderror"
                                    required>
                                    <option value="" disabled selected>Select project manager...</option>
                                    @foreach($managers as $manager)
                                    <option value="{{ $manager->id }}" {{ old('manager_id', isset($project) ? $project->
                                        manager_id : '') == $manager->id ? 'selected' : '' }}>
                                        {{ $manager->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <!-- Project Status Selection Field -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold text-dark">Project Status</label>
                                <select name="status" class="form-control @error('status') is-invalid @enderror">
                                    <option value="in_progress" {{ old('status', $project->status) == 'in_progress' ?
                                        'selected' : '' }}>In Progress</option>
                                    <option value="pending" {{ old('status', $project->status) == 'pending' ? 'selected'
                                        : '' }}>Pending</option>
                                    <option value="completed" {{ old('status', $project->status) == 'completed' ?
                                        'selected' : '' }}>Completed</option>
                                </select>
                            </div>
                        </div>

                        <!-- Project Start Date Field -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold text-dark">Start Date</label>
                                <input type="date" name="start_date"
                                    class="form-control @error('start_date') is-invalid @enderror"
                                    value="{{ old('start_date', optional($project->start_date)->format('Y-m-d') ?? $project->start_date) }}">
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
                                    value="{{ old('end_date', optional($project->end_date)->format('Y-m-d') ?? $project->end_date) }}">
                            </div>
                        </div>

                        <!-- Project Budget Field (Added) -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold text-dark">Project Budget ($)</label>
                                <input type="number" step="0.01" name="budget"
                                    class="form-control @error('budget') is-invalid @enderror"
                                    placeholder="Enter project budget..." value="{{ old('budget', $project->budget) }}">
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <!-- Project Description Textarea Field -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold text-dark">Description</label>
                                <textarea name="description" rows="4"
                                    class="form-control @error('description') is-invalid @enderror"
                                    placeholder="Enter project description and objectives...">{{ old('description', $project->description) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!--
                      ====================================================================
                      FORM ACTION BUTTONS SECTION
                      ====================================================================
                      Contains navigation cancel button and submission trigger button.
                      ====================================================================
                    -->
                    <div class="row mt-4">
                        <div class="col-md-12 text-right">
                            <!-- Cancel and return to index route -->
                            <a href="{{ route('admin.project.index') }}"
                                class="btn btn-secondary btn-round px-4 mr-2 shadow-sm">
                                Cancel
                            </a>

                            <!-- Submit Form Button -->
                            <button type="submit" class="btn btn-primary btn-round px-4 shadow-sm">
                                Update Project
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection

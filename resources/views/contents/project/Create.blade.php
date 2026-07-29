@extends('layouts.app')

@section('Main_Content')

<!-- Main Form Card Container Centered -->
<div class="row justify-content-center">
    <div class="col-lg-9 col-md-10">
        <div class="card shadow-sm border-0 project-form-card">

            <!-- Styled Card Header with Gradient and Centered/Aligned Content -->
            <div class="card-header custom-card-header text-white d-flex align-items-center py-3 px-4">
                <div class="icon icon-shape bg-white text-primary rounded-circle shadow-sm mr-3">
                    <i class="now-ui-icons ui-1_simple-add"></i>
                </div>
                <div>
                    <h4 class="font-weight-bold text-white mb-0">Create New Project</h4>
                    <p class="text-white-50 text-sm mb-0">Fill in the details below to add a new system project.</p>
                </div>
            </div>

            <div class="card-body px-5 py-4">
                <form action="{{ route('admin.project.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <!-- Project Title Field -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold text-dark">Project Title</label>
                                <input type="text" name="title" class="form-control"
                                    placeholder="Enter project title..." value="{{ old('title') }}" required>
                                @error('title')
                                <span class="text-danger text-sm mt-1 d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Project Manager Field -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold text-dark">Assign Manager</label>
                                <select name="manager_id" class="form-control" required>
                                    <option value="" selected disabled>Select project manager...</option>
                                    {{-- Assuming you pass $managers from your controller --}}
                                    {{-- @foreach($managers as $manager) --}}
                                    {{-- <option value="{{ $manager->id }}">{{ $manager->name }}</option> --}}
                                    {{-- @endforeach --}}
                                </select>
                                @error('manager_id')
                                <span class="text-danger text-sm mt-1 d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <!-- Project Status Field -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold text-dark">Project Status</label>
                                <select name="status" class="form-control">
                                    <option value="In Progress">In Progress</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Completed">Completed</option>
                                </select>
                                @error('status')
                                <span class="text-danger text-sm mt-1 d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <!-- Project Description Field -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold text-dark">Description</label>
                                <textarea name="description" rows="4" class="form-control"
                                    placeholder="Enter project description and objectives...">{{ old('description') }}</textarea>
                                @error('description')
                                <span class="text-danger text-sm mt-1 d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Form Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-md-12 text-right">
                            <a href="{{ route('admin.project.index') }}"
                                class="btn btn-secondary btn-round px-4 mr-2 shadow-sm">
                                Cancel
                            </a>
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
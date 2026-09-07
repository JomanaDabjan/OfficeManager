@extends('layouts.app')

<!-- Set the dynamic title for this specific page -->
@section('title', 'Create Team')

@section('Main_Content')

<!-- ========================================================================= -->
<!-- MAIN FORM CONTAINER SECTION                                               -->
<!-- ========================================================================= -->

@can('create', \App\Models\Team::class)

<!-- Main Form Card Container Centered -->
<div class="row justify-content-center mt-4 mb-4">
    <div class="col-lg-9 col-md-10">



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
                    <h4 class="font-weight-bold text-white mb-0">Create New Team</h4>
                    <p class="text-white-50 text-sm mb-0">Fill in the details below to create a new team.</p>
                </div>
            </div>

            <div class="card-body px-5 py-4">
                <!-- Form with multipart/form-data to support file/logo uploads if needed -->
                <form action="{{ route('admin.team.store') }}" method="POST" enctype="multipart/form-data">
                    <!-- CSRF Security Token Protection -->
                    @csrf

                    <!-- ========================================================= -->
                    <!-- ROW 1: PROJECT SELECTION                                  -->
                    <!-- ========================================================= -->
                    <div class="row">
                        <!-- Project Selection Field -->
                        <div class="col-md-12">
                            <!-- Include Session Alert Message Component -->
                            <x-alert-message />
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold text-dark">Project</label>
                                <!-- Added select2-ajax class for dynamic search and pagination handling -->
                                <select name="project_id" class="form-control select2-ajax" required>
                                    <option value="" disabled selected>Select a project...</option>
                                    <!-- Preserve old selected project if validation fails -->
                                    @if(isset($selectedProject))
                                    <option value="{{ $selectedProject->id }}" selected>{{ $selectedProject->title }}
                                    </option>
                                    @endif
                                </select>
                                @error('project_id')
                                <span class="text-danger text-sm mt-1 d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- ========================================================= -->
                    <!-- ROW: TEAM LEADER SELECTION                                -->
                    <!-- ========================================================= -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold text-dark">Team Leader</label>
                                <select name="team_leader_id" class="form-control select2" required>
                                    <option value="" disabled selected>Select a team leader...</option>
                                    @foreach($teamLeaders ?? [] as $leader)
                                    <option value="{{ $leader->id }}" {{ old('team_leader_id')==$leader->id ? 'selected'
                                        : '' }}>
                                        {{ $leader->name }} ({{ ucfirst($leader->role) }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('team_leader_id')
                                <span class="text-danger text-sm mt-1 d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- ========================================================= -->
                    <!-- ROW 2: TEAM NAME                                          -->
                    <!-- ========================================================= -->
                    <div class="row mt-3">
                        <!-- Team Name Field -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold text-dark">Team Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Enter team name..."
                                    value="{{ old('name') }}" required>
                                @error('name')
                                <span class="text-danger text-sm mt-1 d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- ========================================================= -->
                    <!-- ROW 3: TEAM DESCRIPTION                                   -->
                    <!-- ========================================================= -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold text-dark">Description</label>
                                <textarea name="description" rows="4" class="form-control"
                                    placeholder="Enter team description and objectives...">{{ old('description') }}</textarea>
                                @error('description')
                                <span class="text-danger text-sm mt-1 d-block">{{ $message++ }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- ========================================================= -->
                    <!-- FORM SUBMISSION BUTTONS SECTION                           -->
                    <!-- ========================================================= -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="text-right">
                                <a href="{{ route('admin.team.create') }}"
                                    class="btn btn-secondary btn-round mr-2">Cancel</a>
                                <button type="submit" class="btn btn-primary btn-round">Save Team</button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endcan

@endsection
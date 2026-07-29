@extends('layouts.app')

@section('Main_Content')

<!-- ========================================== -->
<!-- PAGE HEADER AND CREATION ACTION BUTTON     -->
<!-- ========================================== -->
<div class="row mt-4 mb-4 align-items-center">
    <div class="col-md-6">
        <h3 class="font-weight-bold text-dark mb-0">Projects Management</h3>
        <p class="text-muted text-sm mb-0">Manage all system projects, assign managers, and track statuses.</p>
    </div>
    <div class="col-md-6 text-right">
        <!-- Render the 'Add New Project' button for administrators -->
        <a href="{{ route('admin.project.create') }}" class="btn btn-primary btn-round text-white shadow-sm px-4">
            <i class="now-ui-icons ui-1_simple-add"></i> Add New Project
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
            <input type="text" id="projectSearchInput" class="form-control border rounded-pill shadow-sm"
                placeholder="Search projects..." style="background-color: #f9fbfd;">
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MAIN PROJECTS TABLE CARD SECTION           -->
<!-- ========================================== -->
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-body px-0 pb-0">
                <div class="table-responsive">
                    <table class="table align-items-center table-flush mb-0" id="projectsTable">
                        <!-- Table Headings with Gradient Style -->
                        <thead style="background: linear-gradient(135deg, #f96332 0%, #ff8c42 100%); color: white;">
                            <tr>
                                <th class="py-3 font-weight-bold text-white pl-4">Title</th>
                                <th class="py-3 font-weight-bold text-white">Description</th>
                                <th class="py-3 font-weight-bold text-white">Manager</th>
                                <th class="py-3 font-weight-bold text-white">Status</th>
                                <th class="py-3 font-weight-bold text-white text-right pr-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Loop through each project record using Laravel forelse directive -->
                            @forelse($projects as $project)
                            <tr class="border-bottom project-row">
                                <!-- Project Title Column -->
                                <td class="font-weight-bold text-dark pl-4 align-middle project-title">
                                    {{ $project->title }}
                                </td>

                                <!-- Project Description Column (Truncated for clean view) -->
                                <td class="text-muted align-middle project-desc">
                                    {{ Str::limit($project->description, 45) }}
                                </td>

                                <!-- Assigned Manager Column with Avatar Initials -->
                                <td class="align-middle project-manager">
                                    <div class="d-flex align-items-center">
                                        <span
                                            class="avatar-sm rounded-circle bg-light text-primary font-weight-bold d-flex align-items-center justify-content-center shadow-sm mr-2"
                                            style="width: 32px; height: 32px; font-size: 12px;">
                                            {{ strtoupper(substr(optional($project->manager)->name ?? 'U', 0, 2)) }}
                                        </span>
                                        <span class="text-dark font-weight-normal">
                                            {{ optional($project->manager)->name ?? 'No Manager' }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Project Status Column with Dynamic Color Badges -->
                                <td class="align-middle project-status">
                                    <span class="badge badge-pill
                                        @if($project->status == 'completed') badge-success
                                        @elseif($project->status == 'in_progress') badge-warning
                                        @elseif($project->status == 'pending') badge-info
                                        @else badge-secondary @endif px-3 py-2 text-white shadow-sm">
                                        {{ ucfirst(str_replace('_', ' ', $project->status ?? 'in_progress')) }}
                                    </span>
                                </td>

                                <!-- ========================================== -->
                                <!-- ACTIONS COLUMN (VIEW, EDIT, DELETE)        -->
                                <!-- ========================================== -->
                                <td class="text-right pr-4 align-middle">
                                    <!-- View Project Details Button -->
                                    <a href="{{ route('admin.project.show', $project->id) }}"
                                        class="btn btn-info btn-round btn-icon btn-sm" title="View">
                                        <i class="now-ui-icons design_image"></i>
                                    </a>

                                    <!-- Edit Project Button -->
                                    <a href="{{ route('admin.project.edit', $project->id) }}"
                                        class="btn btn-success btn-round btn-icon btn-sm" title="Edit">
                                        <i class="now-ui-icons ui-2_settings-90"></i>
                                    </a>

                                    <!-- Delete Form with Confirmation Prompt -->
                                    <form action="{{ route('admin.project.destroy', $project->id) }}" method="POST"
                                        style="display: inline-block;"
                                        onsubmit="return confirm('Are you sure you want to delete this project?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-round btn-icon btn-sm"
                                            title="Delete">
                                            <i class="now-ui-icons ui-1_simple-remove"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <!-- Empty State Row when no projects match filter criteria -->
                            <tr id="noProjectsDefault">
                                <td colspan="5" class="text-center text-muted py-5">
                                    <div class="py-4">
                                        <i class="now-ui-icons business_briefcase-24 fa-3x mb-3 text-muted"
                                            style="font-size: 28px;"></i>
                                        <p class="font-weight-bold mb-1">No projects found.</p>
                                        <p class="text-sm text-muted">Click "Add New Project" to create one.</p>
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
                @if($projects->hasPages())
                <div class="card-footer bg-white py-4 d-flex justify-content-between align-items-center">
                    <div class="text-muted text-sm">
                        Showing <b>{{ $projects->firstItem() }}</b> to <b>{{ $projects->lastItem() }}</b> of <b>{{
                            $projects->total() }}</b> entries
                    </div>
                    <div>
                        {{ $projects->links('pagination::bootstrap-4') }}
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>


@endsection

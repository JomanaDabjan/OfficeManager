@extends('layouts.app')

@section('Main_Content')

<div class="row mt-4 mb-4 align-items-center">
    <div class="col-md-6">
        <h3 class="font-weight-bold text-dark mb-0">Employees Management</h3>
        <p class="text-muted text-sm mb-0">Manage all system employees, track their roles, departments, and status.</p>
    </div>
    <div class="col-md-6 text-right">
        @if(auth()->user()->role === 'admin')
        <a href="{{ route('admin.user.create') }}" class="btn btn-primary btn-round text-white shadow-sm px-4">
            <i class="now-ui-icons ui-1_simple-add"></i> Add New Employee
        </a>
        @endif
    </div>
</div>


<div class="row mb-3">
    <div class="col-md-12">
        <div class="search-container position-relative">
            <i class="now-ui-icons ui-1_zoom-bold search-icon" style="position: absolute; top: 14px; left: 15px; color: #888;"></i>
            <input type="text" id="employeeSearchInput" class="form-control border rounded-pill shadow-sm pl-5"
                placeholder="Search employees..." style="background-color: #f9fbfd; padding-left: 40px !important;">
        </div>
    </div>
</div>

<!-- SUCCESS ALERT NOTIFICATION -->
@if(session("success"))
<div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-3" role="alert">
    <span class="text-white">{{ session("success") }}</span>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif



<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-body px-0 pb-0">
                <div class="table-responsive">
                    <table class="table align-items-center table-flush mb-0">
                        <thead style="background: linear-gradient(135deg, #f96332 0%, #ff8c42 100%); color: white;">
                            <tr>
                              <th class="py-3 text-white pl-4" style="border-right: 1px solid rgba(255,255,255,0.2); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">#</th>
                                <th class="py-3 text-white text-center" style="border-right: 1px solid rgba(255,255,255,0.2); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Name</th>
                                <th class="py-3 text-white text-center" style="border-right: 1px solid rgba(255,255,255,0.2); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Email</th>
                                <th class="py-3 text-white text-center" style="border-right: 1px solid rgba(255,255,255,0.2); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Role</th>
                                <th class="py-3 text-white text-center" style="border-right: 1px solid rgba(255,255,255,0.2); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Position</th>
                                <th class="py-3 text-white text-center" style="border-right: 1px solid rgba(255,255,255,0.2); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Department</th>
                                <th class="py-3 text-white text-center" style="border-right: 1px solid rgba(255,255,255,0.2); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Status</th>
                                <th class="py-3 text-white text-right pr-4" style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr class="border-bottom">
                                <td class="font-weight-bold text-dark pl-4 align-middle">
                                    {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                                </td>

                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <span class="avatar-sm rounded-circle bg-light text-primary font-weight-bold d-flex align-items-center justify-content-center shadow-sm mr-2" style="width: 32px; height: 32px; font-size: 12px;">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </span>
                                        <span class="text-dark font-weight-bold">
                                            {{ $user->name }}
                                        </span>
                                    </div>
                                </td>

                                <td class="text-muted align-middle">
                                    {{ $user->email }}
                                </td>

                                <td class="text-muted align-middle">
                                    <span class="badge badge-pill badge-neutral text-dark border px-3 py-1">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>

                                <td class="text-muted align-middle">
                                    {{ $user->position ?? "N/A" }}
                                </td>

                                <td class="text-muted align-middle">
                                    {{ $user->department ?? "N/A" }}
                                </td>

                                <td class="align-middle">
                                    <span class="badge badge-pill @if($user->status == "active") badge-success @else badge-secondary @endif px-3 py-2 text-white shadow-sm">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>

                                <td class="text-right pr-4 align-middle">
                                    <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-success btn-round btn-icon btn-sm" title="Edit">
                                        <i class="now-ui-icons ui-2_settings-90"></i>
                                    </a>
                                    <form id="delete-form-{{ $user->id }}" action="{{ route('admin.user.destroy', $user->id) }}" method="POST" style="display: inline-block;">
                                         @csrf
                                         @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-round btn-icon btn-sm" title="Delete" onclick="confirmDelete({{ $user->id }})">
                                            <i class="now-ui-icons ui-1_simple-remove"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <div class="py-4">
                                        <i class="now-ui-icons users_single-02 text-muted mb-3" style="font-size: 28px;"></i>
                                        <p class="font-weight-bold mb-1">No employees found.</p>
                                        <p class="text-sm text-muted">Click "Add New Employee" to create one.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

              
                @if($users->hasPages())
                <div class="card-footer bg-white py-4 d-flex justify-content-between align-items-center">
                    <div class="text-muted text-sm">
                        Showing <b>{{ $users->firstItem() }}</b> to <b>{{ $users->lastItem() }}</b> of <b>{{ $users->total() }}</b> entries
                    </div>
                    <div>
                        {{ $users->links("pagination::bootstrap-4") }}
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
@push('Script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDelete(userId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f96332', 
        cancelButtonColor: '#888888',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + userId).submit();
        }
    });
}
</script>
@endpush

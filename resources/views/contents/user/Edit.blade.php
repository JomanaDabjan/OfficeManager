@extends('layouts.app')

@section('Main_Content')

<div class="container-fluid">
    <div class="card shadow mb-4" style="border: none; border-radius: 12px; overflow: hidden;">
        <div class="card-header py-4 text-white" style="background-color: #f96332; border: none;">
            <div class="d-flex align-items-center">
                <div class="text-center rounded-circle bg-white text-primary mr-3 shadow-sm d-flex align-items-center justify-content-center"
                    style="width: 48px; height: 48px; min-width: 48px;">
                    <i class="now-ui-icons ui-2_settings-90" style="font-size: 20px;"></i>
                </div>
                <div>
                    <h4 class="m-0 font-weight-bold text-white">Edit User: {{ $user->name }}</h4>
                    <p class="mb-0 text-white-50" style="font-size: 14px;">Update the user details below.</p>
                </div>
            </div>
        </div>

        <div class="card-body p-4">
            @if ($errors->any())
            <div class="alert alert-danger" style="border-radius: 8px;">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @can('update', $user)

            <form action="{{ route('admin.user.update', $user->id) }}" method="POST" autocomplete="off">
                @csrf
                @method('PUT')

                <input type="text" name="fake_username" style="display:none;" autocomplete="username">
                <input type="password" name="fake_password" style="display:none;" autocomplete="current-password">

                <div class="form-group mb-4">
                    <label for="name" class="form-label font-weight-bold text-muted"
                        style="font-size: 12px; letter-spacing: 1px;">FULL NAME</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}"
                        placeholder="Enter full name..." autocomplete="off" required
                        style="border-radius: 8px; padding: 12px 15px; height: auto;">
                </div>

                <div class="form-group mb-4">
                    <label for="email" class="form-label font-weight-bold text-muted"
                        style="font-size: 12px; letter-spacing: 1px;">EMAIL ADDRESS</label>
                    <input type="email" class="form-control" id="email" name="email"
                        value="{{ old('email', $user->email) }}" placeholder="Enter email address..."
                        autocomplete="new-email" required style="border-radius: 8px; padding: 12px 15px; height: auto;">
                </div>

                <div class="form-group mb-4">
                    <label for="password" class="form-label font-weight-bold text-muted"
                        style="font-size: 12px; letter-spacing: 1px;">PASSWORD</label>
                    <input type="password" class="form-control" id="password" name="password"
                        placeholder="Leave blank if you don't want to change the password..."
                        autocomplete="new-password" style="border-radius: 8px; padding: 12px 15px; height: auto;">
                </div>

                <div class="form-group mb-4">
                    <label for="role" class="form-label font-weight-bold text-muted"
                        style="font-size: 12px; letter-spacing: 1px;">ROLE</label>
                    <select class="form-control" id="role" name="role" required
                        style="border-radius: 8px; padding: 10px 15px; height: auto;">
                        <option value="">Select Role</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="project_manager" {{ old('role', $user->role) == 'project_manager' ? 'selected' :
                            '' }}>Project Manager</option>
                        <option value="team_leader" {{ old('role', $user->role) == 'team_leader' ? 'selected' : ''
                            }}>Team Leader</option>
                        <option value="employee" {{ old('role', $user->role) == 'employee' ? 'selected' : '' }}>Employee
                        </option>
                    </select>
                </div>

                <div class="form-group mb-4">
                    <label for="phone" class="form-label font-weight-bold text-muted"
                        style="font-size: 12px; letter-spacing: 1px;">PHONE NUMBER</label>
                    <input type="text" class="form-control" id="phone" name="phone"
                        value="{{ old('phone', $user->phone) }}" placeholder="Enter phone number..."
                        style="border-radius: 8px; padding: 12px 15px; height: auto;">
                </div>

                <div class="form-group mb-4">
                    <label for="position" class="form-label font-weight-bold text-muted"
                        style="font-size: 12px; letter-spacing: 1px;">POSITION</label>
                    <input type="text" class="form-control" id="position" name="position"
                        value="{{ old('position', $user->position) }}" placeholder="Enter job position..."
                        style="border-radius: 8px; padding: 12px 15px; height: auto;">
                </div>

                <div class="form-group mb-4">
                    <label for="department" class="form-label font-weight-bold text-muted"
                        style="font-size: 12px; letter-spacing: 1px;">DEPARTMENT</label>
                    <input type="text" class="form-control" id="department" name="department"
                        value="{{ old('department', $user->department) }}" placeholder="Enter department name..."
                        style="border-radius: 8px; padding: 12px 15px; height: auto;">
                </div>

                <div class="form-group mb-4">
                    <label for="status" class="form-label font-weight-bold text-muted"
                        style="font-size: 12px; letter-spacing: 1px;">STATUS</label>
                    <select class="form-control" id="status" name="status"
                        style="border-radius: 8px; padding: 10px 15px; height: auto;">
                        <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active
                        </option>
                        <option value="deactivated" {{ old('status', $user->status) == 'deactivated' ? 'selected' : ''
                            }}>Deactivated</option>
                    </select>
                </div>

                <div class="form-group mb-4">
                    <label for="working_hours" class="form-label font-weight-bold text-muted"
                        style="font-size: 12px; letter-spacing: 1px;">WORKING HOURS</label>
                    <input type="number" class="form-control" id="working_hours" name="working_hours"
                        value="{{ old('working_hours', $user->working_hours) }}" placeholder="Enter working hours..."
                        style="border-radius: 8px; padding: 12px 15px; height: auto;">
                </div>

                <div class="form-group mb-4">
                    <label for="joining_date" class="form-label font-weight-bold text-muted"
                        style="font-size: 12px; letter-spacing: 1px;">JOINING DATE</label>
                    <input type="date" class="form-control" id="joining_date" name="joining_date"
                        value="{{ old('joining_date', $user->joining_date) }}"
                        style="border-radius: 8px; padding: 12px 15px; height: auto;">
                </div>

                <div class="d-flex justify-content-end align-items-center mt-5 pt-3 border-top" style="gap: 10px;">
                    <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-secondary px-4 py-2"
                        style="border-radius: 20px; font-weight: 600; background-color: #888888; border: none;">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4 py-2"
                        style="border-radius: 20px; font-weight: 600; background-color: #f96332; border: none;">Update
                        User</button>
                </div>

            </form>

            @endcan
        </div>
    </div>
</div>
@endsection
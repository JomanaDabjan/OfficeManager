@extends('layouts.app')

@section('title', 'members add form')
@section('Main_Content')
<div class="content py-5 d-flex align-items-center justify-content-center" style="min-height: 85vh;">
    <div class="row justify-content-center w-100">
        <div class="col-md-8">
            <!-- Now UI Styled Card -->
            <div class="card shadow-sm border-0">

                <!-- Card Header with Now UI theme -->
                <div class="card-header text-white py-4 px-4" style="background-color: #f97316;">
                    <div class="d-flex align-items-center">
                        <div class="bg-white rounded-circle shadow mr-3 me-3 d-flex align-items-center justify-content-center"
                            style="width: 50px; height: 50px; min-width: 50px; color: #f97316; font-size: 24px; font-weight: bold;">
                            +
                        </div>
                        <div>
                            <h4 class="card-title text-white mb-1 font-weight-bold">Add New Members</h4>
                            <p class="category text-white opacity-75 mb-0">Team: <strong>{{ $team->name }}</strong></p>
                        </div>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body p-4">
                    <form action="{{ route('admin.team.store', $team->id) }}" method="POST">
                        @csrf

                        <!-- Validation Errors Alert -->
                        @if ($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm mb-4">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <!-- Form Group for Employees Select -->
                        <div class="form-group mb-4">
                            <label for="members"
                                class="text-uppercase text-muted font-weight-bold small d-block mb-2">Select
                                Employees</label>
                            <select name="members[]" id="members" class="form-control w-150" multiple required
                                style="min-height: 180px; height: auto; width: 100%;">
                                @php
                                $groupedEmployees = $employees->groupBy(function($employee) {
                                if (strtolower($employee->position) === 'full stack' || strtolower($employee->role ??
                                '') === 'team leader') {
                                return 'Full Stack';
                                }
                                return $employee->position ?: 'Unspecified Position';
                                });
                                @endphp

                                @foreach($groupedEmployees as $position => $group)
                                <optgroup label="{{ $position }}">
                                    @foreach($group as $employee)
                                    <option value="{{ $employee->id }}" class="py-2 px-3 border-bottom">
                                        {{ $employee->name }} ({{ $employee->email }})
                                    </option>
                                    @endforeach
                                </optgroup>
                                @endforeach
                            </select>
                            <small class="form-text text-muted mt-2 d-block">
                                <i class="now-ui-icons tech_laptop me-1"></i> Hold Ctrl on keyboard to select multiple
                                employees.
                            </small>
                        </div>

                        <!-- Card Footer / Action Buttons -->
                        <div class="d-flex justify-content-end align-items-center pt-3 border-top">
                            <a href="{{ route('admin.team.create', $team->id) }}"
                                class="btn btn-secondary btn-round me-2 px-4">Cancel</a>
                            <button type="submit" class="btn btn-round px-4 text-white"
                                style="background-color: #f97316; border-color: #f97316;">Save Members</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
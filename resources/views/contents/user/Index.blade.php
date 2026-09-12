@extends('layouts.app')

@section('Main_Content')

<!-- ========================================== -->
<!-- SECTION: PAGE HEADER & ACTION BUTTONS        -->
<!-- Top section containing page title and add  -->
<!-- button visible only to admin users.       -->
<!-- ========================================== -->
<div class="row mt-4 mb-4 align-items-center">
    <!-- Column for the page title and description -->
    <div class="col-md-6">
        <h3 class="font-weight-bold text-dark mb-0">Users Management</h3>
        <p class="text-muted text-sm mb-0">Manage all system users, track their roles, departments, and status.</p>
    </div>
    <!-- Column for action buttons aligned to the right -->
    <div class="col-md-6 text-right">
        <!-- Check UserPolicy permission for creating a new user -->
        @can('create', \App\Models\User::class)
        <!-- Button to navigate to the create user form -->
        <a href="{{ route('admin.user.create') }}" class="btn btn-primary btn-round text-white shadow-sm px-4">
            <i class="now-ui-icons ui-1_simple-add"></i> Add New User
        </a>
        @endcan
    </div>
</div>


<!-- ========================================== -->
<!-- COLUMN FILTERS DROPDOWNS SECTION (SERVER)  -->
<!-- ========================================== -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm" style="border-radius: 16px; background: #ffffff;">
            <div class="card-body p-3">


                <div class="d-flex flex-wrap align-items-center justify-content-between mb-3" style="gap: 10px;">
                    <div class="d-flex flex-wrap align-items-center flex-grow-1" style="gap: 10px;">
                        <span class="text-muted font-weight-bold mr-1 d-none d-xl-inline-block"
                            style="font-size: 13px;">
                            <i class="now-ui-icons ui-1_zoom-bold mr-1 text-primary"></i> Filter By:
                        </span>

                        <!-- Name Filter Dropdown -->
                        <div class="dropdown flex-fill">
                            <button
                                class="btn btn-light btn-sm dropdown-toggle text-dark shadow-none px-3 py-2 font-weight-bold rounded-pill border w-100 text-truncate"
                                type="button" id="dropdownTitle" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false"
                                style="font-size: 13px; background-color: #f8f9fa; border-color: #e3e6f0 !important; height: 35px; display: flex; align-items: center; justify-content: space-between;">
                                <span>{{ request('name') ? Str::limit(request('name'), 15) : 'All Names' }}</span>
                            </button>

                            <div class="dropdown-menu shadow-lg border-0 py-2" aria-labelledby="dropdownTitle"
                                style="border-radius: 12px; min-width: 180px;">

                                <!-- Live Search -->
                                <div class="px-3 pb-2">
                                    <input type="text" id="titleLiveSearch"
                                        class="form-control form-control-sm shadow-none" placeholder="Search titles..."
                                        autocomplete="off" style="border-radius: 8px; font-size: 13px;">
                                </div>

                                <div id="titleList">

                                    <a class="dropdown-item py-2 px-3 text-sm title-option {{ !request('name') ? 'active font-weight-bold text-primary' : '' }}"
                                        href="{{ route('admin.user.index', array_merge(request()->except(['name', 'page']), [])) }}"
                                        data-title="All Titles">
                                        <i class="now-ui-icons ui-1_simple-add mr-2"></i> All Names
                                    </a>

                                    @foreach($allNames as $nameItem)
                                    <a class="dropdown-item py-2 px-3 text-sm title-option {{ request('name') == $nameItem ? 'active font-weight-bold text-primary' : '' }}"
                                        href="{{ route('admin.user.index', array_merge(request()->except(['name', 'page']), ['name' => $nameItem])) }}"
                                        data-title="{{ $nameItem }}">
                                        {{ $nameItem }}
                                    </a>
                                    @endforeach

                                </div>

                                <!-- No Results -->
                                <div id="noTitleResults" class="text-center text-muted py-2 px-3"
                                    style="display: none; font-size: 12px;">
                                    No names found
                                </div>

                            </div>
                        </div>

                        <!-- Role Filter Dropdown -->
                        <div class="dropdown flex-fill" style="min-width: 140px;">
                            <button
                                class="btn btn-light btn-sm dropdown-toggle text-dark shadow-none px-3 py-2 font-weight-bold rounded-pill border w-100 text-truncate"
                                type="button" id="dropdownRole" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false"
                                style="font-size: 13px; background-color: #f8f9fa; border-color: #e3e6f0 !important; height: 35px; display: flex; align-items: center; justify-content: space-between;">
                                <span>{{ request('role') ? ucwords(str_replace('_', ' ', request('role'))) : 'Role'
                                    }}</span>
                            </button>
                            <div class="dropdown-menu shadow-lg border-0 py-2" aria-labelledby="dropdownRole"
                                style="border-radius: 12px; min-width: 160px;">
                                <a class="dropdown-item py-2 px-3 text-sm {{ !request('role') || request('role') == 'all' ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.user.index', array_merge(request()->except(['role', 'page']), ['role' => 'all'])) }}">All
                                    Roles</a>
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('role') == 'admin' ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.user.index', array_merge(request()->except(['role', 'page']), ['role' => 'admin'])) }}">Admin</a>
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('role') == 'project manager' ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.user.index', array_merge(request()->except(['role', 'page']), ['role' => 'project manager'])) }}">Project
                                    Manager</a>
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('role') == 'team_leader' ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.user.index', array_merge(request()->except(['role', 'page']), ['role' => 'team_leader'])) }}">Team
                                    Leader</a>
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('role') == 'employee' ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.user.index', array_merge(request()->except(['role', 'page']), ['role' => 'employee'])) }}">Employee</a>
                            </div>
                        </div>

                        <!-- Position Filter Dropdown -->
                        <div class="dropdown flex-fill" style="min-width: 140px;">
                            <button
                                class="btn btn-light btn-sm dropdown-toggle text-dark shadow-none px-3 py-2 font-weight-bold rounded-pill border w-100 text-truncate"
                                type="button" id="dropdownPosition" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false"
                                style="font-size: 13px; background-color: #f8f9fa; border-color: #e3e6f0 !important; height: 35px; display: flex; align-items: center; justify-content: space-between;">
                                <span>{{ request('position') ? Str::limit(request('position'), 15) : 'All Positions'
                                    }}</span>
                            </button>
                            <div class="dropdown-menu shadow-lg border-0 py-2" aria-labelledby="dropdownPosition"
                                style="border-radius: 12px; min-width: 180px;">
                                <a class="dropdown-item py-2 px-3 text-sm {{ !request('position') ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.user.index', array_merge(request()->except(['position', 'page']), [])) }}">
                                    <i class="now-ui-icons ui-1_simple-add mr-2"></i> All Positions
                                </a>
                                @foreach($allPositions ?? [] as $positionItem)
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('position') == $positionItem ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.user.index', array_merge(request()->except(['position', 'page']), ['position' => $positionItem])) }}">
                                    {{ $positionItem }}
                                </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Department Filter Dropdown -->
                        <div class="dropdown flex-fill" style="min-width: 140px;">
                            <button
                                class="btn btn-light btn-sm dropdown-toggle text-dark shadow-none px-3 py-2 font-weight-bold rounded-pill border w-100 text-truncate"
                                type="button" id="dropdownDepartment" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false"
                                style="font-size: 13px; background-color: #f8f9fa; border-color: #e3e6f0 !important; height: 35px; display: flex; align-items: center; justify-content: space-between;">
                                <span>{{ request('department') ? Str::limit(request('department'), 15) : 'All
                                    Departments' }}</span>
                            </button>
                            <div class="dropdown-menu shadow-lg border-0 py-2" aria-labelledby="dropdownDepartment"
                                style="border-radius: 12px; min-width: 180px;">
                                <a class="dropdown-item py-2 px-3 text-sm {{ !request('department') ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.user.index', array_merge(request()->except(['department', 'page']), [])) }}">
                                    <i class="now-ui-icons ui-1_simple-add mr-2"></i> All Departments
                                </a>
                                @foreach($allDepartments ?? [] as $departmentItem)
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('department') == $departmentItem ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.user.index', array_merge(request()->except(['department', 'page']), ['department' => $departmentItem])) }}">
                                    {{ $departmentItem }}
                                </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Status Filter Dropdown -->
                        <div class="dropdown flex-fill" style="min-width: 140px;">
                            <button
                                class="btn btn-light btn-sm dropdown-toggle text-dark shadow-none px-3 py-2 font-weight-bold rounded-pill border w-100 text-truncate"
                                type="button" id="dropdownStatus" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false"
                                style="font-size: 13px; background-color: #f8f9fa; border-color: #e3e6f0 !important; height: 35px; display: flex; align-items: center; justify-content: space-between;">
                                <span>{{ request('status') ? ucfirst(request('status')) : 'All Statuses' }}</span>
                            </button>
                            <div class="dropdown-menu shadow-lg border-0 py-2" aria-labelledby="dropdownStatus"
                                style="border-radius: 12px; min-width: 160px;">
                                <a class="dropdown-item py-2 px-3 text-sm {{ !request('status') || request('status') == 'all' ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.user.index', array_merge(request()->except(['status', 'page']), ['status' => 'all'])) }}">All
                                    Statuses</a>
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('status') == 'active' ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.user.index', array_merge(request()->except(['status', 'page']), ['status' => 'active'])) }}">Active</a>
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('status') == 'deactivated' ? 'active font-weight-bold text-primary' : '' }}"
                                    href="{{ route('admin.user.index', array_merge(request()->except(['status', 'page']), ['status' => 'deactivated'])) }}">Deactivated</a>
                            </div>
                        </div>
                    </div>

                    <!-- Reset Filters Button -->
                    @if(request()->anyFilled(['name', 'role', 'position', 'department', 'status', 'date_from',
                    'date_to']))
                    <div>
                        <a href="{{ route('admin.user.index') }}"
                            class="btn btn-outline-danger btn-sm rounded-pill px-3 py-2"
                            style="font-size: 12px; white-space: nowrap; height: 35px;">
                            <i class="now-ui-icons ui-1_simple-remove mr-1"></i> Reset
                        </a>
                    </div>
                    @endif
                </div>

                <!-- السطر الثاني: فلاتر التاريخ (Date From & To) -->
                <div class="d-flex flex-wrap align-items-center pt-2 border-top" style="gap: 10px;">
                    <span class="text-muted font-weight-bold mr-1" style="font-size: 13px;">
                        <i class="now-ui-icons ui-1_calendar-60 mr-1 text-primary"></i> Joined Date:
                    </span>
                    <form method="GET" action="{{ route('admin.user.index') }}"
                        class="d-flex align-items-center flex-grow-1 flex-wrap" style="gap: 10px;">
                        @foreach(request()->except(['date_from', 'date_to', 'page']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach

                        <div class="d-flex align-items-center flex-fill"
                            style="background-color: #f8f9fa; border: 1px solid #e3e6f0 !important; border-radius: 50rem; padding: 2px 12px; height: 35px; min-width: 200px;">
                            <span class="text-muted mr-2" style="font-size: 12px; white-space: nowrap;">From:</span>
                            <input type="date" name="date_from" value="{{ request('date_from') }}"
                                class="form-control form-control-sm border-0 bg-transparent shadow-none px-0 py-0 w-100"
                                style="font-size: 12px;" onchange="this.form.submit()">
                        </div>

                        <div class="d-flex align-items-center flex-fill"
                            style="background-color: #f8f9fa; border: 1px solid #e3e6f0 !important; border-radius: 50rem; padding: 2px 12px; height: 35px; min-width: 200px;">
                            <span class="text-muted mr-2" style="font-size: 12px; white-space: nowrap;">To:</span>
                            <input type="date" name="date_to" value="{{ request('date_to') }}"
                                class="form-control form-control-sm border-0 bg-transparent shadow-none px-0 py-0 w-100"
                                style="font-size: 12px;" onchange="this.form.submit()">
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>


<!-- ========================================== -->
<!-- SECTION: SEARCH FILTER INPUT CONTAINER     -->
<!-- Input field to search users dynamically.   -->
<!-- ========================================== -->
<div class="row mb-3">
    <div class="col-md-12">
        <div class="search-container position-relative">
            <!-- Search icon positioned absolutely inside the input wrapper -->
            <i class="now-ui-icons ui-1_zoom-bold search-icon"
                style="position: absolute; top: 50%; left: 18px; transform: translateY(-50%); color: #888; z-index: 5;"></i>
            <!-- Search text input field -->
            <input type="text" id="employeeSearchInput" class="form-control border rounded-pill shadow-sm"
                placeholder="Search users..." style="background-color: #f9fbfd; padding-left: 45px !important;">
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- SECTION: USERS TABLE CARD CONTAINER        -->
<!-- Main card wrapper holding the data table.  -->
<!-- ========================================== -->
<div class="row">
    <div class="col-md-12">
        <x-alert-message />
        <div class="card shadow-sm border-0">
            <div class="card-body px-0 pb-0">
                <!-- Responsive wrapper to enable horizontal scrolling on small screens -->
                <div class="table-responsive">

                    @can('viewAny', \App\Models\User::class)

                    <table class="table align-items-center table-flush mb-0" id="usersTable"
                        style="table-layout: auto; width: 100%;">

                        <!-- Table header with gradient background -->
                        <thead style="background: linear-gradient(135deg, #f96332 0%, #ff8c42 100%); color: white;">
                            <tr id="tableHeaders">

                                <!-- Draggable header cell for Name column -->
                                <th class="py-3 text-white text-center pl-4 draggable-header" draggable="true"
                                    data-column="0"
                                    style="cursor: grab; border-right: 1px solid rgba(255,255,255,0.2); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; width: 25%;">
                                    Name <i class="now-ui-icons arrows-1_move-horizontal ml-1"
                                        style="font-size: 10px; opacity: 0.7;"></i>
                                </th>

                                <!-- Draggable header cell for Email column -->
                                <th class="py-3 text-white text-center draggable-header" draggable="true"
                                    data-column="1"
                                    style="cursor: grab; border-right: 1px solid rgba(255,255,255,0.2); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; width: 23%;">
                                    Email <i class="now-ui-icons arrows-1_move-horizontal ml-1"
                                        style="font-size: 10px; opacity: 0.7;"></i>
                                </th>

                                <!-- Draggable header cell for Role column -->
                                <th class="py-3 text-white text-center draggable-header" draggable="true"
                                    data-column="2"
                                    style="cursor: grab; border-right: 1px solid rgba(255,255,255,0.2); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; width: 13%;">
                                    Role <i class="now-ui-icons arrows-1_move-horizontal ml-1"
                                        style="font-size: 10px; opacity: 0.7;"></i>
                                </th>

                                <!-- Draggable header cell for Position column -->
                                <th class="py-3 text-white text-center draggable-header" draggable="true"
                                    data-column="3"
                                    style="cursor: grab; border-right: 1px solid rgba(255,255,255,0.2); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; width: 15%;">
                                    Position <i class="now-ui-icons arrows-1_move-horizontal ml-1"
                                        style="font-size: 10px; opacity: 0.7;"></i>
                                </th>

                                <!-- Draggable header cell for Status column -->
                                <th class="py-3 text-white text-center draggable-header" draggable="true"
                                    data-column="4"
                                    style="cursor: grab; border-right: 1px solid rgba(255,255,255,0.2); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; width: 12%;">
                                    Status <i class="now-ui-icons arrows-1_move-horizontal ml-1"
                                        style="font-size: 10px; opacity: 0.7;"></i>
                                </th>

                                <!-- Draggable header cell for Actions column -->
                                <th class="py-3 text-white text-center pr-4 draggable-header" draggable="true"
                                    data-column="5"
                                    style="cursor: grab; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; width: 12%;">
                                    Actions <i class="now-ui-icons arrows-1_move-horizontal ml-1"
                                        style="font-size: 10px; opacity: 0.7;"></i>
                                </th>

                            </tr>
                        </thead>

                        <!-- Table body containing dynamic user records -->
                        <tbody>

                            <!-- Loop through each user collection item -->
                            @forelse($users as $user)

                            <tr class="border-bottom">

                                <!-- Table data cell for user name with avatar initial badge -->
                                <td class="align-middle text-left pl-4" data-col-index="0"
                                    style="word-wrap: break-word; white-space: normal;">

                                    <div class="d-flex align-items-center">

                                        <!-- Display first two letters of the user name as an uppercase avatar badge -->
                                        <span
                                            class="avatar-sm rounded-circle bg-light text-primary font-weight-bold d-flex align-items-center justify-content-center shadow-sm mr-2 flex-shrink-0"
                                            style="width: 32px; height: 32px; font-size: 12px;">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </span>

                                        <!-- Output the full user name -->
                                        <span class="text-dark font-weight-bold" style="word-break: break-word;">
                                            {{ $user->name }}
                                        </span>

                                    </div>
                                </td>

                                <!-- Table data cell for user email address -->
                                <td class="text-muted align-middle text-center" data-col-index="1"
                                    style="word-wrap: break-word; white-space: normal; word-break: break-all;">
                                    {{ $user->email }}
                                </td>

                                <!-- Table data cell for user role badge -->
                                <td class="text-muted align-middle text-center" data-col-index="2"
                                    style="word-wrap: break-word; white-space: normal;">
                                    <span class="badge badge-pill badge-neutral text-dark border px-3 py-1">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>

                                <!-- Table data cell for user position or fallback to N/A -->
                                <td class="text-muted align-middle text-center" data-col-index="3"
                                    style="word-wrap: break-word; white-space: normal;">
                                    {{ $user->position ?? "N/A" }}
                                </td>

                                <!-- Table data cell for user active/inactive status badge -->
                                <td class="align-middle text-center" data-col-index="4"
                                    style="word-wrap: break-word; white-space: normal;">
                                    <span
                                        class="badge badge-pill @if($user->status == 'active') badge-success @else badge-danger @endif px-3 py-2 text-white shadow-sm">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>

                                <!-- Table data cell for record action buttons (Show, Edit, Delete) -->
                                <td class="text-right pr-4 align-middle" data-col-index="5">

                                    <div class="btn-group" role="group" aria-label="User Actions">

                                        <!-- View User Details Button -->
                                        @can('view', $user)
                                        <a href="{{ route('admin.user.show', $user->id) }}"
                                            class="btn btn-info btn-sm btn-icon shadow-sm mx-1 rounded"
                                            title="View Details"
                                            style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                            <i class="now-ui-icons business_bulb-63" style="font-size: 13px;"></i>
                                        </a>
                                        @endcan

                                        <!-- Edit User Button -->
                                        @can('update', $user)
                                        <a href="{{ route('admin.user.edit', $user->id) }}"
                                            class="btn btn-warning btn-sm btn-icon shadow-sm mx-1 rounded"
                                            title="Edit User"
                                            style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                            <i class="now-ui-icons ui-2_settings-90" style="font-size: 13px;"></i>
                                        </a>
                                        @endcan

                                        <!-- Delete Form -->
                                        @can('delete', $user)
                                        <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST"
                                            style="display: inline-block;" id="delete-form-user-{{ $user->id }}">

                                            <!-- Include CSRF token for security and spoof DELETE method -->
                                            @csrf
                                            @method('DELETE')

                                            <!-- Trigger button for deletion confirmation modal function -->
                                            <button type="button"
                                                class="btn btn-danger btn-sm btn-icon shadow-sm mx-1 rounded"
                                                title="Delete User" onclick="confirmDelete('user', {{ $user->id }})">

                                                <!-- Trash/Remove icon -->
                                                <i class="now-ui-icons ui-1_simple-remove" style="font-size: 13px;"></i>

                                            </button>
                                        </form>
                                        @endcan

                                    </div>

                                </td>

                            </tr>

                            @empty
                            <tr>
                                <td colspan="6" class="p-0">
                                    <div class="datatable-empty-state"
                                        style="padding: 45px 20px; text-align: center; width: 100%;">
                                        <div
                                            style="width: 64px; height: 64px; margin: 0 auto 16px auto; border-radius: 50%; background: linear-gradient(135deg, #fff1eb 0%, #ffe4d8 100%); display: flex; align-items: center; justify-content: center; box-shadow: 0 6px 18px rgba(249, 99, 50, 0.12);">
                                            <i class="now-ui-icons users_single-02"
                                                style="font-size: 28px; color: #f96332;"></i>
                                        </div>
                                        <div
                                            style="font-size: 16px; font-weight: 700; color: #32325d; margin-bottom: 6px;">
                                            No users available
                                        </div>
                                        <div
                                            style="font-size: 13px; color: #8898aa; max-width: 420px; margin: 0 auto; line-height: 1.6;">
                                            There is no user data to display at the moment.
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>

                    </table>

                    @endcan

                </div>

                <!-- ========================================== -->
                <!-- SECTION: PAGINATION CONTROLS               -->
                <!-- Renders pagination links if pages exist.   -->
                <!-- ========================================== -->
                @if($users->hasPages())
                <div class="card-footer bg-white py-4 d-flex justify-content-between align-items-center">

                    <!-- Display pagination entry counts -->
                    <div class="text-muted text-sm">
                        Showing <b>{{ $users->firstItem() }}</b> to <b>{{ $users->lastItem() }}</b> of <b>{{
                            $users->total() }}</b> entries
                    </div>

                    <!-- Render pagination links using bootstrap-4 view -->
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
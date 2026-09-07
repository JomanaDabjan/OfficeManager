@extends('layouts.app') 
 
@section('title', 'User Report') 
@section('Main_Content') 
 
@can('viewAny', \App\Models\Report::class)

<!-- ========================================== --> 
<!-- PAGE TITLE + EXPORT & PRINT SECTION       --> 
<!-- ========================================== --> 
<div class="row mb-4 pt-4"> 
    <div class="col-md-12"> 
        <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap: 15px;"> 
 
            <!-- Page Title --> 
            <div> 
                <h2 class="font-weight-bold text-dark mb-1" style="font-size: 30px;"> 
                    User Report 
                </h2> 
 
                <p class="text-muted mb-0" style="font-size: 14px;"> 
                    View and manage user reports, track user roles, departments, positions, and account status. 
                </p> 
            </div> 
 
            <!-- Export & Print Dropdown --> 
            <div class="text-right"> 
                <div class="dropdown d-inline-block"> 
 
                    {{-- Dropdown Toggle Button --}} 
                    <button class="btn btn-primary btn-round dropdown-toggle text-white shadow-sm px-4" type="button" 
                        id="exportDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> 
 
                        <i class="now-ui-icons files_single-copy-04 mr-1"></i> 
                        Export & Print 
 
                    </button> 
 
                    {{-- Dropdown Menu Options --}} 
                    <div class="dropdown-menu dropdown-menu-right shadow-lg border-0 py-2" 
                        aria-labelledby="exportDropdown" style="border-radius: 12px;"> 
 
                        {{-- DOWNLOAD PDF ACTION --}} 
                        <a class="dropdown-item py-2 px-3 text-sm font-weight-bold" href="javascript:void(0);" 
                            id="downloadPdfBtn"> 
 
                            <i class="now-ui-icons files_paper text-danger mr-2"></i> 
                            Download PDF 
 
                        </a> 
 
                        {{-- DOWNLOAD EXCEL ACTION --}} 
                        <a class="dropdown-item py-2 px-3 text-sm font-weight-bold" href="javascript:void(0);" 
                            id="downloadExcelBtn"> 
 
                            <i class="now-ui-icons business_chart-pie-36 text-success mr-2"></i> 
                            Download Excel 
 
                        </a> 
 
                        {{-- Divider line --}} 
                        <div class="dropdown-divider"></div> 
 
                        {{-- PRINT REPORT ACTION --}} 
                        <a class="dropdown-item py-2 px-3 text-sm font-weight-bold" href="javascript:void(0);" 
                            id="printReportBtn"> 
 
                            <i class="now-ui-icons media-1_album text-primary mr-2"></i> 
                            Print Report 
 
                        </a> 
 
                    </div> 
 
                </div> 
            </div> 
 
        </div> 
    </div> 
</div> 
 
 
<!-- ========================================== --> 
<!-- USER STATISTICS CARDS                      --> 
<!-- ========================================== --> 
<div class="row mb-4"> 
 
    <!-- Total Users --> 
    <div class="col-md-6 col-lg-3 mb-3"> 
        <div class="card border-0 h-100" 
            style="border-radius: 16px; background: #ffffff; box-shadow: 0 6px 22px rgba(0,0,0,0.06); overflow: hidden; border-bottom: 4px solid #f96332 !important;"> 
 
            <div class="card-body d-flex align-items-center justify-content-between" 
                style="padding: 24px 22px; min-height: 120px;"> 
 
                <div style="min-width: 0;"> 
                    <div class="text-muted text-uppercase font-weight-bold mb-1" 
                        style="font-size: 10px; letter-spacing: 1.2px; white-space: nowrap;"> 
                        TOTAL USERS 
                    </div> 
 
                    <div class="text-dark font-weight-normal" style="font-size: 30px; line-height: 1.2;"> 
                        {{ \App\Models\User::count() }} 
                    </div> 
                </div> 
 
                <div class="d-flex align-items-center justify-content-center" 
                    style="width: 48px; height: 48px; min-width: 48px; border-radius: 50%; background: linear-gradient(135deg, #f96332 0%, #ff8c42 100%);"> 
 
                    <i class="now-ui-icons users_single-02" style="font-size: 21px; color: #ffffff;"> 
                    </i> 
 
                </div> 
 
            </div> 
        </div> 
    </div> 
 
 
    <!-- Total Employees --> 
    <div class="col-md-6 col-lg-3 mb-3"> 
        <div class="card border-0 h-100" 
            style="border-radius: 16px; background: #ffffff; box-shadow: 0 6px 22px rgba(0,0,0,0.06); overflow: hidden; border-bottom: 4px solid #51cbce !important;"> 
 
            <div class="card-body d-flex align-items-center justify-content-between" 
                style="padding: 24px 22px; min-height: 120px;"> 
 
                <div style="min-width: 0;"> 
                    <div class="text-muted text-uppercase font-weight-bold mb-1" 
                        style="font-size: 10px; letter-spacing: 1.2px; white-space: nowrap;"> 
                        TOTAL EMPLOYEES 
                    </div> 
 
                    <div class="text-dark font-weight-normal" style="font-size: 30px; line-height: 1.2;"> 
                        {{ \App\Models\User::where('role', 'employee')->count() }} 
                    </div> 
                </div> 
 
                <div class="d-flex align-items-center justify-content-center" 
                    style="width: 48px; height: 48px; min-width: 48px; border-radius: 50%; background: linear-gradient(135deg, #1fa7db 0%, #51cbce 100%);"> 
 
                    <i class="now-ui-icons users_single-02" style="font-size: 21px; color: #ffffff;"> 
                    </i> 
 
                </div> 
 
            </div> 
        </div> 
    </div> 
 
 
    <!-- Total Team Leaders --> 
    <div class="col-md-6 col-lg-3 mb-3"> 
        <div class="card border-0 h-100" 
            style="border-radius: 16px; background: #ffffff; box-shadow: 0 6px 22px rgba(0,0,0,0.06); overflow: hidden; border-bottom: 4px solid #2acb9f !important;"> 
 
            <div class="card-body d-flex align-items-center justify-content-between" 
                style="padding: 24px 22px; min-height: 120px;"> 
 
                <div style="min-width: 0;"> 
                    <div class="text-muted text-uppercase font-weight-bold mb-1" 
                        style="font-size: 10px; letter-spacing: 1.2px; white-space: nowrap;"> 
                        TOTAL TEAM LEADERS 
                    </div> 
 
                    <div class="text-dark font-weight-normal" style="font-size: 30px; line-height: 1.2;"> 
                        {{ \App\Models\User::where('role', 'team_leader')->count() }} 
                    </div> 
                </div> 
 
                <div class="d-flex align-items-center justify-content-center" 
                    style="width: 48px; height: 48px; min-width: 48px; border-radius: 50%; background: linear-gradient(135deg, #20c997 0%, #2acb9f 100%);"> 
 
                    <i class="now-ui-icons users_single-02" style="font-size: 21px; color: #ffffff;"> 
                    </i> 
 
                </div> 
 
            </div> 
        </div> 
    </div> 
 
 
    <!-- Total Project Managers --> 
    <div class="col-md-6 col-lg-3 mb-3"> 
        <div class="card border-0 h-100" 
            style="border-radius: 16px; background: #ffffff; box-shadow: 0 6px 22px rgba(0,0,0,0.06); overflow: hidden; border-bottom: 4px solid #9b5de5 !important;"> 
 
            <div class="card-body d-flex align-items-center justify-content-between" 
                style="padding: 24px 22px; min-height: 120px;"> 
 
                <div style="min-width: 0;"> 
                    <div class="text-muted text-uppercase font-weight-bold mb-1" 
                        style="font-size: 10px; letter-spacing: 1.2px; line-height: 1.35; max-width: 145px;"> 
                        TOTAL PROJECT MANAGERS 
                    </div> 
 
                    <div class="text-dark font-weight-normal" style="font-size: 30px; line-height: 1.2;"> 
                        {{ \App\Models\User::where('role', 'Manager')->count() }} 
                    </div> 
                </div> 
 
                <div class="d-flex align-items-center justify-content-center" 
                    style="width: 48px; height: 48px; min-width: 48px; border-radius: 50%; background: linear-gradient(135deg, #8b5bd6 0%, #9b5de5 100%);"> 
 
                    <i class="now-ui-icons users_single-02" style="font-size: 21px; color: #ffffff;"> 
                    </i> 
 
                </div> 
 
            </div> 
        </div> 
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
 
                        <!-- Title Filter Dropdown --> 
                        <div class="dropdown flex-fill"> 
                            <button 
                                class="btn btn-light btn-sm dropdown-toggle text-dark shadow-none px-3 py-2 font-weight-bold rounded-pill border w-100 text-truncate" 
                                type="button" id="dropdownTitle" data-toggle="dropdown" aria-haspopup="true" 
                                aria-expanded="false" 
                                style="font-size: 13px; background-color: #f8f9fa; border-color: #e3e6f0 !important; height: 35px; display: flex; align-items: center; justify-content: space-between;"> 
                                <span>{{ request('title') ? Str::limit(request('title'), 15) : 'All Names' 
                                    }}</span> 
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
 
                                    <a class="dropdown-item py-2 px-3 text-sm title-option {{ !request('title') ? 'active font-weight-bold text-primary' : '' }}" 
                                        href="{{ route('admin.report.user-report', array_merge(request()->except(['title', 'page']), [])) }}" 
                                        data-title="All Titles"> 
                                        <i class="now-ui-icons ui-1_simple-add mr-2"></i> All Titles 
                                    </a> 
 
                                    @foreach($allNames as $nameItem) 
                                    <a class="dropdown-item py-2 px-3 text-sm title-option {{ request('name') == $nameItem ? 'active font-weight-bold text-primary' : '' }}" 
                                        href="{{ route('admin.report.user-report', array_merge(request()->except(['name', 'page']), ['name' => $nameItem])) }}" 
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
                                    href="{{ route('admin.report.user-report', array_merge(request()->except(['role', 'page']), ['role' => 'all'])) }}">All 
                                    Roles</a> 
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('role') == 'admin' ? 'active font-weight-bold text-primary' : '' }}" 
                                    href="{{ route('admin.report.user-report', array_merge(request()->except(['role', 'page']), ['role' => 'admin'])) }}">Admin</a> 
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('role') == 'project manager' ? 'active font-weight-bold text-primary' : '' }}" 
                                    href="{{ route('admin.report.user-report', array_merge(request()->except(['role', 'page']), ['role' => 'project manager'])) }}">Project 
                                    Manager</a> 
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('role') == 'team_leader' ? 'active font-weight-bold text-primary' : '' }}" 
                                    href="{{ route('admin.report.user-report', array_merge(request()->except(['role', 'page']), ['role' => 'team_leader'])) }}">Team 
                                    Leader</a> 
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('role') == 'employee' ? 'active font-weight-bold text-primary' : '' }}" 
                                    href="{{ route('admin.report.user-report', array_merge(request()->except(['role', 'page']), ['role' => 'employee'])) }}">Employee</a> 
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
                                    href="{{ route('admin.report.user-report', array_merge(request()->except(['position', 'page']), [])) }}"> 
                                    <i class="now-ui-icons ui-1_simple-add mr-2"></i> All Positions 
                                </a> 
                                @foreach($allPositions ?? [] as $positionItem) 
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('position') == $positionItem ? 'active font-weight-bold text-primary' : '' }}" 
                                    href="{{ route('admin.report.user-report', array_merge(request()->except(['position', 'page']), ['position' => $positionItem])) }}"> 
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
                                    href="{{ route('admin.report.user-report', array_merge(request()->except(['department', 'page']), [])) }}"> 
                                    <i class="now-ui-icons ui-1_simple-add mr-2"></i> All Departments 
                                </a> 
                                @foreach($allDepartments ?? [] as $departmentItem) 
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('department') == $departmentItem ? 'active font-weight-bold text-primary' : '' }}" 
                                    href="{{ route('admin.report.user-report', array_merge(request()->except(['department', 'page']), ['department' => $departmentItem])) }}"> 
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
                                    href="{{ route('admin.report.user-report', array_merge(request()->except(['status', 'page']), ['status' => 'all'])) }}">All 
                                    Statuses</a> 
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('status') == 'active' ? 'active font-weight-bold text-primary' : '' }}" 
                                    href="{{ route('admin.report.user-report', array_merge(request()->except(['status', 'page']), ['status' => 'active'])) }}">Active</a> 
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('status') == 'deactivated' ? 'active font-weight-bold text-primary' : '' }}" 
                                    href="{{ route('admin.report.user-report', array_merge(request()->except(['status', 'page']), ['status' => 'deactivated'])) }}">Deactivated</a> 
                            </div> 
                        </div> 
                    </div> 
 
                    <!-- Reset Filters Button --> 
                    @if(request()->anyFilled(['name', 'role', 'position', 'department', 'status', 'date_from', 
                    'date_to'])) 
                    <div> 
                        <a href="{{ route('admin.report.user-report') }}" 
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
                    <form method="GET" action="{{ route('admin.report.user-report') }}" 
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
                    <table class="table align-items-center table-flush mb-0" id="usersTable" 
                        style="table-layout: auto; width: 100%;"> 
 
                        <!-- Table header with gradient background --> 
                        <thead style="background: linear-gradient(135deg, #f96332 0%, #ff8c42 100%); color: white;"> 
                            <tr> 
 
                                <!-- Name --> 
                                <th class="py-3 text-white text-center pl-4" data-column="0" 
                                    style="border-right: 1px solid rgba(255,255,255,0.2); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; width: 16%;"> 
                                    Name 
                                </th> 
 
                                <!-- Email --> 
                                <th class="py-3 text-white text-center" data-column="1" 
                                    style="border-right: 1px solid rgba(255,255,255,0.2); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; width: 14%;"> 
                                    Email 
                                </th> 
 
                                <!-- Role --> 
                                <th class="py-3 text-white text-center" data-column="2" 
                                    style="border-right: 1px solid rgba(255,255,255,0.2); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; width: 10%;"> 
                                    Role 
                                </th> 
 
                                <!-- Position --> 
                                <th class="py-3 text-white text-center" data-column="3" 
                                    style="border-right: 1px solid rgba(255,255,255,0.2); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; width: 12%;"> 
                                    Position 
                                </th> 
 
                                <!-- Department --> 
                                <th class="py-3 text-white text-center" data-column="4" 
                                    style="border-right: 1px solid rgba(255,255,255,0.2); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; width: 12%;"> 
                                    Department 
                                </th> 
 
                                <!-- Phone Number --> 
                                <th class="py-3 text-white text-center" data-column="5" 
                                    style="border-right: 1px solid rgba(255,255,255,0.2); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; width: 11%;"> 
                                    Phone Number 
                                </th> 
 
                                <!-- Joined Date --> 
                                <th class="py-3 text-white text-center" data-column="6" 
                                    style="border-right: 1px solid rgba(255,255,255,0.2); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; width: 11%;"> 
                                    Joined Date 
                                </th> 
 
                                <!-- Working Hours --> 
                                <th class="py-3 text-white text-center" data-column="7" 
                                    style="border-right: 1px solid rgba(255,255,255,0.2); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; width: 12%;"> 
                                    Working Hours 
                                </th> 
 
                                <!-- Status --> 
                                <th class="py-3 text-white text-center" data-column="8" 
                                    style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; width: 10%;"> 
                                    Status 
                                </th> 
 
                            </tr> 
                        </thead> 
 
                        <!-- Table body containing dynamic user records --> 
                        <tbody> 
                            <!-- Loop through each user collection item --> 
                            @forelse($users as $user) 
 
                            <tr class="border-bottom"> 
 
                                <!-- Name --> 
                                <td class="align-middle text-left pl-4" data-col-index="0" 
                                    style="word-wrap: break-word; white-space: normal;"> 
                                    <div class="d-flex align-items-center"> 
 
                                        <span 
                                            class="avatar-sm rounded-circle bg-light text-primary font-weight-bold d-flex align-items-center justify-content-center shadow-sm mr-2 flex-shrink-0" 
                                            style="width: 32px; height: 32px; font-size: 12px;"> 
                                            {{ strtoupper(substr($user->name, 0, 2)) }} 
                                        </span> 
 
                                        <span class="text-dark font-weight-bold" style="word-break: break-word;"> 
                                            {{ $user->name }} 
                                        </span> 
 
                                    </div> 
                                </td> 
 
                                <!-- Email --> 
                                <td class="text-muted align-middle text-center" data-col-index="1" 
                                    style="word-wrap: break-word; white-space: normal; word-break: break-all;"> 
                                    {{ $user->email }} 
                                </td> 
 
                                <!-- Role --> 
                                <td class="text-muted align-middle text-center" data-col-index="2" 
                                    style="word-wrap: break-word; white-space: normal;"> 
                                    <span class="badge badge-pill badge-neutral text-dark border px-3 py-1"> 
                                        {{ ucwords(str_replace('_', ' ', $user->role)) }} 
                                    </span> 
                                </td> 
 
                                <!-- Position --> 
                                <td class="text-muted align-middle text-center" data-col-index="3" 
                                    style="word-wrap: break-word; white-space: normal;"> 
                                    {{ $user->position ?? "N/A" }} 
                                </td> 
 
                                <!-- Department --> 
                                <td class="text-muted align-middle text-center" data-col-index="4" 
                                    style="word-wrap: break-word; white-space: normal;"> 
                                    {{ $user->department ?? "NOT ASSIGNED" }} 
                                </td> 
 
                                <!-- Phone Number --> 
                                <td class="text-muted align-middle text-center" data-col-index="5" 
                                    style="word-wrap: break-word; white-space: normal; word-break: break-word;"> 
                                    {{ $user->phone ?? $user->phone_number ?? "NO PHONE PROVIDED" }} 
                                </td> 
 
                                <!-- Joined Date --> 
                                <td class="text-muted align-middle text-center" data-col-index="6" 
                                    style="word-wrap: break-word; white-space: normal;"> 
                                    {{ $user->joining_date ?? $user->joined_date ?? $user->created_at?->format('Y-m-d') 
                                    ?? "N/A" }} 
                                </td> 
 
                                <!-- Working Hours --> 
                                <td class="text-muted align-middle text-center" data-col-index="7" 
                                    style="word-wrap: break-word; white-space: normal;"> 
                                    {{ $user->working_hours ?? $user->work_hours ?? "STANDARD HOURS" }} 
                                </td> 
 
                                <!-- Status --> 
                                <td class="align-middle text-center" data-col-index="8" 
                                    style="word-wrap: break-word; white-space: normal;"> 
                                    <span 
                                        class="badge badge-pill @if($user->status == 'active') badge-success @else badge-danger @endif px-3 py-2 text-white shadow-sm"> 
                                        {{ ucfirst($user->status) }} 
                                    </span> 
                                </td> 
 
                            </tr> 
 
                            <!-- Fallback empty state if no user records exist --> 
                            @empty 
 
 
 
                            @endforelse 
                        </tbody> 
                    </table> 
                </div> 
 
                <!-- ========================================== --> 
                <!-- SECTION: PAGINATION CONTROLS               --> 
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

@endcan
 
@endsection
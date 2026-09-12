@extends('layouts.app') 
 
{{-- Set the dynamic title for this specific page --}} 
@section('title', 'Team Report') 
 
@section('Main_Content') 
 
@can('viewAny', \App\Models\Report::class) 
 
{{-- ========================================== --}} 
{{-- PAGE HEADER AND EXPORT ACTION BUTTON --}} 
{{-- ========================================== --}} 
 
<div class="row mt-4 mb-5 align-items-center"> 
 
    <div class="col-lg-6 col-md-8 col-12 mb-3 mb-lg-0"> 
 
        <h3 class="font-weight-bold text-dark mb-0 text-break"> 
            Team Report Management 
        </h3> 
 
        <p class="text-muted text-sm mb-0"> 
            Manage all project teams, assign projects, and track member counts. 
        </p> 
 
    </div> 
 
    {{-- ========================================== --}} 
    {{-- EXPORT & PRINT DROPDOWN ACTIONS SECTION --}} 
    {{-- ========================================== --}} 
 
    <div class="col-lg-6 col-md-4 col-12 text-lg-right text-md-right text-left"> 
 
        <div class="dropdown d-inline-block"> 
 
            {{-- Dropdown Toggle Button --}} 
            <button class="btn btn-primary btn-round dropdown-toggle text-white shadow-sm px-4" type="button" 
                id="exportDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> 
 
                <i class="now-ui-icons files_single-copy-04"></i> 
                Export & Print 
 
            </button> 
 
            {{-- Dropdown Menu Options --}} 
            <div class="dropdown-menu dropdown-menu-right shadow-lg border-0 py-2" aria-labelledby="exportDropdown" 
                style="border-radius: 12px;"> 
 
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
 
                {{-- Divider line separating file exports from browser actions --}} 
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
 
 
{{-- ========================================== --}} 
{{-- TEAM STATISTICS CARDS SECTION --}} 
{{-- ========================================== --}} 
 
<div class="row mb-5"> 
 
    {{-- Total Teams Card --}} 
    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4"> 
        <div class="card card-stats border-0 shadow-lg position-relative overflow-hidden" 
            style="border-radius: 18px; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); transition: transform 0.2s ease;"> 
            <div class="card-body p-4"> 
                <div class="d-flex align-items-center justify-content-between"> 
                    <div> 
                        <p class="card-category text-uppercase text-muted font-weight-bold mb-1" 
                            style="font-size: 10px; letter-spacing: 1px;"> 
                            Total Teams 
                        </p> 
                        <h3 class="card-title font-weight-bolder text-dark mb-0"> 
                            {{ $totalTeamsCount ?? (clone $statsQuery)->count() }} 
                        </h3> 
                    </div> 
                    <div class="icon-shape text-white rounded-circle shadow d-flex align-items-center justify-content-center flex-shrink-0" 
                        style="width: 48px; height: 48px; background: linear-gradient(135deg, #f96332 0%, #ff8c42 100%);"> 
                        <i class="now-ui-icons users_single-02" style="font-size: 20px;"></i> 
                    </div> 
                </div> 
            </div> 
            <div class="position-absolute w-100" 
                style="height: 4px; bottom: 0; left: 0; background: linear-gradient(135deg, #f96332 0%, #ff8c42 100%);"> 
            </div> 
        </div> 
    </div> 
 
 
    {{-- Active Teams Card --}} 
    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4"> 
        <div class="card card-stats border-0 shadow-lg position-relative overflow-hidden" 
            style="border-radius: 18px; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); transition: transform 0.2s ease;"> 
            <div class="card-body p-4"> 
                <div class="d-flex align-items-center justify-content-between"> 
                    <div> 
                        <p class="card-category text-uppercase text-muted font-weight-bold mb-1" 
                            style="font-size: 10px; letter-spacing: 1px;"> 
                            Active Teams 
                        </p> 
                        <h3 class="card-title font-weight-bolder text-dark mb-0"> 
                            {{ (clone $statsQuery)->has('members')->count() }} 
                        </h3> 
                    </div> 
                    <div class="icon-shape text-white rounded-circle shadow d-flex align-items-center justify-content-center flex-shrink-0" 
                        style="width: 48px; height: 48px; background: linear-gradient(135deg, #2dce89 0%, #2ddfc4 100%);"> 
                        <i class="now-ui-icons ui-1_check" style="font-size: 20px;"></i> 
                    </div> 
                </div> 
            </div> 
            <div class="position-absolute w-100" 
                style="height: 4px; bottom: 0; left: 0; background: linear-gradient(135deg, #2dce89 0%, #2ddfc4 100%);"> 
            </div> 
        </div> 
    </div> 
 
 
    {{-- Empty Teams Card --}} 
    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4"> 
        <div class="card card-stats border-0 shadow-lg position-relative overflow-hidden" 
            style="border-radius: 18px; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); transition: transform 0.2s ease;"> 
            <div class="card-body p-4"> 
                <div class="d-flex align-items-center justify-content-between"> 
                    <div> 
                        <p class="card-category text-uppercase text-muted font-weight-bold mb-1" 
                            style="font-size: 10px; letter-spacing: 1px;"> 
                            Empty Teams 
                        </p> 
                        <h3 class="card-title font-weight-bolder text-dark mb-0"> 
                            {{ (clone $statsQuery)->doesntHave('members')->count() }} 
                        </h3> 
                    </div> 
                    <div class="icon-shape text-white rounded-circle shadow d-flex align-items-center justify-content-center flex-shrink-0" 
                        style="width: 48px; height: 48px; background: linear-gradient(135deg, #fbb140 0%, #f39c12 100%);"> 
                        <i class="now-ui-icons users_single-02" style="font-size: 20px;"></i> 
                    </div> 
                </div> 
            </div> 
            <div class="position-absolute w-100" 
                style="height: 4px; bottom: 0; left: 0; background: linear-gradient(135deg, #fbb140 0%, #f39c12 100%);"> 
            </div> 
        </div> 
    </div> 
 
 
    {{-- Total Members Card --}} 
    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4"> 
        <div class="card card-stats border-0 shadow-lg position-relative overflow-hidden" 
            style="border-radius: 18px; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); transition: transform 0.2s ease;"> 
            <div class="card-body p-4"> 
                <div class="d-flex align-items-center justify-content-between"> 
                    <div> 
                        <p class="card-category text-uppercase text-muted font-weight-bold mb-1" 
                            style="font-size: 10px; letter-spacing: 1px;"> 
                            Total Members 
                        </p> 
                        <h3 class="card-title font-weight-bolder text-dark mb-0"> 
                            {{ $totalTeamMembersCount ?? \App\Models\User::whereHas('teams', function ($q) use 
                            ($statsQuery) { $q->whereIn('teams.id', (clone $statsQuery)->select('id')); })->count() }} 
                        </h3> 
                    </div> 
                    <div class="icon-shape text-white rounded-circle shadow d-flex align-items-center justify-content-center flex-shrink-0" 
                        style="width: 48px; height: 48px; background: linear-gradient(135deg, #8965e0 0%, #bc6fe1 100%);"> 
                        <i class="now-ui-icons users_single-02" style="font-size: 20px;"></i> 
                    </div> 
                </div> 
            </div> 
            <div class="position-absolute w-100" 
                style="height: 4px; bottom: 0; left: 0; background: linear-gradient(135deg, #8965e0 0%, #bc6fe1 100%);"> 
            </div> 
        </div> 
    </div> 
 
 
    {{-- With Project Card --}} 
    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4"> 
        <div class="card card-stats border-0 shadow-lg position-relative overflow-hidden" 
            style="border-radius: 18px; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); transition: transform 0.2s ease;"> 
            <div class="card-body p-4"> 
                <div class="d-flex align-items-center justify-content-between"> 
                    <div> 
                        <p class="card-category text-uppercase text-muted font-weight-bold mb-1" 
                            style="font-size: 10px; letter-spacing: 1px;"> 
                            With Project 
                        </p> 
                        <h3 class="card-title font-weight-bolder text-dark mb-0"> 
                            {{ (clone $statsQuery)->whereNotNull('project_id')->count() }} 
                        </h3> 
                    </div> 
                    <div class="icon-shape text-white rounded-circle shadow d-flex align-items-center justify-content-center flex-shrink-0" 
                        style="width: 48px; height: 48px; background: linear-gradient(135deg, #11cdef 0%, #1171ef 100%);"> 
                        <i class="now-ui-icons business_briefcase-24" style="font-size: 20px;"></i> 
                    </div> 
                </div> 
            </div> 
            <div class="position-absolute w-100" 
                style="height: 4px; bottom: 0; left: 0; background: linear-gradient(135deg, #11cdef 0%, #1171ef 100%);"> 
            </div> 
        </div> 
    </div> 
 
 
    {{-- Without Project Card --}} 
    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4"> 
        <div class="card card-stats border-0 shadow-lg position-relative overflow-hidden" 
            style="border-radius: 18px; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); transition: transform 0.2s ease;"> 
            <div class="card-body p-4"> 
                <div class="d-flex align-items-center justify-content-between"> 
                    <div> 
                        <p class="card-category text-uppercase text-muted font-weight-bold mb-1" 
                            style="font-size: 10px; letter-spacing: 1px;"> 
                            Without Project 
                        </p> 
                        <h3 class="card-title font-weight-bolder text-dark mb-0"> 
                            {{ (clone $statsQuery)->whereNull('project_id')->count() }} 
                        </h3> 
                    </div> 
                    <div class="icon-shape text-white rounded-circle shadow d-flex align-items-center justify-content-center flex-shrink-0" 
                        style="width: 48px; height: 48px; background: linear-gradient(135deg, #f5365c 0%, #f56036 100%);"> 
                        <i class="now-ui-icons ui-1_simple-remove" style="font-size: 20px;"></i> 
                    </div> 
                </div> 
            </div> 
            <div class="position-absolute w-100" 
                style="height: 4px; bottom: 0; left: 0; background: linear-gradient(135deg, #f5365c 0%, #f56036 100%);"> 
            </div> 
        </div> 
    </div> 
 
</div> 
 
{{-- ========================================== --}} 
{{-- COLUMN FILTERS DROPDOWNS SECTION --}} 
{{-- ========================================== --}} 
 
<div class="row mb-4"> 
 
    <div class="col-md-12"> 
 
        <div class="card border-0 shadow-sm" style="border-radius: 16px; background: #ffffff; overflow: visible;"> 
 
            <div class="card-body p-3" style="overflow: visible;"> 
 
                <div class="d-flex flex-wrap align-items-center justify-content-between" style="gap: 12px;"> 
 
                    {{-- Filters Grouping --}} 
                    <div class="d-flex flex-wrap align-items-center flex-grow-1" style="gap: 10px;"> 
 
                        <span class="text-muted font-weight-bold mr-1 d-none d-xl-inline-block" 
                            style="font-size: 13px;"> 
 
                            <i class="now-ui-icons ui-1_zoom-bold mr-1 text-primary"></i> 
                            Filter By: 
 
                        </span> 
 
 
                        {{-- Team Filter Dropdown --}} 
                        <div class="dropdown flex-fill"> 
 
                            <button 
                                class="btn btn-light btn-sm dropdown-toggle text-dark shadow-none px-3 py-2 font-weight-bold rounded-pill border w-100 text-truncate" 
                                type="button" id="dropdownTeam" data-toggle="dropdown" aria-haspopup="true" 
                                aria-expanded="false" 
                                style="font-size: 13px; background-color: #f8f9fa; border-color: #e3e6f0 !important; height: 35px; display: flex; align-items: center; justify-content: space-between;"> 
 
                                <span> 
                                    {{ request('team_name') ? Str::limit(request('team_name'), 15) : 'All Teams' }} 
                                </span> 
 
                            </button> 
 
                            <div class="dropdown-menu shadow-lg border-0 py-2" aria-labelledby="dropdownTeam" 
                                style="border-radius: 12px; min-width: 180px;"> 
 
                                <a class="dropdown-item py-2 px-3 text-sm {{ !request('team_name') ? 'active font-weight-bold text-primary' : '' }}" 
                                    href="{{ route('admin.report.team-report', request()->except(['team_name', 'page'])) }}"> 
 
                                    All Teams 
 
                                </a> 
 
                                @foreach($allTeamNames as $teamName) 
 
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('team_name') == $teamName ? 'active font-weight-bold text-primary' : '' }}" 
                                    href="{{ route('admin.report.team-report', array_merge(request()->except(['team_name', 'page']), ['team_name' => $teamName])) }}"> 
 
                                    {{ $teamName }} 
 
                                </a> 
 
                                @endforeach 
 
                            </div> 
 
                        </div> 
 
 
                        {{-- Project Filter Dropdown --}} 
                        <div class="dropdown flex-fill"> 
 
                            <button 
                                class="btn btn-light btn-sm dropdown-toggle text-dark shadow-none px-3 py-2 font-weight-bold rounded-pill border w-100 text-truncate" 
                                type="button" id="dropdownProject" data-toggle="dropdown" aria-haspopup="true" 
                                aria-expanded="false" 
                                style="font-size: 13px; background-color: #f8f9fa; border-color: #e3e6f0 !important; height: 35px; display: flex; align-items: center; justify-content: space-between;"> 
 
                                @php 
                                $selectedProject = $projects->firstWhere('id', request('project_id')); 
                                @endphp 
 
                                <span class="text-truncate"> 
                                    {{ $selectedProject ? $selectedProject->title : 'All Projects' }} 
                                </span> 
 
                            </button> 
 
                            <div class="dropdown-menu shadow-lg border-0 py-2" aria-labelledby="dropdownProject" 
                                style="border-radius: 12px; min-width: 200px;"> 
 
                                {{-- Live Search Input Field --}} 
                                <div class="px-3 pb-2 pt-1"> 
                                    <input type="text" id="projectLiveSearch" class="form-control form-control-sm" 
                                        placeholder="Search project..." autocomplete="off" 
                                        style="border-radius: 20px; font-size: 12px;"> 
                                </div> 
                                <div class="dropdown-divider my-1"></div> 
 
                                <div id="projectOptionsContainer" style="max-height: 200px; overflow-y: auto;"> 
                                    <a class="dropdown-item py-2 px-3 text-sm project-option {{ !request('project_id') ? 'active font-weight-bold text-primary' : '' }}" 
                                        data-title="All Projects" 
                                        href="{{ route('admin.report.team-report', request()->except(['project_id', 'page'])) }}"> 
                                        All Projects 
                                    </a> 
 
                                    @foreach($projects as $project) 
                                    <a class="dropdown-item py-2 px-3 text-sm project-option {{ request('project_id') == $project->id ? 'active font-weight-bold text-primary' : '' }}" 
                                        data-title="{{ $project->title }}" 
                                        href="{{ route('admin.report.team-report', array_merge(request()->except(['project_id', 'page']), ['project_id' => $project->id])) }}"> 
                                        {{ $project->title }} 
                                    </a> 
                                    @endforeach 
                                </div> 
 
                                {{-- No Results Message --}} 
                                <div id="noProjectResults" class="px-3 py-2 text-muted text-center text-sm" 
                                    style="display: none; font-size: 12px;"> 
                                    No projects found 
                                </div> 
 
                            </div> 
 
                        </div> 
 
 
 
                        {{-- Team Leader Filter Dropdown --}} 
                        <div class="dropdown flex-fill"> 
 
                            <button 
                                class="btn btn-light btn-sm dropdown-toggle text-dark shadow-none px-3 py-2 font-weight-bold rounded-pill border w-100 text-truncate" 
                                type="button" id="dropdownLeader" data-toggle="dropdown" aria-haspopup="true" 
                                aria-expanded="false" 
                                style="font-size: 13px; background-color: #f8f9fa; border-color: #e3e6f0 !important; height: 35px; display: flex; align-items: center; justify-content: space-between;"> 
 
                                @php 
                                $selectedLeader = $leaders->firstWhere( 
                                'id', 
                                request('team_leader_id') 
                                ); 
                                @endphp 
 
                                <span class="text-truncate"> 
                                    {{ $selectedLeader ? $selectedLeader->name : 'Team Leader' }} 
                                </span> 
 
                            </button> 
 
 
                            <div class="dropdown-menu shadow-lg border-0 py-2" aria-labelledby="dropdownLeader" 
                                style="border-radius: 12px; min-width: 180px;"> 
 
                                {{-- All Team Leaders --}} 
                                <a class="dropdown-item py-2 px-3 text-sm {{ !request('team_leader_id') ? 'active font-weight-bold text-primary' : '' }}" 
                                    href="{{ route('admin.report.team-report', request()->except(['team_leader_id', 'page'])) }}"> 
 
                                    Team Leader 
 
                                </a> 
 
 
                                {{-- Team Leaders --}} 
                                @foreach($leaders as $leader) 
 
                                <a class="dropdown-item py-2 px-3 text-sm {{ request('team_leader_id') == $leader->id ? 'active font-weight-bold text-primary' : '' }}" 
                                    href="{{ route('admin.report.team-report', array_merge(request()->except(['team_leader_id', 'page']), ['team_leader_id' => $leader->id])) }}"> 
 
                                    {{ $leader->name }} 
 
                                </a> 
 
                                @endforeach 
 
                            </div> 
 
                        </div> 
 
                    </div> 
 
 
                    {{-- Reset Filters Button --}} 
                    @if(request()->anyFilled(['team_name', 'project_id', 'team_leader_id', 'search'])) 
 
                    <div> 
 
                        <a href="{{ route('admin.report.team-report') }}" 
                            class="btn btn-outline-danger btn-sm rounded-pill px-3 py-2" 
                            style="font-size: 12px; white-space: nowrap; height: 35px;"> 
 
                            <i class="now-ui-icons ui-1_simple-remove mr-1"></i> 
                            Reset 
 
                        </a> 
 
                    </div> 
 
                    @endif 
 
                </div> 
 
            </div> 
 
        </div> 
 
    </div> 
 
</div> 
 
 
{{-- ========================================== --}} 
{{-- MAIN REPORT TABLE CARD SECTION --}} 
{{-- ========================================== --}} 
 
<div class="row"> 
    <div class="col-md-12"> 
        <x-alert-message /> 
        <div class="card shadow-sm border-0" id="printable-report"> 
            <div class="card-body px-0 pb-0"> 
                <div class="table-responsive"> 
                    <table class="table table-bordered align-items-center table-flush mb-0" id="teamsTable" 
                        style="min-width: 950px; width: 100%; table-layout: fixed; border-collapse: collapse;"> 
 
                        {{-- Table Column Widths --}} 
                        <colgroup> 
                            <col style="width: 16%;"> 
                            <col style="width: 24%;"> 
                            <col style="width: 19%;"> 
                            <col style="width: 19%;"> 
                            <col style="width: 11%;"> 
                            <col style="width: 11%;"> 
                        </colgroup> 
 
                        <thead style="background: linear-gradient(135deg, #f96332 0%, #ff8c42 100%); color: white;"> 
                            <tr> 
                                {{-- Team Name --}} 
                                <th class="py-3 font-weight-bold text-white pl-4 text-center align-middle" 
                                    style="font-size: 13px; white-space: nowrap; border: 1px solid #dee2e6;"> 
                                    Team Name 
                                </th> 
                                {{-- Description --}} 
                                <th class="py-3 font-weight-bold text-white text-center align-middle" 
                                    style="font-size: 13px; border: 1px solid #dee2e6;"> 
                                    Description 
                                </th> 
                                {{-- Project Name --}} 
                                <th class="py-3 font-weight-bold text-white text-center align-middle" 
                                    style="font-size: 13px; border: 1px solid #dee2e6;"> 
                                    Project Name 
                                </th> 
                                {{-- Team Leader --}} 
                                <th class="py-3 font-weight-bold text-white text-center align-middle" 
                                    style="font-size: 13px; border: 1px solid #dee2e6;"> 
                                    Team Leader 
                                </th> 
                                {{-- Members Count --}} 
                                <th class="py-3 font-weight-bold text-white text-center align-middle" 
                                    style="font-size: 13px; white-space: nowrap; border: 1px solid #dee2e6;"> 
                                    Members Count 
                                </th> 
                                {{-- Create At --}} 
                                <th class="py-3 font-weight-bold text-white text-center align-middle" 
                                    style="font-size: 13px; white-space: nowrap; border: 1px solid #dee2e6;"> 
                                    Create At 
                                </th> 
                            </tr> 
                        </thead> 
 
                        <tbody> 
                            @forelse($teams as $team) 
                            <tr class="border-bottom team-row" style="transition: background-color 0.15s ease;"> 
                                {{-- Team Name --}} 
                                <td class="font-weight-bold text-dark pl-4 align-middle text-center" 
                                    style="font-size: 13px; word-break: break-word; padding-top: 16px; padding-bottom: 16px; border: 1px solid #dee2e6;"> 
                                    {{ $team->name }} 
                                </td> 
 
                                {{-- Description --}} 
                                <td class="align-middle text-muted text-center" 
                                    style="max-width: 200px; word-break: break-word; white-space: normal; padding: 16px 12px; font-size: 13px; border: 1px solid #dee2e6;"> 
                                    @php 
                                    $fullDescription = $team->description ?? 'No description'; 
                                    $isLong = mb_strlen($fullDescription) > 50; 
                                    @endphp 
 
                                    <span class="desc-short-text" style="display: inline; line-height: 1.5;"> 
                                        {{ Str::limit($fullDescription, 50) }} 
                                    </span> 
 
                                    @if($isLong) 
                                    {{-- This Button Will Open The Modal To Show The Full Description --}} 
                                    <button type="button" 
                                        class="btn btn-link btn-icon btn-sm text-primary p-0 ml-1 d-print-none font-weight-bold" 
                                        data-toggle="modal" data-target="#descModal{{ $team->id }}" 
                                        style="font-size: 11px; text-decoration: underline;"> 
                                        More 
                                    </button> 
 
                                    {{-- This Modal Will Show The Full Description --}} 
                                    <div class="modal fade d-print-none" id="descModal{{ $team->id }}" tabindex="-1" 
                                        role="dialog" aria-labelledby="descModalLabel{{ $team->id }}" 
                                        aria-hidden="true"> 
                                        <div class="modal-dialog modal-dialog-centered" role="document"> 
                                            <div class="modal-content shadow-lg border-0" 
                                                style="border-radius: 12px; overflow: hidden;"> 
                                                <div class="modal-header text-white" 
                                                    style="background: linear-gradient(135deg, #f96332 0%, #ff8c42 100%);"> 
                                                    <h5 class="modal-title font-weight-bold text-white" 
                                                        id="descModalLabel{{ $team->id }}"> 
                                                        <i class="now-ui-icons text_align-left mr-2"></i> 
                                                        Team Description 
                                                    </h5> 
                                                    <button type="button" class="close text-white" data-dismiss="modal" 
                                                        aria-label="Close" style="opacity: 1;"> 
                                                        <span aria-hidden="true">&times;</span> 
                                                    </button> 
                                                </div> 
 
                                                <div class="modal-body p-4 bg-white text-dark"> 
                                                    <h6 class="font-weight-bold text-primary mb-2"> 
                                                        {{ $team->name }} 
                                                    </h6> 
                                                    <hr class="mt-1 mb-3"> 
                                                    <p class="text-break" 
                                                        style="line-height: 1.6; white-space: pre-line;"> 
                                                        {{ $fullDescription }} 
                                                    </p> 
                                                </div> 
 
                                                <div class="modal-footer bg-light px-4 py-3"> 
                                                    <button type="button" class="btn btn-secondary btn-round btn-sm" 
                                                        data-dismiss="modal"> 
                                                        Close 
                                                    </button> 
                                                </div> 
                                            </div> 
                                        </div> 
                                    </div> 
                                    @endif 
 
                                    {{-- This text will be visible only on print --}} 
                                    <div class="d-none d-print-block text-dark" 
                                        style="white-space: normal; line-height: 1.5;"> 
                                        {{ $fullDescription }} 
                                    </div> 
                                </td> 
 
                                {{-- Project Name --}} 
                                <td class="align-middle text-center" 
                                    style="padding: 16px 12px; font-size: 13px; word-break: break-word; border: 1px solid #dee2e6;"> 
                                    <span class="text-dark font-weight-normal text-break"> 
                                        {{ optional($team->project)->title ?? 'No Project' }} 
                                    </span> 
                                </td> 
 
                                {{-- Team Leader --}} 
                                <td class="align-middle text-center" 
                                    style="padding: 16px 10px; font-size: 13px; border: 1px solid #dee2e6;"> 
                                    <div class="d-flex align-items-center justify-content-center"> 
                                        <span 
                                            class="avatar-sm rounded-circle bg-light text-primary font-weight-bold d-flex align-items-center justify-content-center shadow-sm mr-2 flex-shrink-0" 
                                            style="width: 30px; height: 30px; font-size: 11px;"> 
                                            {{ strtoupper(substr(optional($team->leader)->name ?? 'U', 0, 2)) }} 
                                        </span> 
                                        <span class="text-dark font-weight-normal text-break" style="line-height: 1.4;"> 
                                            {{ optional($team->leader)->name ?? 'No Leader' }} 
                                        </span> 
                                    </div> 
                                </td> 
 
                                {{-- Members Count --}} 
                                <td class="align-middle text-center" 
                                    style="padding: 16px 8px; border: 1px solid #dee2e6;"> 
                                    <span 
                                        class="text-white font-weight-bold rounded-pill d-inline-flex align-items-center justify-content-center" 
                                        style="background-color: #1171ef; min-width: 68px; height: 26px; padding: 0 10px; font-size: 11px; line-height: 1; white-space: nowrap;"> 
                                        {{ $team->members()->count() }} Members 
                                    </span> 
                                </td> 
 
                                {{-- Create At --}} 
                                <td class="align-middle text-center" 
                                    style="padding: 16px 8px; border: 1px solid #dee2e6;"> 
                                    <div class="text-muted" style="font-size: 11px; line-height: 1.5;"> 
                                        @if($team->created_at) 
                                        <div class="font-weight-bold text-dark"> 
                                            {{ $team->created_at->format('Y-m-d') }} 
                                        </div> 
                                        @else 
                                        <span class="italic"> 
                                            N/A 
                                        </span> 
                                        @endif 
                                    </div> 
                                </td> 
                            </tr> 
 
                            @empty 

                            <tr>
                                <td colspan="6" class="p-0 text-center align-middle"
                                    style="height: 240px; vertical-align: middle !important; border: 1px solid #dee2e6;">

                                    <div class="datatable-empty-state"
                                        style="padding: 45px 20px; text-align: center; width: 100%; margin: 0 auto;">

                                        <div
                                            style="width: 64px; height: 64px; margin: 0 auto 16px auto; border-radius: 50%; background: linear-gradient(135deg, #fff1eb 0%, #ffe4d8 100%); display: flex; align-items: center; justify-content: center; box-shadow: 0 6px 18px rgba(249, 99, 50, 0.12);">

                                            <i class="now-ui-icons users_circle-08"
                                                style="font-size: 28px; color: #f96332;"></i>

                                        </div>

                                        <div
                                            style="font-size: 16px; font-weight: 700; color: #32325d; margin-bottom: 6px;">

                                            No teams available

                                        </div>

                                        <div
                                            style="font-size: 13px; color: #8898aa; max-width: 420px; margin: 0 auto; line-height: 1.6;">

                                            There is no team data to display at the moment.

                                        </div>

                                    </div>

                                </td>
                            </tr>

                            @endforelse 
 
 
                        </tbody> 
                    </table> 
                </div> 
            </div> 
        </div> 
    </div> 
</div> 
 
 
{{-- ========================================== --}} 
{{-- PAGINATION CONTROLS SECTION --}} 
{{-- ========================================== --}} 
 
@if(method_exists($teams, 'hasPages') && $teams->hasPages()) 
 
<div class="row mt-3 mb-4"> 
 
    <div class="col-md-12"> 
 
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between bg-white" 
            style="gap: 15px; padding: 18px 20px; border-radius: 0 0 12px 12px;"> 
 
            {{-- Pagination Information - LEFT --}} 
            <div class="text-muted text-sm text-center text-md-left flex-shrink-0"> 
 
                Showing 
                <b>{{ $teams->firstItem() }}</b> 
                to 
                <b>{{ $teams->lastItem() }}</b> 
                of 
                <b>{{ $teams->total() }}</b> 
                entries 
 
            </div> 
 
            {{-- Pagination Buttons - RIGHT --}} 
            <div class="d-flex align-items-center justify-content-end ml-md-auto"> 
 
                {{ $teams->links('pagination::bootstrap-4') }} 
 
            </div> 
 
        </div> 
 
    </div> 
 
</div> 
 
@endif 
 
@endcan 
 
@endsection
@extends('layouts.report')

@section('title', 'Projects Report')
@section('report_title', 'Project Report')

@section('content')
{{-- To include the partial view of the project table --}}
<div class="taskTableContainer">
    @include('contents.report.partial.Project_table')
</div>
@endsection

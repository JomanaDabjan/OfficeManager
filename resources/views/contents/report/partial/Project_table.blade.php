<!-- ========================================================================== -->
<!-- 1. CSS STYLESHEET IMPORTS & CUSTOM PDF / PRINT DESIGN                      -->
<!-- ========================================================================== -->
<style>
    /* Base body settings for print and PDF output */
    body {
        font-family: 'Helvetica', 'Arial', sans-serif !important;
        color: #333333 !important;
        background-color: #ffffff !important;
        margin: 0;
        padding: 10px;
    }

    /* Styling for the main dynamic report heading */
    h2 {
        text-align: center;
        color: #f96332 !important;
        margin-bottom: 15px;
        font-size: 18px;
        text-transform: uppercase;
    }

    /* Professional table styling compatible with PDF rendering engines and print */
    table {
        width: 100% !important;
        border-collapse: collapse !important;
        background-color: #ffffff !important;
        border: 1px solid #e3e3e3 !important;
        margin-top: 5px;
        table-layout: fixed;
        /* Fix table layout to respect column widths */
    }

    /* Table Header styling - Centered */
    th {
        background-color: #f96332 !important;
        color: #ffffff !important;
        padding: 10px 4px !important;
        font-size: 10px !important;
        text-transform: uppercase;
        text-align: center !important;
        border: 1px solid #e65100 !important;
        word-wrap: break-word;
    }

    /* Table Data cells styling - Centered */
    td {
        border: 1px solid #dee2e6 !important;
        padding: 8px 4px !important;
        vertical-align: middle !important;
        text-align: center !important;
        font-size: 9px !important;
        color: #333333 !important;
        word-wrap: break-word;
    }

    /* Zebra striping: Alternating background color for even rows for better readability */
    tbody tr:nth-child(even) {
        background-color: #f7f9fa !important;
    }

    /* Unordered list formatting inside table cells */
    ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
        text-align: center;
    }

    /* Individual list items formatting */
    ul li {
        font-size: 8.5px;
        padding: 2px 0;
        border-bottom: 1px dashed #eee;
    }

    /* Print specific adjustments to fix colors and margins */
    @media print {
        body {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            padding: 0;
        }

        @page {
            size: A4 portrait;
            margin: 10mm 10mm 10mm 10mm;
        }

        th {
            background-color: #f96332 !important;
            color: #ffffff !important;
        }

        span[style*="background-color"] {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Hide elements marked as non-printable during print execution */
        .no-print {
            display: none !important;
        }
    }

    /* Page break wrapper utility for PDF and Print engines (6 records per page) */
    .page-break-container {
        page-break-before: always;
        break-before: page;
    }

    .page-break-container:first-child {
        page-break-before: avoid;
        break-before: avoid;
    }
</style>

<!-- ========================================================================== -->
<!-- 2. MAIN REPORT TABLE CARD SECTION (CONTAINER WITH UNIQUE ID)               -->
<!-- ========================================================================== -->
<div class="row">
    <div class="col-md-12">
        <!-- Target container wrapper for printing isolated report content -->
        <div class="card shadow-sm border-0" id="printable-report">
            <div class="card-body px-0 pb-0">
                <div class="table-responsive">
                    @forelse($chunks as $pageIndex => $chunkedProjects)
                    <!-- Chunk container to force 6 records per page for both print and PDF engines -->
                    <div class="{{ $pageIndex > 0 ? 'page-break-container' : '' }}"
                        style="{{ $pageIndex > 0 ? 'margin-top: 10px;' : '' }}">
                        <table class="table align-items-center table-flush mb-0" id="projectsTable_{{ $pageIndex }}">
                            <thead>
                                <tr>
                                    <th style="width: 13%;">Project Title</th>
                                    <th style="width: 15%;">Description</th>
                                    <th style="width: 11%;">Project Manager</th>
                                    <th style="width: 10%;">Start Date</th>
                                    <th style="width: 10%;">End Date</th>
                                    <th style="width: 9%;">Budget</th>
                                    <th style="width: 7%;">Total Tasks</th>
                                    <th style="width: 13%;">Tasks & Employees</th>
                                    <th style="width: 12%;">Status & Progress</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($chunkedProjects as $project)
                                @php
                                $projectStatus = strtolower($project->status ?? 'in_progress');

                                $progressPercent = $project->progress ?? match($projectStatus) {
                                'completed' => 100,
                                'in_progress' => 50,
                                'pending' => 10,
                                default => 0
                                };
                                @endphp
                                <tr>
                                    <!-- Project Title Column -->
                                    <td style="font-weight: bold;">
                                        {{ $project->title }}
                                    </td>

                                    <!-- Project Description Column -->
                                    <td>
                                        {{ $project->description ?? 'No description' }}
                                    </td>

                                    <!-- Manager Name Column -->
                                    <td>
                                        {{ optional($project->manager)->name ?? 'No Manager' }}
                                    </td>

                                    <!-- Start Date Column -->
                                    <td>
                                        {{ $project->start_date ?
                                        \Carbon\Carbon::parse($project->start_date)->format('Y-m-d') : 'N/A' }}
                                    </td>

                                    <!-- End Date Column -->
                                    <td>
                                        {{ $project->end_date ?
                                        \Carbon\Carbon::parse($project->end_date)->format('Y-m-d') : 'N/A' }}
                                    </td>

                                    <!-- Budget Column -->
                                    <td style="font-weight: bold; color: #2dce89 !important;">
                                        {{ $project->budget ? '$' . number_format($project->budget, 2) : 'N/A' }}
                                    </td>

                                    <!-- Total Tasks Count Column -->
                                    <td>
                                        {{ $project->tasks_count ?? ($project->tasks ? $project->tasks->count() : 0) }}
                                    </td>

                                    <!-- Project Tasks & Assigned Employees Column -->
                                    <td>
                                        @if(isset($project->tasks) && $project->tasks->count() > 0)
                                        <ul>
                                            @foreach($project->tasks as $task)
                                            <li>
                                                <strong>{{ $task->title ?? $task->name }}</strong><br>
                                                <span style="color: #666;">{{ optional($task->assignedUser ??
                                                    $task->employee)->name ?? 'Unassigned' }}</span>
                                            </li>
                                            @endforeach
                                        </ul>
                                        @else
                                        <span style="color: #888; font-style: italic;">No tasks</span>
                                        @endif
                                    </td>

                                    <!-- Status Badge & Centered Progress Battery Column -->
                                    <td>
                                        <!-- Status Pill Badge -->
                                        <div style="margin-bottom: 4px;">
                                            <span
                                                style="display: inline-block; padding: 2px 6px; border-radius: 10px; font-weight: bold; font-size: 7.5px; color: #fff; background-color: @if($projectStatus == 'completed') #2dce89 @elseif($projectStatus == 'in_progress') #fbb140 @else #11cdef @endif;">
                                                {{ ucfirst(str_replace('_', ' ', $project->status ?? 'in_progress')) }}
                                            </span>
                                        </div>

                                        <!-- Centered Battery Style Progress Bar -->
                                        <div style="text-align: center; width: 100%;">
                                            <div
                                                style="font-size: 7px; font-weight: bold; color: #666; margin-bottom: 2px; text-align: center;">
                                                {{ $progressPercent }}%
                                            </div>

                                            <table
                                                style="width: auto !important; margin: 0 auto !important; border: none !important; background: transparent !important;">
                                                <tr>
                                                    <td
                                                        style="padding: 0 !important; border: none !important; background: transparent !important; vertical-align: middle !important;">
                                                        <div
                                                            style="height: 5px; width: 38px; background-color: #f1f3f5; border: 1px solid #dcdcdc; border-radius: 2px; overflow: hidden; padding: 0.5px;">
                                                            <div
                                                                style="height: 100%; width: {{ $progressPercent }}%; border-radius: 1px; background: @if($projectStatus == 'completed') #2dce89 @elseif($projectStatus == 'in_progress') #fbb140 @else #11cdef @endif;">
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td
                                                        style="padding: 0 !important; border: none !important; background: transparent !important; vertical-align: middle !important;">
                                                        <div
                                                            style="width: 1px; height: 2.5px; background-color: #dcdcdc; border-top-right-radius: 1px; border-bottom-right-radius: 1px; margin-left: 1px;">
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @empty
                    <table class="table align-items-center table-flush mb-0">
                        <tbody>
                            <tr>
                                <td colspan="9" style="padding: 30px; color: #777;">
                                    No project data available for report.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
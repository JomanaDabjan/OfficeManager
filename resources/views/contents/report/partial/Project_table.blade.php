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
                                    <th style="width: 12%;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($chunkedProjects as $project)
                                @php
                                $rawStatus = strtolower($project->status ?? 'in_progress');

                                // حساب الحالة الديناميكية لكي تظهر overdue أو due_today عند الطباعة و PDF
                                if ($rawStatus !== 'completed' && $rawStatus !== 'complete' && $project->end_date) {
                                $endDate = \Carbon\Carbon::parse($project->end_date)->startOfDay();
                                $today = \Carbon\Carbon::today();

                                if ($endDate->isToday()) {
                                $projectStatus = 'due_today';
                                } elseif ($endDate->isPast()) {
                                $projectStatus = 'overdue';
                                } else {
                                $projectStatus = $rawStatus;
                                }
                                } else {
                                $projectStatus = $rawStatus;
                                }
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

                                    <!-- Status Badge Column (Progress Removed) -->
                                    <td>
                                        <div style="text-align: center;">
                                            <span
                                                style="display: inline-block; padding: 3px 8px; border-radius: 10px; font-weight: bold; font-size: 8px; color: #fff; background-color: @if($projectStatus == 'completed' || $projectStatus == 'complete') #2dce89 @elseif($projectStatus == 'in_progress') #fbb140 @elseif($projectStatus == 'pending') #11cdef @elseif($projectStatus == 'overdue') #f5365c @elseif($projectStatus == 'due_today') #5e72e4 @else #8898aa @endif;">
                                                {{ ucfirst(str_replace('_', ' ', $projectStatus)) }}
                                            </span>
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

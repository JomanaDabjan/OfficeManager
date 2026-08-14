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
        padding: 15px;
    }

    /* Styling for the main dynamic report heading */
    h2 {
        text-align: center;
        color: #f96332 !important;
        margin-bottom: 20px;
        font-size: 20px;
        text-transform: uppercase;
    }

    /* Professional table styling compatible with PDF rendering engines and print */
    table {
        width: 100% !important;
        border-collapse: collapse !important;
        background-color: #ffffff !important;
        border: 1px solid #e3e3e3 !important;
        margin-top: 10px;
    }

    /* Table Header styling - Centered */
    th {
        background-color: #f96332 !important;
        color: #ffffff !important;
        padding: 12px 8px !important;
        font-size: 11px !important;
        text-transform: uppercase;
        text-align: center !important;
        border: 1px solid #e65100 !important;
    }

    /* Table Data cells styling - Centered */
    td {
        border: 1px solid #dee2e6 !important;
        padding: 10px 8px !important;
        vertical-align: middle !important;
        text-align: center !important;
        font-size: 10px !important;
        color: #333333 !important;
    }

    /* Zebra striping: Alternating background color for even rows for better readability */
    tbody tr:nth-child(even) {
        background-color: #f7f9fa !important;
    }

    /* Print specific adjustments to fix colors and margins */
    @media print {
        body {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        @page {
            size: A4 portrait;
            margin: 20mm 15mm 20mm 15mm;
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
<!-- 2. MAIN REPORT TASK TABLE CARD SECTION (CONTAINER WITH UNIQUE ID)          -->
<!-- ========================================================================== -->
<div class="row">
    <div class="col-md-12">
        <!-- Target container wrapper for printing isolated report content -->
        <div class="card shadow-sm border-0" id="printable-report">
            <div class="card-body px-0 pb-0">
                <div class="table-responsive">
                    @forelse($tasks->chunk(6) as $pageIndex => $chunkedTasks)
                    <!-- Chunk container to force 6 records per page for both print and PDF engines -->
                    <div class="{{ $pageIndex > 0 ? 'page-break-container' : '' }}"
                        style="{{ $pageIndex > 0 ? 'margin-top: 20px;' : '' }}">
                        <table class="table align-items-center table-flush mb-0" id="tasksTable_{{ $pageIndex }}">
                            <thead>
                                <tr>
                                    <th>Task Title</th>
                                    <th>Description</th>
                                    <th>Project</th>
                                    <th>Assigned Employee</th>
                                    <th>Last Update</th>
                                    <th>Status & Progress</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($chunkedTasks as $task)
                                @php
                                $taskStatus = strtolower($task->status ?? 'in_progress');

                                $progressPercent = $task->progress ?? match($taskStatus) {
                                'completed' => 100,
                                'in_progress' => 50,
                                'pending' => 10,
                                default => 0
                                };
                                @endphp
                                <tr>
                                    <!-- Task Title Column -->
                                    <td style="font-weight: bold;">
                                        {{ $task->title ?? $task->name }}
                                    </td>

                                    <!-- Task Description Column -->
                                    <td style="max-width: 180px; word-break: break-word;">
                                        {{ $task->description ?? 'No description' }}
                                    </td>

                                    <!-- Project Name Column -->
                                    <td>
                                        {{ optional($task->project)->title ?? 'No Project' }}
                                    </td>

                                    <!-- Assigned Employee Column -->
                                    <td>
                                        {{ optional($task->assignedUser ?? $task->employee)->name ?? 'Unassigned' }}
                                    </td>

                                    <!-- Last Update Column -->
                                    <td>
                                        @if($task->updated_at)
                                        <div style="font-weight: bold;">{{ $task->updated_at->format('Y-m-d') }}</div>
                                        <div style="font-size: 8.5px;">{{ $task->updated_at->format('h:i A') }}</div>
                                        <div style="font-size: 7.5px; color: #5e72e4;">{{
                                            $task->updated_at->diffForHumans() }}</div>
                                        @else
                                        <span>N/A</span>
                                        @endif
                                    </td>

                                    <!-- Status Badge & Centered Progress Battery Column -->
                                    <td>
                                        <!-- Status Pill Badge -->
                                        <div style="margin-bottom: 4px;">
                                            <span
                                                style="display: inline-block; padding: 2px 8px; border-radius: 10px; font-weight: bold; font-size: 8px; color: #fff; background-color: @if($taskStatus == 'completed') #2dce89 @elseif($taskStatus == 'in_progress') #fbb140 @else #11cdef @endif;">
                                                {{ ucfirst(str_replace('_', ' ', $task->status ?? 'in_progress')) }}
                                            </span>
                                        </div>

                                        <!-- Centered Battery Style Progress Bar -->
                                        <div style="text-align: center; width: 100%;">
                                            <div
                                                style="font-size: 7.5px; font-weight: bold; color: #666; margin-bottom: 2px; text-align: center;">
                                                {{ $progressPercent }}%
                                            </div>

                                            <table
                                                style="width: auto !important; margin: 0 auto !important; border: none !important; background: transparent !important;">
                                                <tr>
                                                    <td
                                                        style="padding: 0 !important; border: none !important; background: transparent !important; vertical-align: middle !important;">
                                                        <div
                                                            style="height: 6px; width: 45px; background-color: #f1f3f5; border: 1px solid #dcdcdc; border-radius: 2px; overflow: hidden; padding: 0.5px;">
                                                            <div
                                                                style="height: 100%; width: {{ $progressPercent }}%; border-radius: 1px; background: @if($taskStatus == 'completed') #2dce89 @elseif($taskStatus == 'in_progress') #fbb140 @else #11cdef @endif;">
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td
                                                        style="padding: 0 !important; border: none !important; background: transparent !important; vertical-align: middle !important;">
                                                        <div
                                                            style="width: 1.5px; height: 3px; background-color: #dcdcdc; border-top-right-radius: 1px; border-bottom-right-radius: 1px; margin-left: 1px;">
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
                                <td colspan="6" style="padding: 30px; color: #777;">
                                    No task data available for report.
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
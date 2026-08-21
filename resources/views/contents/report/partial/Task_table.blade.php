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
<!-- MAIN REPORT TASK TABLE CARD SECTION -->
<!-- ========================================================================== -->
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0" id="printable-report">
            <div class="card-body px-0 pb-0">
                <div class="table-responsive">
                    @forelse($chunks as $pageIndex => $chunkedTasks)
                    <!-- Chunk container to force structured pages for print and PDF -->
                    <div class="{{ $pageIndex > 0 ? 'page-break-container' : '' }}"
                        style="{{ $pageIndex > 0 ? 'margin-top: 20px;' : '' }}">
                        <table class="table align-items-center table-flush mb-0" id="tasksTable_{{ $pageIndex }}">
                            <thead>
                                <tr>
                                    <th>Task Title</th>
                                    <th>Description</th>
                                    <th>Project</th>
                                    <th>Assigned Employee</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Last Update</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($chunkedTasks as $task)
                                @php
                                $today = now()->toDateString();
                                $dueDate = $task->due_date ? \Carbon\Carbon::parse($task->due_date)->toDateString() :
                                null;
                                $rawStatus = strtolower($task->status ?? 'in_progress');

                                // تحديد الحالة الحقيقية بناءً على التواريخ
                                if ($rawStatus === 'completed') {
                                $displayStatus = 'completed';
                                $statusLabel = 'Completed';
                                $statusColor = '#2dce89';
                                } elseif ($dueDate && $dueDate < $today) { $displayStatus='overdue' ;
                                    $statusLabel='Overdue' ; $statusColor='#f5365c' ; } elseif ($dueDate &&
                                    $dueDate===$today) { $displayStatus='due_today' ; $statusLabel='Due Today' ;
                                    $statusColor='#23b7e5' ; } else { $displayStatus=$rawStatus;
                                    $statusLabel=ucfirst(str_replace('_', ' ' , $rawStatus));
                                    $statusColor=$rawStatus==='in_progress' ? '#fbb140' : '#11cdef' ; } @endphp <tr>
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

                                    <!-- Start Date Column -->
                                    <td>
                                        @if($task->started_at)
                                        <div style="font-weight: bold;">{{
                                            \Carbon\Carbon::parse($task->started_at)->format('Y-m-d') }}</div>
                                        @else
                                        <span>N/A</span>
                                        @endif
                                    </td>

                                    <!-- End Date Column -->
                                    <td>
                                        @if($task->due_date)
                                        <div style="font-weight: bold;">{{
                                            \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') }}</div>
                                        @else
                                        <span>N/A</span>
                                        @endif
                                    </td>

                                    <!-- Last Update Column -->
                                    <td>
                                        @if($updatedAt = $task->updated_at)
                                        <div style="font-weight: bold;">{{
                                            \Carbon\Carbon::parse($updatedAt)->format('Y-m-d') }}</div>
                                        <div style="font-size: 8.5px;">{{ \Carbon\Carbon::parse($updatedAt)->format('h:i
                                            A') }}</div>
                                        @else
                                        <span>N/A</span>
                                        @endif
                                    </td>

                                    <!-- Status Column (Progress Bar Removed) -->
                                    <td>
                                        <span
                                            style="display: inline-block; padding: 3px 10px; border-radius: 10px; font-weight: bold; font-size: 9px; color: #fff; background-color: {{ $statusColor }};">
                                            {{ $statusLabel }}
                                        </span>
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
                                <td colspan="8" style="padding: 30px; color: #777; text-align: center;">
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

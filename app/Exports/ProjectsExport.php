<?php

namespace App\Exports;

use App\Models\Project;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class ProjectsExport implements FromQuery, WithHeadings, WithMapping, WithColumnWidths, WithStyles
{
    protected $request;

    /**
     * =========================================================================
     * CONSTRUCTOR
     * Accept the HTTP request containing the active filters from the controller.
     * =========================================================================
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * =========================================================================
     * QUERY METHOD
     * Replaces collection() to build a filtered query instead of fetching all records.
     * =========================================================================
     */
    public function query()
    {
        $query = Project::with(['manager', 'tasks.assignedUser']);

        /* Filter by project title if provided and not set to 'all' */
        if ($this->request->filled('title') && $this->request->title !== 'all') {
            $query->where('title', 'like', '%' . trim($this->request->title, '.') . '%');
        }

        /* Filter by specific manager ID if provided and not set to 'all' */
        if ($this->request->filled('manager_id') && $this->request->manager_id !== 'all') {
            $query->where('manager_id', $this->request->manager_id);
        }

        /* Filter by project status if provided and not set to 'all' */
        if ($this->request->filled('status') && $this->request->status != 'all') {
            $status = $this->request->status;

            if ($status === 'overdue') {
                $query->whereNotIn('status', ['completed', 'complete'])
                    ->whereDate('end_date', '<', Carbon::today());
            } elseif ($status === 'due_today') {
                $query->whereNotIn('status', ['completed', 'complete'])
                    ->whereDate('end_date', '=', Carbon::today());
            } else {
                $query->where('status', $status)
                    ->where(function ($q) {
                        $q->whereNull('end_date')
                            ->orWhereDate('end_date', '>', Carbon::today());
                    });
            }
        }

        /* Global search filter if provided */
        if ($this->request->filled('search')) {
            $search = $this->request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('manager', function ($mQuery) use ($search) {
                        $mQuery->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('tasks', function ($t) use ($search) {
                        $t->where('title', 'like', "%{$search}%");
                    });
            });
        }

        return $query;
    }

    /**
     * =========================================================================
     * HEADINGS METHOD
     * Define the titles that will appear in the very first row of your Excel file.
     * =========================================================================
     */
    public function headings(): array
    {
        return [
            'Project Title',
            'Description',
            'Project Manager',
            'Start Date',
            'End Date',
            'Budget',
            'Total Tasks',
            'Project Tasks & Assigned Employees',
            'Status'
        ];
    }

    /**
     * =========================================================================
     * COLUMN WIDTHS METHOD
     * Sets fixed, neat widths for each column to control text wrapping.
     * =========================================================================
     */
    public function columnWidths(): array
    {
        return [
            'A' => 25, // Project Title
            'B' => 35, // Description
            'C' => 20, // Project Manager
            'D' => 16, // Start Date
            'E' => 16, // End Date
            'F' => 18, // Budget
            'G' => 15, // Total Tasks
            'H' => 40, // Tasks list
            'I' => 25, // Status
        ];
    }

    /**
     * =========================================================================
     * MAPPING METHOD
     * This shapes the data row-by-row. We convert complex database objects
     * into simple, readable strings.
     * =========================================================================
     */
    public function map($project): array
    {
        $tasksCount = $project->tasks_count ?? ($project->tasks ? $project->tasks->count() : 0);

        $tasksList = "";
        if ($project->tasks && $project->tasks->count() > 0) {
            $tasksArray = [];
            foreach ($project->tasks as $task) {
                $taskTitle = $task->title ?? $task->name ?? 'Untitled Task';
                $assignedName = optional($task->assignedUser ?? $task->employee)->name ?? 'Unassigned';
                $tasksArray[] = "- " . $taskTitle . " (" . $assignedName . ")";
            }
            $tasksList = implode("\n", $tasksArray);
        } else {
            $tasksList = "No tasks assigned";
        }

        $rawStatus = strtolower($project->status ?? 'in_progress');

        // حساب الحالة الديناميكية أثناء التصدير لتعكس overdue أو due_today بدقة
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

        $statusText = ucfirst(str_replace('_', ' ', $projectStatus));

        $startDate = $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('Y-m-d') : 'N/A';
        $endDate = $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('Y-m-d') : 'N/A';
        $budget = $project->budget ? '$' . number_format($project->budget, 2) : 'N/A';

        return [
            $project->title,
            $project->description ?? 'No description',
            optional($project->manager)->name ?? 'No Manager',
            $startDate,
            $endDate,
            $budget,
            $tasksCount . ' Tasks',
            $tasksList,
            $statusText
        ];
    }

    /**
     * =========================================================================
     * STYLES METHOD
     * Applies professional styling: bold coral header, light-grey rows, borders.
     * =========================================================================
     */
    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        $dataRange = 'A2:' . $highestColumn . $highestRow;

        return [
            // --- HEADER STYLE ---
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFF'],
                    'size' => 11,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF5E3A'], // Now UI Primary Coral
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],

            // --- DATA TABLE STYLE ---
            $dataRange => [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'F2F4F7'], // Light professional gray
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'B0B0B0'],
                    ],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
            ],
        ];
    }
}

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
            $query->where('status', $this->request->status);
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
            'Total Tasks',
            'Project Tasks & Assigned Employees',
            'Status & Progress'
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
            'A' => 25, // Project Title: Medium width
            'B' => 35, // Description: Fixed at 35 so text wraps to multiple lines
            'C' => 20, // Manager: Standard width
            'D' => 15, // Total Tasks: Compact width
            'E' => 40, // Tasks list: Wider to accommodate multi-line content
            'F' => 25, // Status: Medium width
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

        $projectStatus = strtolower($project->status ?? 'in_progress');
        $progressPercent = $project->progress ?? match ($projectStatus) {
            'completed' => 100,
            'in_progress' => 50,
            'pending' => 10,
            default => 0
        };

        $statusText = ucfirst(str_replace('_', ' ', $project->status ?? 'in_progress'));
        $statusAndProgress = $statusText . " (" . $progressPercent . "%)";

        return [
            $project->title,
            $project->description ?? 'No description',
            optional($project->manager)->name ?? 'No Manager',
            $tasksCount . ' Tasks',
            $tasksList,
            $statusAndProgress
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
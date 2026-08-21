<?php

namespace App\Exports;

// =========================================================================
// IMPORT NECESSARY MODELS AND PACKAGES
// =========================================================================
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Carbon\Carbon;

class TaskExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $request;

    public function __construct(Request $request = null)
    {
        $this->request = $request;
    }

    /**
     * =========================================================================
     * QUERY METHOD (Filtered Support matching Controller and PDF/Print)
     * =========================================================================
     */
    public function query()
    {
        $user = Auth::user();
        $request = $this->request ?? request();

        $query = Task::with(['project', 'assignedUser', 'user']);

        // فلترة دقيقة تعتمد على التاريخ الحالي للسيرفر لضمان جلب المهام المتأخرة أو المستحقة اليوم بمعزل عن الحالات الأخرى
        if ($request->filled('status') && $request->status !== 'all') {
            $status = $request->status;

            if ($status === 'overdue') {
                $query->whereNotIn('status', ['completed', 'complete'])
                    ->whereDate('due_date', '<', Carbon::today());
            } elseif ($status === 'due_today') {
                $query->whereNotIn('status', ['completed', 'complete'])
                    ->whereDate('due_date', '=', Carbon::today());
            } else {
                $query->where('status', $status)
                    ->where(function ($q) {
                        $q->whereNull('due_date')
                            ->orWhereDate('due_date', '>', Carbon::today());
                    });
            }
        }

        return $query->filterAndSearch($user, $request);
    }

    public function headings(): array
    {
        return [
            'Task Title',
            'Description',
            'Project',
            'Assigned Employee',
            'Start Date',
            'End Date',
            'Last Update',
            'Status'
        ];
    }

    public function map($task): array
    {
        $today = now()->toDateString();
        $dueDate = $task->due_date ? \Carbon\Carbon::parse($task->due_date)->toDateString() : null;
        $rawStatus = strtolower($task->status ?? 'in_progress');

        if ($rawStatus === 'completed') {
            $statusLabel = 'Completed';
        } elseif ($dueDate && $dueDate < $today) {
            $statusLabel = 'Overdue';
        } elseif ($dueDate && $dueDate === $today) {
            $statusLabel = 'Due Today';
        } else {
            $statusLabel = ucfirst(str_replace('_', ' ', $rawStatus));
        }

        return [
            $task->title ?? $task->name,
            $task->description ?? 'No description',
            optional($task->project)->title ?? 'No Project',
            optional($task->assignedUser ?? $task->employee)->name ?? 'Unassigned',
            $task->started_at ? \Carbon\Carbon::parse($task->started_at)->format('Y-m-d') : 'N/A',
            $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : 'N/A',
            $task->updated_at ? $task->updated_at->format('Y-m-d H:i:s') : 'N/A',
            $statusLabel
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 22,
            'B' => 35,
            'C' => 20,
            'D' => 22,
            'E' => 16,
            'F' => 16,
            'G' => 20,
            'H' => 18,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $dataRange = 'A2:' . $highestColumn . $highestRow;

        return [
            1 => [
                'font' => [
                    'bold' => `true`,
                    'color' => ['argb' => 'FFFFFF'],
                    'size' => 11,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF5E3A'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
            $dataRange => [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'F2F4F7'],
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

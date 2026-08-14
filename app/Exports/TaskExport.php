<?php

namespace App\Exports;

// =========================================================================
// IMPORT NECESSARY MODELS AND PACKAGES
// =========================================================================
use App\Models\Task;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class TaskExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $request;

    public function __construct(Request $request = null)
    {
        $this->request = $request;
    }

    /**
     * =========================================================================
     * COLLECTION METHOD (Filtered Support)
     * =========================================================================
     */
    public function collection()
    {
        // ابدأ بإنشاء Query مع جلب العلاقات المطلوبة مسبقاً لتحسين الأداء (Eager Loading)
        $query = Task::with(['project', 'assignedUser']);

        // التحقق من وجود الطلب وتطبيق الفلاتر إذا وجدت
        if ($this->request) {

            // 1. الفلترة حسب حالة المهمة (Status)
            if ($this->request->filled('status')) {
                $query->where('status', $this->request->status);
            }

            // 2. الفلترة حسب المشروع (Project ID)
            if ($this->request->filled('project_id')) {
                $query->where('project_id', $this->request->project_id);
            }

            // 3. الفلترة حسب الموظف المسؤول (Assigned User ID)
            if ($this->request->filled('user_id') || $this->request->filled('employee_id')) {
                $userId = $this->request->input('user_id') ?? $this->request->input('employee_id');
                $query->where('assigned_to', $userId); // استبدل assigned_to باسم عمود المفتاح الأجنبي لديك إن كان مختلفاً
            }

            // 4. البحث النصي العام (Search query) إن وجد
            if ($this->request->filled('search') || $this->request->filled('keyword')) {
                $keyword = $this->request->input('search') ?? $this->request->input('keyword');
                $query->where(function ($q) use ($keyword) {
                    $q->where('title', 'like', "%{$keyword}%")
                        ->orWhere('description', 'like', "%{$keyword}%");
                });
            }
        }

        // إرجاع البيانات المطابقة للفلتر أو كل البيانات إن لم توجد فلاتر
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Task Title',
            'Description',
            'Project',
            'Assigned Employee',
            'Last Update',
            'Status',
            'Progress'
        ];
    }

    public function map($task): array
    {
        $taskStatus = strtolower($task->status ?? 'in_progress');

        $progressPercent = $task->progress ?? match ($taskStatus) {
            'completed'   => 100,
            'in_progress' => 50,
            'pending'     => 10,
            default       => 0
        };

        return [
            $task->title ?? $task->name,
            $task->description ?? 'No description',
            optional($task->project)->title ?? 'No Project',
            optional($task->assignedUser ?? $task->employee)->name ?? 'Unassigned',
            $task->updated_at ? $task->updated_at->format('Y-m-d H:i:s') : 'N/A',
            ucfirst(str_replace('_', ' ', $task->status ?? 'in_progress')),
            $progressPercent . '%'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 22,
            'B' => 35,
            'C' => 20,
            'D' => 22,
            'E' => 20,
            'F' => 18,
            'G' => 15,
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
                    'bold' => true,
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
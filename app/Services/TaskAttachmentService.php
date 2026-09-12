<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TaskAttachmentService
{
    public function uploadAttachments($request)
    {
        // قم بتغيير 'attachment' إلى 'attachments' بالجمع
        if (!$request->hasFile('attachments')) {
            return null;
        }

        $uploadedFiles = [];

        // قم بتغيير 'attachment' إلى 'attachments' هنا أيضاً
        foreach ($request->file('attachments') as $file) {
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeName = Str::slug($originalName);
            $filename = time() . '_' . uniqid() . '_' . $safeName . '.' . $file->getClientOriginalExtension();

            $file->storeAs('tasks_attachments', $filename, 'public');

            $uploadedFiles[] = 'tasks_attachments/' . $filename;
        }

        return $uploadedFiles;
    }

    public function deleteAttachments($attachmentData)
    {
        if (!$attachmentData) {
            return;
        }

        $attachments = is_array($attachmentData)
            ? $attachmentData
            : [$attachmentData];

        foreach ($attachments as $attachment) {
            $filePath = is_array($attachment)
                ? ($attachment['path'] ?? $attachment['file'] ?? '')
                : $attachment;

            $filePath = trim($filePath, '"[] ');

            if (!empty($filePath) && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
        }
    }

    public function getFormattedAttachments($task)
    {
        $attachments = $task->attachments_list;

        $formatted = [];

        foreach ($attachments as $file) {
            $filePath = is_array($file) ? ($file['path'] ?? $file['file'] ?? '') : $file;
            $filePath = trim($filePath, '"[] ');

            if (empty($filePath)) {
                continue;
            }

            $fileName = basename($filePath);
            $extension = strtolower(pathinfo(parse_url($fileName, PHP_URL_PATH), PATHINFO_EXTENSION));
            $extension = rtrim($extension, '"]');

            $formatted[] = [
                'path'      => $filePath,
                'name'      => $fileName,
                'extension' => $extension,
                'is_image'  => in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']),
                'is_video'  => in_array($extension, ['mp4', 'mov', 'avi', 'mkv', 'webm']),
                'is_pdf'    => $extension === 'pdf',
                'is_doc'     => in_array($extension, ['doc', 'docx', 'txt', 'rtf']),
                'is_archive' => in_array($extension, ['zip', 'rar', 'tar', 'gz']),
            ];
        }

        return $formatted;
    }

    public function validateAndGetPath($request)
    {
        $path = $request->get('path');

        if (!$path || !Storage::disk('public')->exists($path)) {
            return null;
        }

        return $path;
    }

    public function handleTaskAttachments($request, $task)
    {
        // 1. جلب المرفقات الحالية من المهمة (باعتبارها مصفوفة جاهزة بسبب الـ Casts)
        $currentAttachments = $task->attachments_list;

        // 2. معالجة حذف ملفات محددة إذا طلبت الواجهة ذلك
        if ($request->has('remove_attachments')) {
            $filesToRemove = $request->input('remove_attachments', []);

            foreach ($filesToRemove as $oldFile) {
                $this->deleteAttachments($oldFile);

                $currentAttachments = array_values(
                    array_diff($currentAttachments, [$oldFile])
                );
            }
        }

        // 3. معالجة رفع ملفات جديدة
        if ($request->hasFile('attachments')) {
            // (اختياري) إذا أردت حذف جميع الملفات القديمة عند رفع ملفات جديدة بالكامل:
            foreach ($currentAttachments as $oldFile) {
                $this->deleteAttachments($oldFile);
            }

            $currentAttachments = [];

            // رفع الملفات الجديدة باستخدام دالتك الموجودة مسبقاً uploadAttachments
            $newAttachments = $this->uploadAttachments($request) ?? [];

            $currentAttachments = $newAttachments;
        }

        // إرجاع المصفوفة النهائية لتخزينها في قاعدة البيانات
        return !empty($currentAttachments) ? $currentAttachments : null;
    }
}

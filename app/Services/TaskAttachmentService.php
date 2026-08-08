<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * =========================================================================
 * TASK ATTACHMENT SERVICE CLASS
 * =========================================================================
 * This service class is responsible for handling all file attachment
 * operations (uploading and deleting files securely) for tasks.
 * This keeps our controllers clean and follows the Single Responsibility Principle.
 */
class TaskAttachmentService
{
    // =========================================================================
    // UPLOAD ATTACHMENTS METHOD
    // =========================================================================

    /**
     * Upload multiple attachments securely and return JSON encoded paths.
     *
     * @param \Illuminate\Http\Request $request
     * @return string|null
     */
    public function uploadAttachments($request)
    {
        // -----------------------------------------------------------------
        // STEP 1: Check if the request contains 'attachments' files
        // -----------------------------------------------------------------
        if (!$request->hasFile('attachments')) {
            return null; // Return null if no files were uploaded
        }

        // Initialize an empty array to store the paths of successfully uploaded files
        $uploadedFiles = [];

        // -----------------------------------------------------------------
        // STEP 2: Loop through each uploaded file and store it securely
        // -----------------------------------------------------------------
        foreach ($request->file('attachments') as $file) {

            // Generate a unique, safe filename to prevent overwriting and security issues
            // Str::slug sanitizes the original filename (removes spaces and special characters)
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeName = Str::slug($originalName);
            $filename = time() . '_' . uniqid() . '_' . $safeName . '.' . $file->getClientOriginalExtension();

            // Store the file in the 'public' disk inside the 'tasks_attachments' folder
            $file->storeAs('tasks_attachments', $filename, 'public');

            // Save the relative file path to the array
            $uploadedFiles[] = 'tasks_attachments/' . $filename;
        }

        // -----------------------------------------------------------------
        // STEP 3: Convert the array of file paths into a JSON string for database storage
        // -----------------------------------------------------------------
        return json_encode($uploadedFiles);
    }

    // =========================================================================
    // DELETE ATTACHMENTS METHOD
    // =========================================================================

    /**
     * Delete old attachments from public storage safely.
     *
     * @param string|null $attachmentData
     * @return void
     */
    public function deleteAttachments($attachmentData)
    {
        // -----------------------------------------------------------------
        // STEP 1: Stop execution if there is no attachment data provided
        // -----------------------------------------------------------------
        if (!$attachmentData) {
            return;
        }

        // -----------------------------------------------------------------
        // STEP 2: Decode JSON data back into an array (for multiple files)
        // -----------------------------------------------------------------
        $oldAttachments = json_decode($attachmentData, true);

        // -----------------------------------------------------------------
        // STEP 3: Loop and delete each file if it exists in storage
        // -----------------------------------------------------------------
        if (is_array($oldAttachments)) {
            foreach ($oldAttachments as $oldFile) {
                // Check if file physically exists before attempting deletion to avoid errors
                if (Storage::disk('public')->exists($oldFile)) {
                    Storage::disk('public')->delete($oldFile);
                }
            }
        }
        // Fallback for handling legacy single-file strings if any exist in the database
        elseif (Storage::disk('public')->exists($attachmentData)) {
            Storage::disk('public')->delete($attachmentData);
        }
    }
}

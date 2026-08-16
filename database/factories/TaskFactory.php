<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * ========================================================================= * TASK FACTORY CLASS DEFINITION
 * ========================================================================= */
class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Task::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // محاكاة حالة وجود مرفقات أو عدم وجودها عشوائياً (مثلاً 70% يمتلكون مرفقات و 30% بدون مرفقات)
        $hasAttachments = $this->faker->boolean(70);
        $attachments = null;

        if ($hasAttachments) {
            // تحديد عدد الملفات الوهمية (مثلاً من 1 إلى 3 ملفات)
            $fileCount = $this->faker->numberBetween(1, 3);
            $files = [];

            for ($i = 0; $i < $fileCount; $i++) {
                // توليد مسار وهمي للملف داخل مجلد التخزين
                $files[] = 'tasks/attachments/' . $this->faker->uuid() . '.' . $this->faker->randomElement(['pdf', 'docx', 'png', 'jpg', 'zip']);
            }

            // إذا كنت تخزن الملفات كـ JSON في قاعدة البيانات:
            $attachments = json_encode($files);

            // ملاحظة: إذا كان عمود قاعدة البيانات لديك يحفظ مسار ملف واحد كنص (String) بدلاً من JSON،
            // يمكنك استخدام السطر التالي بدلاً من السطر أعلاه:
            // $attachments = $files[0];
        }

        // توليد تواريخ زمنية متناسقة للمهمة
        $startDate = $this->faker->dateTimeBetween('-1 month', '+1 month');
        $dueDate = (clone $startDate)->modify('+' . $this->faker->numberBetween(1, 14) . ' days');

        return [
            // ========================================================= //
            // TASK BASIC ATTRIBUTES //
            // ========================================================= //

            // Generate a random 4-word sentence for the task title
            'title' => $this->faker->sentence(4),

            // Generate a random paragraph for detailed task descriptions
            'description' => $this->faker->paragraph(),

            // ========================================================= //
            // RELATIONSHIPS BINDING (PROJECT & USER) //
            // ========================================================= //

            // Assign a random existing project ID, or create a new project factory instance if none exist
            'project_id' => Project::inRandomOrder()->first()?->id ?? Project::factory(),

            // Assign a random existing user ID, or create a new user factory instance if none exist
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),

            // ========================================================= //
            // TASK WORKFLOW STATUS & ATTACHMENTS //
            // ========================================================= //

            // Randomly pick a status state for the task lifecycle progression
            'status' => $this->faker->randomElement(['pending', 'in_progress', 'completed']),

            // Add simulated attachments data
            'attachment' => $attachments, // This will be either a JSON string of file paths or null if no attachments are present

            // ========================================================= //
            // TASK TIMELINE (START & DUE DATES) //
            // ========================================================= //
            'started_at' => $startDate,
            'due_date' => $dueDate,
        ];
    }
}
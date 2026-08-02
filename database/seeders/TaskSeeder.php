<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        // Generate 15 random task records using the Task factory
        Task::factory()->count(15)->create();
    }
}
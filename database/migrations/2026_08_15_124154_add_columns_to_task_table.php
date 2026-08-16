<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->integer('estimated_hours')->nullable()->after('description'); // This column will store the estimated hours
            $table->timestamp('started_at')->nullable()->after('estimated_hours'); // This column will store the start date
            $table->timestamp('due_date')->nullable()->after('started_at'); // This column will store the due date
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('estimated_hours');
            $table->dropColumn('started_at');
            $table->dropColumn('due_date');
        });
    }
};

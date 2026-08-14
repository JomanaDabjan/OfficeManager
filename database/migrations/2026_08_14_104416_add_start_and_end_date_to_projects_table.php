<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add start_date and end_date columns to the projects table.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Add project start date column after description or status
            $table->date('start_date')->nullable()->after('status');

            // Add project target end date column
            $table->date('end_date')->nullable()->after('start_date');
        });
    }

    /**
     * Reverse the migrations.
     * Drop start_date and end_date columns if rollback is executed.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Drop columns safely on rollback
            $table->dropColumn(['start_date', 'end_date']);
        });
    }
};

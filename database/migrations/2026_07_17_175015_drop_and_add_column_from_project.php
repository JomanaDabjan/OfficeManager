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
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('status');
            // إضافة الـ foreign key الجديد
            $table->foreignId('status_id')->constrained('statuses')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // في حال أردتِ التراجع عن الحذف مستقبلاً
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
        });
    }
};

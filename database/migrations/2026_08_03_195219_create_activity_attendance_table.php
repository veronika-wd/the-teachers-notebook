<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_activity_id')
                ->constrained('student_activity')
                ->cascadeOnDelete();
            $table->date('date');
            // present / absent / late
            $table->enum('status', ['present', 'absent', 'late'])->default('present');
            $table->timestamps();

            // Одна запись на ученика на дату
            $table->unique(['student_activity_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_attendance');
    }
};

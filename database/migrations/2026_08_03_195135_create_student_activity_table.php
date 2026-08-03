<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_activity', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('activity_id')->constrained('activities')->cascadeOnDelete();
            $table->date('enrolled_at')->useCurrent();
            $table->date('withdrawn_at')->nullable(); // дата отчисления, null = активен
            $table->timestamps();

            // Ученик может быть записан на один кружок только один раз (активно)
            $table->unique(['student_id', 'activity_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_activity');
    }
};

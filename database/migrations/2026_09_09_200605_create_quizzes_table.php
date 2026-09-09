<?php

use App\Models\QuizLevel;
use App\Models\QuizType;
use App\Models\SchoolClass;
use App\Models\Subject;
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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Subject::class)->constrained();
            $table->date('date');
            $table->foreignIdFor(QuizLevel::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(QuizType::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(SchoolClass::class)->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};

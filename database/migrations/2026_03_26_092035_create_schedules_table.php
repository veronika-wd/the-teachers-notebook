<?php

use App\Models\User;
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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->integer('day');
            $table->integer('number');
            $table->string('class');
            $table->integer('cabinet');
            $table->foreignIdFor(User::class)->constrained();
            $table->timestamps();

            $table->unique(['class', 'day', 'number'], 'unique_class_schedule');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};

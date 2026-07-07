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
        Schema::table('guardians', function (Blueprint $table) {
            $table->string('snils', 14)->unique()->nullable(); // СНИЛС
            $table->string('inn', 12)->unique()->nullable(); // ИНН
            $table->string('passport_data', 100)->unique()->nullable(); // Серия/номер паспорта
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guardians', function (Blueprint $table) {
            $table->dropColumn(['snils', 'inn', 'passport_data']);
        });
    }
};

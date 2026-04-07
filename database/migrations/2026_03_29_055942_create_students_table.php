<?php

use App\Models\SchoolClass;
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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Имя пользователя
            $table->string('surname'); // Фамилия пользователя
            $table->string('patronymic')->nullable(); // Отчество пользователя (допускает NULL)
            $table->string('snils', 14)->unique()->nullable(); // СНИЛС
            $table->string('inn', 12)->unique()->nullable(); // ИНН
            $table->string('passport_data', 100)->unique()->nullable(); // Серия/номер паспорта
            $table->date('birth_date'); // Дата рождения
            $table->string('status')->nullable();
            $table->foreignIdFor(SchoolClass::class, 'class')->constrained(); // Телефон
            $table->string('address', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};

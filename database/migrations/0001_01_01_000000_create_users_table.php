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
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // INT (PK, AI)
            $table->string('email')->unique();
            $table->string('password');
            $table->string('name', 50); // Имя пользователя
            $table->string('snils', 14)->unique(); // СНИЛС
            $table->string('inn', 12)->unique(); // ИНН
            $table->string('passport_data', 100)->unique(); // Серия/номер паспорта
            $table->dateTime('birth_date'); // Дата рождения
            $table->string('post', 50); // Должность (учитель, секретарь и т.д.)
            $table->string('phone', 20); // Телефон
            $table->string('education', 255); // Образование
            $table->string('qualification', 100)->nullable(); // Квалификационная категория (может отсутствовать)
            $table->integer('experience');
            $table->string('address', 255); // Адрес проживания
            $table->string('role');
            $table->timestamps(); // created_at и updated_at
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};


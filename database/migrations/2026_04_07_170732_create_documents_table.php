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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('original_name'); // Имя файла, которое было у пользователя (например, "Отчет.docx")
            $table->string('file_path');     // Путь к файлу в хранилище (например, "documents/xyz123.docx")
            $table->string('mime_type');     // MIME-тип файла (для безопасности и корректной отдачи)
            $table->unsignedBigInteger('size')->nullable(); // Размер файла (опционально)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};

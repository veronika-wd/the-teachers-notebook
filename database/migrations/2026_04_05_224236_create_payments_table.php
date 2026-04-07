<?php

// database/migrations/xxxx_xx_xx_create_payments_table.php

use App\Models\Student;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Student::class)->constrained();
            $table->date('payment_date'); // Дата внесения денег
            $table->decimal('amount', 10, 2); // Сумма
            $table->string('comment')->nullable(); // Примечание (например, "За октябрь", "Доплата")
            $table->timestamps();

            $table->index(['student_id', 'payment_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

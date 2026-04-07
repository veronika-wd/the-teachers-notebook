<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'student_id',
        'payment_date',
        'amount',
        'comment',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    // Красивый вывод суммы: 1 500,00 ₽
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2, ',', ' ') . ' ₽';
    }
}

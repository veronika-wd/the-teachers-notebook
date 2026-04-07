<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Student extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function achievements(): MorphMany
    {
        return $this->morphMany(Achievement::class, 'achievable');
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function guardians(): BelongsToMany
    {
        return $this->belongsToMany(Guardian::class, 'guardian_student', 'student_id', 'guardian_id');
    }

    public function competitions(): BelongsToMany
    {
        return $this->belongsToMany(Competition::class, 'competition_student', 'student_id', 'competition_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    // Добавьте этот метод в класс Student

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

// Общая сумма платежей за период
    public function getTotalPaymentsAttribute()
    {
        return $this->payments()->sum('amount');
    }

// Сумма платежей за текущий месяц
    public function getCurrentMonthPaymentsAttribute()
    {
        return $this->payments()
            ->whereYear('payment_date', now()->year)
            ->whereMonth('payment_date', now()->month)
            ->sum('amount');
    }

    protected $casts = [
        'birth_date' => 'date',
    ];
}

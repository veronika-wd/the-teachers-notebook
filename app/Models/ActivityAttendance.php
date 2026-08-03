<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityAttendance extends Model
{
    // 👇 Явно указываем имя таблицы
    protected $table = 'activity_attendance';

    protected $fillable = [
        'student_activity_id',
        'date',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function studentActivity(): BelongsTo
    {
        return $this->belongsTo(StudentActivity::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'present' => 'Присутствует',
            'absent'  => 'Отсутствует',
            'late'    => 'Опоздал',
            default   => 'Не отмечено',
        };
    }
}

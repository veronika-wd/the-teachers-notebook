<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentActivity extends Model
{
    protected $table = 'student_activity';

    protected $fillable = [
        'student_id',
        'activity_id',
        'enrolled_at',
        'withdrawn_at',
    ];

    protected $casts = [
        'enrolled_at'  => 'date',
        'withdrawn_at' => 'date',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    /**
     * Записи посещаемости
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(ActivityAttendance::class);
    }

    /**
     * Активна ли запись
     */
    public function isActive(): bool
    {
        return is_null($this->withdrawn_at);
    }
}

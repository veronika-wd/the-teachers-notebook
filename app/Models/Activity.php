<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

class Activity extends Model
{
    protected $fillable = [
        'name',
        'description',
        'week_days',
        'start_time',
        'end_time',
        'room',
        'teacher_id',
    ];

    protected $casts = [
        'week_days' => 'array', // [1, 3, 5] — автоматически из JSON
    ];

    /**
     * Ученики, записанные на этот кружок
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(
            Student::class,
            'student_activity',
            'activity_id',
            'student_id'
        )->withPivot(['enrolled_at', 'withdrawn_at'])
            ->wherePivotNull('withdrawn_at'); // только активные записи
    }

    /**
     * Все записи (включая pivot данные)
     */
    public function studentActivities(): HasMany
    {
        return $this->hasMany(StudentActivity::class);
    }

    /**
     * Темы занятий
     */
    public function themes(): HasMany
    {
        return $this->hasMany(Theme::class)->orderBy('date');
    }

    /**
     * Получить все даты месяца, когда кружок проводится
     * (только дни недели из week_days)
     */
    public function getMeetingDatesForMonth(int $year, int $month): array
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate   = Carbon::create($year, $month, 1)->endOfMonth();

        $dates = [];
        $current = $startDate->copy();

        while ($current <= $endDate) {
            // isoWeekday(): 1=Пн, 2=Вт, ... 7=Вс
            if (in_array($current->isoWeekday(), $this->week_days)) {
                $dates[] = $current->copy();
            }
            $current->addDay();
        }

        return $dates;
    }

    /**
     * Человекочитаемое расписание: "Пн, Ср, Пт"
     */
    public function getScheduleLabelAttribute(): string
    {
        $dayNames = [
            1 => 'Пн', 2 => 'Вт', 3 => 'Ср', 4 => 'Чт',
            5 => 'Пт', 6 => 'Сб', 7 => 'Вс',
        ];

        return collect($this->week_days)
            ->sort()
            ->map(fn($d) => $dayNames[$d] ?? '?')
            ->implode(', ');
    }

    /**
     * Количество занятий в неделю
     */
    public function getFrequencyAttribute(): int
    {
        return count($this->week_days);
    }
}

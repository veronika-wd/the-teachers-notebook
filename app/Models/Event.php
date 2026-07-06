<?php

// app/Models/Event.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon $date_start
 * @property \Illuminate\Support\Carbon $date_end
 * @property string|null $description
 * @property string|null $color
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereDateEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereDateStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereUserId($value)
 * @mixin \Eloquent
 */
class Event extends Model
{
    protected $fillable = [
        'name',
        'date_start',
        'date_end',
        'description',
        'color',
        'user_id'
    ];

    protected $casts = [
        'date_start' => 'datetime',
        'date_end' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Конвертация для FullCalendar
    public function toCalendarFormat(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->name,
            'start' => $this->date_start->toISOString(),
            'end' => $this->date_end?->toISOString(),
            'description' => $this->description,
            'backgroundColor' => $this->color,
            'borderColor' => $this->color,
            'allDay' => !$this->date_start->format('H:i') || $this->date_start->format('H:i') === '00:00'
        ];
    }
}

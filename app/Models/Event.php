<?php

// app/Models/Event.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

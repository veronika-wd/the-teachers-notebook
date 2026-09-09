<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Monolog\Level;
use Nette\Utils\Type;

class Quiz extends Model
{
    protected $guarded = ['id'];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function quizLevel(): BelongsTo
    {
        return $this->belongsTo(QuizLevel::class);
    }

    public function quizType(): BelongsTo
    {
        return $this->belongsTo(QuizType::class);
    }

    protected $casts = [
        'date' => 'datetime',
    ];
}

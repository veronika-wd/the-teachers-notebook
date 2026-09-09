<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizLevel extends Model
{
    protected $guarded = ['id'];

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }
}

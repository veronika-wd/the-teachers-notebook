<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Competition extends Model
{
    protected $guarded = ['id'];

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'competition_student', 'competition_id', 'student_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(CompetitionFile::class);
    }

    protected $casts = [
        'registration_start' => 'datetime',
        'registration_end'   => 'datetime',
        'contest_start'      => 'datetime',
        'contest_end'        => 'datetime',
    ];
}

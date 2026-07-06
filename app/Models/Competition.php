<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string|null $file
 * @property string $register_start
 * @property string $register_end
 * @property string $competition_start
 * @property string $competition_end
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CompetitionFile> $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Student> $students
 * @property-read int|null $students_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Competition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Competition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Competition query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Competition whereCompetitionEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Competition whereCompetitionStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Competition whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Competition whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Competition whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Competition whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Competition whereRegisterEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Competition whereRegisterStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Competition whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Competition whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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

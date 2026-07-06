<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $school_class_id
 * @property int $subject_id
 * @property int $cabinet
 * @property int $number
 * @property int $user_id
 * @property string $date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\SchoolClass $schoolClass
 * @property-read \App\Models\Subject $subject
 * @property-read \App\Models\User $teacher
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Change newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Change newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Change query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Change whereCabinet($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Change whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Change whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Change whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Change whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Change whereSchoolClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Change whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Change whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Change whereUserId($value)
 * @mixin \Eloquent
 */
class Change extends Model
{
    protected $guarded = ['id'];

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher() {
        return $this->belongsTo(User::class, 'user_id');
    }
}

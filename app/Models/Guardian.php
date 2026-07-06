<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $full_name
 * @property string $phone
 * @property string $job
 * @property string $address
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Student> $students
 * @property-read int|null $students_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guardian newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guardian newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guardian query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guardian whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guardian whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guardian whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guardian whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guardian whereJob($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guardian wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guardian whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guardian whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Guardian extends Model
{
    protected $guarded = ['id'];

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'guardian_student', 'guardian_id', 'student_id');
    }
}

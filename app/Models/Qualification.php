<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $title
 * @property string $image
 * @property string $date_start
 * @property string $date_end
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Qualification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Qualification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Qualification query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Qualification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Qualification whereDateEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Qualification whereDateStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Qualification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Qualification whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Qualification whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Qualification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Qualification whereUserId($value)
 * @mixin \Eloquent
 */
class Qualification extends Model
{
    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

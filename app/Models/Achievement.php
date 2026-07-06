<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property string $name
 * @property string $path
 * @property int $achievable_id
 * @property string $achievable_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $achievementable
 * @method static Builder<static>|Achievement newModelQuery()
 * @method static Builder<static>|Achievement newQuery()
 * @method static Builder<static>|Achievement query()
 * @method static Builder<static>|Achievement whereAchievableId($value)
 * @method static Builder<static>|Achievement whereAchievableType($value)
 * @method static Builder<static>|Achievement whereCreatedAt($value)
 * @method static Builder<static>|Achievement whereId($value)
 * @method static Builder<static>|Achievement whereName($value)
 * @method static Builder<static>|Achievement wherePath($value)
 * @method static Builder<static>|Achievement whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Achievement extends Model
{
    protected $guarded = ['id'];

    public function achievementable(): MorphTo
    {
        return $this->morphTo();
    }
}

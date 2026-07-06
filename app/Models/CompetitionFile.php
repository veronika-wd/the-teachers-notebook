<?php

// app/Models/CompetitionFile.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $competition_id
 * @property string $original_name
 * @property string $file_path
 * @property string $mime_type
 * @property int $size
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetitionFile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetitionFile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetitionFile query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetitionFile whereCompetitionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetitionFile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetitionFile whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetitionFile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetitionFile whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetitionFile whereOriginalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetitionFile whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetitionFile whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CompetitionFile extends Model
{
    protected $fillable = [
        'competition_id',
        'original_name',
        'file_path',
        'mime_type',
        'size',
    ];

    public function competition(): BelongsTo
    {
        return $this->belongs(Competition::class);
    }
}

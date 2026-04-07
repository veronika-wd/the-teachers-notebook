<?php

// app/Models/CompetitionFile.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

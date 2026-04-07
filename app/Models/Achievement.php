<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Achievement extends Model
{
    protected $guarded = ['id'];

    public function achievementable(): MorphTo
    {
        return $this->morphTo();
    }
}

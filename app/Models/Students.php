<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Students extends Model
{
    protected $guarded = ['id'];

    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class);
    }
    public function results(): HasMany
    {
        return $this->hasMany(AssessmentResult::class);
    }
}

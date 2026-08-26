<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subject extends Model
{
    protected $guarded = ['id'];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function results(): BelongsToMany
    {
        return $this->belongsToMany(AssessmentResult::class, 'assessment_result_subject')
            ->withPivot('acertos');
    }
}

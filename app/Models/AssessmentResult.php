<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AssessmentResult extends Model
{
    protected $guarded = ['id'];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }
    public function student(): BelongsTo
    {
        return $this->belongsTo(Students::class);
    }
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'assessment_result_subject')
            ->withPivot('acertos');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassRoom extends Model
{
    protected $guarded = ['id'];

    public function students(): HasMany
    {
        return $this->hasMany(Students::class);
    }
    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class);
    }
}

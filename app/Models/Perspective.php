<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Perspective extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'color',
        'icon',
        'order',
        'is_active',
    ];

    /**
     * Objetivos Estratégicos desta perspectiva.
     */
    public function strategicObjectives(): HasMany
    {
        return $this->hasMany(StrategicObjective::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CriticalSuccessFactor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'strategic_objective_id',
        'name',
        'description',
        'weight',
        'is_active',
    ];

    /**
     * Objetivo Estratégico ao qual este FCS pertence.
     */
    public function strategicObjective(): BelongsTo
    {
        return $this->belongsTo(StrategicObjective::class);
    }
}

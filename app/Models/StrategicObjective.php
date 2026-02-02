<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StrategicObjective extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'perspective_id',
        'owner_id',
        'name',
        'code',
        'description',
        'is_active',
    ];

    /**
     * Perspectiva a qual este objetivo pertence.
     */
    public function perspective(): BelongsTo
    {
        return $this->belongsTo(Perspective::class);
    }

    /**
     * Dono/Responsável pelo objetivo.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Fatores Críticos de Sucesso deste objetivo.
     */
    public function criticalSuccessFactors(): HasMany
    {
        return $this->hasMany(CriticalSuccessFactor::class);
    }

    public function kpis(): HasMany
    {
        return $this->hasMany(KeyPerformanceIndicator::class);
    }
}

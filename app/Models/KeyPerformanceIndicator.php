<?php

namespace App\Models;

use App\Enums\KpiFrequency;
use App\Enums\KpiPolarity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class KeyPerformanceIndicator extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'strategic_objective_id',
        'name',
        'description',
        'measurement_unit',
        'frequency',
        'polarity',
        'base_target',
        'owner_id',
        'is_active',
    ];

    protected $casts = [
        'frequency' => KpiFrequency::class,
        'polarity' => KpiPolarity::class,
        'base_target' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function strategicObjective(): BelongsTo
    {
        return $this->belongsTo(StrategicObjective::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function measurements(): HasMany
    {
        return $this->hasMany(KpiMeasurement::class);
    }

    public function calculatePerformance(?float $actual, ?float $target): ?float
    {
        if (is_null($actual) || is_null($target) || $target == 0) {
            return null;
        }

        if ($this->polarity === KpiPolarity::LOWER_IS_BETTER) {
            // Se Menor é Melhor: Meta 100, Real 80 -> (100/80) = 125%
            // Evitar divisão por zero se Real for 0
            if ($actual == 0) return 100; // Considera 100% se for 0 e a meta não for 0? Caso complexo.
            return ($target / $actual) * 100;
        }

        if ($this->polarity === KpiPolarity::EQUAL_IS_BETTER) {
            // Igual é melhor (ex: Manter temperatura em 20 graus).
            // Variação percentual absoluta
            $diff = abs($actual - $target);
            $deviation = ($diff / $target);
            return (1 - $deviation) * 100;
        }

        // Higher is Better (Default)
        return ($actual / $target) * 100;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KpiMeasurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'key_performance_indicator_id',
        'period',
        'target_value',
        'actual_value',
        'notes',
        'recorded_by_id',
    ];

    protected $casts = [
        'period' => 'date',
        'target_value' => 'decimal:2',
        'actual_value' => 'decimal:2',
    ];

    public function indicator(): BelongsTo
    {
        return $this->belongsTo(KeyPerformanceIndicator::class, 'key_performance_indicator_id');
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by_id');
    }
    
    // Acessor virtual para performance
    public function getPerformancePercentageAttribute(): ?float
    {
        return $this->indicator?->calculatePerformance($this->actual_value, $this->target_value);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiskResponse extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'risk_id',
        'responsible_id',
        'type',
        'title',
        'description',
        'action_plan',
        'status',
        'planned_date',
        'completed_at',
        'estimated_cost',
        'actual_cost',
    ];

    protected $casts = [
        'planned_date' => 'date',
        'completed_at' => 'date',
        'estimated_cost' => 'decimal:2',
        'actual_cost' => 'decimal:2',
    ];

    public function risk(): BelongsTo
    {
        return $this->belongsTo(ProjectRisk::class, 'risk_id');
    }

    public function responsible(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }

    public static function availableTypes(): array
    {
        return [
            'avoid' => 'Evitar',
            'transfer' => 'Transferir',
            'mitigate' => 'Mitigar',
            'accept' => 'Aceitar',
        ];
    }

    public static function availableStatuses(): array
    {
        return [
            'planned' => 'Planejado',
            'in_progress' => 'Em Andamento',
            'completed' => 'Concluído',
            'cancelled' => 'Cancelado',
        ];
    }
}


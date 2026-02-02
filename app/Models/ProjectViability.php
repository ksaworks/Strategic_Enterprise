<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectViability extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'technical_feasibility',
        'financial_return',
        'payback_period',
        'score',
        'decision',
        'comments',
        'analyzed_by_id',
        'analyzed_at',
    ];

    protected $casts = [
        'financial_return' => 'decimal:2',
        'analyzed_at' => 'datetime',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function analyzer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'analyzed_by_id');
    }

    // Helpers
    public function getDecisionLabelAttribute(): string
    {
        return match ($this->decision) {
            'approved' => 'Aprovado',
            'rejected' => 'Rejeitado',
            'on_hold' => 'Em Análise',
            default => $this->decision,
        };
    }

    public function getDecisionColorAttribute(): string
    {
        return match ($this->decision) {
            'approved' => 'success',
            'rejected' => 'danger',
            'on_hold' => 'warning',
            default => 'gray',
        };
    }
}

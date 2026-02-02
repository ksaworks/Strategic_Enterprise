<?php

namespace App\Models;

use App\Enums\Priority;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectDemand extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'justification',
        'requester_id',
        'status',
        'priority',
        'project_id',
    ];

    protected $casts = [
        'priority' => Priority::class,
    ];

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    // Scopes e Helpers

    public static function availableStatuses(): array
    {
        return [
            'draft' => 'Rascunho',
            'submitted' => 'Submetida',
            'analyzing' => 'Em Análise',
            'approved' => 'Aprovada',
            'rejected' => 'Rejeitada',
            'converted' => 'Convertida em Projeto',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::availableStatuses()[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'gray',
            'submitted' => 'info',
            'analyzing' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            'converted' => 'success',
            default => 'gray',
        };
    }
}

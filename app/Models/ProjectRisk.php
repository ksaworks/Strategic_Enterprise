<?php

namespace App\Models;

use App\Enums\RiskImpact;
use App\Enums\RiskProbability;
use App\Enums\RiskStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo de Risco de Projeto
 * 
 * Representa um risco identificado em um projeto
 * com probabilidade, impacto e planos de mitigação.
 */
class ProjectRisk extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id',
        'name',
        'description',
        'category',
        'probability',
        'impact',
        'mitigation_plan',
        'contingency_plan',
        'status',
        'owner_id',
        'identified_at',
        'occurred_at',
    ];

    protected $casts = [
        'probability' => RiskProbability::class,
        'impact' => RiskImpact::class,
        'status' => RiskStatus::class,
        'identified_at' => 'date',
        'occurred_at' => 'date',
    ];

    /**
     * Projeto
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Responsável pelo risco
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Respostas ao risco
     */
    public function responses(): HasMany
    {
        return $this->hasMany(RiskResponse::class, 'risk_id');
    }

    /**
     * Calcula o score de risco (Probability x Impact)
     */
    public function getScoreAttribute(): int
    {
        $prob = $this->probability instanceof RiskProbability 
            ? $this->probability->value 
            : (int) $this->probability;
        
        $imp = $this->impact instanceof RiskImpact 
            ? $this->impact->value 
            : (int) $this->impact;

        return $prob * $imp;
    }

    /**
     * Retorna o nível de risco baseado no score
     */
    public function getRiskLevelAttribute(): string
    {
        $score = $this->score;

        return match (true) {
            $score <= 4 => 'low',
            $score <= 9 => 'medium',
            $score <= 16 => 'high',
            default => 'critical',
        };
    }

    /**
     * Label do nível de risco
     */
    public function getRiskLevelLabelAttribute(): string
    {
        return match ($this->risk_level) {
            'low' => 'Baixo',
            'medium' => 'Médio',
            'high' => 'Alto',
            'critical' => 'Crítico',
            default => 'Desconhecido',
        };
    }

    /**
     * Cor do nível de risco
     */
    public function getRiskLevelColorAttribute(): string
    {
        return match ($this->risk_level) {
            'low' => 'success',
            'medium' => 'warning',
            'high' => 'danger',
            'critical' => 'danger',
            default => 'gray',
        };
    }

    /**
     * Categorias disponíveis
     */
    public static function availableCategories(): array
    {
        return [
            'technical' => 'Técnico',
            'financial' => 'Financeiro',
            'schedule' => 'Cronograma',
            'scope' => 'Escopo',
            'resource' => 'Recursos',
            'external' => 'Externo',
            'other' => 'Outro',
        ];
    }

    // ==========================================
    // SCOPES
    // ==========================================

    /**
     * Apenas riscos ativos (não fechados/ocorridos)
     */
    public function scopeActive($query)
    {
        return $query->whereNotIn('status', [RiskStatus::CLOSED, RiskStatus::OCCURRED]);
    }

    /**
     * Riscos de alto impacto
     */
    public function scopeHighRisk($query)
    {
        return $query->whereRaw('probability * impact >= 12');
    }

    /**
     * Por categoria
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}

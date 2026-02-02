<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo de Membro de Equipe de Projeto
 * 
 * Representa a participação de um usuário em um projeto
 * com papel, alocação e permissões específicas.
 */
class ProjectTeamMember extends Model
{
    protected $table = 'project_team';

    protected $fillable = [
        'project_id',
        'user_id',
        'role',
        'allocation_percentage',
        'start_date',
        'end_date',
        'is_active',
        'can_edit',
        'can_delete',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'can_edit' => 'boolean',
        'can_delete' => 'boolean',
        'allocation_percentage' => 'decimal:2',
    ];

    /**
     * Projeto
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Usuário membro
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Retorna o label do papel
     */
    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'manager' => 'Gerente',
            'coordinator' => 'Coordenador',
            'analyst' => 'Analista',
            'developer' => 'Desenvolvedor',
            'tester' => 'Testador',
            'designer' => 'Designer',
            'member' => 'Membro',
            default => ucfirst($this->role),
        };
    }

    /**
     * Verifica se está ativo no período
     */
    public function isCurrentlyActive(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $today = now()->toDateString();

        if ($this->start_date && $this->start_date->toDateString() > $today) {
            return false;
        }

        if ($this->end_date && $this->end_date->toDateString() < $today) {
            return false;
        }

        return true;
    }

    /**
     * Roles disponíveis para seleção
     */
    public static function availableRoles(): array
    {
        return [
            'manager' => 'Gerente',
            'coordinator' => 'Coordenador',
            'analyst' => 'Analista',
            'developer' => 'Desenvolvedor',
            'tester' => 'Testador',
            'designer' => 'Designer',
            'member' => 'Membro',
        ];
    }
}

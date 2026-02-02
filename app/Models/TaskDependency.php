<?php

namespace App\Models;

use App\Enums\DependencyType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo de Dependência de Tarefa
 * 
 * Representa a relação de dependência entre duas tarefas.
 * Baseado no sistema legado GPWeb (tabela: tarefa_dependencias)
 */
class TaskDependency extends Model
{
    protected $fillable = [
        'task_id',
        'depends_on_id',
        'type',
        'lag_days',
    ];

    protected $casts = [
        'type' => DependencyType::class,
        'lag_days' => 'integer',
    ];

    /**
     * A tarefa que depende (dependente)
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    /**
     * A tarefa da qual depende (predecessor)
     */
    public function dependsOn(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'depends_on_id');
    }

    /**
     * Alias para dependsOn (terminologia de gestão de projetos)
     */
    public function predecessor(): BelongsTo
    {
        return $this->dependsOn();
    }

    /**
     * Verifica se a dependência é válida (não circular)
     */
    public function isValid(): bool
    {
        return $this->task_id !== $this->depends_on_id;
    }

    /**
     * Retorna descrição legível da dependência
     */
    public function getDescriptionAttribute(): string
    {
        $lag = '';
        if ($this->lag_days > 0) {
            $lag = " +{$this->lag_days}d";
        } elseif ($this->lag_days < 0) {
            $lag = " {$this->lag_days}d";
        }

        return $this->type->shortLabel() . $lag;
    }
}

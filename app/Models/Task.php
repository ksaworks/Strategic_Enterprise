<?php

namespace App\Models;

use App\Enums\Priority;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo de Tarefa
 * 
 * Suporta hierarquia de subtarefas, dependências entre tarefas e múltiplos designados.
 * Baseado no sistema legado GPWeb (tabela: tarefas)
 */
class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected static function booted(): void
    {
        static::created(function ($task) {
            if ($task->owner) {
                $task->owner->notify(new \App\Notifications\TaskAssigned($task));
            }
        });

        static::updated(function ($task) {
            if ($task->isDirty('owner_id') && $task->owner) {
                $task->owner->notify(new \App\Notifications\TaskAssigned($task));
            }
        });
    }

    protected $fillable = [
        // Vínculos
        'project_id',
        'parent_id',
        'owner_id',
        'created_by_id',
        // Identificação
        'name',
        'code',
        // Datas e duração
        'start_date',
        'end_date',
        'duration',
        // Conteúdo
        'description',
        // Estado
        'status',
        'priority',
        'progress',
        'is_milestone',
        'is_dynamic',
        // Custos
        'cost',
        'spent',
        'hours_worked',
        // 5W2H
        'where',
        'why',
        'how',
        'meeting_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_milestone' => 'boolean',
        'is_dynamic' => 'boolean',
        'status' => TaskStatus::class,
        'priority' => Priority::class,
        'progress' => 'decimal:2',
        'duration' => 'decimal:2',
        'cost' => 'decimal:2',
        'spent' => 'decimal:2',
        'hours_worked' => 'decimal:2',
    ];

    // ==========================================
    // RELACIONAMENTOS DE HIERARQUIA
    // ==========================================

    /**
     * Tarefa pai (para subtarefas)
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    /**
     * Subtarefas
     */
    public function children(): HasMany
    {
        return $this->hasMany(Task::class, 'parent_id');
    }

    /**
     * Todos os descendentes (recursivo)
     */
    public function descendants(): HasMany
    {
        return $this->children()->with('descendants');
    }

    // ==========================================
    // RELACIONAMENTOS DE PROJETO
    // ==========================================

    /**
     * Projeto da tarefa
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function meeting(): BelongsTo
    {
        return $this->belongsTo(Meeting::class);
    }

    // ==========================================
    // RELACIONAMENTOS DE USUÁRIOS
    // ==========================================

    /**
     * Responsável principal
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Criador da tarefa
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    /**
     * Múltiplos designados (além do owner)
     */
    public function assignees(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'task_assignees')
            ->withPivot(['allocation_percentage', 'is_primary', 'is_admin'])
            ->withTimestamps();
    }

    // ==========================================
    // RELACIONAMENTOS DE DEPENDÊNCIAS
    // ==========================================

    /**
     * Tarefas das quais esta tarefa depende (predecessoras)
     */
    public function dependencies(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'task_dependencies', 'task_id', 'depends_on_id')
            ->withPivot(['type', 'lag_days'])
            ->withTimestamps();
    }

    /**
     * Tarefas que dependem desta tarefa (sucessoras)
     */
    public function dependents(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'task_dependencies', 'depends_on_id', 'task_id')
            ->withPivot(['type', 'lag_days'])
            ->withTimestamps();
    }

    /**
     * Registros de dependência (para acesso ao modelo pivot)
     */
    public function dependencyRecords(): HasMany
    {
        return $this->hasMany(TaskDependency::class, 'task_id');
    }

    // ==========================================
    // RELACIONAMENTOS DE CONTEÚDO
    // ==========================================

    /**
     * Documentos anexados
     */
    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    /**
     * Comentários
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Itens de custo estimados
     */
    public function costItems(): HasMany
    {
        return $this->hasMany(ProjectCostItem::class);
    }

    /**
     * Registros de horas trabalhadas
     */
    public function timesheets(): HasMany
    {
        return $this->hasMany(Timesheet::class);
    }

    // ==========================================
    // ACESSORES
    // ==========================================

    /**
     * Calcula progresso baseado nas subtarefas (se houver)
     */
    public function getCalculatedProgressAttribute(): float
    {
        $childrenCount = $this->children()->count();

        if ($childrenCount === 0) {
            return $this->progress ?? 0;
        }

        return round($this->children()->avg('progress') ?? 0, 2);
    }

    /**
     * Verifica se tem dependência circular
     */
    public function hasCircularDependency(int $potentialDependencyId): bool
    {
        if ($this->id === $potentialDependencyId) {
            return true;
        }

        // Verificar se a tarefa potencial depende desta (ciclo direto)
        $visited = [$this->id];
        $queue = $this->dependencies->pluck('id')->toArray();

        while (!empty($queue)) {
            $currentId = array_shift($queue);

            if ($currentId === $potentialDependencyId) {
                return true;
            }

            if (in_array($currentId, $visited)) {
                continue;
            }

            $visited[] = $currentId;

            $task = Task::find($currentId);
            if ($task) {
                $queue = array_merge($queue, $task->dependencies->pluck('id')->toArray());
            }
        }

        return false;
    }

    // ==========================================
    // SCOPES
    // ==========================================

    /**
     * Apenas tarefas raiz (sem pai)
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Filtrar por status
     */
    public function scopeByStatus($query, TaskStatus $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Apenas marcos
     */
    public function scopeMilestones($query)
    {
        return $query->where('is_milestone', true);
    }

    /**
     * Tarefas atrasadas
     */
    public function scopeOverdue($query)
    {
        return $query->where('end_date', '<', now())
            ->where('status', '!=', TaskStatus::COMPLETED);
    }

    /**
     * Tarefas de um projeto
     */
    public function scopeOfProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }
}

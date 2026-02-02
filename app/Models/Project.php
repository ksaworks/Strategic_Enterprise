<?php

namespace App\Models;

use App\Enums\Priority;
use App\Enums\ProjectStatus;
use App\Enums\ProjectType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\ProjectDemand;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo de Projeto
 * 
 * Suporta hierarquia: Portfolio → Programa → Projeto → Subprojeto
 * Baseado no sistema legado GPWeb (tabela: projetos)
 */
class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected static function booted(): void
    {
        static::created(function ($project) {
            if ($project->owner) {
                $project->owner->notify(new \App\Notifications\ProjectAdded($project));
            }
        });
    }

    protected $fillable = [
        // Hierarquia
        'parent_id',
        'type',
        // Vínculos organizacionais
        'company_id',
        'department_id',
        // Responsáveis
        'owner_id',
        'created_by_id',
        // Dados básicos
        'name',
        'code',
        'description',
        'objectives',
        // Escopo (Sprint 9)
        'justification',
        'scope',
        'out_of_scope',
        'assumptions',
        'constraints',
        'success_criteria',
        'main_risks',
        // Datas
        'start_date',
        'end_date',
        // Estado
        'status',
        'priority',
        'progress',
        'budget',
        'cost',
        'spent',
        'is_active',
        'is_private',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'is_private' => 'boolean',
        'status' => ProjectStatus::class,
        'priority' => Priority::class,
        'type' => ProjectType::class,
        'progress' => 'decimal:2',
        'budget' => 'decimal:2',
    ];

    // ==========================================
    // RELACIONAMENTOS DE HIERARQUIA
    // ==========================================

    /**
     * Projeto pai (para hierarquia)
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'parent_id');
    }

    /**
     * Projetos filhos (subprojetos)
     */
    public function children(): HasMany
    {
        return $this->hasMany(Project::class, 'parent_id');
    }

    /**
     * Demanda que originou este projeto
     */
    public function demand(): HasOne
    {
        return $this->hasOne(ProjectDemand::class);
    }

    /**
     * Todos os descendentes (recursivo)
     */
    public function descendants(): HasMany
    {
        return $this->children()->with('descendants');
    }

    // ==========================================
    // RELACIONAMENTOS ORGANIZACIONAIS
    // ==========================================

    /**
     * Empresa/Organização do projeto
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Departamento responsável
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    // ==========================================
    // RELACIONAMENTOS DE USUÁRIOS
    // ==========================================

    /**
     * Responsável principal do projeto
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Usuário que criou o projeto
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    // ==========================================
    // RELACIONAMENTOS DE CONTEÚDO
    // ==========================================

    /**
     * Tarefas do projeto
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Tarefas raiz (sem pai) do projeto
     */
    public function rootTasks(): HasMany
    {
        return $this->tasks()->whereNull('parent_id');
    }

    /**
     * Membros da equipe do projeto
     */
    public function teamMembers(): HasMany
    {
        return $this->hasMany(ProjectTeamMember::class);
    }

    /**
     * Riscos do projeto
     */
    public function risks(): HasMany
    {
        return $this->hasMany(ProjectRisk::class);
    }

    /**
     * Baselines do projeto (Snapshot)
     */
    public function baselines(): HasMany
    {
        return $this->hasMany(ProjectBaseline::class);
    }

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
     * Despesas do projeto
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(ProjectExpense::class);
    }

    /**
     * Estudos de Viabilidade
     */
    public function viabilities(): HasMany
    {
        return $this->hasMany(ProjectViability::class);
    }

    /**
     * Lições Aprendidas
     */
    public function lessonsLearned(): HasMany
    {
        return $this->hasMany(LessonLearned::class);
    }

    // ==========================================
    // ACESSORES E MUTADORES
    // ==========================================

    /**
     * Calcula o progresso baseado nas tarefas
     */
    public function getCalculatedProgressAttribute(): float
    {
        $tasksCount = $this->tasks()->count();
        
        if ($tasksCount === 0) {
            return 0;
        }

        return round($this->tasks()->avg('progress') ?? 0, 2);
    }

    /**
     * Retorna o nome completo com hierarquia
     */
    public function getFullNameAttribute(): string
    {
        if ($this->parent) {
            return $this->parent->full_name . ' > ' . $this->name;
        }

        return $this->name;
    }

    // ==========================================
    // SCOPES
    // ==========================================

    /**
     * Apenas projetos ativos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Filtrar por status
     */
    public function scopeByStatus($query, ProjectStatus $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Filtrar por tipo
     */
    public function scopeByType($query, ProjectType $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Apenas projetos raiz (sem pai)
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Projetos de um departamento
     */
    public function scopeByDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }
}

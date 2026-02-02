<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class LessonLearned extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'project_lessons_learned';

    protected $fillable = [
        'project_id',
        'category',
        'type',
        'description',
        'impact',
        'recommendation',
        'tags',
        'reported_by_id',
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by_id');
    }
    
    public static function getCategories(): array
    {
        return [
            'scope' => 'Escopo',
            'time' => 'Tempo/Cronograma',
            'cost' => 'Custos',
            'quality' => 'Qualidade',
            'risk' => 'Riscos',
            'communication' => 'Comunicação',
            'procurement' => 'Aquisições',
            'stakeholder' => 'Partes Interessadas',
            'technical' => 'Técnico',
            'other' => 'Outros',
        ];
    }
}

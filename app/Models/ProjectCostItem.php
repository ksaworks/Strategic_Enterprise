<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectCostItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'task_id',
        'name',
        'category',
        'unit',
        'quantity',
        'unit_price',
        'description',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(ProjectExpense::class, 'cost_item_id');
    }

    public static function availableCategories(): array
    {
        return [
            'labor' => 'Mão de Obra',
            'material' => 'Material',
            'service' => 'Serviço',
            'equipment' => 'Equipamento',
            'other' => 'Outro',
        ];
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::availableCategories()[$this->category] ?? $this->category;
    }
}


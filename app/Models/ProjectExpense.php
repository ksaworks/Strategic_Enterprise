<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectExpense extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'task_id',
        'cost_item_id',
        'created_by_id',
        'description',
        'category',
        'amount',
        'expense_date',
        'status',
        'approved_by_id',
        'approved_at',
        'notes',
        'receipt_path',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function costItem(): BelongsTo
    {
        return $this->belongsTo(ProjectCostItem::class, 'cost_item_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public static function availableStatuses(): array
    {
        return [
            'pending' => 'Pendente',
            'approved' => 'Aprovada',
            'rejected' => 'Rejeitada',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::availableStatuses()[$this->status] ?? $this->status;
    }
}


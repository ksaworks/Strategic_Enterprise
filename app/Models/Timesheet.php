<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Timesheet extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'task_id',
        'work_date',
        'hours',
        'start_time',
        'end_time',
        'description',
        'hourly_rate',
        'status',
        'approved_by_id',
        'approved_at',
        'rejection_reason',
    ];

    protected $casts = [
        'work_date' => 'date',
        'hours' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
        'labor_cost' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
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

    public function scopeForCurrentUser($query)
    {
        return $query->where('user_id', auth()->id());
    }

    public static function availableStatuses(): array
    {
        return [
            'pending' => 'Pendente',
            'approved' => 'Aprovado',
            'rejected' => 'Rejeitado',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::availableStatuses()[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            default => 'gray',
        };
    }
}


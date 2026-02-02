<?php

namespace App\Models;

use App\Enums\CharterStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectCharter extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'created_by_id',
        'approved_by_id',
        'title',
        'version',
        'status',
        'approved_at',
        'objective',
        'scope',
        'out_of_scope',
        'deliverables',
        'stakeholders',
        'constraints',
        'assumptions',
        'risks',
        'budget_summary',
        'timeline_summary',
        'approval_justification',
    ];

    protected $casts = [
        'status' => CharterStatus::class,
        'approved_at' => 'datetime',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_id');
    }
}


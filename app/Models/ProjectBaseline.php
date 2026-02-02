<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectBaseline extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'name',
        'description',
        'snapshot_data',
        'type',
        'created_by_id',
    ];

    protected $casts = [
        'snapshot_data' => 'array',
    ];

    /**
     * O projeto ao qual esta baseline pertence.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Quem criou a baseline.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}

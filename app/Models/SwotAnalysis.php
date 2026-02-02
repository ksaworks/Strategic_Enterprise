<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SwotAnalysis extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'created_by_id',
        'title',
        'strengths',
        'weaknesses',
        'opportunities',
        'threats',
        'notes',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}


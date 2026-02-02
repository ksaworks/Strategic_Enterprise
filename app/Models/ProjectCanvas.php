<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectCanvas extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'name',
        'description',
        'status',
        'owner_id',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ProjectCanvasItem::class);
    }

    /**
     * Get items grouped by section.
     */
    public function getItemsGroupedBySectionAttribute()
    {
        return $this->items->groupBy('section');
    }
}

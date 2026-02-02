<?php

namespace App\Models;

use App\Enums\ProjectCanvasSection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectCanvasItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_canvas_id',
        'section',
        'title',
        'content',
        'color',
        'order',
    ];

    protected $casts = [
        'section' => ProjectCanvasSection::class,
    ];

    public function canvas(): BelongsTo
    {
        return $this->belongsTo(ProjectCanvas::class, 'project_canvas_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project;
use App\Models\User;

class Task extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::created(function ($task) {
            if ($task->owner) {
                $task->owner->notify(new \App\Notifications\TaskAssigned($task));
            }
        });

        static::updated(function ($task) {
            if ($task->isDirty('owner_id') && $task->owner) {
                $task->owner->notify(new \App\Notifications\TaskAssigned($task));
            }
        });
    }

    protected $fillable = [
        'project_id',
        'parent_id',
        'owner_id',
        'name',
        'start_date',
        'end_date',
        'duration',
        'description',
        'status',
        'priority',
        'progress',
        'is_milestone',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_milestone' => 'boolean',
    ];

    public function project(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Task::class, 'parent_id');
    }

    public function owner(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function documents(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function comments(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}

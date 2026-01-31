<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Comment extends Model
{
    protected static function booted()
    {
        static::created(function ($comment) {
            $owner = $comment->commentable->owner ?? null;
            if ($owner && $owner->id !== $comment->user_id) {
                $owner->notify(new \App\Notifications\CommentNotification(
                    $comment->commentable,
                    $comment->user->name,
                    $comment->body
                ));
            }
        });
    }

    protected $fillable = [
        'user_id',
        'commentable_id',
        'commentable_type',
        'body',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent commentable model (Project, Task, etc.).
     */
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }
}

<?php

namespace App\Models;

use Guava\Calendar\Contracts\Eventable;
use Guava\Calendar\ValueObjects\CalendarEvent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model implements Eventable
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_datetime',
        'end_datetime',
        'all_day',
        'location',
        'project_id',
        'contact_id',
        'user_id',
        'color',
        'status',
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'all_day' => 'boolean',
    ];

    public function toCalendarEvent(): CalendarEvent
    {
        // Default color based on status or fallback to gold
        $backgroundColor = $this->color ?? match ($this->status ?? 'default') {
            'scheduled' => '#f59e0b',  // Amber
            'completed' => '#22c55e',  // Green
            'cancelled' => '#ef4444',  // Red
            default => '#d58f05',      // Gold
        };


        return CalendarEvent::make($this)
            ->title($this->title)
            ->start($this->start_datetime)
            ->end($this->end_datetime)
            ->backgroundColor($backgroundColor)
            ->textColor('#ffffff');
    }

    public function project(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Project::class);
    }

    public function contact(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Contact::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}

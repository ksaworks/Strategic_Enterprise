<?php

namespace App\Models;

use App\Enums\MeetingStatus;
use App\Enums\MeetingType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meeting extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'organizer_id',
        'title',
        'type',
        'status',
        'start_time',
        'end_time',
        'location',
        'description',
        'minutes',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'type' => MeetingType::class,
        'status' => MeetingStatus::class,
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function attendees()
    {
        return $this->hasMany(MeetingAttendee::class);
    }

    public function actionItems()
    {
        return $this->hasMany(Task::class);
    }
}

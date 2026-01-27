<?php

use App\Models\Event;
use App\Models\User;
use App\Models\Project;
use Guava\Calendar\ValueObjects\CalendarEvent;

test('it belongs to a user', function () {
    $user = User::factory()->create();
    $event = Event::factory()->create(['user_id' => $user->id]);

    expect($event->user)->toBeInstanceOf(User::class)
        ->and($event->user->id)->toBe($user->id);
});

test('it belongs to a project', function () {
    $project = Project::factory()->create();
    $event = Event::factory()->create(['project_id' => $project->id]);

    expect($event->project)->toBeInstanceOf(Project::class)
        ->and($event->project->id)->toBe($project->id);
});

test('it casts datetimes correctly', function () {
    $event = Event::factory()->create([
        'start_datetime' => '2026-01-26 10:00:00',
        'end_datetime' => '2026-01-26 12:00:00',
    ]);

    expect($event->start_datetime)->toBeInstanceOf(\Carbon\Carbon::class)
        ->and($event->end_datetime)->toBeInstanceOf(\Carbon\Carbon::class);
});

test('it implements eventable interface', function () {
    $event = Event::factory()->create([
        'title' => 'Meeting',
        'status' => 'scheduled',
        'color' => null,
    ]);

    $calendarEvent = $event->toCalendarEvent();

    expect($calendarEvent)->toBeInstanceOf(CalendarEvent::class)
        ->and($calendarEvent->getTitle())->toBe('Meeting')
        ->and($calendarEvent->getBackgroundColor())->toBe('#f59e0b'); // Amber for scheduled
});

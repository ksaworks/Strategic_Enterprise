<?php

use App\Models\Task;
use App\Models\Project;
use App\Models\User;

test('it belongs to a project', function () {
    $project = Project::factory()->create();
    $task = Task::factory()->create(['project_id' => $project->id]);

    expect($task->project)->toBeInstanceOf(Project::class)
        ->and($task->project->id)->toBe($project->id);
});

test('it belongs to an owner', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create(['owner_id' => $user->id]);

    expect($task->owner)->toBeInstanceOf(User::class)
        ->and($task->owner->id)->toBe($user->id);
});

test('it casts dates correctly', function () {
    $task = Task::factory()->create([
        'start_date' => '2026-01-01',
        'end_date' => '2026-01-31',
        'is_milestone' => true,
    ]);

    expect($task->start_date)->toBeInstanceOf(\Carbon\Carbon::class)
        ->and($task->end_date)->toBeInstanceOf(\Carbon\Carbon::class)
        ->and($task->is_milestone)->toBeTrue();
});

test('it can have a parent task', function () {
    $parent = Task::factory()->create();
    $child = Task::factory()->create(['parent_id' => $parent->id]);

    expect($child->parent)->toBeInstanceOf(Task::class)
        ->and($child->parent->id)->toBe($parent->id);
});

test('it can have children tasks', function () {
    $parent = Task::factory()->create();
    $child = Task::factory()->create(['parent_id' => $parent->id]);

    expect($parent->children)->toHaveCount(1)
        ->and($parent->children->first()->id)->toBe($child->id);
});

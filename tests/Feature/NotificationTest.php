<?php

use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\Company;
use App\Models\Comment;
use App\Notifications\TaskAssigned;
use App\Notifications\ProjectAdded;
use App\Notifications\CommentNotification;
use Illuminate\Support\Facades\Notification;

test('notifies user when assigned to a task', function () {
    Notification::fake();

    $user = User::factory()->create();
    $project = Project::factory()->create();
    
    $task = Task::factory()->create([
        'project_id' => $project->id,
        'owner_id' => $user->id,
    ]);

    Notification::assertSentTo($user, TaskAssigned::class);
});

test('notifies user when they become project owner', function () {
    Notification::fake();

    $user = User::factory()->create();
    
    $project = Project::factory()->create([
        'owner_id' => $user->id,
    ]);

    Notification::assertSentTo($user, ProjectAdded::class);
});

test('notifies owner when a comment is added', function () {
    Notification::fake();

    $owner = User::factory()->create();
    $visitor = User::factory()->create();
    
    $project = Project::factory()->create(['owner_id' => $owner->id]);
    
    $comment = Comment::create([
        'user_id' => $visitor->id,
        'commentable_id' => $project->id,
        'commentable_type' => Project::class,
        'body' => 'Great project!',
    ]);

    Notification::assertSentTo($owner, CommentNotification::class);
});

test('does not notify owner when they comment on their own item', function () {
    Notification::fake();

    $owner = User::factory()->create();
    
    $project = Project::factory()->create(['owner_id' => $owner->id]);
    
    Comment::create([
        'user_id' => $owner->id,
        'commentable_id' => $project->id,
        'commentable_type' => Project::class,
        'body' => 'I am commenting on my own project',
    ]);

    Notification::assertNotSentTo($owner, CommentNotification::class);
});

<?php

use App\Models\Project;
use App\Models\Task;
use App\Enums\TaskStatus;
use App\Enums\Priority;

$project = Project::find(2);

if (!$project) {
    echo "Project not found!\n";
    exit;
}

// Cleanup existing tasks for idempotency
Task::where('project_id', $project->id)->forceDelete();

// 1. Task 1
$t1 = Task::create([
    'project_id' => $project->id,
    'name' => 'Planejamento Inicial',
    'start_date' => '2026-02-01',
    'end_date' => '2026-02-05',
    'status' => TaskStatus::COMPLETED,
    'priority' => Priority::HIGH,
    'progress' => 100,
]);

// 2. Task 2 (Depends on 1)
$t2 = Task::create([
    'project_id' => $project->id,
    'name' => 'Contratação de Equipe',
    'start_date' => '2026-02-06',
    'end_date' => '2026-02-10',
    'status' => TaskStatus::IN_PROGRESS,
    'priority' => Priority::MEDIUM,
    'progress' => 50,
]);

$t2->dependencies()->attach($t1->id, ['type' => 'FS']);

// 3. Task 3 (Depends on 2)
$t3 = Task::create([
    'project_id' => $project->id,
    'name' => 'Treinamento',
    'start_date' => '2026-02-11',
    'end_date' => '2026-02-15',
    'status' => TaskStatus::TODO,
    'priority' => Priority::MEDIUM,
    'progress' => 0,
]);

$t3->dependencies()->attach($t2->id, ['type' => 'FS']);

echo "Tasks created successfully for Project: " . $project->name . "\n";

<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;

class GanttService
{
    public function getGanttData(Project $project): array
    {
        $tasks = $project->tasks()
            ->with(['dependencies'])
            ->orderBy('start_date')
            ->get();

        return $tasks->map(function (Task $task) {
            return [
                'id' => (string) $task->id,
                'name' => $task->name,
                'start' => $task->start_date ? $task->start_date->format('Y-m-d') : now()->format('Y-m-d'),
                'end' => $task->end_date ? $task->end_date->format('Y-m-d') : now()->addDay()->format('Y-m-d'),
                'progress' => $task->progress ?? 0,
                'dependencies' => $task->dependencies->pluck('id')->implode(', '),
                'custom_class' => $this->getStatusClass($task->status),
            ];
        })->toArray();
    }

    protected function getStatusClass($status): string
    {
        if (! $status instanceof \App\Enums\TaskStatus) {
            return 'bar-gray';
        }

        return match ($status) {
            \App\Enums\TaskStatus::COMPLETED => 'bar-success',
            \App\Enums\TaskStatus::IN_PROGRESS => 'bar-primary',
            \App\Enums\TaskStatus::WAITING, \App\Enums\TaskStatus::CANCELLED => 'bar-danger',
            default => 'bar-gray',
        };
    }
}

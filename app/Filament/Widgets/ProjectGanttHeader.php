<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class ProjectGanttHeader extends Widget
{
    protected string $view = 'filament.widgets.project-gantt-header';

    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $tasks = \App\Models\Task::with('project')
            ->whereNotNull('start_date')
            ->whereNotNull('end_date')
            ->limit(50) // Limit for dashboard performance
            ->get()
            ->map(function ($task) {
                return [
                    'id' => (string) $task->id,
                    'name' => $task->name,
                    'start' => $task->start_date->format('Y-m-d'),
                    'end' => $task->end_date->format('Y-m-d'),
                    'progress' => $task->progress ?? 0,
                    'dependencies' => '', // TODO: implement tasks dependencies if model supports it
                    'custom_class' => $this->getTaskClass($task->status),
                ];
            });

        return [
            'tasks' => $tasks,
        ];
    }

    protected function getTaskClass($status): string
    {
        return match ($status) {
            3 => 'bar-success', // Concluído
            0 => 'bar-planning', // Não Iniciado
            default => 'bar-standard',
        };
    }
}

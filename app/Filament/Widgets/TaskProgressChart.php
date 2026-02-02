<?php

namespace App\Filament\Widgets;

use App\Enums\TaskStatus;
use App\Models\Task;
use Filament\Widgets\ChartWidget;

class TaskProgressChart extends ChartWidget
{
    protected ?string $heading = 'Distribuição de Tarefas';
    
    protected static ?int $sort = 3;
    
    protected int | string | array $columnSpan = 1;

    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $completed = Task::where('status', TaskStatus::COMPLETED)->count();
        $inProgress = Task::where('status', TaskStatus::IN_PROGRESS)->count();
        $todo = Task::where('status', TaskStatus::TODO)->count();
        $waiting = Task::where('status', TaskStatus::WAITING)->count();
        $cancelled = Task::where('status', TaskStatus::CANCELLED)->count();

        return [
            'datasets' => [
                [
                    'label' => 'Tarefas',
                    'data' => [$completed, $inProgress, $todo, $waiting, $cancelled],
                    'backgroundColor' => [
                        'rgba(16, 185, 129, 0.8)',  // green - completed
                        'rgba(59, 130, 246, 0.8)',  // blue - in progress
                        'rgba(107, 114, 128, 0.8)', // gray - todo
                        'rgba(245, 158, 11, 0.8)',  // yellow - waiting
                        'rgba(239, 68, 68, 0.8)',   // red - cancelled
                    ],
                    'borderColor' => [
                        'rgb(16, 185, 129)',
                        'rgb(59, 130, 246)',
                        'rgb(107, 114, 128)',
                        'rgb(245, 158, 11)',
                        'rgb(239, 68, 68)',
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => ['Concluídas', 'Em Progresso', 'A Fazer', 'Aguardando', 'Canceladas'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
            'cutout' => '60%',
        ];
    }
}

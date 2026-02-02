<?php

namespace App\Filament\Widgets;

use App\Enums\ProjectStatus;
use App\Enums\TaskStatus;
use App\Enums\RiskStatus;
use App\Models\Project;
use App\Models\Task;
use App\Models\ProjectDemand;
use App\Models\ProjectRisk;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProjectStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $activeProjects = Project::whereIn('status', [
            ProjectStatus::NOT_STARTED,
            ProjectStatus::IN_PROGRESS,
            ProjectStatus::ON_HOLD,
        ])->count();

        $pendingTasks = Task::whereIn('status', [
            TaskStatus::TODO,
            TaskStatus::IN_PROGRESS,
            TaskStatus::WAITING,
        ])->count();

        $openDemands = ProjectDemand::whereIn('status', [
            'submitted',
            'under_analysis',
        ])->count();

        $criticalRisks = ProjectRisk::whereIn('status', [
            RiskStatus::IDENTIFIED,
            RiskStatus::ANALYZING,
            RiskStatus::MITIGATING,
            RiskStatus::MONITORING,
        ])
            ->whereRaw('(probability * impact) >= 9')
            ->count();

        return [
            Stat::make('Projetos Ativos', $activeProjects)
                ->description('Em andamento ou não iniciados')
                ->descriptionIcon('heroicon-m-folder-open')
                ->color('primary')
                ->chart([7, 3, 4, 5, 6, $activeProjects]),

            Stat::make('Tarefas Pendentes', $pendingTasks)
                ->description('Aguardando ou em progresso')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('warning')
                ->chart([5, 8, 6, 9, 7, $pendingTasks]),

            Stat::make('Demandas Abertas', $openDemands)
                ->description('Submetidas ou em análise')
                ->descriptionIcon('heroicon-m-inbox-arrow-down')
                ->color('info')
                ->chart([2, 4, 3, 5, 4, $openDemands]),

            Stat::make('Riscos Críticos', $criticalRisks)
                ->description('Alta probabilidade x impacto')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger')
                ->chart([1, 2, 1, 3, 2, $criticalRisks]),
        ];
    }
}

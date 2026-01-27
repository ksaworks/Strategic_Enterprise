<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total de Empresas', \App\Models\Company::count())
                ->description('Empresas cadastradas')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('primary'),

            Stat::make('Projetos Ativos', \App\Models\Project::where('status', 'active')->count())
                ->description('Projetos em andamento')
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('success'),

            Stat::make('Tarefas Pendentes', \App\Models\Task::where('status', 'pending')->count())
                ->description('Tarefas a concluir')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('warning'),

            Stat::make('Usuários Ativos', \App\Models\User::count())
                ->description('Usuários no sistema')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
        ];
    }
}

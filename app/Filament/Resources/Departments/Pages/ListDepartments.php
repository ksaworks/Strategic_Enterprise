<?php

namespace App\Filament\Resources\Departments\Pages;

use App\Filament\Resources\Departments\DepartmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDepartments extends ListRecords
{
    protected static string $resource = DepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('delayedReport')
                ->label('Relatório de Atrasos')
                ->icon('heroicon-o-document-text')
                ->url(route('reports.departments.delayed'))
                ->openUrlInNewTab(),
            CreateAction::make(),
        ];
    }
}

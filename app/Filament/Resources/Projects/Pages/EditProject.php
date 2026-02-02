<?php

namespace App\Filament\Resources\Projects\Pages;

use App\Filament\Resources\Projects\ProjectResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProject extends EditRecord
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('print')
                ->label('Relatório')
                ->icon('heroicon-o-printer')
                ->url(fn($record) => route('reports.projects.status', $record))
                ->openUrlInNewTab(),
            DeleteAction::make(),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()
                ->label('Salvar alterações')
                ->color('primary'),

            $this->getCancelFormAction()
                ->label('Cancelar')
                ->color('gray')
                ->link(),
        ];
    }
    protected function getFooterWidgets(): array
    {
        return [
            \App\Filament\Resources\Projects\Widgets\ProjectGanttChart::class,
        ];
    }
}

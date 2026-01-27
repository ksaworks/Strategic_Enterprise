<?php

namespace App\Filament\Resources\Tasks\Pages;

use App\Filament\Resources\Tasks\TaskResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTask extends CreateRecord
{
    protected static string $resource = TaskResource::class;

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
                ->label('Criar')
                ->color('primary'),

            $this->getCreateAnotherFormAction()
                ->label('Salvar e criar outro')
                ->color('gray')
                ->outlined()
                ->icon('heroicon-o-plus-circle'),

            $this->getCancelFormAction()
                ->label('Cancelar')
                ->color('gray')
                ->link(),
        ];
    }
}

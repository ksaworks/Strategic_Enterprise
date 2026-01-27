<?php

namespace App\Filament\Resources\Projects\Pages;

use App\Filament\Resources\Projects\ProjectResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProject extends CreateRecord
{
    protected static string $resource = ProjectResource::class;

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

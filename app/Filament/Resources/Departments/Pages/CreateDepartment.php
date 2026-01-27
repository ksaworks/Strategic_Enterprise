<?php

namespace App\Filament\Resources\Departments\Pages;

use App\Filament\Resources\Departments\DepartmentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDepartment extends CreateRecord
{
    protected static string $resource = DepartmentResource::class;

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

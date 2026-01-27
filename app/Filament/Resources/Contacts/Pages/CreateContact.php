<?php

namespace App\Filament\Resources\Contacts\Pages;

use App\Filament\Resources\Contacts\ContactResource;
use Filament\Resources\Pages\CreateRecord;

class CreateContact extends CreateRecord
{
    protected static string $resource = ContactResource::class;

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

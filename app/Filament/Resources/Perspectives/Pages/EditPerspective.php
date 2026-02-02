<?php

namespace App\Filament\Resources\Perspectives\Pages;

use App\Filament\Resources\Perspectives\PerspectiveResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditPerspective extends EditRecord
{
    protected static string $resource = PerspectiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\ProjectViabilities\Pages;

use App\Filament\Resources\ProjectViabilities\ProjectViabilityResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditProjectViability extends EditRecord
{
    protected static string $resource = ProjectViabilityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}

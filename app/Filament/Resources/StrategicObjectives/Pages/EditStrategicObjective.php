<?php

namespace App\Filament\Resources\StrategicObjectives\Pages;

use App\Filament\Resources\StrategicObjectives\StrategicObjectiveResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditStrategicObjective extends EditRecord
{
    protected static string $resource = StrategicObjectiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}

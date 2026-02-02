<?php

namespace App\Filament\Resources\ProjectDemands\Pages;

use App\Filament\Resources\ProjectDemands\ProjectDemandResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditProjectDemand extends EditRecord
{
    protected static string $resource = ProjectDemandResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}

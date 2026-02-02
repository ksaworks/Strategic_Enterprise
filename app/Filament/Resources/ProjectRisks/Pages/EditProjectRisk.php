<?php

namespace App\Filament\Resources\ProjectRisks\Pages;

use App\Filament\Resources\ProjectRisks\ProjectRiskResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditProjectRisk extends EditRecord
{
    protected static string $resource = ProjectRiskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}

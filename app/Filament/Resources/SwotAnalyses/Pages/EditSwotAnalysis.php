<?php

namespace App\Filament\Resources\SwotAnalyses\Pages;

use App\Filament\Resources\SwotAnalyses\SwotAnalysisResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditSwotAnalysis extends EditRecord
{
    protected static string $resource = SwotAnalysisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}

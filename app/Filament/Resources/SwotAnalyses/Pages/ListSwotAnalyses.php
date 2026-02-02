<?php

namespace App\Filament\Resources\SwotAnalyses\Pages;

use App\Filament\Resources\SwotAnalyses\SwotAnalysisResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSwotAnalyses extends ListRecords
{
    protected static string $resource = SwotAnalysisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

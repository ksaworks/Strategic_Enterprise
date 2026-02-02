<?php

namespace App\Filament\Resources\ProjectRisks\Pages;

use App\Filament\Resources\ProjectRisks\ProjectRiskResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProjectRisks extends ListRecords
{
    protected static string $resource = ProjectRiskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

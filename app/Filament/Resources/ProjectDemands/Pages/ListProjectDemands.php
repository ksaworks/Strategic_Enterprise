<?php

namespace App\Filament\Resources\ProjectDemands\Pages;

use App\Filament\Resources\ProjectDemands\ProjectDemandResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProjectDemands extends ListRecords
{
    protected static string $resource = ProjectDemandResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\ProjectViabilities\Pages;

use App\Filament\Resources\ProjectViabilities\ProjectViabilityResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProjectViabilities extends ListRecords
{
    protected static string $resource = ProjectViabilityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\ProjectCharters\Pages;

use App\Filament\Resources\ProjectCharters\ProjectCharterResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProjectCharters extends ListRecords
{
    protected static string $resource = ProjectCharterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

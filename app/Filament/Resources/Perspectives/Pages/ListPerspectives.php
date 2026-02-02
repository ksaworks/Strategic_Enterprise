<?php

namespace App\Filament\Resources\Perspectives\Pages;

use App\Filament\Resources\Perspectives\PerspectiveResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPerspectives extends ListRecords
{
    protected static string $resource = PerspectiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

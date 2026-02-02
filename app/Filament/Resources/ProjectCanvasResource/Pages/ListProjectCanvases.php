<?php

namespace App\Filament\Resources\ProjectCanvasResource\Pages;

use App\Filament\Resources\ProjectCanvasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProjectCanvases extends ListRecords
{
    protected static string $resource = ProjectCanvasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

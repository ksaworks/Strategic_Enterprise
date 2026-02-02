<?php

namespace App\Filament\Resources\ProjectCanvasResource\Pages;

use App\Filament\Resources\ProjectCanvasResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProjectCanvas extends CreateRecord
{
    protected static string $resource = ProjectCanvasResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['owner_id'] = auth()->id();
        return $data;
    }
}

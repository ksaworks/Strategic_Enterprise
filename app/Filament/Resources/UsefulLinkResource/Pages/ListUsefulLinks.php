<?php

namespace App\Filament\Resources\UsefulLinkResource\Pages;

use App\Filament\Resources\UsefulLinkResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsefulLinks extends ListRecords
{
    protected static string $resource = UsefulLinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\ResourceBookingResource\Pages;

use App\Filament\Resources\ResourceBookingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListResourceBookings extends ListRecords
{
    protected static string $resource = ResourceBookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

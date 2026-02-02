<?php

namespace App\Filament\Resources\LessonLearneds\Pages;

use App\Filament\Resources\LessonLearneds\LessonLearnedResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLessonLearneds extends ListRecords
{
    protected static string $resource = LessonLearnedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

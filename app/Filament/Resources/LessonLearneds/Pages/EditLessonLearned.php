<?php

namespace App\Filament\Resources\LessonLearneds\Pages;

use App\Filament\Resources\LessonLearneds\LessonLearnedResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditLessonLearned extends EditRecord
{
    protected static string $resource = LessonLearnedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}

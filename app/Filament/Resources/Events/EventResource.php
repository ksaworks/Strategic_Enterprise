<?php

namespace App\Filament\Resources\Events;

use App\Filament\Resources\Events\Pages\CreateEvent;
use App\Filament\Resources\Events\Pages\EditEvent;
use App\Filament\Resources\Events\Pages\ListEvents;
use App\Filament\Resources\Events\Schemas\EventForm;
use App\Filament\Resources\Events\Tables\EventsTable;
use App\Models\Event;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?string $recordTitleAttribute = 'title';

    protected static string|\UnitEnum|null $navigationGroup = 'Sistema';

    protected static ?int $navigationSort = 4;

    public static function getModelLabel(): string
    {
        return 'Evento';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Eventos';
    }

    public static function form(Schema $schema): Schema
    {
        return EventForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EventsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEvents::route('/'),
            'create' => CreateEvent::route('/create'),
            'edit' => EditEvent::route('/{record}/edit'),
        ];
    }
}

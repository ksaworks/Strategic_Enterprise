<?php

namespace App\Filament\Resources\StrategicObjectives;

use App\Filament\Resources\StrategicObjectives\Pages\CreateStrategicObjective;
use App\Filament\Resources\StrategicObjectives\Pages\EditStrategicObjective;
use App\Filament\Resources\StrategicObjectives\Pages\ListStrategicObjectives;
use App\Filament\Resources\StrategicObjectives\Schemas\StrategicObjectiveForm;
use App\Filament\Resources\StrategicObjectives\Tables\StrategicObjectivesTable;
use App\Models\StrategicObjective;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StrategicObjectiveResource extends Resource
{
    protected static ?string $model = StrategicObjective::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string | \UnitEnum | null $navigationGroup = 'Gestão';

    public static function getModelLabel(): string
    {
        return 'Objetivo Estratégico';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Objetivos Estratégicos';
    }

    public static function form(Schema $schema): Schema
    {
        return StrategicObjectiveForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StrategicObjectivesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\StrategicObjectives\RelationManagers\CriticalSuccessFactorsRelationManager::class,
            \App\Filament\Resources\StrategicObjectives\RelationManagers\KpisRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStrategicObjectives::route('/'),
            'create' => CreateStrategicObjective::route('/create'),
            'edit' => EditStrategicObjective::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}

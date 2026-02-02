<?php

namespace App\Filament\Resources\ProjectViabilities;

use App\Filament\Resources\ProjectViabilities\Pages\CreateProjectViability;
use App\Filament\Resources\ProjectViabilities\Pages\EditProjectViability;
use App\Filament\Resources\ProjectViabilities\Pages\ListProjectViabilities;
use App\Filament\Resources\ProjectViabilities\Schemas\ProjectViabilityForm;
use App\Filament\Resources\ProjectViabilities\Tables\ProjectViabilitiesTable;
use App\Models\ProjectViability;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProjectViabilityResource extends Resource
{
    protected static ?string $model = ProjectViability::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    protected static string | \UnitEnum | null $navigationGroup = 'Projetos';

    protected static ?int $navigationSort = 4;

    public static function getModelLabel(): string
    {
        return 'Estudo de Viabilidade';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Estudos de Viabilidade';
    }

    public static function form(Schema $schema): Schema
    {
        return ProjectViabilityForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProjectViabilitiesTable::configure($table);
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
            'index' => ListProjectViabilities::route('/'),
            'create' => CreateProjectViability::route('/create'),
            'edit' => EditProjectViability::route('/{record}/edit'),
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

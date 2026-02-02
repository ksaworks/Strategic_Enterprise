<?php

namespace App\Filament\Resources\ProjectDemands;

use App\Filament\Resources\ProjectDemands\Pages\CreateProjectDemand;
use App\Filament\Resources\ProjectDemands\Pages\EditProjectDemand;
use App\Filament\Resources\ProjectDemands\Pages\ListProjectDemands;
use App\Filament\Resources\ProjectDemands\Schemas\ProjectDemandForm;
use App\Filament\Resources\ProjectDemands\Tables\ProjectDemandsTable;
use App\Models\ProjectDemand;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProjectDemandResource extends Resource
{
    protected static ?string $model = ProjectDemand::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    protected static string | \UnitEnum | null $navigationGroup = 'Projetos';

    protected static ?int $navigationSort = 0; // First in Projects

    public static function getModelLabel(): string
    {
        return 'Demanda';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Demandas';
    }

    public static function form(Schema $schema): Schema
    {
        return ProjectDemandForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProjectDemandsTable::configure($table);
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
            'index' => ListProjectDemands::route('/'),
            'create' => CreateProjectDemand::route('/create'),
            'edit' => EditProjectDemand::route('/{record}/edit'),
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

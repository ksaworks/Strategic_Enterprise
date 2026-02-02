<?php

namespace App\Filament\Resources\ProjectRisks;

use App\Filament\Resources\ProjectRisks\Pages\CreateProjectRisk;
use App\Filament\Resources\ProjectRisks\Pages\EditProjectRisk;
use App\Filament\Resources\ProjectRisks\Pages\ListProjectRisks;
use App\Filament\Resources\ProjectRisks\Schemas\ProjectRiskForm;
use App\Filament\Resources\ProjectRisks\Tables\ProjectRisksTable;
use App\Models\ProjectRisk;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use UnitEnum;

class ProjectRiskResource extends Resource
{
    protected static ?string $model = ProjectRisk::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldExclamation;

    protected static string | \UnitEnum | null $navigationGroup = 'Projetos';

    protected static ?int $navigationSort = 6;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getModelLabel(): string
    {
        return 'Risco';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Riscos';
    }

    public static function form(Schema $schema): Schema
    {
        return ProjectRiskForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProjectRisksTable::configure($table);
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
            'index' => ListProjectRisks::route('/'),
            'create' => CreateProjectRisk::route('/create'),
            'edit' => EditProjectRisk::route('/{record}/edit'),
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

<?php

namespace App\Filament\Resources\SwotAnalyses;

use App\Filament\Resources\SwotAnalyses\Pages\CreateSwotAnalysis;
use App\Filament\Resources\SwotAnalyses\Pages\EditSwotAnalysis;
use App\Filament\Resources\SwotAnalyses\Pages\ListSwotAnalyses;
use App\Filament\Resources\SwotAnalyses\Schemas\SwotAnalysisForm;
use App\Filament\Resources\SwotAnalyses\Tables\SwotAnalysesTable;
use App\Models\SwotAnalysis;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use UnitEnum;

class SwotAnalysisResource extends Resource
{
    protected static ?string $model = SwotAnalysis::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedViewColumns;

    protected static string|UnitEnum|null $navigationGroup = 'Projetos';

    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $modelLabel = 'Análise SWOT';

    protected static ?string $pluralModelLabel = 'Análises SWOT';

    public static function form(Schema $schema): Schema
    {
        return SwotAnalysisForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SwotAnalysesTable::configure($table);
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
            'index' => ListSwotAnalyses::route('/'),
            'create' => CreateSwotAnalysis::route('/create'),
            'edit' => EditSwotAnalysis::route('/{record}/edit'),
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

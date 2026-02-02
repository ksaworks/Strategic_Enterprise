<?php

namespace App\Filament\Resources\Perspectives;

use App\Filament\Resources\Perspectives\Pages\CreatePerspective;
use App\Filament\Resources\Perspectives\Pages\EditPerspective;
use App\Filament\Resources\Perspectives\Pages\ListPerspectives;
use App\Filament\Resources\Perspectives\Schemas\PerspectiveForm;
use App\Filament\Resources\Perspectives\Tables\PerspectivesTable;
use App\Models\Perspective;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PerspectiveResource extends Resource
{
    protected static ?string $model = Perspective::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string | \UnitEnum | null $navigationGroup = 'Gestão';

    public static function getModelLabel(): string
    {
        return 'Perspectiva';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Perspectivas';
    }

    public static function form(Schema $schema): Schema
    {
        return PerspectiveForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PerspectivesTable::configure($table);
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
            'index' => ListPerspectives::route('/'),
            'create' => CreatePerspective::route('/create'),
            'edit' => EditPerspective::route('/{record}/edit'),
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

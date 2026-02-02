<?php

namespace App\Filament\Resources\ProjectCharters;

use App\Filament\Resources\ProjectCharters\Pages\CreateProjectCharter;
use App\Filament\Resources\ProjectCharters\Pages\EditProjectCharter;
use App\Filament\Resources\ProjectCharters\Pages\ListProjectCharters;
use App\Filament\Resources\ProjectCharters\Schemas\ProjectCharterForm;
use App\Filament\Resources\ProjectCharters\Tables\ProjectChartersTable;
use App\Models\ProjectCharter;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use UnitEnum;

class ProjectCharterResource extends Resource
{
    protected static ?string $model = ProjectCharter::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string | \UnitEnum | null $navigationGroup = 'Projetos';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getModelLabel(): string
    {
        return 'Termo de Abertura';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Termos de Abertura';
    }

    public static function form(Schema $schema): Schema
    {
        return ProjectCharterForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProjectChartersTable::configure($table);
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
            'index' => ListProjectCharters::route('/'),
            'create' => CreateProjectCharter::route('/create'),
            'edit' => EditProjectCharter::route('/{record}/edit'),
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

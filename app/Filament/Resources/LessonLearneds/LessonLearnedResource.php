<?php

namespace App\Filament\Resources\LessonLearneds;

use App\Filament\Resources\LessonLearneds\Pages\CreateLessonLearned;
use App\Filament\Resources\LessonLearneds\Pages\EditLessonLearned;
use App\Filament\Resources\LessonLearneds\Pages\ListLessonLearneds;
use App\Filament\Resources\LessonLearneds\Schemas\LessonLearnedForm;
use App\Filament\Resources\LessonLearneds\Tables\LessonLearnedsTable;
use App\Models\LessonLearned;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LessonLearnedResource extends Resource
{
    protected static ?string $model = LessonLearned::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'category';

    protected static string | \UnitEnum | null $navigationGroup = 'Projetos';

    protected static ?int $navigationSort = 5;

    public static function getModelLabel(): string
    {
        return 'Lição Aprendida';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Lições Aprendidas';
    }

    public static function form(Schema $schema): Schema
    {
        return LessonLearnedForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LessonLearnedsTable::configure($table);
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
            'index' => ListLessonLearneds::route('/'),
            'create' => CreateLessonLearned::route('/create'),
            'edit' => EditLessonLearned::route('/{record}/edit'),
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

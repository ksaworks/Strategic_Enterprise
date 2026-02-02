<?php

namespace App\Filament\Resources\Tasks;

use App\Filament\Resources\Tasks\Pages\CreateTask;
use App\Filament\Resources\Tasks\Pages\EditTask;
use App\Filament\Resources\Tasks\Pages\ListTasks;
use App\Filament\Resources\Tasks\Schemas\TaskForm;
use App\Filament\Resources\Tasks\Tables\TasksTable;
use App\Models\Task;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $recordTitleAttribute = 'name';

    protected static string | \UnitEnum | null $navigationGroup = 'Projetos';

    protected static ?int $navigationSort = 2; // After Projects

    public static function getModelLabel(): string
    {
        return 'Tarefa';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Tarefas';
    }

    public static function form(Schema $schema): Schema
    {
        return TaskForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TasksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\Tasks\RelationManagers\CostItemsRelationManager::class,
            \App\Filament\Resources\Companies\RelationManagers\DocumentsRelationManager::class,
            \App\Filament\RelationManagers\CommentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTasks::route('/'),
            'create' => CreateTask::route('/create'),
            'edit' => EditTask::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources\Projects;

use App\Filament\Resources\Projects\Pages\CreateProject;
use App\Filament\Resources\Projects\Pages\EditProject;
use App\Filament\Resources\Projects\Pages\ListProjects;
use App\Filament\Resources\Projects\Pages\ProjectWbs;
use App\Filament\Resources\Projects\Schemas\ProjectForm;
use App\Filament\Resources\Projects\Tables\ProjectsTable;
use App\Models\Project;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $recordTitleAttribute = 'name';

    protected static string | \UnitEnum | null $navigationGroup = 'Projetos';

    protected static ?int $navigationSort = 1;

    public static function getModelLabel(): string
    {
        return 'Projeto';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Projetos';
    }

    public static function form(Schema $schema): Schema
    {
        return ProjectForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProjectsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            // Sprint 9: Equipe e Riscos
            \App\Filament\Resources\Projects\RelationManagers\ProjectTeamRelationManager::class,
            \App\Filament\Resources\Projects\RelationManagers\ProjectRisksRelationManager::class,
            // Sprint 10: Baselines
            \App\Filament\Resources\Projects\RelationManagers\ProjectBaselinesRelationManager::class,
            // Sprint 19: Despesas (Cronograma Financeiro)
            \App\Filament\Resources\Projects\RelationManagers\ExpensesRelationManager::class,
            // Documentos e Comentários
            \App\Filament\Resources\Companies\RelationManagers\DocumentsRelationManager::class,
            \App\Filament\RelationManagers\CommentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProjects::route('/'),
            'create' => CreateProject::route('/create'),
            'edit' => EditProject::route('/{record}/edit'),
            'wbs' => ProjectWbs::route('/{record}/wbs'),
        ];
    }
}

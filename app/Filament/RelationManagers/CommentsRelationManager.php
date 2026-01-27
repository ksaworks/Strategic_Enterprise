<?php

namespace App\Filament\RelationManagers;

use App\Filament\Resources\Comments\Schemas\CommentForm;
use App\Filament\Resources\Comments\Tables\CommentsTable;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';

    protected static ?string $title = 'Comentários';

    protected static ?string $modelLabel = 'Comentário';

    public function form(Schema $schema): Schema
    {
        return CommentForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return CommentsTable::configure($table);
    }
}

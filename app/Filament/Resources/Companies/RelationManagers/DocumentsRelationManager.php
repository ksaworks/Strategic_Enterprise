<?php

namespace App\Filament\Resources\Companies\RelationManagers;

use App\Filament\Resources\Documents\Schemas\DocumentForm;
use App\Filament\Resources\Documents\Tables\DocumentsTable;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class DocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';

    public function form(Schema $schema): Schema
    {
        return DocumentForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return DocumentsTable::configure($table);
    }
}

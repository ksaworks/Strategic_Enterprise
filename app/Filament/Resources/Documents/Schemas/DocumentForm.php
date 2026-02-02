<?php

namespace App\Filament\Resources\Documents\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class DocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Título')
                    ->required()
                    ->maxLength(255),
                \Filament\Forms\Components\FileUpload::make('file_path')
                    ->label('Arquivo')
                    ->required()
                    ->storeFileNamesIn('file_name')
                    ->preserveFilenames(),
                Textarea::make('description')
                    ->label('Descrição')
                    ->columnSpanFull(),
                /*TextInput::make('documentable_type')
                    ->required(),
                TextInput::make('documentable_id')
                    ->required()
                    ->numeric(),*/
            ]);
    }
}

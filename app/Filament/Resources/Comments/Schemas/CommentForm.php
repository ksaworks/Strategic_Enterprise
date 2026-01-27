<?php

namespace App\Filament\Resources\Comments\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Hidden;

class CommentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('user_id')
                    ->default(auth()->id())
                    ->required(),

                RichEditor::make('body')
                    ->label('Comentário')
                    ->required()
                    ->columnSpanFull()
                    ->prefixIcon('heroicon-o-chat-bubble-left'),
            ]);
    }
}

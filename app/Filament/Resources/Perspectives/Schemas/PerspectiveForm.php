<?php

namespace App\Filament\Resources\Perspectives\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PerspectiveForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Grid::make(3)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nome da Perspectiva')
                            ->required()
                            ->columnSpan(2),
                        TextInput::make('order')
                            ->label('Ordem')
                            ->numeric()
                            ->default(0),
                    ]),
                
                \Filament\Schemas\Components\Grid::make(3)
                    ->schema([
                        \Filament\Forms\Components\ColorPicker::make('color')
                            ->label('Cor de Identificação')
                            ->required(),
                        TextInput::make('icon')
                            ->label('Ícone (Heroicon)')
                            ->placeholder('heroicon-o-currency-dollar')
                            ->helperText('Nome do ícone do Heroicons v2'),
                        Toggle::make('is_active')
                            ->label('Ativo')
                            ->inline(false)
                            ->default(true),
                    ]),

                Textarea::make('description')
                    ->label('Descrição')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}

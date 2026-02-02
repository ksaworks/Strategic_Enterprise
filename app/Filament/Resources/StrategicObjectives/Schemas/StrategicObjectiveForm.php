<?php

namespace App\Filament\Resources\StrategicObjectives\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class StrategicObjectiveForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Grid::make(3)
                    ->schema([
                        Select::make('perspective_id')
                            ->label('Perspectiva')
                            ->relationship('perspective', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('owner_id')
                            ->label('Responsável')
                            ->relationship('owner', 'name')
                            ->searchable()
                            ->preload(),
                        TextInput::make('code')
                            ->label('Código')
                            ->placeholder('Ex: FIN-01'),
                    ]),
                
                TextInput::make('name')
                    ->label('Nome do Objetivo')
                    ->required()
                    ->columnSpanFull(),
                
                Textarea::make('description')
                    ->label('Descrição')
                    ->rows(3)
                    ->columnSpanFull(),
                
                Toggle::make('is_active')
                    ->label('Ativo')
                    ->inline(false)
                    ->default(true),
            ]);
    }
}

<?php

namespace App\Filament\Resources\StrategicObjectives\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Schemas\Schema;

class CriticalSuccessFactorsRelationManager extends RelationManager
{
    protected static string $relationship = 'criticalSuccessFactors';
    
    protected static ?string $title = 'Fatores Críticos de Sucesso (FCS)';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Fator Crítico')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                
                \Filament\Schemas\Components\Grid::make(3)
                    ->schema([
                        TextInput::make('weight')
                            ->label('Peso')
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->maxValue(100),
                        
                        Toggle::make('is_active')
                            ->label('Ativo')
                            ->inline(false)
                            ->default(true),
                    ]),

                Textarea::make('description')
                    ->label('Descrição')
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Fator Crítico')
                    ->searchable(),
                
                TextColumn::make('weight')
                    ->label('Peso')
                    ->numeric()
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Ativo')
                    ->boolean(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Adicionar FCS'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}

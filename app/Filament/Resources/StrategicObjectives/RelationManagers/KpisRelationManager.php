<?php

namespace App\Filament\Resources\StrategicObjectives\RelationManagers;

use App\Enums\KpiFrequency;
use App\Enums\KpiPolarity;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class KpisRelationManager extends RelationManager
{
    protected static string $relationship = 'kpis';

    protected static ?string $title = 'Indicadores de Desempenho (KPIs)';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nome do Indicador')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),
                        
                        Textarea::make('description')
                            ->label('Descrição')
                            ->columnSpan(2),

                        Select::make('frequency')
                            ->label('Frequência')
                            ->options(KpiFrequency::toSelectArray())
                            ->default('monthly')
                            ->required(),

                        Select::make('polarity')
                            ->label('Polaridade')
                            ->options(KpiPolarity::toSelectArray())
                            ->default('higher_is_better')
                            ->required()
                            ->helperText('Define como calcular a performance'),

                        TextInput::make('measurement_unit')
                            ->label('Unidade')
                            ->placeholder('%, R$, #')
                            ->default('number'),

                        TextInput::make('base_target')
                            ->label('Meta Base')
                            ->numeric()
                            ->helperText('Meta padrão para novos períodos'),

                        Select::make('owner_id')
                            ->label('Responsável')
                            ->relationship('owner', 'name')
                            ->searchable()
                            ->preload(),

                        Toggle::make('is_active')
                            ->label('Ativo')
                            ->default(true),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Indicador')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('frequency')
                    ->label('Frequência')
                    ->badge(),

                TextColumn::make('polarity')
                    ->label('Polaridade')
                    ->badge()
                    ->color(fn ($state) => $state->getColor()),

                TextColumn::make('measurements_count')
                    ->label('Medições')
                    ->counts('measurements')
                    ->badge(),

                IconColumn::make('is_active')
                    ->label('Ativo')
                    ->boolean(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Novo KPI'),
            ])
            ->actions([
                Action::make('measurements')
                    ->label('Lançar Medições')
                    ->icon('heroicon-o-chart-bar')
                    ->color('info')
                    ->form([
                        Repeater::make('measurements')
                            ->relationship()
                            ->label('Histórico de Medições')
                            ->schema([
                                Grid::make(4)
                                    ->schema([
                                        DatePicker::make('period')
                                            ->label('Período')
                                            ->required()
                                            ->default(now()),
                                        
                                        TextInput::make('target_value')
                                            ->label('Meta')
                                            ->numeric()
                                            ->required(),
                                            
                                        TextInput::make('actual_value')
                                            ->label('Realizado')
                                            ->numeric(),
                                            
                                        TextInput::make('notes')
                                            ->label('Obs')
                                            ->columnSpan(1),
                                    ]),
                            ])
                            ->defaultItems(1)
                            ->columns(1)
                            ->collapsed(false)
                            ->itemLabel(fn (array $state): ?string => isset($state['period']) ? \Carbon\Carbon::parse($state['period'])->format('d/m/Y') : null),
                    ])
                    ->modalHeading('Lançamento de Medições')
                    ->modalWidth('4xl'),

                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}

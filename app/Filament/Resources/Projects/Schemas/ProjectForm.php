<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Schemas\Schema;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\RichEditor;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Dados Principais')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Select::make('company_id')
                                    ->label('Empresa')
                                    ->relationship('company', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->prefixIcon('heroicon-o-building-office'),

                                TextInput::make('name')
                                    ->label('Nome do Projeto')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpan(2)
                                    ->prefixIcon('heroicon-o-briefcase'),

                                Select::make('owner_id')
                                    ->label('Gerente do Projeto')
                                    ->relationship('owner', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->prefixIcon('heroicon-o-user'),

                                TextInput::make('code')
                                    ->label('Código Interno')
                                    ->maxLength(50)
                                    ->prefixIcon('heroicon-o-qr-code'),

                                Select::make('status')
                                    ->options([
                                        0 => 'Não Iniciado',
                                        1 => 'Em Andamento',
                                        2 => 'Em Pausa',
                                        3 => 'Concluído',
                                        4 => 'Cancelado',
                                    ])
                                    ->default(0)
                                    ->required()
                                    ->prefixIcon('heroicon-o-flag'),
                            ]),
                    ]),

                Section::make('Cronograma e Financeiro')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                DatePicker::make('start_date')
                                    ->label('Início Previsto')
                                    ->prefixIcon('heroicon-o-calendar'),

                                DatePicker::make('end_date')
                                    ->label('Término Previsto')
                                    ->prefixIcon('heroicon-o-calendar'),

                                TextInput::make('progress')
                                    ->label('Progresso (%)')
                                    ->numeric()
                                    ->default(0)
                                    ->suffix('%')
                                    ->prefixIcon('heroicon-o-chart-pie'),

                                TextInput::make('budget')
                                    ->label('Orçamento (R$)')
                                    ->numeric()
                                    ->prefix('R$'),
                            ]),
                    ]),

                Section::make('Detalhes')
                    ->schema([
                        RichEditor::make('description')
                            ->label('Descrição do Projeto')
                            ->columnSpanFull(),

                        RichEditor::make('objectives')
                            ->label('Objetivos')
                            ->columnSpanFull(),
                    ])->collapsible(),

                Grid::make(2)
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Projeto Ativo')
                            ->default(true),

                        Toggle::make('is_private')
                            ->label('Privado')
                            ->default(false),
                    ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Tasks\Schemas;

use Filament\Schemas\Schema;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\RichEditor;

class TaskForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Contexto')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Select::make('project_id')
                                    ->label('Projeto')
                                    ->relationship('project', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->prefixIcon('heroicon-o-briefcase')
                                    ->columnSpan(2),

                                Select::make('parent_id')
                                    ->label('Tarefa Pai')
                                    ->relationship('parent', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->prefixIcon('heroicon-o-arrow-uturn-left')
                                    ->helperText('Caso seja uma subtarefa.'),
                            ]),
                    ]),

                Section::make('Detalhes da Tarefa')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Título da Tarefa')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpan(2)
                                    ->prefixIcon('heroicon-o-check-circle'),

                                Select::make('owner_id')
                                    ->label('Atribuído para')
                                    ->relationship('owner', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->prefixIcon('heroicon-o-user'),

                                Select::make('status')
                                    ->options([
                                        0 => 'A Fazer',
                                        1 => 'Em Andamento',
                                        2 => 'Aguardando',
                                        3 => 'Concluída',
                                        4 => 'Cancelada',
                                    ])
                                    ->default(0)
                                    ->required()
                                    ->prefixIcon('heroicon-o-flag'),

                                Select::make('priority')
                                    ->label('Prioridade')
                                    ->options([
                                        0 => 'Baixa',
                                        5 => 'Média',
                                        9 => 'Alta',
                                    ])
                                    ->default(5)
                                    ->required()
                                    ->prefixIcon('heroicon-o-exclamation-circle'),

                                TextInput::make('progress')
                                    ->label('Progresso (%)')
                                    ->numeric()
                                    ->default(0)
                                    ->suffix('%')
                                    ->prefixIcon('heroicon-o-chart-pie'),
                            ]),

                        RichEditor::make('description')
                            ->label('Descrição')
                            ->columnSpanFull(),
                    ]),

                Section::make('Planejamento')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                DatePicker::make('start_date')
                                    ->label('Data Início'),

                                DatePicker::make('end_date')
                                    ->label('Data Fim'),

                                TextInput::make('duration')
                                    ->label('Duração Estimada (h)')
                                    ->numeric(),
                            ]),
                    ]),

                Toggle::make('is_milestone')
                    ->label('É um Marco (Milestone)?')
                    ->default(false),
            ]);
    }
}

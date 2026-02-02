<?php

namespace App\Filament\Resources\Tasks\Schemas;

use App\Enums\DependencyType;
use App\Enums\Priority;
use App\Enums\TaskStatus;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;

class TaskForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Contexto e Definição')
                        ->icon('heroicon-o-folder')
                        ->schema([
                            Section::make('Contexto do Projeto')
                                ->schema([
                                    Grid::make(2)
                                        ->schema([
                                            Select::make('project_id')
                                                ->label('Projeto')
                                                ->relationship('project', 'name')
                                                ->searchable()
                                                ->preload()
                                                ->required()
                                                ->prefixIcon('heroicon-o-briefcase'),

                                            Select::make('parent_id')
                                                ->label('Tarefa Pai')
                                                ->relationship('parent', 'name')
                                                ->searchable()
                                                ->preload()
                                                ->prefixIcon('heroicon-o-arrow-uturn-left')
                                                ->helperText('Caso seja uma subtarefa'),
                                        ]),
                                ]),
                            
                            Section::make('Dados da Tarefa')
                                ->schema([
                                    Grid::make(3)
                                        ->schema([
                                            TextInput::make('name')
                                                ->label('Título da Tarefa')
                                                ->required()
                                                ->maxLength(255)
                                                ->columnSpan(2)
                                                ->prefixIcon('heroicon-o-check-circle'),

                                            TextInput::make('code')
                                                ->label('Código')
                                                ->maxLength(50)
                                                ->prefixIcon('heroicon-o-qr-code')
                                                ->helperText('Código interno da tarefa'),
                                        ]),

                                    RichEditor::make('description')
                                        ->label('Descrição')
                                        ->columnSpanFull(),
                                ]),
                        ]),

                    Step::make('Execução e Responsabilidade')
                        ->icon('heroicon-o-user')
                        ->schema([
                            Section::make('Responsáveis e Status')
                                ->schema([
                                    Grid::make(2)
                                        ->schema([
                                            Select::make('owner_id')
                                                ->label('Responsável')
                                                ->relationship('owner', 'name')
                                                ->searchable()
                                                ->preload()
                                                ->prefixIcon('heroicon-o-user')
                                                ->columnSpanFull(),

                                            Select::make('status')
                                                ->label('Status')
                                                ->options(TaskStatus::toSelectArray())
                                                ->default(0)
                                                ->required()
                                                ->prefixIcon('heroicon-o-flag'),

                                            Select::make('priority')
                                                ->label('Prioridade')
                                                ->options(Priority::toSelectArray())
                                                ->default(5)
                                                ->required()
                                                ->prefixIcon('heroicon-o-exclamation-circle'),
                                            
                                            TextInput::make('progress')
                                                ->label('Progresso (%)')
                                                ->numeric()
                                                ->default(0)
                                                ->minValue(0)
                                                ->maxValue(100)
                                                ->suffix('%')
                                                ->prefixIcon('heroicon-o-chart-pie')
                                                ->columnSpanFull(),
                                        ]),
                                ]),

                            Section::make('Configurações')
                                ->schema([
                                    Grid::make(2)
                                        ->schema([
                                            Toggle::make('is_milestone')
                                                ->label('É um Marco (Milestone)?')
                                                ->default(false)
                                                ->helperText('Pontos de verificação importantes'),

                                            Toggle::make('is_dynamic')
                                                ->label('Datas Dinâmicas')
                                                ->default(false)
                                                ->helperText('Calcular datas automaticamente'),
                                        ]),
                                ]),
                        ]),

                    Step::make('Planejamento')
                        ->icon('heroicon-o-calendar')
                        ->schema([
                            Section::make('Cronograma')
                                ->schema([
                                    Grid::make(2)
                                        ->schema([
                                            DatePicker::make('start_date')
                                                ->label('Data Início')
                                                ->prefixIcon('heroicon-o-calendar'),

                                            DatePicker::make('end_date')
                                                ->label('Data Fim')
                                                ->prefixIcon('heroicon-o-calendar'),

                                            TextInput::make('duration')
                                                ->label('Duração (horas)')
                                                ->numeric(),

                                            TextInput::make('hours_worked')
                                                ->label('Horas Trabalhadas')
                                                ->numeric()
                                                ->default(0),
                                        ]),
                                ]),
                        ]),

                    Step::make('Financeiro e 5W2H')
                        ->icon('heroicon-o-banknotes')
                        ->schema([
                            Section::make('Custos')
                                ->schema([
                                    Grid::make(2)
                                        ->schema([
                                            TextInput::make('cost')
                                                ->label('Custo Planejado')
                                                ->numeric()
                                                ->prefix('R$'),

                                            TextInput::make('spent')
                                                ->label('Custo Realizado')
                                                ->numeric()
                                                ->prefix('R$'),
                                        ]),
                                ]),

                            Section::make('Detalhamento 5W2H')
                                ->description('Onde, Por quê, Como')
                                ->schema([
                                    Grid::make(1)
                                        ->schema([
                                            Textarea::make('where')
                                                ->label('Onde? (Where)')
                                                ->rows(2)
                                                ->helperText('Local de execução da tarefa'),

                                            Textarea::make('why')
                                                ->label('Por quê? (Why)')
                                                ->rows(2)
                                                ->helperText('Justificativa/motivo da tarefa'),

                                            Textarea::make('how')
                                                ->label('Como? (How)')
                                                ->rows(2)
                                                ->helperText('Método de execução'),
                                        ]),
                                ]),
                        ]),
                ])->columnSpanFull(),
            ]);
    }
}

<?php

namespace App\Filament\Resources\ProjectViabilities\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class ProjectViabilityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Contexto')
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            Section::make('Identificação')
                                ->schema([
                                    Select::make('project_id')
                                        ->label('Projeto')
                                        ->relationship('project', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->required()
                                        ->prefixIcon('heroicon-o-briefcase'),
                                ]),
                        ]),

                    Step::make('Análise Técnica')
                        ->icon('heroicon-o-wrench-screwdriver')
                        ->schema([
                            Section::make('Viabilidade Técnica')
                                ->schema([
                                    \Filament\Forms\Components\RichEditor::make('technical_feasibility')
                                        ->label('Detalhes Técnicos')
                                        ->placeholder('Descreva a viabilidade técnica do projeto...')
                                        ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList', 'link', 'attachFiles'])
                                        ->columnSpanFull(),
                                ]),
                        ]),

                    Step::make('Financeiro')
                        ->icon('heroicon-o-banknotes')
                        ->schema([
                            Section::make('Análise Financeira')
                                ->columns(3)
                                ->schema([
                                    TextInput::make('financial_return')
                                        ->label('Retorno Financeiro (ROI)')
                                        ->prefix('R$')
                                        ->numeric(),
                                    TextInput::make('payback_period')
                                        ->label('Payback (Meses)')
                                        ->suffix('meses')
                                        ->numeric(),
                                    TextInput::make('score')
                                        ->label('Pontuação (0-100)')
                                        ->required()
                                        ->numeric()
                                        ->minValue(0)
                                        ->maxValue(100)
                                        ->default(0)
                                        ->suffix('/ 100'),
                                ]),
                        ]),

                    Step::make('Decisão')
                        ->icon('heroicon-o-check-circle')
                        ->schema([
                            Section::make('Conclusão da Análise')
                                ->schema([
                                    Grid::make(2)
                                        ->schema([
                                            Select::make('decision')
                                                ->label('Decisão Final')
                                                ->options([
                                                    'on_hold' => 'Em Análise',
                                                    'approved' => 'Aprovado',
                                                    'rejected' => 'Rejeitado',
                                                ])
                                                ->required()
                                                ->default('on_hold')
                                                ->prefixIcon('heroicon-o-check'),
                                            
                                            Select::make('analyzed_by_id')
                                                ->label('Analisado Por')
                                                ->relationship('analyzer', 'name')
                                                ->searchable()
                                                ->preload()
                                                ->prefixIcon('heroicon-o-user'),
                                        ]),

                                    DateTimePicker::make('analyzed_at')
                                        ->label('Data da Análise')
                                        ->prefixIcon('heroicon-o-calendar'),

                                    Textarea::make('comments')
                                        ->label('Comentários / Justificativa')
                                        ->columnSpanFull()
                                        ->rows(3),
                                ]),
                        ]),
                ])->columnSpanFull(),
            ]);
    }
}

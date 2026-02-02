<?php

namespace App\Filament\Resources\SwotAnalyses\Schemas;

use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SwotAnalysisForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Contexto')
                        ->icon('heroicon-o-document-magnifying-glass')
                        ->schema([
                            Section::make('Identificação')
                                ->columns(2)
                                ->schema([
                                    Select::make('project_id')
                                        ->label('Projeto')
                                        ->relationship('project', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->required()
                                        ->prefixIcon('heroicon-o-briefcase'),

                                    Select::make('created_by_id')
                                        ->label('Criado por')
                                        ->relationship('createdBy', 'name')
                                        ->default(fn () => auth()->id())
                                        ->required()
                                        ->searchable()
                                        ->preload()
                                        ->prefixIcon('heroicon-o-user'),

                                    TextInput::make('title')
                                        ->label('Título da Análise')
                                        ->placeholder('Ex: Análise SWOT - Q1 2026')
                                        ->columnSpanFull()
                                        ->prefixIcon('heroicon-o-pencil'),
                                ]),
                        ]),

                    Step::make('Análise Interna')
                        ->icon('heroicon-o-building-office-2')
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    // Forças (interno, positivo)
                                    Section::make('💪 Forças (Strengths)')
                                        ->description('Pontos fortes internos da organização/projeto')
                                        ->schema([
                                            RichEditor::make('strengths')
                                                ->label('')
                                                ->placeholder('Liste os pontos fortes internos...')
                                                ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList']),
                                        ]),

                                    // Fraquezas (interno, negativo)
                                    Section::make('⚠️ Fraquezas (Weaknesses)')
                                        ->description('Pontos fracos internos que precisam melhoria')
                                        ->schema([
                                            RichEditor::make('weaknesses')
                                                ->label('')
                                                ->placeholder('Liste os pontos fracos internos...')
                                                ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList']),
                                        ]),
                                ]),
                        ]),

                    Step::make('Análise Externa')
                        ->icon('heroicon-o-globe-americas')
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    // Oportunidades (externo, positivo)
                                    Section::make('🚀 Oportunidades (Opportunities)')
                                        ->description('Fatores externos que podem ser aproveitados')
                                        ->schema([
                                            RichEditor::make('opportunities')
                                                ->label('')
                                                ->placeholder('Liste as oportunidades externas...')
                                                ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList']),
                                        ]),

                                    // Ameaças (externo, negativo)
                                    Section::make('🔥 Ameaças (Threats)')
                                        ->description('Fatores externos de risco')
                                        ->schema([
                                            RichEditor::make('threats')
                                                ->label('')
                                                ->placeholder('Liste as ameaças externas...')
                                                ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList']),
                                        ]),
                                ]),
                        ]),

                    Step::make('Conclusão')
                        ->icon('heroicon-o-light-bulb')
                        ->schema([
                            Section::make('Notas e Conclusões')
                                ->schema([
                                    RichEditor::make('notes')
                                        ->label('Observações')
                                        ->placeholder('Análise cruzada, conclusões, plano de ação...')
                                        ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList']),
                                ]),
                        ]),
                ])->columnSpanFull(),
            ]);
    }
}


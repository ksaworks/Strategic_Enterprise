<?php

namespace App\Filament\Resources\ProjectRisks\Schemas;

use App\Enums\RiskImpact;
use App\Enums\RiskProbability;
use App\Enums\RiskStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;

class ProjectRiskForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Identificação')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Section::make('Dados Básicos')
                                ->columns(2)
                                ->schema([
                                    Select::make('project_id')
                                        ->label('Projeto')
                                        ->relationship('project', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->required()
                                        ->columnSpan(1),
                                    TextInput::make('name')
                                        ->label('Nome do Risco')
                                        ->required()
                                        ->columnSpan(1),
                                    TextInput::make('category')
                                        ->label('Categoria')
                                        ->required()
                                        ->default('technical')
                                        ->columnSpanFull(),
                                    Textarea::make('description')
                                        ->label('Descrição Detalhada')
                                        ->columnSpanFull()
                                        ->rows(3),
                                ]),
                        ]),

                    Step::make('Análise')
                        ->icon('heroicon-o-chart-bar')
                        ->schema([
                            Section::make('Avaliação Qualitativa')
                                ->columns(3)
                                ->schema([
                                    Select::make('probability')
                                        ->label('Probabilidade')
                                        ->options(RiskProbability::class)
                                        ->default(3)
                                        ->required(),
                                    Select::make('impact')
                                        ->label('Impacto')
                                        ->options(RiskImpact::class)
                                        ->default(3)
                                        ->required(),
                                    TextInput::make('score')
                                        ->label('Pontuação')
                                        ->numeric()
                                        ->helperText('Cálculo automático: Probabilidade x Impacto'),
                                ]),
                        ]),

                    Step::make('Planos de Ação')
                        ->icon('heroicon-o-shield-check')
                        ->schema([
                            Section::make('Mitigação e Contingência')
                                ->schema([
                                    Textarea::make('mitigation_plan')
                                        ->label('Plano de Mitigação')
                                        ->placeholder('O que faremos para evitar que o risco ocorra?')
                                        ->columnSpanFull()
                                        ->rows(3),
                                    Textarea::make('contingency_plan')
                                        ->label('Plano de Contingência')
                                        ->placeholder('O que faremos se o risco ocorrer?')
                                        ->columnSpanFull()
                                        ->rows(3),
                                ]),
                        ]),

                    Step::make('Monitoramento')
                        ->icon('heroicon-o-eye')
                        ->schema([
                            Section::make('Status e Responsabilidade')
                                ->columns(2)
                                ->schema([
                                    Select::make('status')
                                        ->label('Status')
                                        ->options(RiskStatus::class)
                                        ->default('identified')
                                        ->required(),
                                    Select::make('owner_id')
                                        ->label('Responsável')
                                        ->relationship('owner', 'name')
                                        ->searchable()
                                        ->preload(),
                                    DatePicker::make('identified_at')
                                        ->label('Data de Identificação'),
                                    DatePicker::make('occurred_at')
                                        ->label('Data de Ocorrência'),
                                ]),
                        ]),
                ])->columnSpanFull(),
            ]);
    }
}

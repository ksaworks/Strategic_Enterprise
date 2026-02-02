<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Enums\Priority;
use App\Enums\ProjectStatus;
use App\Enums\ProjectType;
use App\Models\Project;
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

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Visão Geral')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Section::make('Identificação')
                                ->schema([
                                    Grid::make(3)
                                        ->schema([
                                            TextInput::make('name')
                                                ->label('Nome do Projeto')
                                                ->required()
                                                ->maxLength(255)
                                                ->columnSpan(2)
                                                ->prefixIcon('heroicon-o-briefcase'),

                                            TextInput::make('code')
                                                ->label('Código Interno')
                                                ->maxLength(50)
                                                ->prefixIcon('heroicon-o-qr-code'),
                                        ]),

                                    Grid::make(3)
                                        ->schema([
                                            Select::make('type')
                                                ->label('Tipo')
                                                ->options(ProjectType::toSelectArray())
                                                ->default('project')
                                                ->required()
                                                ->prefixIcon('heroicon-o-folder')
                                                ->helperText('Portfólio → Programa → Projeto'),

                                            Select::make('status')
                                                ->label('Status')
                                                ->options(ProjectStatus::toSelectArray())
                                                ->default(0)
                                                ->required()
                                                ->prefixIcon('heroicon-o-flag'),

                                            Select::make('priority')
                                                ->label('Prioridade')
                                                ->options(Priority::toSelectArray())
                                                ->default(5)
                                                ->prefixIcon('heroicon-o-arrow-up'),
                                        ]),

                                    Grid::make(2)
                                        ->schema([
                                            Toggle::make('is_active')
                                                ->label('Projeto Ativo')
                                                ->default(true),

                                            Toggle::make('is_private')
                                                ->label('Privado')
                                                ->default(false)
                                                ->helperText('Visível apenas para membros'),
                                        ]),
                                ]),
                        ]),

                    Step::make('Organização')
                        ->icon('heroicon-o-building-office')
                        ->schema([
                            Section::make('Estrutura Organizacional')
                                ->schema([
                                    Grid::make(2)
                                        ->schema([
                                            Select::make('company_id')
                                                ->label('Empresa')
                                                ->relationship('company', 'name')
                                                ->searchable()
                                                ->preload()
                                                ->required()
                                                ->prefixIcon('heroicon-o-building-office')
                                                ->columnSpanFull(),

                                            Select::make('department_id')
                                                ->label('Departamento')
                                                ->relationship('department', 'name')
                                                ->searchable()
                                                ->preload()
                                                ->prefixIcon('heroicon-o-building-office-2'),
                                            
                                            Select::make('parent_id')
                                                ->label('Projeto Pai / Programa')
                                                ->relationship(
                                                    'parent',
                                                    'name',
                                                    fn($query) => $query->whereIn('type', ['program', 'portfolio'])
                                                )
                                                ->searchable()
                                                ->preload()
                                                ->prefixIcon('heroicon-o-arrow-up-circle'),

                                            Select::make('owner_id')
                                                ->label('Gerente do Projeto')
                                                ->relationship('owner', 'name')
                                                ->searchable()
                                                ->preload()
                                                ->prefixIcon('heroicon-o-user')
                                                ->columnSpanFull(),
                                        ]),
                                ]),
                        ]),

                    Step::make('Cronograma e Financeiro')
                        ->icon('heroicon-o-chart-bar')
                        ->schema([
                            Section::make('Prazos e Progresso')
                                ->schema([
                                    Grid::make(3)
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
                                                ->minValue(0)
                                                ->maxValue(100)
                                                ->suffix('%')
                                                ->prefixIcon('heroicon-o-chart-pie'),
                                        ]),
                                ]),

                            Section::make('Custos e Orçamento')
                                ->schema([
                                    Grid::make(3)
                                        ->schema([
                                            TextInput::make('budget')
                                                ->label('Orçamento Total')
                                                ->numeric()
                                                ->prefix('R$')
                                                ->prefixIcon('heroicon-o-banknotes'),

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
                        ]),

                    Step::make('Escopo e Definições')
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            Section::make('Detalhamento')
                                ->schema([
                                    RichEditor::make('description')
                                        ->label('Descrição do Projeto')
                                        ->columnSpanFull(),

                                    RichEditor::make('objectives')
                                        ->label('Objetivos')
                                        ->columnSpanFull(),
                                ]),

                            Section::make('Especificações do Escopo')
                                ->icon('heroicon-o-clipboard-document-list')
                                ->collapsible()
                                ->collapsed()
                                ->schema([
                                    RichEditor::make('justification')
                                        ->label('Justificativa')
                                        ->columnSpanFull(),

                                    Grid::make(2)
                                        ->schema([
                                            RichEditor::make('scope')
                                                ->label('Escopo (In)'),
                                            RichEditor::make('out_of_scope')
                                                ->label('Fora do Escopo (Out)'),
                                        ]),
                                    
                                    Grid::make(2)
                                        ->schema([
                                            RichEditor::make('assumptions')
                                                ->label('Premissas'),
                                            RichEditor::make('constraints')
                                                ->label('Restrições'),
                                        ]),

                                    Grid::make(2)
                                        ->schema([
                                            RichEditor::make('success_criteria')
                                                ->label('Critérios de Sucesso'),
                                            RichEditor::make('main_risks')
                                                ->label('Principais Riscos'),
                                        ]),
                                ]),
                        ]),
                ])->columnSpanFull(),
            ]);
    }
}

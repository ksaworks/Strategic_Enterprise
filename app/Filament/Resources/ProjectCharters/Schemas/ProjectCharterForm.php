<?php

namespace App\Filament\Resources\ProjectCharters\Schemas;

use App\Enums\CharterStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;

class ProjectCharterForm
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
                                ->columns(2)
                                ->schema([
                                    TextInput::make('title')
                                        ->label('Título do Termo')
                                        ->required()
                                        ->maxLength(255)
                                        ->columnSpanFull(),

                                    Select::make('project_id')
                                        ->label('Projeto')
                                        ->relationship('project', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->required(),

                                    Select::make('created_by_id')
                                        ->label('Criado por')
                                        ->relationship('createdBy', 'name')
                                        ->default(fn () => auth()->id())
                                        ->required()
                                        ->searchable()
                                        ->preload(),

                                    TextInput::make('version')
                                        ->label('Versão')
                                        ->default('1.0')
                                        ->required(),

                                    Select::make('status')
                                        ->label('Status')
                                        ->options(CharterStatus::class)
                                        ->default(CharterStatus::DRAFT)
                                        ->disabled()
                                        ->dehydrated(),
                                ]),

                            Section::make('Objetivo e Escopo')
                                ->columns(1)
                                ->schema([
                                    RichEditor::make('objective')
                                        ->label('Objetivo do Projeto')
                                        ->placeholder('Descreva o objetivo principal do projeto...')
                                        ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList']),

                                    RichEditor::make('scope')
                                        ->label('Escopo')
                                        ->placeholder('O que está incluído no projeto...')
                                        ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList']),

                                    RichEditor::make('out_of_scope')
                                        ->label('Fora do Escopo')
                                        ->placeholder('O que NÃO está incluído no projeto...')
                                        ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList']),
                                ]),
                        ]),

                    Step::make('Escopo e Entregas')
                        ->icon('heroicon-o-clipboard-document-list')
                        ->schema([
                            Section::make('Entregas e Stakeholders')
                                ->columns(1)
                                ->schema([
                                    RichEditor::make('deliverables')
                                        ->label('Entregas Principais')
                                        ->placeholder('Liste as entregas esperadas do projeto...')
                                        ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList']),

                                    RichEditor::make('stakeholders')
                                        ->label('Partes Interessadas')
                                        ->placeholder('Liste as partes interessadas e seus papéis...')
                                        ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList']),
                                ]),
                        ]),

                    Step::make('Riscos e Premissas')
                        ->icon('heroicon-o-exclamation-triangle')
                        ->schema([
                            Section::make('Restrições e Premissas')
                                ->columns(1)
                                ->schema([
                                    RichEditor::make('constraints')
                                        ->label('Restrições')
                                        ->placeholder('Liste as restrições do projeto (orçamento, prazo, recursos)...')
                                        ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList']),

                                    RichEditor::make('assumptions')
                                        ->label('Premissas')
                                        ->placeholder('Liste as premissas assumidas para o projeto...')
                                        ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList']),
                                ]),

                            Section::make('Riscos e Orçamento')
                                ->columns(1)
                                ->schema([
                                    RichEditor::make('risks')
                                        ->label('Riscos Iniciais')
                                        ->placeholder('Identifique os riscos iniciais do projeto...')
                                        ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList']),

                                    RichEditor::make('budget_summary')
                                        ->label('Resumo Orçamentário')
                                        ->placeholder('Resumo do orçamento estimado...')
                                        ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList']),

                                    RichEditor::make('timeline_summary')
                                        ->label('Resumo do Cronograma')
                                        ->placeholder('Marcos principais e datas-chave...')
                                        ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList']),
                                ]),
                        ]),

                    Step::make('Aprovação')
                        ->icon('heroicon-o-check-badge')
                        ->schema([
                            Section::make('Aprovação')
                                ->columns(2)
                                ->hidden(fn ($record) => $record === null || $record->status === CharterStatus::DRAFT)
                                ->schema([
                                    Select::make('approved_by_id')
                                        ->label('Aprovado por')
                                        ->relationship('approvedBy', 'name')
                                        ->disabled(),

                                    DateTimePicker::make('approved_at')
                                        ->label('Data de Aprovação')
                                        ->disabled(),

                                    RichEditor::make('approval_justification')
                                        ->label('Justificativa')
                                        ->placeholder('Justificativa da aprovação ou rejeição...')
                                        ->columnSpanFull()
                                        ->disabled(),
                                ]),
                        ]),
                ])->columnSpanFull(),
            ]);
    }
}


<?php

namespace App\Filament\Resources\ProjectDemands\Schemas;

use App\Enums\Priority;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;

class ProjectDemandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Identificação')
                        ->icon('heroicon-o-identification')
                        ->schema([
                            Section::make('Dados da Demanda')
                                ->columns(2)
                                ->schema([
                                    TextInput::make('title')
                                        ->label('Título da Demanda')
                                        ->required()
                                        ->maxLength(255)
                                        ->columnSpanFull()
                                        ->prefixIcon('heroicon-o-pencil-square'),

                                    Select::make('requester_id')
                                        ->label('Solicitante')
                                        ->relationship('requester', 'name')
                                        ->default(auth()->id())
                                        ->disabled()
                                        ->dehydrated()
                                        ->prefixIcon('heroicon-o-user'),

                                    Select::make('priority')
                                        ->label('Prioridade')
                                        ->options(Priority::class)
                                        ->default(Priority::MEDIUM)
                                        ->required()
                                        ->prefixIcon('heroicon-o-exclamation-triangle'),

                                    TextInput::make('status')
                                        ->label('Status')
                                        ->default('draft')
                                        ->disabled()
                                        ->dehydrated()
                                        ->prefixIcon('heroicon-o-tag'),
                                ]),
                        ]),

                    Step::make('Descrição')
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            Section::make('Conteúdo')
                                ->schema([
                                    \Filament\Forms\Components\RichEditor::make('description')
                                        ->label('Descrição da Demanda')
                                        ->required()
                                        ->columnSpanFull(),
                                ]),
                        ]),

                    Step::make('Justificativa')
                        ->icon('heroicon-o-chat-bubble-bottom-center-text')
                        ->schema([
                            Section::make('Argumentação')
                                ->schema([
                                    Textarea::make('justification')
                                        ->label('Justificativa')
                                        ->placeholder('Pq esta demanda é importante?')
                                        ->columnSpanFull()
                                        ->rows(5),
                                    
                                    Select::make('project_id')
                                        ->label('Projeto Gerado')
                                        ->relationship('project', 'name')
                                        ->disabled()
                                        ->visible(fn ($record) => $record?->project_id !== null)
                                        ->prefixIcon('heroicon-o-briefcase'),
                                ]),
                        ]),
                ])->columnSpanFull(),
            ]);
    }
}

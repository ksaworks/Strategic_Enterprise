<?php

namespace App\Filament\Resources\LessonLearneds\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class LessonLearnedForm
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
                                    
                                    Select::make('reported_by_id')
                                        ->label('Reportado Por')
                                        ->relationship('reporter', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->required()
                                        ->prefixIcon('heroicon-o-user'),
                                ]),
                        ]),

                    Step::make('Detalhes')
                        ->icon('heroicon-o-tag')
                        ->schema([
                            Section::make('Classificação')
                                ->columns(2)
                                ->schema([
                                    Select::make('category')
                                        ->label('Categoria')
                                        ->options(\App\Models\LessonLearned::getCategories())
                                        ->required()
                                        ->searchable()
                                        ->prefixIcon('heroicon-o-folder'),
                                    Select::make('type')
                                        ->label('Tipo')
                                        ->options([
                                            'positive' => 'Positiva (Sucesso)',
                                            'negative' => 'Negativa (Problema)',
                                        ])
                                        ->required()
                                        ->prefixIcon('heroicon-o-check-circle'),
                                    \Filament\Forms\Components\TagsInput::make('tags')
                                        ->label('Tags / Palavras-chave')
                                        ->columnSpanFull()
                                        ->prefixIcon('heroicon-o-hashtag'),
                                ]),
                        ]),

                    Step::make('Conteúdo')
                        ->icon('heroicon-o-pencil-square')
                        ->schema([
                            Section::make('Descrição Completa')
                                ->schema([
                                    \Filament\Forms\Components\RichEditor::make('description')
                                        ->label('Descrição da Situação')
                                        ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList']),
                                    Textarea::make('impact')
                                        ->label('Impacto Causado')
                                        ->rows(3),
                                    Textarea::make('recommendation')
                                        ->label('Recomendação para o Futuro')
                                        ->rows(3),
                                ]),
                        ]),
                ])->columnSpanFull(),
            ]);
    }
}

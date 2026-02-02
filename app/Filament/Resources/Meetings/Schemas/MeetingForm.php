<?php

namespace App\Filament\Resources\Meetings\Schemas;

use App\Enums\MeetingStatus;
use App\Enums\MeetingType;
use App\Models\Project;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;

class MeetingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Informações Gerais')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Section::make('Identificação')
                                ->columns(2)
                                ->schema([
                                    TextInput::make('title')
                                        ->label('Título')
                                        ->required()
                                        ->maxLength(255)
                                        ->columnSpanFull()
                                        ->prefixIcon('heroicon-o-pencil-square'),

                                    Select::make('project_id')
                                        ->label('Projeto')
                                        ->relationship('project', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->prefixIcon('heroicon-o-briefcase'),

                                    Select::make('organizer_id')
                                        ->label('Organizador')
                                        ->relationship('organizer', 'name')
                                        ->default(fn () => auth()->id())
                                        ->required()
                                        ->searchable()
                                        ->preload()
                                        ->prefixIcon('heroicon-o-user'),

                                    Select::make('type')
                                        ->label('Tipo')
                                        ->options(MeetingType::class)
                                        ->default(MeetingType::OTHER)
                                        ->required()
                                        ->prefixIcon('heroicon-o-tag'),

                                    Select::make('status')
                                        ->label('Status')
                                        ->options(MeetingStatus::class)
                                        ->default(MeetingStatus::SCHEDULED)
                                        ->required()
                                        ->prefixIcon('heroicon-o-check-circle'),
                                ]),
                        ]),

                    Step::make('Agendamento')
                        ->icon('heroicon-o-calendar-days')
                        ->schema([
                            Section::make('Horário e Local')
                                ->columns(3)
                                ->schema([
                                    DateTimePicker::make('start_time')
                                        ->label('Início')
                                        ->required()
                                        ->native(false)
                                        ->prefixIcon('heroicon-o-clock'),

                                    DateTimePicker::make('end_time')
                                        ->label('Término')
                                        ->native(false)
                                        ->prefixIcon('heroicon-o-clock'),

                                    TextInput::make('location')
                                        ->label('Local')
                                        ->placeholder('Sala de Reuniões / Link Zoom / etc')
                                        ->prefixIcon('heroicon-o-map-pin'),
                                ]),
                        ]),

                    Step::make('Pauta e Ata')
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            Section::make('Discussão')
                                ->schema([
                                    Textarea::make('description')
                                        ->label('Pauta (Agenda)')
                                        ->placeholder('Descreva os tópicos a serem discutidos...')
                                        ->rows(4)
                                        ->columnSpanFull(),

                                    RichEditor::make('minutes')
                                        ->label('Ata da Reunião')
                                        ->placeholder('Registre os pontos discutidos, decisões tomadas e responsabilidades definidas.')
                                        ->toolbarButtons([
                                            'bold',
                                            'italic',
                                            'underline',
                                            'strike',
                                            'bulletList',
                                            'orderedList',
                                            'h2',
                                            'h3',
                                            'blockquote',
                                            'redo',
                                            'undo',
                                        ])
                                        ->columnSpanFull(),
                                ]),
                        ]),
                ])->columnSpanFull(),
            ]);
    }
}

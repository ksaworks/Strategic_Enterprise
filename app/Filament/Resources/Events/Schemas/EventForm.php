<?php

namespace App\Filament\Resources\Events\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informações do Evento')
                    ->icon(Heroicon::OutlinedCalendarDays)
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('title')
                                ->label('Título')
                                ->prefixIcon('heroicon-o-document-text')
                                ->required()
                                ->maxLength(255),
                            Select::make('status')
                                ->label('Status')
                                ->prefixIcon('heroicon-o-flag')
                                ->options([
                                    'scheduled' => 'Agendado',
                                    'completed' => 'Concluído',
                                    'cancelled' => 'Cancelado',
                                ])
                                ->default('scheduled')
                                ->required(),
                        ]),
                        Textarea::make('description')
                            ->label('Descrição')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Section::make('Data e Hora')
                    ->icon(Heroicon::OutlinedClock)
                    ->schema([
                        Grid::make(3)->schema([
                            DateTimePicker::make('start_datetime')
                                ->label('Início')
                                ->prefixIcon('heroicon-o-calendar')
                                ->required()
                                ->native(false),
                            DateTimePicker::make('end_datetime')
                                ->label('Término')
                                ->prefixIcon('heroicon-o-calendar')
                                ->native(false),
                            Toggle::make('all_day')
                                ->label('Dia Inteiro')
                                ->inline(false),
                        ]),
                        TextInput::make('location')
                            ->label('Local')
                            ->prefixIcon('heroicon-o-map-pin')
                            ->maxLength(255),
                    ]),

                Section::make('Vínculos')
                    ->icon(Heroicon::OutlinedLink)
                    ->columns(3)
                    ->schema([
                        Select::make('project_id')
                            ->label('Projeto')
                            ->relationship('project', 'name')
                            ->prefixIcon('heroicon-o-briefcase')
                            ->searchable()
                            ->preload(),
                        Select::make('contact_id')
                            ->label('Contato')
                            ->relationship('contact', 'first_name')
                            ->prefixIcon('heroicon-o-user')
                            ->searchable()
                            ->preload(),
                        Select::make('user_id')
                            ->label('Responsável')
                            ->relationship('user', 'name')
                            ->prefixIcon('heroicon-o-user-circle')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->default(auth()->id()),
                    ]),

                Section::make('Aparência')
                    ->icon(Heroicon::OutlinedSwatch)
                    ->collapsed()
                    ->schema([
                        ColorPicker::make('color')
                            ->label('Cor do Evento')
                            ->default('#d58f05'),
                    ]),
            ]);
    }
}

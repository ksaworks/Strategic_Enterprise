<?php

namespace App\Filament\Resources\Timesheets\Schemas;

use App\Models\Timesheet;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;

class TimesheetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Registro de Horas')
                        ->icon('heroicon-o-clock')
                        ->schema([
                            Section::make('Lançamento')
                                ->description('Informe os detalhes das horas trabalhadas')
                                ->schema([
                                    Grid::make(3)
                                        ->schema([
                                            Select::make('user_id')
                                                ->label('Colaborador')
                                                ->relationship('user', 'name')
                                                ->default(auth()->id())
                                                ->required()
                                                ->searchable()
                                                ->preload()
                                                ->prefixIcon('heroicon-o-user'),

                                            Select::make('task_id')
                                                ->label('Tarefa')
                                                ->relationship('task', 'name')
                                                ->required()
                                                ->searchable()
                                                ->preload()
                                                ->prefixIcon('heroicon-o-check-circle'),

                                            DatePicker::make('work_date')
                                                ->label('Data')
                                                ->default(now())
                                                ->required()
                                                ->prefixIcon('heroicon-o-calendar'),
                                        ]),

                                    Grid::make(3)
                                        ->schema([
                                            TextInput::make('hours')
                                                ->label('Horas Trabalhadas')
                                                ->numeric()
                                                ->step(0.5)
                                                ->minValue(0.5)
                                                ->maxValue(24)
                                                ->required()
                                                ->suffix('h')
                                                ->prefixIcon('heroicon-o-identification'),

                                            TimePicker::make('start_time')
                                                ->label('Hora Início')
                                                ->prefixIcon('heroicon-o-play'),

                                            TimePicker::make('end_time')
                                                ->label('Hora Fim')
                                                ->prefixIcon('heroicon-o-stop'),
                                        ]),

                                    Textarea::make('description')
                                        ->label('Descrição do Trabalho')
                                        ->placeholder('Descreva as atividades realizadas...')
                                        ->rows(3)
                                        ->columnSpanFull(),
                                ]),
                        ]),

                    Step::make('Aprovação e Financeiro')
                        ->icon('heroicon-o-banknotes')
                        ->schema([
                            Section::make('Valores e Status')
                                ->schema([
                                    Grid::make(2)
                                        ->schema([
                                            TextInput::make('hourly_rate')
                                                ->label('Valor/Hora')
                                                ->numeric()
                                                ->prefix('R$')
                                                ->helperText('Deixe em branco para usar o valor padrão do colaborador')
                                                ->prefixIcon('heroicon-o-currency-dollar'),

                                            Select::make('status')
                                                ->label('Status')
                                                ->options(Timesheet::availableStatuses())
                                                ->default('pending')
                                                ->required()
                                                ->disabled(fn () => !auth()->user()?->can('approve_timesheets'))
                                                ->prefixIcon('heroicon-o-tag'),
                                        ]),
                                ]),
                        ]),
                ])->columnSpanFull(),
            ]);
    }
}


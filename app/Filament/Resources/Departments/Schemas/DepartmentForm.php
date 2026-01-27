<?php

namespace App\Filament\Resources\Departments\Schemas;

use Filament\Schemas\Schema;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class DepartmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detalhes do Departamento')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('company_id')
                                    ->label('Empresa')
                                    ->relationship('company', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->prefixIcon('heroicon-o-building-office')
                                    ->required(),

                                Select::make('parent_id')
                                    ->label('Departamento Superior')
                                    ->relationship('parent', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->prefixIcon('heroicon-o-building-office-2'),

                                TextInput::make('name')
                                    ->label('Nome do Departamento')
                                    ->required()
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-squares-2x2'),

                                TextInput::make('code')
                                    ->label('Código Interno')
                                    ->maxLength(50)
                                    ->prefixIcon('heroicon-o-qr-code'),

                                Select::make('manager_id')
                                    ->label('Gerente Responsável')
                                    ->relationship('manager', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->prefixIcon('heroicon-o-user-circle')
                                    ->columnSpan(2),
                            ]),
                    ]),

                Toggle::make('is_active')
                    ->label('Ativo')
                    ->default(true)
                    ->onColor('success')
                    ->offColor('danger'),
            ]);
    }
}

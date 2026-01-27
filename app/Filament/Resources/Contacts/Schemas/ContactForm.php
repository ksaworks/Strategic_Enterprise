<?php

namespace App\Filament\Resources\Contacts\Schemas;

use Filament\Schemas\Schema;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class ContactForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Dados Pessoais')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('first_name')
                                    ->label('Nome')
                                    ->required()
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-user'),

                                TextInput::make('last_name')
                                    ->label('Sobrenome')
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-user'),

                                Select::make('user_id')
                                    ->label('Usuário de Sistema')
                                    ->relationship('user', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->prefixIcon('heroicon-o-user-circle')
                                    ->helperText('Relacionar este contato a um login de usuário.'),
                            ]),

                        Grid::make(3)
                            ->schema([
                                TextInput::make('email')
                                    ->email()
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-envelope'),
                                TextInput::make('phone')
                                    ->label('Telefone')
                                    ->tel()
                                    ->mask('(99) 9999-9999')
                                    ->prefixIcon('heroicon-o-phone'),
                                TextInput::make('mobile')
                                    ->label('Celular')
                                    ->tel()
                                    ->mask('(99) 99999-9999')
                                    ->prefixIcon('heroicon-o-device-phone-mobile'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('cpf')
                                    ->label('CPF')
                                    ->mask('999.999.999-99')
                                    ->prefixIcon('heroicon-o-identification'),
                                TextInput::make('job_title')
                                    ->label('Cargo/Função')
                                    ->prefixIcon('heroicon-o-briefcase'),
                            ]),
                    ]),

                Section::make('Vinculação Corporativa')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('company_id')
                                    ->label('Empresa')
                                    ->relationship('company', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->prefixIcon('heroicon-o-building-office'),

                                Select::make('department_id')
                                    ->label('Departamento')
                                    ->relationship('department', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->prefixIcon('heroicon-o-building-office-2'),
                            ]),
                    ])->collapsible(),

                Section::make('Endereço')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('zip_code')
                                    ->label('CEP')
                                    ->mask('99.999-999')
                                    ->prefixIcon('heroicon-o-map-pin'),
                                TextInput::make('address')
                                    ->label('Endereço Completo')
                                    ->columnSpan(2)
                                    ->prefixIcon('heroicon-o-map'),
                                TextInput::make('city')
                                    ->label('Cidade')
                                    ->prefixIcon('heroicon-o-building-library'),
                                TextInput::make('state')
                                    ->label('UF')
                                    ->maxLength(2)
                                    ->prefixIcon('heroicon-o-map'),
                            ]),
                    ])->collapsible(),

                Toggle::make('is_active')
                    ->label('Ativo')
                    ->default(true)
                    ->onColor('success')
                    ->offColor('danger'),
            ]);
    }
}

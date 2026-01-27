<?php

namespace App\Filament\Resources\Companies\Schemas;

use Filament\Schemas\Schema;

use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class CompanyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Dados Principais')
                    ->description('Informações básicas da empresa')
                    ->icon('heroicon-o-building-office')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Razão Social / Nome')
                                    ->required()
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-building-office')
                                    ->columnSpan(2),

                                TextInput::make('cnpj')
                                    ->label('CNPJ')
                                    ->mask('99.999.999/9999-99')
                                    ->maxLength(18)
                                    ->prefixIcon('heroicon-o-identification'),

                                Select::make('parent_id')
                                    ->label('Matriz')
                                    ->relationship('parent', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->prefixIcon('heroicon-o-building-office-2')
                                    ->columnSpan(3),
                            ]),
                    ]),

                Section::make('Contato')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('email')
                                    ->email()
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-envelope'),
                                TextInput::make('website')
                                    ->url()
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-globe-alt'),
                                TextInput::make('phone_1')
                                    ->tel()
                                    ->mask('(99) 99999-9999')
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-phone'),
                                TextInput::make('phone_2')
                                    ->tel()
                                    ->mask('(99) 99999-9999')
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-device-phone-mobile'),
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
                                TextInput::make('address_1')
                                    ->label('Endereço')
                                    ->columnSpan(2)
                                    ->prefixIcon('heroicon-o-map'),
                                TextInput::make('city')
                                    ->label('Cidade')
                                    ->prefixIcon('heroicon-o-building-library'),
                                Select::make('state')
                                    ->label('Estado')
                                    ->options([
                                        'AC' => 'Acre',
                                        'AL' => 'Alagoas',
                                        'AP' => 'Amapá',
                                        'AM' => 'Amazonas',
                                        'BA' => 'Bahia',
                                        'CE' => 'Ceará',
                                        'DF' => 'Distrito Federal',
                                        'ES' => 'Espírito Santo',
                                        'GO' => 'Goiás',
                                        'MA' => 'Maranhão',
                                        'MT' => 'Mato Grosso',
                                        'MS' => 'Mato Grosso do Sul',
                                        'MG' => 'Minas Gerais',
                                        'PA' => 'Pará',
                                        'PB' => 'Paraíba',
                                        'PR' => 'Paraná',
                                        'PE' => 'Pernambuco',
                                        'PI' => 'Piauí',
                                        'RJ' => 'Rio de Janeiro',
                                        'RN' => 'Rio Grande do Norte',
                                        'RS' => 'Rio Grande do Sul',
                                        'RO' => 'Rondônia',
                                        'RR' => 'Roraima',
                                        'SC' => 'Santa Catarina',
                                        'SP' => 'São Paulo',
                                        'SE' => 'Sergipe',
                                        'TO' => 'Tocantins',
                                    ])
                                    ->searchable()
                                    ->native(false)
                                    ->prefixIcon('heroicon-o-map')
                                    ->required(),
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

<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Schema;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Dados do Usuário')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nome')
                                    ->required()
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-user'),

                                TextInput::make('email')
                                    ->label('E-mail')
                                    ->email()
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->prefixIcon('heroicon-o-envelope'),

                                TextInput::make('password')
                                    ->label('Senha')
                                    ->password()
                                    ->required(fn(string $operation): bool => $operation === 'create')
                                    ->dehydrated(fn(?string $state): bool => filled($state))
                                    ->confirmed()
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-lock-closed'),

                                TextInput::make('password_confirmation')
                                    ->label('Confirmar Senha')
                                    ->password()
                                    ->dehydrated(false),
                            ]),
                    ]),

                Toggle::make('is_active')
                    ->label('Ativo')
                    ->default(true),
            ]);
    }
}

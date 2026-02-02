<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UsefulLinkResource\Pages;
use App\Models\UsefulLink;
use BackedEnum;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UsefulLinkResource extends Resource
{
    protected static ?string $model = UsefulLink::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-link';

    protected static string|\UnitEnum|null $navigationGroup = 'Recursos';

    public static function getModelLabel(): string
    {
        return 'Link Útil';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Links Úteis';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('title')
                    ->label('Título')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('url')
                    ->label('URL')
                    ->url()
                    ->required()
                    ->maxLength(255)
                ,
                Forms\Components\Textarea::make('description')
                    ->label('Descrição')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('category')
                    ->label('Categoria')
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_active')
                    ->label('Ativo')
                    ->required()
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Título')
                    ->searchable(),
                Tables\Columns\TextColumn::make('url')
                    ->label('URL')
                    ->limit(50)
                    ->url(fn($record) => $record->url)
                    ->openUrlInNewTab()
                    ->searchable(),
                Tables\Columns\TextColumn::make('category')
                    ->label('Categoria')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsefulLinks::route('/'),
            'create' => Pages\CreateUsefulLink::route('/create'),
            'edit' => Pages\EditUsefulLink::route('/{record}/edit'),
        ];
    }
}

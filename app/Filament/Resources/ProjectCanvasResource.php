<?php

namespace App\Filament\Resources;

use App\Enums\ProjectCanvasSection;
use App\Filament\Resources\ProjectCanvasResource\Pages;
use App\Models\ProjectCanvas;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables;

class ProjectCanvasResource extends Resource
{
    protected static ?string $model = ProjectCanvas::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-presentation-chart-line';
    
    protected static string|\UnitEnum|null $navigationGroup = 'Gestão de Projetos';
    
    protected static ?string $modelLabel = 'Business Canvas';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('name')
                    ->label('Nome do Projeto/Iniciativa')
                    ->required()
                    ->columnSpanFull(),
                
                Textarea::make('description')
                    ->label('Descrição')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'converted' => 'success',
                        default => 'gray',
                    }),
                    
                TextColumn::make('owner.name')
                    ->label('Criador')
                    ->sortable(),
                    
                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y'),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => Pages\ListProjectCanvases::route('/'),
            'create' => Pages\CreateProjectCanvas::route('/create'),
            'edit' => Pages\EditProjectCanvas::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources\Tasks\RelationManagers;

use App\Models\ProjectCostItem;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CostItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'costItems';

    protected static ?string $title = 'Planilha de Custos';

    protected static ?string $modelLabel = 'Item de Custo';

    protected static ?string $pluralModelLabel = 'Itens de Custo';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nome')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ex: Metros de concreto'),

                        Select::make('category')
                            ->label('Categoria')
                            ->options(ProjectCostItem::availableCategories())
                            ->default('material')
                            ->required(),
                    ]),

                Grid::make(3)
                    ->schema([
                        TextInput::make('unit')
                            ->label('Unidade')
                            ->placeholder('m², un, kg, h...'),

                        TextInput::make('quantity')
                            ->label('Quantidade')
                            ->numeric()
                            ->default(1)
                            ->required(),

                        TextInput::make('unit_price')
                            ->label('Preço Unitário')
                            ->numeric()
                            ->prefix('R$')
                            ->required(),
                    ]),

                Textarea::make('description')
                    ->label('Descrição')
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Item')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category')
                    ->label('Categoria')
                    ->badge()
                    ->formatStateUsing(fn (ProjectCostItem $record) => $record->category_label)
                    ->color('gray'),

                TextColumn::make('quantity')
                    ->label('Qtd')
                    ->numeric()
                    ->suffix(fn (ProjectCostItem $record) => ' ' . ($record->unit ?? 'un')),

                TextColumn::make('unit_price')
                    ->label('Preço Unit.')
                    ->money('BRL'),

                TextColumn::make('total_price')
                    ->label('Total')
                    ->money('BRL')
                    ->sortable()
                    ->summarize(Sum::make()->money('BRL')->label('Total')),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]));
    }
}


<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use App\Models\ProjectExpense;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExpensesRelationManager extends RelationManager
{
    protected static string $relationship = 'expenses';

    protected static ?string $title = 'Despesas';

    protected static ?string $modelLabel = 'Despesa';

    protected static ?string $pluralModelLabel = 'Despesas';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->schema([
                        TextInput::make('description')
                            ->label('Descrição')
                            ->required()
                            ->maxLength(255),

                        Select::make('category')
                            ->label('Categoria')
                            ->options([
                                'labor' => 'Mão de Obra',
                                'material' => 'Material',
                                'service' => 'Serviço',
                                'equipment' => 'Equipamento',
                                'other' => 'Outro',
                            ])
                            ->default('material')
                            ->required(),
                    ]),

                Grid::make(3)
                    ->schema([
                        Select::make('task_id')
                            ->label('Tarefa')
                            ->relationship('task', 'name')
                            ->searchable()
                            ->preload(),

                        TextInput::make('amount')
                            ->label('Valor')
                            ->numeric()
                            ->prefix('R$')
                            ->required(),

                        DatePicker::make('expense_date')
                            ->label('Data da Despesa')
                            ->default(now())
                            ->required(),
                    ]),

                Textarea::make('notes')
                    ->label('Observações')
                    ->rows(2)
                    ->columnSpanFull(),

                FileUpload::make('receipt_path')
                    ->label('Comprovante')
                    ->directory('expense-receipts')
                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->columns([
                TextColumn::make('expense_date')
                    ->label('Data')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('description')
                    ->label('Descrição')
                    ->searchable()
                    ->limit(30),

                TextColumn::make('task.name')
                    ->label('Tarefa')
                    ->limit(20)
                    ->toggleable(),

                TextColumn::make('category')
                    ->label('Categoria')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match($state) {
                        'labor' => 'Mão de Obra',
                        'material' => 'Material',
                        'service' => 'Serviço',
                        'equipment' => 'Equipamento',
                        default => 'Outro',
                    })
                    ->color('gray'),

                TextColumn::make('amount')
                    ->label('Valor')
                    ->money('BRL')
                    ->sortable()
                    ->summarize(Sum::make()->money('BRL')->label('Total')),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (ProjectExpense $record) => $record->status_label)
                    ->color(fn (string $state) => match($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('createdBy.name')
                    ->label('Criado por')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('expense_date', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(ProjectExpense::availableStatuses()),

                SelectFilter::make('category')
                    ->label('Categoria')
                    ->options([
                        'labor' => 'Mão de Obra',
                        'material' => 'Material',
                        'service' => 'Serviço',
                        'equipment' => 'Equipamento',
                        'other' => 'Outro',
                    ]),

                TrashedFilter::make(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['created_by_id'] = auth()->id();
                        return $data;
                    }),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Aprovar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (ProjectExpense $record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->action(fn (ProjectExpense $record) => $record->update([
                        'status' => 'approved',
                        'approved_by_id' => auth()->id(),
                        'approved_at' => now(),
                    ])),

                Action::make('reject')
                    ->label('Rejeitar')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (ProjectExpense $record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->action(fn (ProjectExpense $record) => $record->update([
                        'status' => 'rejected',
                        'approved_by_id' => auth()->id(),
                        'approved_at' => now(),
                    ])),

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

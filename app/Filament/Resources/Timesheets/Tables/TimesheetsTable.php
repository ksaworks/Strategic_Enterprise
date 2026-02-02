<?php

namespace App\Filament\Resources\Timesheets\Tables;

use App\Models\Timesheet;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class TimesheetsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('work_date')
                    ->label('Data')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Colaborador')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('task.name')
                    ->label('Tarefa')
                    ->limit(30)
                    ->searchable(),

                TextColumn::make('hours')
                    ->label('Horas')
                    ->numeric()
                    ->suffix('h')
                    ->sortable(),

                TextColumn::make('labor_cost')
                    ->label('Custo M.O.')
                    ->money('BRL')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (Timesheet $record) => $record->status_label)
                    ->color(fn (Timesheet $record) => $record->status_color),

                TextColumn::make('approvedBy.name')
                    ->label('Aprovado por')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('approved_at')
                    ->label('Aprovado em')
                    ->dateTime('d/m/Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('work_date', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(Timesheet::availableStatuses()),

                SelectFilter::make('user_id')
                    ->label('Colaborador')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),

                TrashedFilter::make(),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Aprovar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Timesheet $record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->action(fn (Timesheet $record) => $record->update([
                        'status' => 'approved',
                        'approved_by_id' => auth()->id(),
                        'approved_at' => now(),
                    ])),

                Action::make('reject')
                    ->label('Rejeitar')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Timesheet $record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->action(fn (Timesheet $record) => $record->update([
                        'status' => 'rejected',
                        'approved_by_id' => auth()->id(),
                        'approved_at' => now(),
                    ])),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}


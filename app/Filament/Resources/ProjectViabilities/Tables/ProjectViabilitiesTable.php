<?php

namespace App\Filament\Resources\ProjectViabilities\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ProjectViabilitiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('project.name')
                    ->label('Projeto')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('financial_return')
                    ->label('ROI Estimado')
                    ->money('BRL')
                    ->sortable(),
                TextColumn::make('payback_period')
                    ->label('Payback')
                    ->suffix(' meses')
                    ->sortable(),
                TextColumn::make('score')
                    ->label('Score')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        $state >= 80 => 'success',
                        $state >= 50 => 'warning',
                        default => 'danger',
                    }),
                TextColumn::make('decision')
                    ->label('Decisão')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'approved' => 'Aprovado',
                        'rejected' => 'Rejeitado',
                        'on_hold' => 'Em Análise',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'on_hold' => 'warning',
                        default => 'gray',
                    })
                    ->searchable(),
                TextColumn::make('analyzer.name')
                    ->label('Analisado Por')
                    ->sortable(),
                TextColumn::make('analyzed_at')
                    ->label('Data Análise')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
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

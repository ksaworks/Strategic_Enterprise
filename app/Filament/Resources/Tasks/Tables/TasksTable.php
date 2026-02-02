<?php

namespace App\Filament\Resources\Tasks\Tables;

use App\Enums\Priority;
use App\Enums\TaskStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Support\Colors\Color;

class TasksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('project.name')
                    ->label('Projeto')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('name')
                    ->label('Tarefa')
                    ->searchable()
                    ->sortable()
                    ->description(fn($record): ?string => $record->parent?->name),
                TextColumn::make('owner.name')
                    ->label('Responsável')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn($state): string => $state instanceof TaskStatus ? $state->getLabel() : match ((int) $state) {
                        0 => 'A Fazer',
                        1 => 'Em Andamento',
                        2 => 'Aguardando',
                        3 => 'Concluída',
                        4 => 'Cancelada',
                        default => 'Desconhecido',
                    })
                    ->color(fn($state): string => $state instanceof TaskStatus ? $state->getColor() : match ((int) $state) {
                        0 => 'gray',
                        1 => 'info',
                        2 => 'warning',
                        3 => 'success',
                        4 => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('priority')
                    ->label('Prioridade')
                    ->badge()
                    ->formatStateUsing(fn($state): string => $state instanceof Priority ? $state->getLabel() : match ((int) $state) {
                        0 => 'Baixa',
                        5 => 'Média',
                        9 => 'Alta',
                        default => 'Média',
                    })
                    ->color(fn($state): string => $state instanceof Priority ? $state->getColor() : match ((int) $state) {
                        0 => 'gray',
                        5 => 'warning',
                        9 => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('progress')
                    ->label('Progresso')
                    ->numeric()
                    ->suffix('%')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('end_date')
                    ->label('Prazo')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('project_id')
                    ->label('Projeto')
                    ->relationship('project', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('status')
                    ->options(TaskStatus::toSelectArray()),
                SelectFilter::make('priority')
                    ->label('Prioridade')
                    ->options(Priority::toSelectArray()),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultPaginationPageOption(25);
    }
}

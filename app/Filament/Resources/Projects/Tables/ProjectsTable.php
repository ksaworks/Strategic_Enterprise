<?php

namespace App\Filament\Resources\Projects\Tables;

use App\Enums\ProjectStatus;
use App\Enums\ProjectType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Support\Colors\Color;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn($state): string => $state instanceof ProjectType ? $state->getLabel() : 'Projeto')
                    ->color(fn($state): string => $state instanceof ProjectType ? $state->getColor() : 'info')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('company.name')
                    ->label('Empresa')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('name')
                    ->label('Projeto')
                    ->searchable()
                    ->sortable()
                    ->description(fn($record): ?string => $record->parent?->name),
                TextColumn::make('owner.name')
                    ->label('Gerente')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn($state): string => $state instanceof ProjectStatus ? $state->getLabel() : match ((int) $state) {
                        0 => 'Não Iniciado',
                        1 => 'Em Andamento',
                        2 => 'Em Pausa',
                        3 => 'Concluído',
                        4 => 'Cancelado',
                        default => 'Desconhecido',
                    })
                    ->color(fn($state): string => $state instanceof ProjectStatus ? $state->getColor() : match ((int) $state) {
                        0 => 'gray',
                        1 => 'info',
                        2 => 'warning',
                        3 => 'success',
                        4 => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('progress')
                    ->label('Progresso')
                    ->numeric()
                    ->suffix('%')
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label('Prazo')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('company_id')
                    ->label('Empresa')
                    ->relationship('company', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('status')
                    ->options(ProjectStatus::toSelectArray()),
                SelectFilter::make('type')
                    ->label('Tipo')
                    ->options(ProjectType::toSelectArray()),
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

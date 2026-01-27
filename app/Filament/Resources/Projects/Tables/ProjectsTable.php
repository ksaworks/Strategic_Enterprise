<?php

namespace App\Filament\Resources\Projects\Tables;

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
                TextColumn::make('company.name')
                    ->label('Empresa')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('name')
                    ->label('Projeto')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('owner.name')
                    ->label('Gerente')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn(int $state): string => match ($state) {
                        0 => 'Não Iniciado',
                        1 => 'Em Andamento',
                        2 => 'Em Pausa',
                        3 => 'Concluído',
                        4 => 'Cancelado',
                        default => 'Desconhecido',
                    })
                    ->color(fn(int $state): string => match ($state) {
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
                    ->options([
                        0 => 'Não Iniciado',
                        1 => 'Em Andamento',
                        2 => 'Em Pausa',
                        3 => 'Concluído',
                        4 => 'Cancelado',
                    ]),
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

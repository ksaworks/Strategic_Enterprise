<?php

namespace App\Filament\Resources\ProjectRisks\Tables;

use App\Enums\RiskStatus;
use App\Models\ProjectRisk;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ProjectRisksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('project.name')
                    ->label('Projeto')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Risco')
                    ->searchable()
                    ->limit(40),

                TextColumn::make('category')
                    ->label('Categoria')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ProjectRisk::availableCategories()[$state] ?? $state)
                    ->color('gray'),

                TextColumn::make('probability')
                    ->label('Probabilidade')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state?->getLabel())
                    ->color(fn ($state) => $state?->getColor()),

                TextColumn::make('impact')
                    ->label('Impacto')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state?->getLabel())
                    ->color(fn ($state) => $state?->getColor()),

                TextColumn::make('score')
                    ->label('Score')
                    ->badge()
                    ->color(fn (ProjectRisk $record): string => $record->risk_level_color)
                    ->formatStateUsing(fn (ProjectRisk $record): string => "{$record->score} ({$record->risk_level_label})")
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable(),

                TextColumn::make('owner.name')
                    ->label('Responsável')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('identified_at')
                    ->label('Identificado em')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(RiskStatus::class),

                SelectFilter::make('category')
                    ->label('Categoria')
                    ->options(ProjectRisk::availableCategories()),

                TrashedFilter::make(),
            ])
            ->defaultSort('created_at', 'desc')
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


<?php

namespace App\Filament\Resources\ProjectCharters\Tables;

use App\Enums\CharterStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ProjectChartersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('project.name')
                    ->label('Projeto')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->limit(40),

                TextColumn::make('version')
                    ->label('Versão')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable(),

                TextColumn::make('createdBy.name')
                    ->label('Criado por')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('approvedBy.name')
                    ->label('Aprovado por')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('approved_at')
                    ->label('Data Aprovação')
                    ->dateTime('d/m/Y H:i')
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
                    ->options(CharterStatus::class),

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


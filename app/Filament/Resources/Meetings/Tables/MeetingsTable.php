<?php

namespace App\Filament\Resources\Meetings\Tables;

use App\Enums\MeetingStatus;
use App\Enums\MeetingType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class MeetingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('start_time')
                    ->label('Data/Hora')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->limit(40),

                TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable(),

                TextColumn::make('project.name')
                    ->label('Projeto')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('organizer.name')
                    ->label('Organizador')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('location')
                    ->label('Local')
                    ->limit(20)
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
                    ->options(MeetingStatus::class),

                SelectFilter::make('type')
                    ->label('Tipo')
                    ->options(MeetingType::class),

                TrashedFilter::make(),
            ])
            ->defaultSort('start_time', 'desc')
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


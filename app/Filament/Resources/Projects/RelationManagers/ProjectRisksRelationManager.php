<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use App\Enums\RiskImpact;
use App\Enums\RiskProbability;
use App\Enums\RiskStatus;
use App\Models\ProjectRisk;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;

class ProjectRisksRelationManager extends RelationManager
{
    protected static string $relationship = 'risks';

    protected static ?string $title = 'Riscos do Projeto';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identificação do Risco')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nome do Risco')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull()
                            ->prefixIcon('heroicon-o-exclamation-triangle'),

                        Grid::make(2)
                            ->schema([
                                Select::make('category')
                                    ->label('Categoria')
                                    ->options(ProjectRisk::availableCategories())
                                    ->default('technical')
                                    ->required(),

                                Select::make('status')
                                    ->label('Status')
                                    ->options(RiskStatus::toSelectArray())
                                    ->default('identified')
                                    ->required(),
                            ]),

                        Textarea::make('description')
                            ->label('Descrição')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Section::make('Classificação')
                    ->description('Defina probabilidade e impacto para calcular o score de risco')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Select::make('probability')
                                    ->label('Probabilidade')
                                    ->options(RiskProbability::toSelectArray())
                                    ->default(3)
                                    ->required()
                                    ->helperText('Chance de ocorrer'),

                                Select::make('impact')
                                    ->label('Impacto')
                                    ->options(RiskImpact::toSelectArray())
                                    ->default(3)
                                    ->required()
                                    ->helperText('Gravidade se ocorrer'),

                                Select::make('owner_id')
                                    ->label('Responsável')
                                    ->relationship('owner', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->prefixIcon('heroicon-o-user'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                DatePicker::make('identified_at')
                                    ->label('Data de Identificação')
                                    ->default(now()),

                                DatePicker::make('occurred_at')
                                    ->label('Data de Ocorrência')
                                    ->helperText('Preencher apenas se o risco ocorreu'),
                            ]),
                    ]),

                Section::make('Planos de Ação')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        RichEditor::make('mitigation_plan')
                            ->label('Plano de Mitigação')
                            ->helperText('Ações para reduzir a probabilidade ou impacto')
                            ->columnSpanFull(),

                        RichEditor::make('contingency_plan')
                            ->label('Plano de Contingência')
                            ->helperText('Ações a executar se o risco ocorrer')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Risco')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                TextColumn::make('category')
                    ->label('Categoria')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => ProjectRisk::availableCategories()[$state] ?? ucfirst($state))
                    ->toggleable(),

                TextColumn::make('probability')
                    ->label('Prob.')
                    ->badge()
                    ->formatStateUsing(fn($state): string => $state instanceof RiskProbability ? $state->getLabel() : RiskProbability::tryFrom((int) $state)?->getLabel() ?? $state)
                    ->color(fn($state): string => $state instanceof RiskProbability ? $state->getColor() : 'gray'),

                TextColumn::make('impact')
                    ->label('Impacto')
                    ->badge()
                    ->formatStateUsing(fn($state): string => $state instanceof RiskImpact ? $state->getLabel() : RiskImpact::tryFrom((int) $state)?->getLabel() ?? $state)
                    ->color(fn($state): string => $state instanceof RiskImpact ? $state->getColor() : 'gray'),

                TextColumn::make('score')
                    ->label('Score')
                    ->badge()
                    ->color(fn($record): string => $record->risk_level_color)
                    ->formatStateUsing(fn($record): string => $record->score . ' (' . $record->risk_level_label . ')'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn($state): string => $state instanceof RiskStatus ? $state->label() : RiskStatus::tryFrom($state)?->label() ?? $state)
                    ->color(fn($state): string => $state instanceof RiskStatus ? $state->getColor() : 'gray'),

                TextColumn::make('owner.name')
                    ->label('Responsável')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Categoria')
                    ->options(ProjectRisk::availableCategories()),

                SelectFilter::make('status')
                    ->options(RiskStatus::toSelectArray()),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Adicionar Risco'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('score', 'desc');
    }
}

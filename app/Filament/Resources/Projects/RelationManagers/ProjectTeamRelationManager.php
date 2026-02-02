<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use App\Models\ProjectTeamMember;
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
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;

class ProjectTeamRelationManager extends RelationManager
{
    protected static string $relationship = 'teamMembers';

    protected static ?string $title = 'Equipe do Projeto';

    protected static ?string $recordTitleAttribute = 'user.name';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Membro da Equipe')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('user_id')
                                    ->label('Usuário')
                                    ->relationship('user', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->prefixIcon('heroicon-o-user'),

                                Select::make('role')
                                    ->label('Papel')
                                    ->options(ProjectTeamMember::availableRoles())
                                    ->default('member')
                                    ->required()
                                    ->prefixIcon('heroicon-o-briefcase'),
                            ]),

                        Grid::make(3)
                            ->schema([
                                TextInput::make('allocation_percentage')
                                    ->label('Alocação (%)')
                                    ->numeric()
                                    ->default(100)
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->suffix('%'),

                                DatePicker::make('start_date')
                                    ->label('Início'),

                                DatePicker::make('end_date')
                                    ->label('Fim'),
                            ]),

                        Grid::make(3)
                            ->schema([
                                Toggle::make('is_active')
                                    ->label('Ativo')
                                    ->default(true),

                                Toggle::make('can_edit')
                                    ->label('Pode Editar')
                                    ->default(false),

                                Toggle::make('can_delete')
                                    ->label('Pode Excluir')
                                    ->default(false),
                            ]),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Membro')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('role')
                    ->label('Papel')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => ProjectTeamMember::availableRoles()[$state] ?? ucfirst($state))
                    ->color('info'),

                TextColumn::make('allocation_percentage')
                    ->label('Alocação')
                    ->suffix('%')
                    ->sortable(),

                TextColumn::make('start_date')
                    ->label('Início')
                    ->date('d/m/Y')
                    ->toggleable(),

                TextColumn::make('end_date')
                    ->label('Fim')
                    ->date('d/m/Y')
                    ->toggleable(),

                IconColumn::make('is_active')
                    ->label('Ativo')
                    ->boolean(),

                IconColumn::make('can_edit')
                    ->label('Edita')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Adicionar Membro'),
                    
                \Filament\Actions\Action::make('export')
                    ->label('Exportar CSV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function (RelationManager $livewire) {
                        $records = $livewire->getRelationship()->with('user')->get();
                        
                        $data = $records->map(function ($record) {
                            return [
                                'Membro' => $record->user->name,
                                'Papel' => \App\Models\ProjectTeamMember::availableRoles()[$record->role] ?? $record->role,
                                'Alocacao' => $record->allocation_percentage . '%',
                                'Inicio' => $record->start_date?->format('d/m/Y'),
                                'Fim' => $record->end_date?->format('d/m/Y'),
                                'Status' => $record->is_active ? 'Ativo' : 'Inativo',
                            ];
                        });
                        
                        return \App\Services\CsvExportService::export(
                            $data, 
                            ['Membro', 'Papel', 'Alocacao', 'Inicio', 'Fim', 'Status'],
                            'equipe_projeto_' . now()->format('Y-m-d_His') . '.csv'
                        );
                    }),
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
            ->defaultSort('role');
    }
}

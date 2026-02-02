<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use App\Models\ProjectBaseline;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\KeyValue;
use Filament\Schemas\Components\Section;

class ProjectBaselinesRelationManager extends RelationManager
{
    protected static string $relationship = 'baselines';

    protected static ?string $title = 'Baselines (Histórico)';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nome da Baseline')
                    ->required()
                    ->maxLength(255)
                    ->default(fn () => 'Baseline ' . now()->format('d/m/Y H:i')),

                Textarea::make('description')
                    ->label('Descrição')
                    ->rows(3)
                    ->columnSpanFull(),
                
                Section::make('Dados do Snapshot')
                    ->schema([
                        KeyValue::make('snapshot_summary')
                            ->label('Resumo dos Dados')
                            ->formatStateUsing(fn ($record) => $record ? [
                                'Tarefas' => count($record->snapshot_data['tasks'] ?? []),
                                'Riscos' => count($record->snapshot_data['risks'] ?? []),
                                'Equipe' => count($record->snapshot_data['team_members'] ?? []),
                                'Despesas' => 'R$ ' . number_format(collect($record->snapshot_data['expenses'] ?? [])->sum('amount'), 2, ',', '.'),
                                'Custo Estimado' => 'R$ ' . number_format($record->snapshot_data['cost'] ?? 0, 2, ',', '.'),
                            ] : [])
                            ->disabled(),
                    ])
                    ->visibleOn('view'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('created_by.name')
                    ->label('Criado por')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Data')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'manual' => 'gray',
                        'automatic' => 'info',
                        'milestone' => 'success',
                        default => 'gray',
                    }),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Criar Nova Baseline')
                    ->modalHeading('Criar Snapshot do Projeto')
                    ->modalDescription('Isso salvará o estado atual de todas as tarefas, riscos e equipe.')
                    ->using(function (RelationManager $livewire, array $data): \Illuminate\Database\Eloquent\Model {
                        /** @var \App\Models\Project $project */
                        $project = $livewire->getOwnerRecord();
                        
                        // Carregar relacionamentos para o snapshot
                        $project->load([
                            'teamMembers', 
                            'risks',
                            'expenses', // Sprint 19
                            'tasks' => function ($query) {
                                $query->with(['dependencies', 'costItems']); // Sprint 19
                            }
                        ]);
                        
                        $data['snapshot_data'] = $project->toArray();
                        $data['type'] = 'manual';
                        $data['created_by_id'] = auth()->id();
                        
                        return $livewire->getRelationship()->create($data);
                    })
                    ->successNotificationTitle('Baseline criada com sucesso'),
            ])
            ->actions([
                ViewAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}

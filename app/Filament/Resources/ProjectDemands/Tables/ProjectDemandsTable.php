<?php

namespace App\Filament\Resources\ProjectDemands\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use App\Models\ProjectDemand;

class ProjectDemandsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),

                TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->limit(50),

                TextColumn::make('requester.name')
                    ->label('Solicitante')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('priority')
                    ->label('Prioridade')
                    ->badge()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'draft' => 'gray',
                        'submitted' => 'info',
                        'analyzing' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'converted' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => \App\Models\ProjectDemand::availableStatuses()[$state] ?? $state)
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                // Botão de Editar (Padrão)
                EditAction::make(),

                // Submeter (Draft -> Submitted)
                Action::make('submit')
                    ->label('Submeter')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('info')
                    ->requiresConfirmation()
                    ->action(fn (ProjectDemand $record) => $record->update(['status' => 'submitted']))
                    ->visible(fn (ProjectDemand $record) => $record->status === 'draft'),

                // Analisar (Submitted -> Analyzing)
                Action::make('analyze')
                    ->label('Analisar')
                    ->icon('heroicon-o-magnifying-glass')
                    ->color('warning')
                    ->action(fn (ProjectDemand $record) => $record->update(['status' => 'analyzing']))
                    ->visible(fn (ProjectDemand $record) => $record->status === 'submitted'),

                // Aprovar (Analyzing -> Approved)
                Action::make('approve')
                    ->label('Aprovar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn (ProjectDemand $record) => $record->update(['status' => 'approved']))
                    ->visible(fn (ProjectDemand $record) => $record->status === 'analyzing'),

                // Rejeitar (Any -> Rejected)
                Action::make('reject')
                    ->label('Rejeitar')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->form([
                        \Filament\Forms\Components\Textarea::make('justification')
                            ->label('Motivo da Rejeição')
                            ->required(),
                    ])
                    ->action(function (ProjectDemand $record, array $data) {
                        $record->update([
                            'status' => 'rejected',
                            'justification' => $record->justification . "\n\n[Rejeição]: " . $data['justification']
                        ]);
                    })
                    ->visible(fn (ProjectDemand $record) => !in_array($record->status, ['draft', 'rejected', 'converted'])),

                // Converter em Projeto (Approved -> Converted)
                Action::make('convert')
                    ->label('Criar Projeto')
                    ->icon('heroicon-o-rocket-launch')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Converter Demanda em Projeto')
                    ->modalDescription('Um novo projeto será criado com base nesta demanda. Deseja continuar?')
                    ->action(function (ProjectDemand $record) {
                        // Lógica de Conversão
                        $project = \App\Models\Project::create([
                            'name' => $record->title,
                            'description' => $record->description,
                            'justification' => $record->justification,
                            'status' => 0, // Pending/Planning
                            'priority' => $record->priority, // Enum compatibility needs check
                            'start_date' => now(),
                            'owner_id' => auth()->id(), // Quem clicou, ou o requester? Vamos por o usuário atual
                            'company_id' => \App\Models\Company::first()->id, // Fallback safe
                            'code' => 'PROJ-' . date('Ymd') . '-' . $record->id,
                        ]);

                        $record->update([
                            'status' => 'converted',
                            'project_id' => $project->id,
                        ]);

                        return redirect()->to(\App\Filament\Resources\Projects\ProjectResource::getUrl('edit', ['record' => $project]));
                    })
                    ->visible(fn (ProjectDemand $record) => $record->status === 'approved'),
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

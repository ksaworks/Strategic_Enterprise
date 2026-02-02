<?php

namespace App\Filament\Resources\ProjectCharters\Pages;

use App\Enums\CharterStatus;
use App\Filament\Resources\ProjectCharters\ProjectCharterResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditProjectCharter extends EditRecord
{
    protected static string $resource = ProjectCharterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Submit for Approval
            Action::make('submit_for_approval')
                ->label('Enviar para Aprovação')
                ->icon('heroicon-o-paper-airplane')
                ->color('warning')
                ->visible(fn () => $this->record->status === CharterStatus::DRAFT)
                ->requiresConfirmation()
                ->modalHeading('Enviar para Aprovação')
                ->modalDescription('Tem certeza que deseja enviar este termo para aprovação? Após enviado, não será possível editar até que seja aprovado ou rejeitado.')
                ->action(function () {
                    $this->record->update(['status' => CharterStatus::PENDING_APPROVAL]);
                    Notification::make()
                        ->success()
                        ->title('Enviado para aprovação')
                        ->body('O termo foi enviado para aprovação.')
                        ->send();
                    $this->redirect($this->getResource()::getUrl('edit', ['record' => $this->record]));
                }),

            // Approve
            Action::make('approve')
                ->label('Aprovar')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->visible(fn () => $this->record->status === CharterStatus::PENDING_APPROVAL)
                ->form([
                    Textarea::make('justification')
                        ->label('Justificativa de Aprovação')
                        ->placeholder('Descreva o motivo da aprovação...')
                        ->required()
                        ->rows(3),
                ])
                ->action(function (array $data) {
                    $this->record->update([
                        'status' => CharterStatus::APPROVED,
                        'approved_by_id' => auth()->id(),
                        'approved_at' => now(),
                        'approval_justification' => $data['justification'],
                    ]);
                    Notification::make()
                        ->success()
                        ->title('Termo Aprovado')
                        ->body('O termo de abertura foi aprovado com sucesso.')
                        ->send();
                    $this->redirect($this->getResource()::getUrl('edit', ['record' => $this->record]));
                }),

            // Reject
            Action::make('reject')
                ->label('Rejeitar')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn () => $this->record->status === CharterStatus::PENDING_APPROVAL)
                ->form([
                    Textarea::make('justification')
                        ->label('Motivo da Rejeição')
                        ->placeholder('Descreva o motivo da rejeição...')
                        ->required()
                        ->rows(3),
                ])
                ->action(function (array $data) {
                    $this->record->update([
                        'status' => CharterStatus::REJECTED,
                        'approved_by_id' => auth()->id(),
                        'approved_at' => now(),
                        'approval_justification' => $data['justification'],
                    ]);
                    Notification::make()
                        ->warning()
                        ->title('Termo Rejeitado')
                        ->body('O termo de abertura foi rejeitado.')
                        ->send();
                    $this->redirect($this->getResource()::getUrl('edit', ['record' => $this->record]));
                }),

            // Revert to Draft (if rejected)
            Action::make('revert_to_draft')
                ->label('Voltar para Rascunho')
                ->icon('heroicon-o-arrow-uturn-left')
                ->color('gray')
                ->visible(fn () => $this->record->status === CharterStatus::REJECTED)
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->update([
                        'status' => CharterStatus::DRAFT,
                        'approved_by_id' => null,
                        'approved_at' => null,
                        'approval_justification' => null,
                    ]);
                    Notification::make()
                        ->info()
                        ->title('Termo revertido')
                        ->body('O termo foi revertido para rascunho e pode ser editado novamente.')
                        ->send();
                    $this->redirect($this->getResource()::getUrl('edit', ['record' => $this->record]));
                }),

            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}


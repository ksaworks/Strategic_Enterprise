<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification;

class TaskAssigned extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Task $task)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nova tarefa atribuída: ' . $this->task->name)
            ->greeting('Olá, ' . $notifiable->name)
            ->line('Você foi atribuído a uma nova tarefa no projeto: ' . $this->task->project->name)
            ->line('Tarefa: ' . $this->task->name)
            ->line('Prazo: ' . ($this->task->end_date ? $this->task->end_date->format('d/m/Y') : 'Não definido'))
            ->action('Visualizar Tarefa', url('/admin/tasks/' . $this->task->id . '/edit'))
            ->line('Bom trabalho!');
    }

    public function toArray(object $notifiable): array
    {
        return FilamentNotification::make()
            ->title('Nova tarefa atribuída')
            ->body("Você foi atribuído à tarefa: {$this->task->name}")
            ->icon('heroicon-o-clipboard-document-check')
            ->iconColor('success')
            ->getDatabaseMessage();
    }
}

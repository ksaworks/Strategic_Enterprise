<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification;

class ProjectAdded extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Project $project)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Novo projeto atribuído: ' . $this->project->name)
            ->greeting('Olá, ' . $notifiable->name)
            ->line('Você foi definido como gerente do novo projeto: ' . $this->project->name)
            ->line('Código: ' . $this->project->code)
            ->action('Visualizar Projeto', url('/admin/projects/' . $this->project->id . '/edit'))
            ->line('Sucesso no projeto!');
    }

    public function toArray(object $notifiable): array
    {
        return FilamentNotification::make()
            ->title('Novo projeto atribuído')
            ->body("Você é o gerente do projeto: {$this->project->name}")
            ->icon('heroicon-o-briefcase')
            ->iconColor('info')
            ->getDatabaseMessage();
    }
}

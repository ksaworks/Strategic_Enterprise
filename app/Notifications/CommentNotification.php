<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Database\Eloquent\Model;

class CommentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Model $commentable,
        public string $commentAuthorName,
        public string $commentContent
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $type = $this->commentable instanceof \App\Models\Task ? 'tarefa' : 'projeto';
        $name = $this->commentable->name ?? 'N/A';

        return FilamentNotification::make()
            ->title('Novo comentário')
            ->body("{$this->commentAuthorName} comentou na {$type}: {$name}")
            ->icon('heroicon-o-chat-bubble-left-ellipsis')
            ->iconColor('warning')
            ->getDatabaseMessage();
    }
}

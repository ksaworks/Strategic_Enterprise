<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;

enum MeetingStatus: string implements HasLabel, HasColor, HasIcon
{
    case SCHEDULED = 'scheduled';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function getLabel(): string
    {
        return match ($this) {
            self::SCHEDULED => 'Agendada',
            self::IN_PROGRESS => 'Em Andamento',
            self::COMPLETED => 'Concluída / Ata Gerada',
            self::CANCELLED => 'Cancelada',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::SCHEDULED => 'info',
            self::IN_PROGRESS => 'warning',
            self::COMPLETED => 'success',
            self::CANCELLED => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::SCHEDULED => 'heroicon-o-calendar-days',
            self::IN_PROGRESS => 'heroicon-o-play-circle',
            self::COMPLETED => 'heroicon-o-check-badge',
            self::CANCELLED => 'heroicon-o-x-circle',
        };
    }
}

<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;

enum MeetingType: string implements HasLabel, HasColor, HasIcon
{
    case KICKOFF = 'kickoff';
    case DAILY = 'daily';
    case WEEKLY = 'weekly';
    case STEERING = 'steering';
    case PERFORMANCE = 'performance';
    case BRAINSTORMING = 'brainstorming';
    case OTHER = 'other';

    public function getLabel(): string
    {
        return match ($this) {
            self::KICKOFF => 'Kick-off',
            self::DAILY => 'Daily Scrum',
            self::WEEKLY => 'Reunião Semanal',
            self::STEERING => 'Comitê Executivo (Steering)',
            self::PERFORMANCE => 'Avaliação de Desempenho',
            self::BRAINSTORMING => 'Brainstorming',
            self::OTHER => 'Outros',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::KICKOFF => 'success',
            self::DAILY => 'info',
            self::WEEKLY => 'primary',
            self::STEERING => 'danger',
            self::PERFORMANCE => 'warning',
            self::BRAINSTORMING => 'secondary',
            self::OTHER => 'gray',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::KICKOFF => 'heroicon-o-rocket-launch',
            self::DAILY => 'heroicon-o-clock',
            self::WEEKLY => 'heroicon-o-calendar',
            self::STEERING => 'heroicon-o-user-group',
            self::PERFORMANCE => 'heroicon-o-chart-bar',
            self::BRAINSTORMING => 'heroicon-o-light-bulb',
            self::OTHER => 'heroicon-o-chat-bubble-left-ellipsis',
        };
    }
}

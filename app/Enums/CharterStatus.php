<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;

enum CharterStatus: string implements HasLabel, HasColor, HasIcon
{
    case DRAFT = 'draft';
    case PENDING_APPROVAL = 'pending_approval';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public function getLabel(): string
    {
        return match ($this) {
            self::DRAFT => 'Rascunho',
            self::PENDING_APPROVAL => 'Aguardando Aprovação',
            self::APPROVED => 'Aprovado',
            self::REJECTED => 'Rejeitado',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::DRAFT => 'gray',
            self::PENDING_APPROVAL => 'warning',
            self::APPROVED => 'success',
            self::REJECTED => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::DRAFT => 'heroicon-o-pencil-square',
            self::PENDING_APPROVAL => 'heroicon-o-clock',
            self::APPROVED => 'heroicon-o-check-badge',
            self::REJECTED => 'heroicon-o-x-circle',
        };
    }
}

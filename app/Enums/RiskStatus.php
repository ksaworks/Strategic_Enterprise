<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;

/**
 * Status de Risco
 * 
 * Define o ciclo de vida de um risco identificado.
 */
enum RiskStatus: string implements HasLabel, HasColor, HasIcon
{
    case IDENTIFIED = 'identified';
    case ANALYZING = 'analyzing';
    case MITIGATING = 'mitigating';
    case MONITORING = 'monitoring';
    case CLOSED = 'closed';
    case OCCURRED = 'occurred';

    public function getLabel(): string
    {
        return match ($this) {
            self::IDENTIFIED => 'Identificado',
            self::ANALYZING => 'Em Análise',
            self::MITIGATING => 'Mitigando',
            self::MONITORING => 'Monitorando',
            self::CLOSED => 'Encerrado',
            self::OCCURRED => 'Ocorreu',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::IDENTIFIED => 'gray',
            self::ANALYZING => 'info',
            self::MITIGATING => 'warning',
            self::MONITORING => 'primary',
            self::CLOSED => 'success',
            self::OCCURRED => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::IDENTIFIED => 'heroicon-o-exclamation-triangle',
            self::ANALYZING => 'heroicon-o-magnifying-glass',
            self::MITIGATING => 'heroicon-o-shield-check',
            self::MONITORING => 'heroicon-o-eye',
            self::CLOSED => 'heroicon-o-check-circle',
            self::OCCURRED => 'heroicon-o-x-circle',
        };
    }

    /**
     * Helper para Filament Select options
     */
    public static function toSelectArray(): array
    {
        return collect(self::cases())->mapWithKeys(
            fn($case) => [$case->value => $case->getLabel()]
        )->toArray();
    }
}


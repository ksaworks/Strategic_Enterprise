<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;

/**
 * Status de Projeto
 * 
 * Define os estados possíveis de um projeto durante seu ciclo de vida.
 * Baseado no sistema legado GPWeb (sisvalores: StatusProjeto)
 */
enum ProjectStatus: int implements HasLabel, HasColor, HasIcon
{
    case NOT_STARTED = 0;
    case IN_PROGRESS = 1;
    case ON_HOLD = 2;
    case COMPLETED = 3;
    case CANCELLED = 4;

    /**
     * Label para exibição na interface
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::NOT_STARTED => 'Não Iniciado',
            self::IN_PROGRESS => 'Em Andamento',
            self::ON_HOLD => 'Em Pausa',
            self::COMPLETED => 'Concluído',
            self::CANCELLED => 'Cancelado',
        };
    }

    /**
     * Cor para badges e indicadores (Filament colors)
     */
    public function getColor(): string | array | null
    {
        return match ($this) {
            self::NOT_STARTED => 'gray',
            self::IN_PROGRESS => 'info',
            self::ON_HOLD => 'warning',
            self::COMPLETED => 'success',
            self::CANCELLED => 'danger',
        };
    }

    /**
     * Ícone Heroicon para a interface
     */
    public function getIcon(): ?string
    {
        return match ($this) {
            self::NOT_STARTED => 'heroicon-o-clock',
            self::IN_PROGRESS => 'heroicon-o-play',
            self::ON_HOLD => 'heroicon-o-pause',
            self::COMPLETED => 'heroicon-o-check-circle',
            self::CANCELLED => 'heroicon-o-x-circle',
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

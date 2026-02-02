<?php

namespace App\Enums;

/**
 * Status de Tarefa
 * 
 * Define os estados possíveis de uma tarefa durante seu ciclo de vida.
 * Baseado no sistema legado GPWeb (sisvalores: StatusTarefa)
 */
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;

/**
 * Status de Tarefa
 * 
 * Define os estados possíveis de uma tarefa durante seu ciclo de vida.
 * Baseado no sistema legado GPWeb (sisvalores: StatusTarefa)
 */
enum TaskStatus: int implements HasLabel, HasColor, HasIcon
{
    case TODO = 0;
    case IN_PROGRESS = 1;
    case WAITING = 2;
    case COMPLETED = 3;
    case CANCELLED = 4;

    /**
     * Label para exibição na interface
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::TODO => 'A Fazer',
            self::IN_PROGRESS => 'Em Andamento',
            self::WAITING => 'Aguardando',
            self::COMPLETED => 'Concluída',
            self::CANCELLED => 'Cancelada',
        };
    }

    /**
     * Cor para badges e indicadores (Filament colors)
     */
    public function getColor(): string | array | null
    {
        return match ($this) {
            self::TODO => 'gray',
            self::IN_PROGRESS => 'info',
            self::WAITING => 'warning',
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
            self::TODO => 'heroicon-o-clipboard-document-list',
            self::IN_PROGRESS => 'heroicon-o-play',
            self::WAITING => 'heroicon-o-pause',
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

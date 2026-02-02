<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;

/**
 * Níveis de Prioridade
 * 
 * Define os níveis de prioridade para projetos e tarefas.
 * Baseado no sistema legado GPWeb (sisvalores: PrioridadeProjeto/PrioridadeTarefa)
 * 
 * Legado usava escala 0-9, simplificamos para 3 níveis principais.
 */
enum Priority: int implements HasLabel, HasColor, HasIcon
{
    case LOW = 0;
    case MEDIUM = 5;
    case HIGH = 9;

    /**
     * Label para exibição na interface
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::LOW => 'Baixa',
            self::MEDIUM => 'Média',
            self::HIGH => 'Alta',
        };
    }

    /**
     * Cor para badges e indicadores (Filament colors)
     */
    public function getColor(): string | array | null
    {
        return match ($this) {
            self::LOW => 'gray',
            self::MEDIUM => 'warning',
            self::HIGH => 'danger',
        };
    }

    /**
     * Ícone Heroicon para a interface
     */
    public function getIcon(): ?string
    {
        return match ($this) {
            self::LOW => 'heroicon-o-arrow-down',
            self::MEDIUM => 'heroicon-o-minus',
            self::HIGH => 'heroicon-o-arrow-up',
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

<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

/**
 * Probabilidade de Risco
 * 
 * Escala de 5 níveis para classificação de probabilidade de ocorrência de riscos.
 * Usada na matriz de riscos junto com RiskImpact.
 */
enum RiskProbability: int implements HasLabel, HasColor
{
    case VERY_LOW = 1;
    case LOW = 2;
    case MEDIUM = 3;
    case HIGH = 4;
    case VERY_HIGH = 5;

    public function getLabel(): string
    {
        return match ($this) {
            self::VERY_LOW => 'Muito Baixa',
            self::LOW => 'Baixa',
            self::MEDIUM => 'Média',
            self::HIGH => 'Alta',
            self::VERY_HIGH => 'Muito Alta',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::VERY_LOW => 'gray',
            self::LOW => 'info',
            self::MEDIUM => 'warning',
            self::HIGH => 'danger',
            self::VERY_HIGH => 'danger',
        };
    }

    public function percentage(): string
    {
        return match ($this) {
            self::VERY_LOW => '< 10%',
            self::LOW => '10-30%',
            self::MEDIUM => '30-50%',
            self::HIGH => '50-70%',
            self::VERY_HIGH => '> 70%',
        };
    }
}


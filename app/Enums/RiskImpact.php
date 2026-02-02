<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

/**
 * Impacto de Risco
 * 
 * Escala de 5 níveis para classificação de impacto de riscos.
 * Usada na matriz de riscos junto com RiskProbability.
 */
enum RiskImpact: int implements HasLabel, HasColor
{
    case VERY_LOW = 1;
    case LOW = 2;
    case MEDIUM = 3;
    case HIGH = 4;
    case VERY_HIGH = 5;

    public function getLabel(): string
    {
        return match ($this) {
            self::VERY_LOW => 'Muito Baixo',
            self::LOW => 'Baixo',
            self::MEDIUM => 'Médio',
            self::HIGH => 'Alto',
            self::VERY_HIGH => 'Muito Alto',
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

    /**
     * Descrição do impacto
     */
    public function description(): string
    {
        return match ($this) {
            self::VERY_LOW => 'Impacto insignificante, facilmente absorvido',
            self::LOW => 'Impacto menor, requer ajustes mínimos',
            self::MEDIUM => 'Impacto moderado, afeta cronograma ou custos',
            self::HIGH => 'Impacto significativo, pode comprometer entregas',
            self::VERY_HIGH => 'Impacto crítico, pode inviabilizar o projeto',
        };
    }
}

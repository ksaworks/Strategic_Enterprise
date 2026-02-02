<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum KpiPolarity: string implements HasLabel, HasColor
{
    case HIGHER_IS_BETTER = 'higher_is_better';
    case LOWER_IS_BETTER = 'lower_is_better';
    case EQUAL_IS_BETTER = 'equal_is_better';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::HIGHER_IS_BETTER => 'Maior é Melhor (↑)',
            self::LOWER_IS_BETTER => 'Menor é Melhor (↓)',
            self::EQUAL_IS_BETTER => 'Igual à Meta (=)',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::HIGHER_IS_BETTER => 'success',
            self::LOWER_IS_BETTER => 'danger',
            self::EQUAL_IS_BETTER => 'info',
        };
    }

    public static function toSelectArray(): array
    {
        return array_reduce(self::cases(), function ($carry, $case) {
            $carry[$case->value] = $case->getLabel();
            return $carry;
        }, []);
    }
}

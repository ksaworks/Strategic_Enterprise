<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum KpiFrequency: string implements HasLabel
{
    case MONTHLY = 'monthly';
    case QUARTERLY = 'quarterly';
    case SEMIANNUALLY = 'semiannually';
    case ANNUALLY = 'annually';
    case ON_DEMAND = 'on_demand';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::MONTHLY => 'Mensal',
            self::QUARTERLY => 'Trimestral',
            self::SEMIANNUALLY => 'Semestral',
            self::ANNUALLY => 'Anual',
            self::ON_DEMAND => 'Sob Demanda',
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

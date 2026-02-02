<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;

enum ProjectCanvasSection: string implements HasLabel, HasColor, HasIcon
{
    case PIT = 'pit';
    case JUSTIFICATION = 'justification';
    case SMART_OBJ = 'smart_obj';
    case BENEFITS = 'benefits';
    case PRODUCT = 'product';
    case REQUIREMENTS = 'requirements';
    case STAKEHOLDERS = 'stakeholders';
    case TEAM = 'team';
    case PREMISES = 'premises';
    case DELIVERABLES = 'deliverables';
    case RISKS = 'risks';
    case TIMELINE = 'timeline';
    case COSTS = 'costs';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PIT => 'Pit de Vendas',
            self::JUSTIFICATION => 'Justificativa',
            self::SMART_OBJ => 'Obj. SMART',
            self::BENEFITS => 'Benefícios',
            self::PRODUCT => 'Produto',
            self::REQUIREMENTS => 'Requisitos',
            self::STAKEHOLDERS => 'Stakeholders',
            self::TEAM => 'Equipe',
            self::PREMISES => 'Premissas',
            self::DELIVERABLES => 'Entregas (Grupo)',
            self::RISKS => 'Riscos',
            self::TIMELINE => 'Linha do Tempo',
            self::COSTS => 'Custos',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::PIT, self::JUSTIFICATION, self::SMART_OBJ, self::BENEFITS => 'warning', // Porquê
            self::PRODUCT, self::REQUIREMENTS => 'info', // O Quê
            self::STAKEHOLDERS, self::TEAM => 'success', // Quem
            self::PREMISES, self::DELIVERABLES, self::RISKS => 'danger', // Como
            self::TIMELINE, self::COSTS => 'primary', // Quando e Quanto
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::PIT => 'heroicon-o-sparkles',
            self::JUSTIFICATION => 'heroicon-o-chat-bubble-bottom-center-text',
            self::SMART_OBJ => 'heroicon-o-ticket',
            self::BENEFITS => 'heroicon-o-hand-thumb-up',
            self::PRODUCT => 'heroicon-o-cube',
            self::REQUIREMENTS => 'heroicon-o-list-bullet',
            self::STAKEHOLDERS => 'heroicon-o-user-group',
            self::TEAM => 'heroicon-o-users',
            self::PREMISES => 'heroicon-o-scale',
            self::DELIVERABLES => 'heroicon-o-gift',
            self::RISKS => 'heroicon-o-exclamation-triangle',
            self::TIMELINE => 'heroicon-o-clock',
            self::COSTS => 'heroicon-o-currency-dollar',
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

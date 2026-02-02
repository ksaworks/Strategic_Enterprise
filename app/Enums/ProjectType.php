<?php

namespace App\Enums;

/**
 * Tipos de Projeto
 * 
 * Define a hierarquia de projetos:
 * - Portfolio: Conjunto de programas e projetos alinhados a objetivos estratégicos
 * - Program: Conjunto de projetos relacionados gerenciados de forma coordenada
 * - Project: Esforço temporário para criar um produto/serviço único
 */
enum ProjectType: string
{
    case PROJECT = 'project';
    case PROGRAM = 'program';
    case PORTFOLIO = 'portfolio';

    /**
     * Label para exibição na interface
     */
    public function label(): string
    {
        return match ($this) {
            self::PROJECT => 'Projeto',
            self::PROGRAM => 'Programa',
            self::PORTFOLIO => 'Portfólio',
        };
    }

    /**
     * Ícone Heroicon para a interface
     */
    public function icon(): string
    {
        return match ($this) {
            self::PROJECT => 'heroicon-o-briefcase',
            self::PROGRAM => 'heroicon-o-folder',
            self::PORTFOLIO => 'heroicon-o-rectangle-stack',
        };
    }

    /**
     * Cor para badges e indicadores
     */
    public function color(): string
    {
        return match ($this) {
            self::PROJECT => 'info',
            self::PROGRAM => 'warning',
            self::PORTFOLIO => 'success',
        };
    }

    /**
     * Helper para Filament Select options
     */
    public static function toSelectArray(): array
    {
        return collect(self::cases())->mapWithKeys(
            fn($case) => [$case->value => $case->label()]
        )->toArray();
    }
}

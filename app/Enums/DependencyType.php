<?php

namespace App\Enums;

/**
 * Tipos de Dependência entre Tarefas
 * 
 * Padrão de gerenciamento de projetos (MS Project, Primavera, etc):
 * - FS: A tarefa B só pode iniciar quando A terminar
 * - SS: A tarefa B inicia quando A iniciar
 * - FF: A tarefa B termina quando A terminar
 * - SF: A tarefa B termina quando A iniciar (raro)
 */
enum DependencyType: string
{
    case FS = 'FS';
    case SS = 'SS';
    case FF = 'FF';
    case SF = 'SF';

    /**
     * Label para exibição na interface
     */
    public function label(): string
    {
        return match ($this) {
            self::FS => 'Término para Início (FS)',
            self::SS => 'Início para Início (SS)',
            self::FF => 'Término para Término (FF)',
            self::SF => 'Início para Término (SF)',
        };
    }

    /**
     * Descrição curta
     */
    public function shortLabel(): string
    {
        return match ($this) {
            self::FS => 'FS',
            self::SS => 'SS',
            self::FF => 'FF',
            self::SF => 'SF',
        };
    }

    /**
     * Descrição da dependência
     */
    public function description(): string
    {
        return match ($this) {
            self::FS => 'A tarefa só pode iniciar quando a predecessor terminar',
            self::SS => 'A tarefa inicia quando a predecessor iniciar',
            self::FF => 'A tarefa termina quando a predecessor terminar',
            self::SF => 'A tarefa termina quando a predecessor iniciar',
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

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * Sprint 9 - Tarefa 9.1: Adiciona campos de escopo em Projects
     * 
     * Campos do legado GPWeb:
     * - projeto_justificativa: Por que o projeto existe
     * - projeto_escopo: O que será entregue
     * - projeto_fora_escopo: O que NÃO será entregue
     * - projeto_premissas: Condições assumidas como verdadeiras
     * - projeto_restricoes: Limitações conhecidas
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Justificativa do projeto
            $table->text('justification')
                ->nullable()
                ->after('objectives')
                ->comment('Por que o projeto existe / problema a resolver');

            // Escopo do projeto
            $table->text('scope')
                ->nullable()
                ->after('justification')
                ->comment('O que será entregue pelo projeto');

            // Fora do escopo
            $table->text('out_of_scope')
                ->nullable()
                ->after('scope')
                ->comment('O que NÃO será entregue pelo projeto');

            // Premissas
            $table->text('assumptions')
                ->nullable()
                ->after('out_of_scope')
                ->comment('Condições assumidas como verdadeiras');

            // Restrições
            $table->text('constraints')
                ->nullable()
                ->after('assumptions')
                ->comment('Limitações conhecidas do projeto');

            // Critérios de sucesso
            $table->text('success_criteria')
                ->nullable()
                ->after('constraints')
                ->comment('Como será medido o sucesso do projeto');

            // Riscos principais (resumo)
            $table->text('main_risks')
                ->nullable()
                ->after('success_criteria')
                ->comment('Principais riscos identificados');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'justification',
                'scope',
                'out_of_scope',
                'assumptions',
                'constraints',
                'success_criteria',
                'main_risks',
            ]);
        });
    }
};

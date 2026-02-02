<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * Sprint 9 - Tarefa 9.3: Cria tabela de riscos de projeto
     * 
     * Baseado no legado GPWeb: gestão básica de riscos
     * Permite registrar riscos com probabilidade, impacto e status.
     */
    public function up(): void
    {
        Schema::create('project_risks', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('project_id')
                ->comment('Projeto')
                ->constrained('projects')
                ->cascadeOnDelete();
            
            // Identificação do risco
            $table->string('name', 255)
                ->comment('Nome/título do risco');
            
            $table->text('description')
                ->nullable()
                ->comment('Descrição detalhada do risco');
            
            // Classificação
            $table->string('category', 50)
                ->default('technical')
                ->comment('Categoria: technical, financial, schedule, scope, external');
            
            // Probabilidade (1-5)
            $table->tinyInteger('probability')
                ->default(3)
                ->comment('Probabilidade: 1=Muito Baixa, 2=Baixa, 3=Média, 4=Alta, 5=Muito Alta');
            
            // Impacto (1-5)
            $table->tinyInteger('impact')
                ->default(3)
                ->comment('Impacto: 1=Muito Baixo, 2=Baixo, 3=Médio, 4=Alto, 5=Muito Alto');
            
            // Score calculado (probability * impact)
            $table->tinyInteger('score')
                ->virtualAs('probability * impact')
                ->comment('Score de risco calculado');
            
            // Plano de mitigação
            $table->text('mitigation_plan')
                ->nullable()
                ->comment('Ações para mitigar o risco');
            
            // Plano de contingência
            $table->text('contingency_plan')
                ->nullable()
                ->comment('Ações se o risco ocorrer');
            
            // Status do risco
            $table->string('status', 30)
                ->default('identified')
                ->comment('Status: identified, analyzing, mitigating, monitoring, closed, occurred');
            
            // Responsável
            $table->foreignId('owner_id')
                ->nullable()
                ->comment('Responsável pelo risco')
                ->constrained('users')
                ->nullOnDelete();
            
            // Data de identificação
            $table->date('identified_at')
                ->nullable()
                ->comment('Data de identificação');
            
            // Data de ocorrência (se ocorreu)
            $table->date('occurred_at')
                ->nullable()
                ->comment('Data de ocorrência');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Índices
            $table->index('project_id');
            $table->index('category');
            $table->index('status');
            $table->index(['probability', 'impact']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_risks');
    }
};

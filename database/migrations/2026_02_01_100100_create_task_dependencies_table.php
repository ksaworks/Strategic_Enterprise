<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * Tarefa 8.2: Cria tabela de dependências entre tarefas
     * 
     * Tipos de dependência (padrão MS Project):
     * - FS (Finish-to-Start): Tarefa B só inicia quando A terminar
     * - SS (Start-to-Start): Tarefa B inicia quando A iniciar
     * - FF (Finish-to-Finish): Tarefa B termina quando A terminar
     * - SF (Start-to-Finish): Tarefa B termina quando A iniciar
     * 
     * Legado: tabela tarefa_dependencias
     */
    public function up(): void
    {
        Schema::create('task_dependencies', function (Blueprint $table) {
            $table->id();
            
            // A tarefa que depende
            $table->foreignId('task_id')
                ->comment('Tarefa dependente (a que espera)')
                ->constrained('tasks')
                ->cascadeOnDelete();
            
            // A tarefa da qual depende
            $table->foreignId('depends_on_id')
                ->comment('Tarefa predecessora (a que deve ser concluída)')
                ->constrained('tasks')
                ->cascadeOnDelete();
            
            // Tipo de dependência
            $table->enum('type', ['FS', 'SS', 'FF', 'SF'])
                ->default('FS')
                ->comment('FS=Finish-Start, SS=Start-Start, FF=Finish-Finish, SF=Start-Finish');
            
            // Lag (atraso em dias, pode ser negativo para lead)
            $table->integer('lag_days')
                ->default(0)
                ->comment('Dias de atraso (positivo) ou antecipação (negativo)');
            
            $table->timestamps();
            
            // Impedir dependência duplicada
            $table->unique(['task_id', 'depends_on_id'], 'task_dependency_unique');
            
            // Índices para performance
            $table->index('task_id');
            $table->index('depends_on_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_dependencies');
    }
};

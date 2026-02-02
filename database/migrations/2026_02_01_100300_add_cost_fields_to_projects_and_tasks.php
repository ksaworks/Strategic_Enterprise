<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * Tarefa 8.4: Adiciona campos de custo em Projects e Tasks
     * 
     * Campos do legado:
     * - projeto_custo / tarefa_custo: Custo planejado
     * - projeto_gasto / tarefa_gasto: Custo realizado
     * - tarefa_horas_trabalhadas: Horas registradas
     * - tarefa_codigo: Código interno da tarefa
     */
    public function up(): void
    {
        // Adicionar campos em Projects
        Schema::table('projects', function (Blueprint $table) {
            $table->decimal('cost', 20, 2)
                ->nullable()
                ->after('budget')
                ->comment('Custo planejado');

            $table->decimal('spent', 20, 2)
                ->nullable()
                ->after('cost')
                ->comment('Custo realizado/gasto');
        });

        // Adicionar campos em Tasks
        Schema::table('tasks', function (Blueprint $table) {
            // Código interno
            $table->string('code', 50)
                ->nullable()
                ->after('name')
                ->comment('Código interno da tarefa');

            // Criador
            $table->foreignId('created_by_id')
                ->nullable()
                ->after('owner_id')
                ->comment('Usuário que criou a tarefa')
                ->constrained('users')
                ->nullOnDelete();

            // Custos
            $table->decimal('cost', 20, 2)
                ->nullable()
                ->after('is_milestone')
                ->comment('Custo planejado');

            $table->decimal('spent', 20, 2)
                ->nullable()
                ->after('cost')
                ->comment('Custo realizado/gasto');

            $table->decimal('hours_worked', 10, 2)
                ->default(0)
                ->after('spent')
                ->comment('Horas trabalhadas registradas');

            // Dinâmica (cálculo automático de datas)
            $table->boolean('is_dynamic')
                ->default(false)
                ->after('hours_worked')
                ->comment('Se as datas são calculadas automaticamente');

            // 5W2H
            $table->text('where')
                ->nullable()
                ->after('description')
                ->comment('5W2H: Onde');

            $table->text('why')
                ->nullable()
                ->after('where')
                ->comment('5W2H: Por quê');

            $table->text('how')
                ->nullable()
                ->after('why')
                ->comment('5W2H: Como');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['cost', 'spent']);
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropConstrainedForeignId('created_by_id');
            $table->dropColumn([
                'code',
                'cost',
                'spent',
                'hours_worked',
                'is_dynamic',
                'where',
                'why',
                'how',
            ]);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('key_performance_indicators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('strategic_objective_id')->constrained('strategic_objectives')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            
            // Definição
            $table->string('measurement_unit')->default('number'); // %, R$, #
            $table->string('frequency')->default('monthly'); // Enum: KpiFrequency
            $table->string('polarity')->default('higher_is_better'); // Enum: KpiPolarity
            $table->decimal('base_target', 15, 2)->nullable(); // Meta base padrão
            
            // Rastreabilidade
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('kpi_measurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('key_performance_indicator_id')->constrained('key_performance_indicators')->cascadeOnDelete();
            $table->date('period'); // Data referência (ex: 2026-01-01)
            
            $table->decimal('target_value', 15, 2)->nullable();
            $table->decimal('actual_value', 15, 2)->nullable();
            
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by_id')->nullable()->constrained('users');
            $table->timestamps();
            
            // Evitar duplicidade para o mesmo período
            $table->unique(['key_performance_indicator_id', 'period'], 'kpi_period_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kpi_tables');
    }
};

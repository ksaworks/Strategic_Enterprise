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
        // 1. Perspectivas (Financeira, Cliente, etc.)
        Schema::create('perspectives', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('color')->default('#2563EB'); // Blue default
            $table->string('icon')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Objetivos Estratégicos (Vinculados a Perspectivas)
        Schema::create('strategic_objectives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perspective_id')->constrained()->cascadeOnDelete();
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('code')->nullable(); // Ex: FIN-01
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // 3. Fatores Críticos de Sucesso (Vinculados a Objetivos)
        Schema::create('critical_success_factors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('strategic_objective_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('weight')->default(1); // Peso para cálculo de score
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('critical_success_factors');
        Schema::dropIfExists('strategic_objectives');
        Schema::dropIfExists('perspectives');
    }
};

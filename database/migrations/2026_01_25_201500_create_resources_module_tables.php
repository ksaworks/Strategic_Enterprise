<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabela de Links Úteis
        Schema::create('useful_links', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('url');
            $table->text('description')->nullable();
            $table->string('category')->default('Geral'); // Ex: 'Ferramentas', 'Documentação', 'RH'
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Tabela de Recursos da Empresa (Salas, Equipamentos, Veículos)
        Schema::create('company_resources', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // Ex: 'Room', 'Equipment', 'Vehicle'
            $table->integer('capacity')->nullable(); // Para salas
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Tabela de Reservas de Recursos
        Schema::create('resource_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_resource_id')->constrained('company_resources')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();

            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('purpose')->nullable();
            $table->string('status')->default('confirmed'); // confirmed, cancelled

            $table->timestamps();

            // Índices para performance em buscas de conflito
            $table->index(['company_resource_id', 'start_time', 'end_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource_bookings');
        Schema::dropIfExists('company_resources');
        Schema::dropIfExists('useful_links');
    }
};

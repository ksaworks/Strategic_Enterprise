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
        Schema::create('risk_responses', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('risk_id')
                ->constrained('project_risks')
                ->cascadeOnDelete();
            
            $table->foreignId('responsible_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            
            $table->string('type', 30)
                ->default('mitigate')
                ->comment('Type: avoid, transfer, mitigate, accept');
            
            $table->string('title', 255);
            
            $table->text('description')
                ->nullable();
            
            $table->text('action_plan')
                ->nullable()
                ->comment('Plano de ação detalhado');
            
            $table->string('status', 30)
                ->default('planned')
                ->comment('Status: planned, in_progress, completed, cancelled');
            
            $table->date('planned_date')->nullable();
            $table->date('completed_at')->nullable();
            
            $table->decimal('estimated_cost', 12, 2)->nullable();
            $table->decimal('actual_cost', 12, 2)->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_responses');
    }
};

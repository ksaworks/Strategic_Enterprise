<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_viabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->text('technical_feasibility')->nullable();
            $table->decimal('financial_return', 15, 2)->nullable()->comment('ROI estimado');
            $table->integer('payback_period')->nullable()->comment('Meses para retorno');
            $table->integer('score')->default(0)->comment('Pontuação 0-100');
            $table->string('decision')->default('on_hold'); // approved, rejected, on_hold
            $table->text('comments')->nullable();
            $table->foreignId('analyzed_by_id')->nullable()->constrained('users');
            $table->timestamp('analyzed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_viabilities');
    }
};

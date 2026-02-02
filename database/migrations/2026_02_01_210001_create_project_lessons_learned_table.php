<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_lessons_learned', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->string('category'); // scope, time, cost, quality, risk, communication, procurement, stakeholder
            $table->string('type'); // positive, negative
            $table->text('description');
            $table->text('impact')->nullable();
            $table->text('recommendation')->nullable();
            $table->json('tags')->nullable();
            $table->foreignId('reported_by_id')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_lessons_learned');
    }
};

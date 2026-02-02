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
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            
            // Contexto
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('organizer_id')->constrained('users');
            
            // Detalhes
            $table->string('title');
            $table->string('type')->default('other'); // Enum MeetingType
            $table->string('status')->default('scheduled'); // Enum MeetingStatus
            
            // Agenda
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();
            $table->string('location')->nullable();
            
            // Conteúdo
            $table->text('description')->nullable()->comment('Pauta / Agenda');
            $table->longText('minutes')->nullable()->comment('Ata da reunião');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};

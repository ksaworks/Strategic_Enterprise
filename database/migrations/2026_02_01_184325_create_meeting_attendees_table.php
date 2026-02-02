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
        Schema::create('meeting_attendees', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('meeting_id')->constrained()->onDelete('cascade');
            
            // Pode ser um usuário do sistema OU um contato externo
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('contact_id')->nullable()->constrained()->nullOnDelete();
            
            // Backup dos dados caso o usuário/contato seja deletado
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('role')->nullable(); // Organizador, Convidado, etc
            
            // Status de presença
            $table->boolean('attended')->default(false);
            $table->string('absence_reason')->nullable();
            
            $table->timestamps();
            
            // Evitar duplicidade
            $table->unique(['meeting_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_attendees');
    }
};

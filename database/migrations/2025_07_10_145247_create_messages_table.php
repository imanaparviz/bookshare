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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();

            // Verknüpfung mit der Conversation
            $table->foreignId('conversation_id')->constrained()->onDelete('cascade');

            // Absender der Nachricht
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');

            // Nachrichteninhalt
            $table->text('content');

            // Nachrichtentyp (text, system, etc.)
            $table->string('type')->default('text');

            // Lese-Status
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();

            // Metadaten
            $table->json('metadata')->nullable();  // Für zusätzliche Daten

            // Indices für Performance
            $table->index(['conversation_id', 'created_at']);
            $table->index(['sender_id', 'created_at']);
            $table->index(['is_read', 'created_at']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};

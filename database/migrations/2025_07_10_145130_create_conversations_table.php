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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();

            // Verknüpfung mit der Ausleihe
            $table->foreignId('loan_id')->constrained()->onDelete('cascade');

            // Die beiden Gesprächspartner (Leiher und Verleiher)
            $table->foreignId('participant_1_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('participant_2_id')->constrained('users')->onDelete('cascade');

            // Metadaten für das Gespräch
            $table->timestamp('last_message_at')->nullable();
            $table->boolean('is_active')->default(true);

            // Eindeutiger Index: Eine Conversation pro Loan
            $table->unique('loan_id');

            // Index für bessere Performance
            $table->index(['participant_1_id', 'participant_2_id']);
            $table->index('last_message_at');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};

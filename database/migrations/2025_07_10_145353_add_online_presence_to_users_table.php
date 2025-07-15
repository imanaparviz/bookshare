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
        Schema::table('users', function (Blueprint $table) {
            // Online-Präsenz-Felder
            $table->timestamp('last_seen_at')->nullable();
            $table->boolean('is_online')->default(false);
            $table->string('status')->default('active');  // active, away, busy, offline

            // Messaging-Einstellungen
            $table->boolean('message_notifications')->default(true);
            $table->boolean('email_notifications')->default(true);

            // Index für Performance
            $table->index(['is_online', 'last_seen_at']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'last_seen_at',
                'is_online',
                'status',
                'message_notifications',
                'email_notifications',
            ]);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Da SQLite ALTER COLUMN nicht gut unterstützt, verwenden wir einen anderen Ansatz
        // Wir löschen die Tabelle und erstellen sie neu mit dem korrekten Schema

        // Erstelle backup der Daten
        $loans = DB::table('loans')->get();

        // Lösche die Tabelle
        Schema::dropIfExists('loans');

        // Erstelle die Tabelle neu mit korrektem Status-Feld
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            $table->foreignId('borrower_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('lender_id')->constrained('users')->onDelete('cascade');
            $table->date('loan_date');
            $table->date('due_date');
            $table->date('return_date')->nullable();
            // Verwende string statt enum für bessere Kompatibilität
            $table->string('status', 20)->default('angefragt');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes for better performance
            $table->index(['book_id', 'status']);
            $table->index(['borrower_id', 'status']);
            $table->index('due_date');
        });

        // Wiederherstellen der Daten
        foreach ($loans as $loan) {
            DB::table('loans')->insert((array) $loan);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rückgängig: Zurück zum ursprünglichen Enum
        $loans = DB::table('loans')->get();

        Schema::dropIfExists('loans');

        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            $table->foreignId('borrower_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('lender_id')->constrained('users')->onDelete('cascade');
            $table->date('loan_date');
            $table->date('due_date');
            $table->date('return_date')->nullable();
            $table->enum('status', ['aktiv', 'angefragt', 'zurückgegeben', 'überfällig'])->default('aktiv');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes for better performance
            $table->index(['book_id', 'status']);
            $table->index(['borrower_id', 'status']);
            $table->index('due_date');
        });

        foreach ($loans as $loan) {
            DB::table('loans')->insert((array) $loan);
        }
    }
};

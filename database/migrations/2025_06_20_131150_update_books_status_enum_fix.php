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
        // Erstelle backup der Daten
        $books = DB::table('books')->get();

        // Lösche die Tabelle
        Schema::dropIfExists('books');

        // Erstelle die Tabelle neu mit korrektem Status-Feld
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->string('isbn')->unique()->nullable();
            $table->text('description')->nullable();
            $table->string('genre')->nullable();
            $table->year('publication_year')->nullable();
            $table->string('language')->default('deutsch');
            $table->enum('condition', ['sehr gut', 'gut', 'befriedigend', 'akzeptabel'])->default('gut');
            // Erweitere das Status-Enum um die fehlenden Werte
            $table->string('status', 20)->default('verfügbar');
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->string('image_path')->nullable();
            $table->timestamps();
        });

        // Wiederherstellen der Daten
        foreach ($books as $book) {
            DB::table('books')->insert((array) $book);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rückgängig: Zurück zum ursprünglichen Enum
        $books = DB::table('books')->get();

        Schema::dropIfExists('books');

        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->string('isbn')->unique()->nullable();
            $table->text('description')->nullable();
            $table->string('genre')->nullable();
            $table->year('publication_year')->nullable();
            $table->string('language')->default('deutsch');
            $table->enum('condition', ['sehr gut', 'gut', 'befriedigend', 'akzeptabel'])->default('gut');
            $table->enum('status', ['verfügbar', 'ausgeliehen', 'reserviert'])->default('verfügbar');
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->string('image_path')->nullable();
            $table->timestamps();
        });

        foreach ($books as $book) {
            DB::table('books')->insert((array) $book);
        }
    }
};

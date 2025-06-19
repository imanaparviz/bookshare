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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};

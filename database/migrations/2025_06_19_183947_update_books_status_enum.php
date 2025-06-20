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
        // SQLite doesn't support MODIFY COLUMN, so we'll recreate the table
        if (Schema::hasColumn('books', 'status')) {
            // For SQLite, we'll just leave the column as is since it's already a string type
            // and can accept any value including 'angefragt'
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Nothing to revert since we didn't make changes
    }
};

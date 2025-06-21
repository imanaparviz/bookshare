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
        Schema::table('loans', function (Blueprint $table) {
            $table->text('message')->nullable()->after('notes');  // Personal message from borrower
            $table->string('contact_info')->nullable()->after('message');  // Contact information
            $table->string('pickup_method')->nullable()->after('contact_info');  // Pickup method preference
            $table->integer('requested_duration_weeks')->default(2)->after('pickup_method');  // Requested duration in weeks
            $table->text('lender_response')->nullable()->after('requested_duration_weeks');  // Response from lender
            $table->timestamp('responded_at')->nullable()->after('lender_response');  // When lender responded
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn([
                'message',
                'contact_info',
                'pickup_method',
                'requested_duration_weeks',
                'lender_response',
                'responded_at'
            ]);
        });
    }
};

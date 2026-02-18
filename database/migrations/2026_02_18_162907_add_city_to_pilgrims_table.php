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
        Schema::table('pilgrims', function (Blueprint $table) {
            $table->string('city')->nullable()->after('address');
            $table->foreignId('agent_id')->nullable()->constrained()->nullOnDelete()->after('gender');
            
            // Make transaction_id nullable to allow pilgrims without a transaction (Database/Lead)
            $table->foreignId('transaction_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pilgrims', function (Blueprint $table) {
            $table->dropColumn(['city', 'agent_id']);
            // Reverting transaction_id to not null might fail if there are nulls, so be careful.
            // For now, we just drop columns we added.
        });
    }
};

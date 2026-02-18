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
        Schema::table('users', function (Blueprint $table) {
            // Index for faster login/search
            $table->index('username');
        });

        Schema::table('pilgrims', function (Blueprint $table) {
            // Indexes for duplicate checks and search
            $table->index('passport_number');
            $table->index('nik');
            $table->index('transaction_id'); // Foreign key index usually auto-created, but good to ensure for joins
        });

        Schema::table('transactions', function (Blueprint $table) {
            // Indexes for reporting and search
            $table->index('transaction_code');
            $table->index('transaction_date');
            $table->index('status');
            $table->index('agent_id');
            $table->index('package_id');
        });

        Schema::table('packages', function (Blueprint $table) {
            // Index for sorting packages by date
            $table->index('departure_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['username']);
        });

        Schema::table('pilgrims', function (Blueprint $table) {
            $table->dropIndex(['passport_number']);
            $table->dropIndex(['nik']);
            $table->dropIndex(['transaction_id']);
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex(['transaction_code']);
            $table->dropIndex(['transaction_date']);
            $table->dropIndex(['status']);
            $table->dropIndex(['agent_id']);
            $table->dropIndex(['package_id']);
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->dropIndex(['departure_date']);
            $table->dropIndex(['status']);
        });
    }
};

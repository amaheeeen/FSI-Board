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
        Schema::table('agents', function (Blueprint $table) {
            // Rename contact_number to phone
            if (Schema::hasColumn('agents', 'contact_number')) {
                $table->renameColumn('contact_number', 'phone');
            }
            
            // If phone doesn't exist (and contact_number didn't), add it
            if (!Schema::hasColumn('agents', 'phone') && !Schema::hasColumn('agents', 'contact_number')) {
                $table->string('phone');
            }

            // DO NOT add email here, as it's handled in the next migration.
            // DO NOT change email here, as it doesn't exist yet.
            
            // Add location if missing
            if (!Schema::hasColumn('agents', 'location')) {
                $table->string('location')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            if (Schema::hasColumn('agents', 'phone')) {
                $table->renameColumn('phone', 'contact_number');
            }
            if (Schema::hasColumn('agents', 'location')) {
                $table->dropColumn('location');
            }
        });
    }
};

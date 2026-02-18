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
        Schema::table('packages', function (Blueprint $table) {
            if (!Schema::hasColumn('packages', 'hotel_makkah')) {
                $table->string('hotel_makkah')->nullable();
            }
            if (!Schema::hasColumn('packages', 'hotel_madinah')) {
                $table->string('hotel_madinah')->nullable();
            }
            if (!Schema::hasColumn('packages', 'airlines')) {
                $table->string('airlines')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn(['hotel_makkah', 'hotel_madinah', 'airlines']);
        });
    }
};

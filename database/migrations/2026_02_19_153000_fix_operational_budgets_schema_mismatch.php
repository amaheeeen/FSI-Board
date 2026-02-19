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
        Schema::table('operational_budgets', function (Blueprint $table) {
            if (!Schema::hasColumn('operational_budgets', 'period_type')) {
                $table->enum('period_type', ['monthly', 'quarterly', 'semi_annually', 'annually'])->default('monthly')->after('id');
            }
            if (!Schema::hasColumn('operational_budgets', 'start_date')) {
                $table->date('start_date')->nullable()->after('period_type'); // Nullable first to avoid data issues, then update? No, just nullable for safety or default.
            }
            if (!Schema::hasColumn('operational_budgets', 'end_date')) {
                $table->date('end_date')->nullable()->after('start_date');
            }
        });

        // Separate schema call for dropping to avoid issues if adding fails
        Schema::table('operational_budgets', function (Blueprint $table) {
             if (Schema::hasColumn('operational_budgets', 'month')) {
                $table->dropColumn(['month']);
            }
            if (Schema::hasColumn('operational_budgets', 'year')) {
                $table->dropColumn(['year']);
            }
        });
        
        // Make dates not nullable if data allows, or just leave as is for now. 
        // Let's enforce not null but we might need to truncate or default existing rows.
        // Since it's dev, we can truncate if needed, but let's assume empty or update.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operational_budgets', function (Blueprint $table) {
            if (!Schema::hasColumn('operational_budgets', 'month')) {
                $table->integer('month')->nullable();
            }
            if (!Schema::hasColumn('operational_budgets', 'year')) {
                $table->year('year')->nullable();
            }
            
            if (Schema::hasColumn('operational_budgets', 'period_type')) {
                $table->dropColumn(['period_type', 'start_date', 'end_date']);
            }
        });
    }
};

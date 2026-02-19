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
            $table->dropColumn(['month', 'year']);
            $table->enum('period_type', ['monthly', 'quarterly', 'semi_annually', 'annually'])->default('monthly')->after('id');
            $table->date('start_date')->after('period_type');
            $table->date('end_date')->after('start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operational_budgets', function (Blueprint $table) {
            $table->dropColumn(['period_type', 'start_date', 'end_date']);
            $table->integer('month');
            $table->year('year');
        });
    }
};

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
        Schema::create('operational_budgets', function (Blueprint $table) {
            $table->id();
            $table->integer('month');
            $table->year('year');
            $table->string('category'); // Marketing, Salary, Utilities, Office, Others
            $table->decimal('allocated_amount', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operational_budgets');
    }
};

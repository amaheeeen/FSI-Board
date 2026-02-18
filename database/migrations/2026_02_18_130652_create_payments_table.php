<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->cascadeOnDelete();
            $table->decimal('amount_paid', 15, 2);
            $table->string('payment_method'); // Bank Transfer, Cash
            $table->date('payment_date');
            $table->string('proof_of_payment')->nullable();
            $table->string('status')->default('Pending'); // Verified, Pending
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

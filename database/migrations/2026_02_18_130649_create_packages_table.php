<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('departure_date');
            $table->date('return_date');
            $table->decimal('price_quad', 15, 2);
            $table->decimal('price_triple', 15, 2);
            $table->decimal('price_double', 15, 2);
            $table->string('hotel_makkah')->nullable();
            $table->string('hotel_madinah')->nullable();
            $table->integer('quota')->default(0);
            $table->string('status')->default('Open'); // Open, Closed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};

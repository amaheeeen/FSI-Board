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
        Schema::create('packet_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('packet_id')->constrained()->cascadeOnDelete();
            $table->string('component_type'); // hotel, visa, flight
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->decimal('cost', 15, 2);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packet_components');
    }
};

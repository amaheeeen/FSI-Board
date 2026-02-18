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
        Schema::create('tgs_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mutawwif_id')->constrained('users');
            $table->foreignId('packet_id')->constrained()->cascadeOnDelete();
            $table->string('channel_name'); // Agora Channel Name
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tgs_sessions');
    }
};

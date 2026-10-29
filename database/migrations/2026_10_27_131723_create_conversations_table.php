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
        Schema::create('conversations', function (Blueprint $table) {
    $table->id();
    $table->foreignId('laporan_id')->constrained('laporans')->cascadeOnDelete();
    $table->foreignId('reporter_id')->constrained('users')->cascadeOnDelete();
    $table->foreignId('participant_id')->constrained('users')->cascadeOnDelete();
    $table->timestamps();

    $table->unique(['laporan_id', 'reporter_id', 'participant_id']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};

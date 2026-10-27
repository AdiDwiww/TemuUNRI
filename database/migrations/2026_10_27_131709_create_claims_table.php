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
        Schema::create('claims', function (Blueprint $table) {
    $table->id();
    $table->foreignId('laporan_id')->constrained('laporans')->cascadeOnDelete();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->text('description'); // keterangan/ciri kepemilikan
    $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
    $table->timestamps();

    $table->unique(['laporan_id', 'user_id']); // 1 user 1 klaim per laporan
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nim')->unique()->nullable()->after('name');
            $table->string('fakultas')->nullable()->after('nim');
            $table->string('program_studi')->nullable()->after('fakultas');
            $table->string('no_hp')->nullable()->after('program_studi');
            $table->string('foto_profil')->nullable()->after('no_hp');
        });
    }

    /**
     * Batalkan migration.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['nim']);
            $table->dropColumn(['nim', 'fakultas', 'program_studi', 'no_hp', 'foto_profil']);
        });
    }
};

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
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();

            $table->string('nama_kelas'); // contoh: X IPA 1

            // FK ke guru (wali kelas)
            $table->foreignId('guru_id')
                  ->constrained('gurus')
                  ->cascadeOnDelete();

            // FK ke tahun ajaran
            $table->foreignId('tahun_ajaran_id')
                  ->constrained('tahun_ajarans')
                  ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
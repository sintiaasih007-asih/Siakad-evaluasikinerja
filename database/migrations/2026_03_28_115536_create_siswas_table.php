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
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            // data siswa
            $table->string('nis')->unique();
            $table->string('nama');
            $table->enum('jk', ['L', 'P']);
            $table->text('alamat')->nullable();

            // relasi ke kelas
            $table->foreignId('kelas_id')
                  ->constrained('kelas')
                  ->cascadeOnDelete();

            // DATA ORANG TUA 
            $table->string('nama_ortu');
            $table->string('no_hp_ortu')->nullable();
            $table->text('alamat_ortu')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
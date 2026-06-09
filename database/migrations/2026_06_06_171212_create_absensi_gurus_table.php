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
        Schema::create('absensi_gurus', function (Blueprint $table) {

            $table->id();

            $table->foreignId('guru_id')
                ->constrained('gurus')
                ->cascadeOnDelete();

            $table->date('tanggal');

            $table->time('jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();

            $table->enum('status', [
                'Hadir',
                'Terlambat',
                'Izin',
                'Sakit',
                'Alpha'
            ])->default('Hadir');

            $table->string('foto_absensi')->nullable();

            $table->string('foto_wajah')->nullable();

            $table->decimal('latitude',10,7)->nullable();
            $table->decimal('longitude',10,7)->nullable();
            $table->integer('radius_absensi')->default(100);

            $table->text('alamat')->nullable();

            $table->double('jarak')->nullable();

            $table->string('device_id')->nullable();

            $table->boolean('face_verified')->default(false);

            $table->boolean('qr_verified')->default(false);

            $table->timestamp('scan_time')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi_gurus');
    }
};

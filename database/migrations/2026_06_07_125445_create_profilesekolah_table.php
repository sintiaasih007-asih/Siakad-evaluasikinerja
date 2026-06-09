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
        Schema::create('profilesekolah', function (Blueprint $table) {

            $table->id();

            // Sekolah
            $table->string('nama_sekolah')->nullable();
            $table->string('logo_sekolah')->nullable();

            $table->string('npsn')->nullable();
            $table->string('nss')->nullable();
            $table->string('status_sekolah')->nullable();

            $table->string('jenjang')->nullable();
            $table->string('akreditasi')->nullable();

            $table->string('izin_operasional')->nullable();

            // Yayasan
            $table->string('nama_yayasan')->nullable();
            $table->string('logo_yayasan')->nullable();

            // Kepala Sekolah
            $table->string('kepala_sekolah')->nullable();
            $table->string('nip_kepala_sekolah')->nullable();
            $table->string('foto_kepala_sekolah')->nullable();

            // Kontak
            $table->string('telepon')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();

            // Alamat
            $table->text('alamat')->nullable();
            $table->string('desa')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kode_pos')->nullable();

            // GPS
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();

            // Visi Misi
            $table->longText('visi')->nullable();
            $table->longText('misi')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profilesekolah');
    }
};

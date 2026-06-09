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
        Schema::table('absensi_gurus', function (Blueprint $table) {

            if (!Schema::hasColumn('absensi_gurus', 'face_verified')) {
                $table->boolean('face_verified')->default(false);
            }

            if (!Schema::hasColumn('absensi_gurus', 'qr_token')) {
                $table->string('qr_token')->nullable();
            }

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensi_gurus', function (Blueprint $table) {

            if (Schema::hasColumn('absensi_gurus', 'face_verified')) {
                $table->dropColumn('face_verified');
            }

            if (Schema::hasColumn('absensi_gurus', 'qr_token')) {
                $table->dropColumn('qr_token');
            }

        });
    }
};

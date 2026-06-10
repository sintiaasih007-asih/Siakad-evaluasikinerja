<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absensi_gurus', function (Blueprint $table) {

            if (!Schema::hasColumn('absensi_gurus', 'qr_verified')) {
                $table->boolean('qr_verified')->default(false)->after('face_verified');
            }

            if (!Schema::hasColumn('absensi_gurus', 'qr_token')) {
                $table->string('qr_token')->nullable()->after('qr_verified');
            }

            if (!Schema::hasColumn('absensi_gurus', 'scan_time')) {
                $table->timestamp('scan_time')->nullable()->after('qr_token');
            }

        });
    }

    public function down(): void
    {
        Schema::table('absensi_gurus', function (Blueprint $table) {

            $cols = ['qr_verified', 'qr_token', 'scan_time'];

            foreach ($cols as $col) {
                if (Schema::hasColumn('absensi_gurus', $col)) {
                    $table->dropColumn($col);
                }
            }

        });
    }
};

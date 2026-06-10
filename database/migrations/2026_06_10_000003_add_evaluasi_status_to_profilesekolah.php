<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profilesekolah', function (Blueprint $table) {
            if (!Schema::hasColumn('profilesekolah', 'evaluasi_bulanan_aktif')) {
                $table->boolean('evaluasi_bulanan_aktif')->default(false)->after('misi');
            }
            if (!Schema::hasColumn('profilesekolah', 'evaluasi_semesteran_aktif')) {
                $table->boolean('evaluasi_semesteran_aktif')->default(false)->after('evaluasi_bulanan_aktif');
            }
        });
    }

    public function down(): void
    {
        Schema::table('profilesekolah', function (Blueprint $table) {
            foreach (['evaluasi_bulanan_aktif', 'evaluasi_semesteran_aktif'] as $col) {
                if (Schema::hasColumn('profilesekolah', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

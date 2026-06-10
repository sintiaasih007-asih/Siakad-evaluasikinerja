<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profilesekolah', function (Blueprint $table) {
            if (!Schema::hasColumn('profilesekolah', 'kurikulum')) {
                $table->string('kurikulum')->nullable()->after('akreditasi');
            }
            if (!Schema::hasColumn('profilesekolah', 'radius_absensi')) {
                $table->integer('radius_absensi')->default(100)->after('longitude');
            }
        });
    }

    public function down(): void
    {
        Schema::table('profilesekolah', function (Blueprint $table) {
            foreach (['kurikulum', 'radius_absensi'] as $col) {
                if (Schema::hasColumn('profilesekolah', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

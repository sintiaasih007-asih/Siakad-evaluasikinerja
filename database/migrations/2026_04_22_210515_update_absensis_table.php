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
        Schema::table('absensis', function (Blueprint $table) {

            $table->foreignId('guru_id')
                  ->nullable()
                  ->after('jadwal_id')
                  ->constrained('gurus')
                  ->cascadeOnDelete();

            $table->string('bulan')->nullable()->after('pertemuan');
            $table->string('semester')->nullable()->after('bulan');
            $table->string('tahun_ajaran')->nullable()->after('semester');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensis', function (Blueprint $table) {

            $table->dropForeign(['guru_id']);
            $table->dropColumn([
                'guru_id',
                'bulan',
                'semester',
                'tahun_ajaran'
            ]);

        });
    }
};

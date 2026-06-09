<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nilais', function (Blueprint $table) {

            $table->date('tanggal')->nullable();
            $table->string('bulan')->nullable();
            $table->string('semester')->nullable();
            $table->string('tahun_ajaran')->nullable();
            $table->double('rata_rata')->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('nilais', function (Blueprint $table) {

            $table->dropColumn([
                'tanggal',
                'bulan',
                'semester',
                'tahun_ajaran',
                'rata_rata'
            ]);

        });
    }
};
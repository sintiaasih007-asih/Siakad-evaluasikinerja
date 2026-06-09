<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['admin','guru','siswa'])
                    ->default('admin');
            }

            if (!Schema::hasColumn('users', 'guru_id')) {
                $table->foreignId('guru_id')
                    ->nullable()
                    ->constrained('gurus')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('users', 'siswa_id')) {
                $table->foreignId('siswa_id')
                    ->nullable()
                    ->constrained('siswas')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }

        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropForeign(['guru_id']);
            $table->dropForeign(['siswa_id']);

            $table->dropColumn([
                'role',
                'guru_id',
                'siswa_id',
                'is_active'
            ]);
        });
    }
};
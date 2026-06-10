<?php
/**
 * Script sementara untuk menjalankan pending migrations.
 * HAPUS file ini setelah dijalankan!
 */
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

$hasil = [];

// ── absensi_gurus ──────────────────────────────────────────────────────
try {
    Schema::table('absensi_gurus', function (Blueprint $table) use (&$hasil) {
        foreach (['qr_verified'=>'boolean','qr_token'=>'string','scan_time'=>'timestamp'] as $col=>$type) {
            if (!Schema::hasColumn('absensi_gurus', $col)) {
                if ($type === 'boolean')   $table->boolean($col)->default(false);
                if ($type === 'string')    $table->string($col)->nullable();
                if ($type === 'timestamp') $table->timestamp($col)->nullable();
                $hasil[] = "✅ absensi_gurus.$col ditambahkan";
            } else { $hasil[] = "ℹ️ absensi_gurus.$col sudah ada"; }
        }
    });
} catch (\Exception $e) { $hasil[] = '❌ absensi_gurus: '.$e->getMessage(); }

// ── profilesekolah ─────────────────────────────────────────────────────
try {
    Schema::table('profilesekolah', function (Blueprint $table) use (&$hasil) {
        if (!Schema::hasColumn('profilesekolah', 'kurikulum')) {
            $table->string('kurikulum')->nullable();
            $hasil[] = '✅ profilesekolah.kurikulum ditambahkan';
        } else { $hasil[] = 'ℹ️ profilesekolah.kurikulum sudah ada'; }

        if (!Schema::hasColumn('profilesekolah', 'radius_absensi')) {
            $table->integer('radius_absensi')->default(100);
            $hasil[] = '✅ profilesekolah.radius_absensi ditambahkan';
        } else { $hasil[] = 'ℹ️ profilesekolah.radius_absensi sudah ada'; }

        if (!Schema::hasColumn('profilesekolah', 'evaluasi_bulanan_aktif')) {
            $table->boolean('evaluasi_bulanan_aktif')->default(false);
            $hasil[] = '✅ profilesekolah.evaluasi_bulanan_aktif ditambahkan';
        } else { $hasil[] = 'ℹ️ profilesekolah.evaluasi_bulanan_aktif sudah ada'; }

        if (!Schema::hasColumn('profilesekolah', 'evaluasi_semesteran_aktif')) {
            $table->boolean('evaluasi_semesteran_aktif')->default(false);
            $hasil[] = '✅ profilesekolah.evaluasi_semesteran_aktif ditambahkan';
        } else { $hasil[] = 'ℹ️ profilesekolah.evaluasi_semesteran_aktif sudah ada'; }
    });
} catch (\Exception $e) { $hasil[] = '❌ profilesekolah: '.$e->getMessage(); }

echo '<pre style="font-family:monospace;font-size:14px;padding:24px;background:#1e1e2e;color:#cdd6f4;min-height:100vh">';
echo "<b style='color:#a6e3a1'>Migration Results</b>\n\n";
foreach ($hasil as $h) { echo $h."\n"; }
echo "\n<b style='color:#f38ba8'>⚠️ SEGERA HAPUS file ini: public/run-migration.php</b>";
echo '</pre>';

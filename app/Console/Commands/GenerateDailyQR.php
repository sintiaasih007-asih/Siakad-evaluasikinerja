<?php

namespace App\Console\Commands;

use App\Models\AbsensiGuru;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateDailyQR extends Command
{
    protected $signature   = 'app:generate-daily-qr';
    protected $description = 'Generate token QR absensi harian guru';

    public function handle(): void
    {
        // Reset qr_token lama
        AbsensiGuru::query()->update(['qr_token' => null]);

        // Simpan token baru ke cache hingga akhir hari
        $token = Str::random(32);

        cache()->put(
            'qr_absensi_harian',
            $token,
            now()->endOfDay()
        );

        $this->info('Token QR harian berhasil dibuat: ' . $token);
    }
}

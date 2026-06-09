<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

#[Signature('app:generate-daily-q-r')]
#[Description('Command description')]
class GenerateDailyQR extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        AbsensiGuru::query()
            ->update([
                'qr_token' => null
            ]);

        cache()->put(
            'qr_absensi_harian',
            Str::random(30),
            now()->endOfDay()
        );
    }
}

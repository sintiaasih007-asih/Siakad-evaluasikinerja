<?php

namespace App\Services;

class FuzzyMamdaniService
{
    public function hitung($nilai, $absensi, $sikap, $disiplin)
    {
        // =========================
        // 1. FUZZIFIKASI
        // =========================

        // NILAI
        $rendah = $this->triangular($nilai, 0, 0, 60);
        $sedang = $this->triangular($nilai, 50, 70, 85);
        $tinggi = $this->triangular($nilai, 75, 100, 100);

        // ABSENSI
        $kurang = $this->triangular($absensi, 0, 0, 70);
        $cukup  = $this->triangular($absensi, 60, 75, 85);
        $baik   = $this->triangular($absensi, 80, 100, 100);

        // SIKAP
        $sikapKurang = $this->triangular($sikap, 0, 0, 70);
        $sikapBaik   = $this->triangular($sikap, 60, 100, 100);

        // DISIPLIN
        $disiplinKurang = $this->triangular($disiplin, 0, 0, 70);
        $disiplinBaik   = $this->triangular($disiplin, 60, 100, 100);

        // =========================
        // 2. RULE BASE 36 RULE
        // =========================

        $rules = [];

        // helper function
        $min = fn(...$v) => min($v);

        // =========================
        // RENDAH (12 RULE)
        // =========================
        $rules[] = ['val' => $min($rendah,$kurang,$sikapKurang,$disiplinKurang), 'out' => 0]; // kurang
        $rules[] = ['val' => $min($rendah,$kurang,$sikapKurang,$disiplinBaik), 'out' => 0];
        $rules[] = ['val' => $min($rendah,$kurang,$sikapBaik,$disiplinKurang), 'out' => 0];
        $rules[] = ['val' => $min($rendah,$kurang,$sikapBaik,$disiplinBaik), 'out' => 1];

        $rules[] = ['val' => $min($rendah,$cukup,$sikapKurang,$disiplinKurang), 'out' => 0];
        $rules[] = ['val' => $min($rendah,$cukup,$sikapKurang,$disiplinBaik), 'out' => 1];
        $rules[] = ['val' => $min($rendah,$cukup,$sikapBaik,$disiplinKurang), 'out' => 1];
        $rules[] = ['val' => $min($rendah,$cukup,$sikapBaik,$disiplinBaik), 'out' => 1];

        $rules[] = ['val' => $min($rendah,$baik,$sikapKurang,$disiplinKurang), 'out' => 0];
        $rules[] = ['val' => $min($rendah,$baik,$sikapKurang,$disiplinBaik), 'out' => 1];
        $rules[] = ['val' => $min($rendah,$baik,$sikapBaik,$disiplinKurang), 'out' => 1];
        $rules[] = ['val' => $min($rendah,$baik,$sikapBaik,$disiplinBaik), 'out' => 1];

        // =========================
        // SEDANG (12 RULE)
        // =========================
        $rules[] = ['val' => $min($sedang,$kurang,$sikapKurang,$disiplinKurang), 'out' => 0];
        $rules[] = ['val' => $min($sedang,$kurang,$sikapKurang,$disiplinBaik), 'out' => 1];
        $rules[] = ['val' => $min($sedang,$kurang,$sikapBaik,$disiplinKurang), 'out' => 1];
        $rules[] = ['val' => $min($sedang,$kurang,$sikapBaik,$disiplinBaik), 'out' => 1];

        $rules[] = ['val' => $min($sedang,$cukup,$sikapKurang,$disiplinKurang), 'out' => 1];
        $rules[] = ['val' => $min($sedang,$cukup,$sikapKurang,$disiplinBaik), 'out' => 2];
        $rules[] = ['val' => $min($sedang,$cukup,$sikapBaik,$disiplinKurang), 'out' => 2];
        $rules[] = ['val' => $min($sedang,$cukup,$sikapBaik,$disiplinBaik), 'out' => 2];

        $rules[] = ['val' => $min($sedang,$baik,$sikapKurang,$disiplinKurang), 'out' => 1];
        $rules[] = ['val' => $min($sedang,$baik,$sikapKurang,$disiplinBaik), 'out' => 2];
        $rules[] = ['val' => $min($sedang,$baik,$sikapBaik,$disiplinKurang), 'out' => 2];
        $rules[] = ['val' => $min($sedang,$baik,$sikapBaik,$disiplinBaik), 'out' => 2];

        // =========================
        // TINGGI (12 RULE)
        // =========================
        $rules[] = ['val' => $min($tinggi,$kurang,$sikapKurang,$disiplinKurang), 'out' => 1];
        $rules[] = ['val' => $min($tinggi,$kurang,$sikapKurang,$disiplinBaik), 'out' => 2];
        $rules[] = ['val' => $min($tinggi,$kurang,$sikapBaik,$disiplinKurang), 'out' => 2];
        $rules[] = ['val' => $min($tinggi,$kurang,$sikapBaik,$disiplinBaik), 'out' => 2];

        $rules[] = ['val' => $min($tinggi,$cukup,$sikapKurang,$disiplinKurang), 'out' => 2];
        $rules[] = ['val' => $min($tinggi,$cukup,$sikapKurang,$disiplinBaik), 'out' => 3];
        $rules[] = ['val' => $min($tinggi,$cukup,$sikapBaik,$disiplinKurang), 'out' => 3];
        $rules[] = ['val' => $min($tinggi,$cukup,$sikapBaik,$disiplinBaik), 'out' => 3];

        $rules[] = ['val' => $min($tinggi,$baik,$sikapKurang,$disiplinKurang), 'out' => 2];
        $rules[] = ['val' => $min($tinggi,$baik,$sikapKurang,$disiplinBaik), 'out' => 3];
        $rules[] = ['val' => $min($tinggi,$baik,$sikapBaik,$disiplinKurang), 'out' => 3];
        $rules[] = ['val' => $min($tinggi,$baik,$sikapBaik,$disiplinBaik), 'out' => 3];

        // =========================
        // 3. INFERENSI MAX
        // =========================
        $output = [
            0 => 0, // Perlu Pembinaan
            1 => 0, // Perlu Bimbingan
            2 => 0, // Baik
            3 => 0  // Sangat Baik
        ];

        foreach ($rules as $r) {
            if ($r['val'] > $output[$r['out']]) {
                $output[$r['out']] = $r['val'];
            }
        }

        // =========================
        // 4. DEFUZZY CENTROID SEDERHANA
        // =========================
        $nilaiOutput = [
            0 => 40,   // pembinaan
            1 => 60,   // bimbingan
            2 => 80,   // baik
            3 => 100   // sangat baik
        ];

        $numerator = 0;
        $denominator = 0;

        foreach ($output as $i => $val) {
            $numerator += $val * $nilaiOutput[$i];
            $denominator += $val;
        }

        $skor = $denominator == 0 ? 0 : $numerator / $denominator;

        // =========================
        // 5. KATEGORI FINAL
        // =========================
        if ($skor < 50) {
            $kategori = 'Perlu Pembinaan';
        } elseif ($skor < 70) {
            $kategori = 'Perlu Bimbingan';
        } elseif ($skor < 85) {
            $kategori = 'Baik';
        } else {
            $kategori = 'Sangat Baik';
        }

        return [
            'skor' => round($skor, 2),
            'kategori' => $kategori,
            'detail' => $output
        ];
    }

    // =========================
    // MEMBERSHIP FUNCTION SEGITIGA
    // =========================
    private function triangular($x, $a, $b, $c)
    {
        if ($x <= $a || $x >= $c) return 0;
        if ($x == $b) return 1;
        if ($x > $a && $x < $b) return ($x - $a) / ($b - $a);
        if ($x > $b && $x < $c) return ($c - $x) / ($c - $b);
        return 0;
    }
}
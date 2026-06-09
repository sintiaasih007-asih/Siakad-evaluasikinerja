<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    protected $fillable = [
        'siswa_id',
        'jadwal_id',
        'guru_id',
        'jenis_nilai',
        'nama_penilaian',
        'nilai',
        'tanggal',
        'bulan',
        'semester',
        'tahun_ajaran'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }
}

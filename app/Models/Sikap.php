<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sikap extends Model
{
    protected $fillable = [
        'siswa_id',
        'jadwal_id',
        'guru_id',
        'tanggal',
        'bulan',
        'semester',
        'tahun_ajaran',
        'nilai_sikap',
        'keterangan'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $fillable = [
        'jadwal_id',
        'guru_id',
        'tanggal',
        'pertemuan',
        'bulan',
        'semester',
        'tahun_ajaran'
    ];
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function details()
    {
        return $this->hasMany(AbsensiDetail::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
    
}

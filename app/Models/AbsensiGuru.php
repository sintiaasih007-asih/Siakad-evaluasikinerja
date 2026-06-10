<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiGuru extends Model
{
    protected $fillable = [
        'guru_id',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'status',
        'latitude',
        'longitude',
        'alamat',
        'foto_absensi',
        'face_verified',
        'qr_verified',
        'qr_token',
        'scan_time',
        'jarak',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}

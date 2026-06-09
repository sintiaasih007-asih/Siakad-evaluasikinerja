<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Kelas;

class Siswa extends Model
{
    protected $fillable = [
        'nis',
        'nama',
        'jk',
        'alamat',
        'kelas_id',

        // orang tua
        'nama_ortu',
        'no_hp_ortu',
        'alamat_ortu'
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function nilai()
    {
        return $this->hasMany(\App\Models\Nilai::class);
    }

    public function sikap()
    {
        return $this->hasMany(Sikap::class);
    }

    public function disiplin()
    {
        return $this->hasMany(Kedisiplinan::class);
    }

    public function riwayatKelas()
    {
        return $this->hasMany(RiwayatKelas::class);
    }
}
<?php

namespace App\Models;

use App\Models\Tahunajaran;
use App\Models\Guru;
use App\Models\Guru as ModelsGuru;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'guru_id',
        'tahun_ajaran_id'
    ];

    // relasi ke guru (wali kelas)
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    // relasi ke tahun ajaran
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function siswas()
    {
        return $this->hasMany(Siswa::class, 'kelas_id');
    }

    public function riwayatKelas()
    {
        return $this->hasMany(RiwayatKelas::class);
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }
}

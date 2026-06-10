<?php

namespace App\Models;

use App\Models\Kelas;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $fillable = [
        'nama',
        'nip',
        'email',
        'foto_wajah',
        'face_descriptor',
    ];

    public function kelas()
    {
        return $this->hasMany(Kelas::class);
    }

    public function mapel()
    {
        return $this->hasMany(Mapel::class);
    }   

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function absensis()
    {
        return $this->hasMany(AbsensiGuru::class);
    }
}

<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name',
    'email',
    'password',
    'role',
    'is_admin',
    'is_active',
    'guru_id',
    'siswa_id'
])]

#[Hidden([
    'password',
    'remember_token'
])]

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RELASI
    |--------------------------------------------------------------------------
    */

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function logins()
    {
        return $this->hasMany(UserLogin::class);
    }

    /*
    |--------------------------------------------------------------------------
    | ROLE
    |--------------------------------------------------------------------------
    */

    public function isAdmin()
    {
        return $this->role == 'admin';
    }

    public function isGuru()
    {
        return $this->role == 'guru';
    }

    public function isWaliKelas()
    {
        return $this->role == 'guru&wali_kelas';
    }

    public function isKepalaSekolah()
    {
        return $this->role == 'kepala_sekolah';
    }

    public function isOrangTua()
    {
        return $this->role == 'orang_tua';
    }
}
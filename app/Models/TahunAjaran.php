<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    protected $fillable = ['tahun', 'semester', 'is_active'];

    public function kelas()
{
    return $this->hasMany(Kelas::class);
}
}


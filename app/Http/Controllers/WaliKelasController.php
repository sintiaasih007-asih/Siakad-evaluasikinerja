<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Support\Facades\Auth;

class WaliKelasController extends Controller
{
    public function dataKelasBinaan()
    {
        $guruId = Auth::user()->guru_id;

        $kelas = Kelas::with('siswas')
            ->where('guru_id', $guruId)
            ->first();

        if (!$kelas) {
            return view('walikelas.index', [
                'kelas' => null,
                'siswas' => collect([]),
                'total' => 0,
                'laki' => 0,
                'perempuan' => 0,
            ]);
        }

        $siswas = $kelas->siswas;

        return view('walikelas.index', [
            'kelas' => $kelas,
            'siswa' => $siswas,
            'total' => $siswas->count(),
            'laki' => $siswas->where('jk', 'L')->count(),
            'perempuan' => $siswas->where('jk', 'P')->count(),
        ]);
    }
}
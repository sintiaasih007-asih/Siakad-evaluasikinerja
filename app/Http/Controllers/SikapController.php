<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Siswa;
use Illuminate\Http\Request;

class SikapController extends Controller
{
    public function index()
    {
        $jadwals = Jadwal::with('kelas','mapel','guru')->get();

        return view('sikap.index', compact('jadwals'));
    }

    public function create($id)
    {
        $jadwal = Jadwal::with('kelas','mapel','guru')->findOrFail($id);

        $siswas = Siswa::where('kelas_id', $jadwal->kelas_id)->get();

        return view('sikap.create', compact('jadwal','siswas'));
    }
}

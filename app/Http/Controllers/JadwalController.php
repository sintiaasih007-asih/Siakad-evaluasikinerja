<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jadwals = Jadwal::with(['kelas','mapel','guru'])->get();
        $kelas = Kelas::all();
        $mapels = Mapel::all();
        $gurus = Guru::all();

        return view('jadwal.index', compact('jadwals','kelas','mapels','gurus'));
    }

    public function create()
    {
        return view('jadwal.create', [
            'gurus' => Guru::all(),
            'kelas' => Kelas::all(),
            'mapels' => Mapel::all()
        ]);
    }

    public function store(Request $request)
    {
        Jadwal::create($request->all());
        return redirect()->route('jadwal.index');
    }

    public function edit($id)
    {
        return view('jadwal.edit', [
            'jadwal' => Jadwal::findOrFail($id),
            'gurus' => Guru::all(),
            'kelas' => Kelas::all(),
            'mapels' => Mapel::all()
        ]);
    }

    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->update($request->all());

        return redirect()->route('jadwal.index');
    }

    public function destroy($id)
    {
        Jadwal::findOrFail($id)->delete();
        return redirect()->route('jadwal.index');
    }
}
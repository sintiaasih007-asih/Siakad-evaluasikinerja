<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Guru;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kelas = Kelas::with(['guru', 'tahunAjaran'])->get();
        $gurus = Guru::all();
        $tahunAjarans = TahunAjaran::all();

    return view('kelas.index', compact('kelas', 'gurus', 'tahunAjarans'));
}

    public function create()
    {
        $guru = Guru::all();
        $tahun = TahunAjaran::all();
        return view('kelas.create', compact('guru', 'tahun'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required',
            'guru_id' => 'required',
            'tahun_ajaran_id' => 'required'
        ]);

        Kelas::create($request->all());

        return redirect()->route('kelas.index');
    }

    public function edit($id)
    {
        $kelas = Kelas::findOrFail($id);
        $guru = Guru::all();
        $tahun = TahunAjaran::all();

        return view('kelas.edit', compact('kelas', 'guru', 'tahun'));
    }

    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $kelas->update($request->all());

        return redirect()->route('kelas.index');
    }

    public function destroy($id)
    {
        Kelas::destroy($id);
        return back();
    }
}
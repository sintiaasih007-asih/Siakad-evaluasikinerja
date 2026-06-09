<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Models\RiwayatKelas;

class SiswaController extends Controller
{
    public function index()
    {
        $kelas = Kelas::with([
            'siswas' => function($q){
                $q->where('status', 'aktif');
            }
        ])->get();

        return view('siswa.index', compact('kelas'));

        // $siswas = Siswa::with('kelas')->get();
        // $kelas = Kelas::all();

        // return view('siswa.index', compact('siswas', 'kelas'));
    }

    public function create()
    {
        $kelas = Kelas::all();
        return view('siswa.create', compact('kelas'));
    }


    public function store(Request $request)
    {
        $siswa = Siswa::create([

            'nis' => $request->nis,
            'nama' => $request->nama,
            'jk' => $request->jk,
            'kelas_id' => $request->kelas_id,
            'alamat' => $request->alamat,
            'nama_ortu' => $request->nama_ortu,
            'no_hp_ortu' => $request->no_hp_ortu,
            'alamat_ortu' => $request->alamat_ortu,
            'status' => 'aktif'
        ]);

        RiwayatKelas::create([
            'siswa_id' => $siswa->id,
            'kelas_id' => $request->kelas_id,
            'tahun_ajaran' => '2025/2026',
            'status' => 'aktif'
        ]);

        return redirect()
            ->route('siswa.index')
            ->with('success', 'Data siswa berhasil ditambahkan');
    }

    // public function store(Request $request)
    // {

    //     $request->validate([
    //         'nis' => 'required|unique:siswas,nis',
    //         'nama' => 'required',
    //         'jk' => 'required|in:L,P',
    //         'kelas_id' => 'required',
    //     ]);

    //     Siswa::create([
    //         'nis' => $request->nis,
    //         'nama' => $request->nama,
    //         'jk' => $request->jk,
    //         'kelas_id' => $request->kelas_id, // INI PENTING
    //         'alamat' => $request->alamat,
    //         'nama_ortu' => $request->nama_ortu,
    //         'no_hp_ortu' => $request->no_hp_ortu,
    //         'alamat_ortu' => $request->alamat_ortu,
    //     ]);

    //     return redirect('/siswa')->with('success', 'Data berhasil ditambah');
    // }

    public function edit($id)
    {
        $siswa = Siswa::findOrFail($id);
        $kelas = Kelas::all();
        return view('siswa.edit', compact('siswa', 'kelas'));
    }

    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        $siswa->update([
            'nis' => $request->nis,
            'nama' => $request->nama,
            'jk' => $request->jk,
            'kelas_id' => $request->kelas_id,
            'alamat' => $request->alamat,
            'nama_ortu' => $request->nama_ortu,
            'no_hp_ortu' => $request->no_hp_ortu,
            'alamat_ortu' => $request->alamat_ortu,
        ]);

        return redirect('/siswa')->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);

        $siswa->delete();
        return redirect('/siswa')->with('success', 'Data berhasil dihapus');
    }

    public function naikKelas()
    {
        $siswas = Siswa::where('status', 'aktif')->get();

        foreach ($siswas as $siswa) {

            $kelasSekarang = $siswa->kelas->nama_kelas;

            // AMBIL ANGKA DEPAN
            preg_match('/\d+/', $kelasSekarang, $match);

            if (!$match) {
                continue;
            }

            $tingkat = (int) $match[0];

            // JIKA KELAS 9 → LULUS
            if ($tingkat >= 9) {

                $siswa->update([
                    'status' => 'lulus'
                ]);

                RiwayatKelas::create([
                    'siswa_id' => $siswa->id,
                    'kelas_id' => $siswa->kelas_id,
                    'tahun_ajaran' => '2026/2027',
                    'status' => 'lulus'
                ]);

                continue;
            }

            // KELAS BARU
            $kelasBaruNama = preg_replace(
                '/^'.$tingkat.'/',
                $tingkat + 1,
                $kelasSekarang
            );

            $kelasBaru = Kelas::where(
                'nama_kelas',
                $kelasBaruNama
            )->first();

            // JIKA KELAS BARU ADA
            if ($kelasBaru) {

                $siswa->update([
                    'kelas_id' => $kelasBaru->id
                ]);

                RiwayatKelas::create([
                    'siswa_id' => $siswa->id,
                    'kelas_id' => $kelasBaru->id,
                    'tahun_ajaran' => '2026/2027',
                    'status' => 'naik_kelas'
                ]);
            }
        }

        return back()->with(
            'success',
            'Kenaikan kelas berhasil'
        );
    }

    public function riwayatKelas()
    {
        $riwayat = \App\Models\RiwayatKelas::with([
            'siswa',
            'kelas'
        ])->latest()->get();

        $siswas = \App\Models\Siswa::with('kelas')->get();

        return view('riwayat.index', compact(
            'riwayat',
            'siswas'
        ));
    }

    public function alumni()
    {
        $alumni = Siswa::where(
            'status',
            'lulus'
        )->with('kelas')->get();

        return view(
            'alumni.index',
            compact('alumni')
        );
    }
}
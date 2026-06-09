<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Siswa;
use App\Models\Kedisiplinan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KedisiplinanController extends Controller
{
    public function index()
    {
        $jadwals = Jadwal::with('kelas','mapel','guru')->get();

        return view('kedisiplinan.index', compact('jadwals'));
    }

    public function create($id)
    {
        $jadwal = Jadwal::with('kelas','mapel','guru')->findOrFail($id);

        $siswas = Siswa::where('kelas_id', $jadwal->kelas_id)->get();

        return view('kedisiplinan.create', compact('jadwal','siswas'));
    }

    public function store(Request $request)
    {
        foreach($request->siswa_id as $key => $siswa)
        {
            $today = Carbon::now();

            Kedisiplinan::create([
                'siswa_id'        => $siswa,
                'jadwal_id'       => $request->jadwal_id,
                'guru_id'         => auth()->user()->id,
                'tanggal'         => $today,
                'bulan'           => $today->translatedFormat('F'),
                'semester'        => $today->month <= 6 ? 'Genap' : 'Ganjil',
                'tahun_ajaran'    => $today->year.'/'.($today->year+1),
                'nilai_disiplin'  => $request->nilai_disiplin[$key],
                'keterangan'      => $request->keterangan[$key] ?? null,
            ]);
        }

        return redirect()->route('kedisiplinan.index')
        ->with('success','Data kedisiplinan berhasil disimpan');
    }
}
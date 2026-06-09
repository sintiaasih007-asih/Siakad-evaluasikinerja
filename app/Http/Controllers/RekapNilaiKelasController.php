<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\DB;

class RekapNilaiKelasController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $guruId = $user->guru_id;

        $tahunAktif = TahunAjaran::where('is_active',1)->first();

        $kelas = Kelas::where('guru_id',$guruId)
                    ->where('tahun_ajaran_id', $tahunAktif->id)
                    ->first();

        if(!$kelas){
            return view('walikelas.rekapnilai', [
                'kelas' => null,
                'data' => [],
                'tahunAktif' => $tahunAktif
            ]);
        }

        $siswas = Siswa::where('kelas_id',$kelas->id)->get();

        $data = [];

        foreach($siswas as $siswa){

            $hadir = DB::table('absensi_details')
                ->join('absensis','absensi_details.absensi_id','=','absensis.id')
                ->where('absensi_details.siswa_id',$siswa->id)
                ->where('absensi_details.status','hadir')
                ->where('absensis.tahun_ajaran',$tahunAktif->tahun)
                ->count();

            $totalAbsen = DB::table('absensi_details')
                ->join('absensis','absensi_details.absensi_id','=','absensis.id')
                ->where('absensi_details.siswa_id',$siswa->id)
                ->where('absensis.tahun_ajaran',$tahunAktif->tahun)
                ->count();

            $persenHadir = $totalAbsen > 0 ? round(($hadir/$totalAbsen)*100) : 0;

            $nilai = DB::table('nilais')
                ->where('siswa_id',$siswa->id)
                ->where('tahun_ajaran',$tahunAktif->tahun)
                ->avg('nilai');

            $sikap = DB::table('sikaps')
                ->where('siswa_id',$siswa->id)
                ->where('tahun_ajaran',$tahunAktif->tahun)
                ->avg('nilai_sikap');

            $disiplin = DB::table('kedisiplinans')
                ->where('siswa_id',$siswa->id)
                ->where('tahun_ajaran',$tahunAktif->tahun)
                ->avg('nilai_disiplin');

            $data[] = [
                'nama' => $siswa->nama,
                'nis' => $siswa->nis,
                'hadir' => $persenHadir,
                'nilai' => round($nilai ?? 0),
                'sikap' => round($sikap ?? 0),
                'disiplin' => round($disiplin ?? 0),
            ];
        }

        return view('walikelas.rekapnilai', compact(
            'kelas',
            'data',
            'tahunAktif'
        ));
    }
}
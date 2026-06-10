<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Siswa;

class EvaluasiSemesteranAnakController extends Controller
{
    private const BULAN_GANJIL = ['Juli','Agustus','September','Oktober','November','Desember'];
    private const BULAN_GENAP  = ['Januari','Februari','Maret','April','Mei','Juni'];

    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user->siswa_id) {
            return view('orangtua.evaluasi-semesteran-anak', ['siswa' => null]);
        }

        $siswa      = Siswa::with('kelas')->findOrFail($user->siswa_id);
        $tahunAktif = DB::table('tahun_ajarans')->where('is_active', 1)->first();
        $tahunStr   = $tahunAktif->tahun ?? '';

        // ── Filter semester ────────────────────────────────────────────────
        $semesterFilter = $request->semester ?? ($tahunAktif->semester ?? 'genap');
        $bulanList      = strtolower($semesterFilter) === 'ganjil'
            ? self::BULAN_GANJIL
            : self::BULAN_GENAP;

        // ── Semua jadwal di kelas siswa ────────────────────────────────────
        $jadwalIds = DB::table('jadwals')
            ->where('kelas_id', $siswa->kelas_id)
            ->pluck('id');

        // ── Akumulasi per bulan ────────────────────────────────────────────
        $perBulan = [];
        foreach ($bulanList as $bln) {
            $avgN = DB::table('nilais')
                ->where('siswa_id',$siswa->id)->whereIn('jadwal_id',$jadwalIds)
                ->where('bulan',$bln)->where('tahun_ajaran',$tahunStr)->avg('nilai');

            $ttl = DB::table('absensi_details as d')
                ->join('absensis as a','a.id','=','d.absensi_id')
                ->where('d.siswa_id',$siswa->id)->whereIn('a.jadwal_id',$jadwalIds)
                ->where('a.bulan',$bln)->where('a.tahun_ajaran',$tahunStr)->count();

            $hdr = DB::table('absensi_details as d')
                ->join('absensis as a','a.id','=','d.absensi_id')
                ->where('d.siswa_id',$siswa->id)->where('d.status','hadir')
                ->whereIn('a.jadwal_id',$jadwalIds)
                ->where('a.bulan',$bln)->where('a.tahun_ajaran',$tahunStr)->count();

            $izn = DB::table('absensi_details as d')
                ->join('absensis as a','a.id','=','d.absensi_id')
                ->where('d.siswa_id',$siswa->id)->where('d.status','izin')
                ->whereIn('a.jadwal_id',$jadwalIds)
                ->where('a.bulan',$bln)->where('a.tahun_ajaran',$tahunStr)->count();

            $skt = DB::table('absensi_details as d')
                ->join('absensis as a','a.id','=','d.absensi_id')
                ->where('d.siswa_id',$siswa->id)->where('d.status','sakit')
                ->whereIn('a.jadwal_id',$jadwalIds)
                ->where('a.bulan',$bln)->where('a.tahun_ajaran',$tahunStr)->count();

            $alp = DB::table('absensi_details as d')
                ->join('absensis as a','a.id','=','d.absensi_id')
                ->where('d.siswa_id',$siswa->id)->where('d.status','alpha')
                ->whereIn('a.jadwal_id',$jadwalIds)
                ->where('a.bulan',$bln)->where('a.tahun_ajaran',$tahunStr)->count();

            $avgS = DB::table('sikaps')->where('siswa_id',$siswa->id)
                ->whereIn('jadwal_id',$jadwalIds)->where('bulan',$bln)
                ->where('tahun_ajaran',$tahunStr)->avg('nilai_sikap');

            $avgD = DB::table('kedisiplinans')->where('siswa_id',$siswa->id)
                ->whereIn('jadwal_id',$jadwalIds)->where('bulan',$bln)
                ->where('tahun_ajaran',$tahunStr)->avg('nilai_disiplin');

            $pHdr = $ttl > 0 ? round(($hdr/$ttl)*100, 2) : null;

            $perBulan[$bln] = [
                'bulan'     => $bln,
                'avg_nilai' => $avgN !== null ? round($avgN, 2) : null,
                'pct_hadir' => $pHdr,
                'hadir'     => $hdr, 'izin' => $izn, 'sakit' => $skt,
                'alpha'     => $alp, 'total' => $ttl,
                'avg_sikap' => $avgS !== null ? round($avgS, 2) : null,
                'avg_disiplin'=> $avgD !== null ? round($avgD, 2) : null,
                'ada_data'  => ($avgN!==null||$pHdr!==null||$avgS!==null||$avgD!==null),
            ];
        }

        // ── Rata-rata semester (hanya bulan ada data) ──────────────────────
        $adaBulan = collect($perBulan)->where('ada_data', true);
        $pembagi  = max(1, count($bulanList)); // rata-rata dari 6 bulan penuh

        $rataaNilai    = collect($perBulan)->sum(fn($b)=>$b['avg_nilai']??0)    / $pembagi;
        $rataAbsensi   = collect($perBulan)->sum(fn($b)=>$b['pct_hadir']??0)   / $pembagi;
        $rataSikap     = collect($perBulan)->sum(fn($b)=>$b['avg_sikap']??0)    / $pembagi;
        $rataDisiplin  = collect($perBulan)->sum(fn($b)=>$b['avg_disiplin']??0) / $pembagi;

        // ── FUZZY LOGIC ────────────────────────────────────────────────────
        $norm = [
            'nilai'    => $this->clamp($rataaNilai,   0, 100),
            'absensi'  => $this->clamp($rataAbsensi,  0, 100),
            'sikap'    => $this->clamp($rataSikap,    0, 100),
            'disiplin' => $this->clamp($rataDisiplin, 0, 100),
        ];
        $mu = [
            'nilai'    => $this->fuzzify($norm['nilai']),
            'absensi'  => $this->fuzzify($norm['absensi']),
            'sikap'    => $this->fuzzify($norm['sikap']),
            'disiplin' => $this->fuzzify($norm['disiplin']),
        ];
        $skorFuzzy = round(
            ($mu['nilai']*0.40 + $mu['absensi']*0.30
           + $mu['sikap']*0.15 + $mu['disiplin']*0.15) * 100,
            2
        );

        $adaData = $adaBulan->count() > 0;

        $hasil = [
            'nilai'       => round($norm['nilai'],    2),
            'absensi'     => round($norm['absensi'],  2),
            'sikap'       => round($norm['sikap'],    2),
            'disiplin'    => round($norm['disiplin'], 2),
            'mu_nilai'    => round($mu['nilai'],    4),
            'mu_absensi'  => round($mu['absensi'],  4),
            'mu_sikap'    => round($mu['sikap'],    4),
            'mu_disiplin' => round($mu['disiplin'], 4),
            'skor'        => $skorFuzzy,
            'kategori'    => $this->kategori($skorFuzzy),
            'ada_data'    => $adaData,
            'total_alpha' => collect($perBulan)->sum('alpha'),
        ];

        // ── Nilai per mapel semester ───────────────────────────────────────
        $nilaiMapel = DB::table('nilais as n')
            ->join('jadwals as j','j.id','=','n.jadwal_id')
            ->join('mapels as m','m.id','=','j.mapel_id')
            ->where('n.siswa_id',$siswa->id)
            ->whereIn('n.jadwal_id',$jadwalIds)
            ->whereIn('n.bulan',$bulanList)
            ->where('n.tahun_ajaran',$tahunStr)
            ->select('m.nama_mapel',
                DB::raw('AVG(n.nilai) as avg_nilai'),
                DB::raw('COUNT(*) as cnt'))
            ->groupBy('m.nama_mapel')
            ->orderBy('m.nama_mapel')
            ->get();

        // ── Rekap absensi semester total ───────────────────────────────────
        $absensiTotal = DB::table('absensi_details as d')
            ->join('absensis as a','a.id','=','d.absensi_id')
            ->where('d.siswa_id',$siswa->id)
            ->whereIn('a.jadwal_id',$jadwalIds)
            ->whereIn('a.bulan',$bulanList)
            ->where('a.tahun_ajaran',$tahunStr)
            ->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN d.status="hadir" THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN d.status="izin"  THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN d.status="sakit" THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN d.status="alpha" THEN 1 ELSE 0 END) as alpha
            ')
            ->first();

        // ── Chart data ─────────────────────────────────────────────────────
        $chartLabels   = array_map(fn($b)=>substr($b,0,3), $bulanList);
        $chartNilai    = array_map(fn($b)=>$perBulan[$b]['avg_nilai'],    $bulanList);
        $chartHadir    = array_map(fn($b)=>$perBulan[$b]['pct_hadir'],   $bulanList);
        $chartSikap    = array_map(fn($b)=>$perBulan[$b]['avg_sikap'],   $bulanList);
        $chartDisiplin = array_map(fn($b)=>$perBulan[$b]['avg_disiplin'],$bulanList);

        return view('orangtua.evaluasi-semesteran-anak', compact(
            'siswa','tahunAktif','semesterFilter','bulanList',
            'perBulan','hasil','nilaiMapel','absensiTotal',
            'chartLabels','chartNilai','chartHadir','chartSikap','chartDisiplin'
        ));
    }

    private function fuzzify(float $x): float
    {
        $muT=$this->trapUp($x,70,80);$muS=$this->trapFull($x,40,55,70,80);$muR=$this->trapDown($x,40,55);
        $b=$muT+$muS+$muR;
        return $b<=0?0.0:($muT*100.0+$muS*67.5+$muR*27.5)/($b*100.0);
    }
    private function trapUp(float $x,float $a,float $b):float{return $x<=$a?0:($x>=$b?1:($x-$a)/($b-$a));}
    private function trapDown(float $x,float $a,float $b):float{return $x<=$a?1:($x>=$b?0:($b-$x)/($b-$a));}
    private function trapFull(float $x,float $a,float $b,float $c,float $d):float
    {if($x<=$a||$x>=$d)return 0;if($x>=$b&&$x<=$c)return 1;return $x<$b?($x-$a)/($b-$a):($d-$x)/($d-$c);}
    private function clamp(float $v,float $mn,float $mx):float{return max($mn,min($mx,$v));}
    private function kategori(float $s):string
    {if($s>=85)return 'Sangat Baik';if($s>=70)return 'Baik';if($s>=55)return 'Perlu Bimbingan';return 'Perlu Pembinaan';}
}

<x-app-layout>

    <x-page-header
        title="Perkembangan Anak"
        subtitle="Grafik dan tren akademik per semester"
    />

    @if(!$siswa)
    <div class="flex flex-col items-center justify-center py-20 text-center bg-white rounded-2xl border shadow-sm">
        <div class="w-16 h-16 bg-amber-100 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75M12 15.75h.007v.008H12v-.008z"/>
            </svg>
        </div>
        <p class="font-bold text-slate-700">Data siswa tidak ditemukan</p>
        <p class="text-sm text-slate-400 mt-1">Hubungi admin sekolah.</p>
    </div>

    @else

    {{-- ── PROFIL SISWA ─────────────────────────────────────────────────────── --}}
    <div class="bg-gradient-to-r from-cyan-700 to-teal-600 rounded-2xl p-5 mb-6 flex items-center gap-5 shadow-lg">
        <div class="w-14 h-14 rounded-2xl bg-white/20 text-white font-bold text-2xl flex items-center justify-center shrink-0">
            {{ strtoupper(substr($siswa->nama, 0, 1)) }}
        </div>
        <div class="flex-1">
            <h2 class="text-white text-xl font-bold">{{ $siswa->nama }}</h2>
            <p class="text-cyan-100 text-sm">NIS: {{ $siswa->nis }} · Kelas {{ $siswa->kelas->nama_kelas ?? '-' }}</p>
            <p class="text-cyan-100 text-xs mt-0.5">Tahun Ajaran: <strong class="text-white">{{ $tahunAktif->tahun ?? '-' }}</strong></p>
        </div>
        <div class="hidden md:flex flex-col items-end gap-1">
            {{-- Indikator tren nilai --}}
            @php
                $trendIcon = match($ringkasan['trend_nilai']) {
                    'naik'  => ['↑', 'text-emerald-300', 'Nilai Meningkat'],
                    'turun' => ['↓', 'text-rose-300',    'Nilai Menurun'],
                    default => ['→', 'text-cyan-200',    'Nilai Stabil'],
                };
            @endphp
            <span class="text-3xl font-bold {{ $trendIcon[1] }}">{{ $trendIcon[0] }}</span>
            <span class="text-xs text-cyan-100">{{ $trendIcon[2] }}</span>
        </div>
    </div>

    {{-- ── FILTER SEMESTER ──────────────────────────────────────────────────── --}}
    <form method="GET" class="bg-white rounded-2xl border shadow-sm px-6 py-4 mb-6 flex flex-wrap items-end gap-4">
        <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Semester</label>
            <select name="semester" onchange="this.form.submit()"
                class="rounded-xl border-slate-300 text-sm focus:ring-2 focus:ring-teal-500 pr-8">
                <option value="genap"  {{ strtolower($semesterFilter) === 'genap'  ? 'selected' : '' }}>Genap (Jan–Jun)</option>
                <option value="ganjil" {{ strtolower($semesterFilter) === 'ganjil' ? 'selected' : '' }}>Ganjil (Jul–Des)</option>
            </select>
        </div>
        <div class="text-xs text-slate-400">
            Menampilkan data semester <strong class="text-slate-600">{{ ucfirst($semesterFilter) }}</strong>
            · {{ $ringkasan['bulan_ada'] }} bulan ada data
        </div>
    </form>

    {{-- ── KARTU RINGKASAN ──────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        @php
            $kartu = [
                ['label'=>'Rata-rata Nilai',   'val'=>$ringkasan['avg_nilai'],    'color'=>$ringkasan['avg_nilai']>=75?'teal':'amber',   'icon'=>'📚'],
                ['label'=>'Rata-rata Hadir',   'val'=>$ringkasan['avg_hadir'].'%','color'=>$ringkasan['avg_hadir']>=75?'emerald':'rose', 'icon'=>'✅'],
                ['label'=>'Rata-rata Sikap',   'val'=>$ringkasan['avg_sikap'],    'color'=>$ringkasan['avg_sikap']>=75?'blue':'amber',   'icon'=>'💛'],
                ['label'=>'Alpha Kumulatif',   'val'=>$ringkasan['total_alpha'].'x','color'=>$ringkasan['total_alpha']>=3?'rose':'slate','icon'=>'⚠️'],
            ];
        @endphp
        @foreach($kartu as $k)
        @php
            $bg = match($k['color']) {
                'teal'   =>'bg-teal-50 border-teal-100',
                'emerald'=>'bg-emerald-50 border-emerald-100',
                'blue'   =>'bg-blue-50 border-blue-100',
                'amber'  =>'bg-amber-50 border-amber-100',
                'rose'   =>'bg-rose-50 border-rose-100',
                default  =>'bg-white border-slate-100',
            };
            $txt = match($k['color']) {
                'teal'   =>'text-teal-700',    'emerald'=>'text-emerald-700',
                'blue'   =>'text-blue-700',    'amber'  =>'text-amber-700',
                'rose'   =>'text-rose-700',    default  =>'text-slate-700',
            };
        @endphp
        <div class="{{ $bg }} rounded-2xl border shadow-sm p-5">
            <div class="flex items-center justify-between mb-1">
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">{{ $k['label'] }}</p>
                <span class="text-base">{{ $k['icon'] }}</span>
            </div>
            <p class="text-3xl font-bold {{ $txt }} mt-1">{{ $k['val'] ?: '—' }}</p>
        </div>
        @endforeach
    </div>

    {{-- ── CHART TREN ───────────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b bg-slate-50 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-700 text-sm">Grafik Tren Perkembangan</h3>
                <p class="text-xs text-slate-400 mt-0.5">Nilai, kehadiran, sikap, dan disiplin per bulan</p>
            </div>
            <div class="flex flex-wrap gap-3 text-xs">
                <span class="flex items-center gap-1"><span class="w-3 h-1 bg-teal-500 inline-block rounded"></span>Nilai</span>
                <span class="flex items-center gap-1"><span class="w-3 h-1 bg-blue-500 inline-block rounded"></span>Kehadiran%</span>
                <span class="flex items-center gap-1"><span class="w-3 h-1 bg-purple-500 inline-block rounded"></span>Sikap</span>
                <span class="flex items-center gap-1"><span class="w-3 h-1 bg-orange-400 inline-block rounded"></span>Disiplin</span>
            </div>
        </div>
        <div class="p-5">
            <canvas id="trendChart" height="100"></canvas>
        </div>
    </div>

    {{-- ── TABEL TREN PER BULAN ─────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b bg-slate-800 flex items-center justify-between">
            <h3 class="font-bold text-white text-sm">Detail Per Bulan</h3>
            <span class="text-slate-300 text-xs">Semester {{ ucfirst($semesterFilter) }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-100 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Bulan</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">Nilai</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">Hadir</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">Alpha</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">Sikap</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">Disiplin</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">Status Nilai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($trendData as $bln => $d)
                    @php
                        $nilaiColor = match(true) {
                            !$d['ada_data']           => 'text-slate-300',
                            $d['avg_nilai'] >= 85     => 'text-emerald-700',
                            $d['avg_nilai'] >= 75     => 'text-blue-700',
                            $d['avg_nilai'] >= 65     => 'text-amber-700',
                            $d['avg_nilai'] !== null  => 'text-rose-700',
                            default                   => 'text-slate-300',
                        };
                        $hadirColor = match(true) {
                            $d['pct_hadir'] === null  => 'text-slate-300',
                            $d['pct_hadir'] >= 85     => 'text-emerald-700',
                            $d['pct_hadir'] >= 70     => 'text-amber-700',
                            default                   => 'text-rose-700',
                        };
                        $statusBadge = match(true) {
                            !$d['ada_data']           => ['—',            'bg-slate-100 text-slate-400'],
                            $d['avg_nilai'] >= 85     => ['Sangat Baik',  'bg-emerald-100 text-emerald-700'],
                            $d['avg_nilai'] >= 75     => ['Baik',         'bg-blue-100 text-blue-700'],
                            $d['avg_nilai'] >= 65     => ['Cukup',        'bg-amber-100 text-amber-700'],
                            $d['avg_nilai'] !== null  => ['Perlu Usaha',  'bg-rose-100 text-rose-700'],
                            default                   => ['Belum Ada',    'bg-slate-100 text-slate-400'],
                        };
                    @endphp
                    <tr class="hover:bg-slate-50 {{ !$d['ada_data'] ? 'opacity-50' : '' }}">
                        <td class="px-4 py-3 font-semibold text-slate-700">{{ $bln }}</td>
                        <td class="px-4 py-3 text-center font-mono font-bold {{ $nilaiColor }}">
                            {{ $d['avg_nilai'] ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-center font-mono {{ $hadirColor }}">
                            @if($d['pct_hadir'] !== null)
                                {{ $d['pct_hadir'] }}%
                                <span class="text-[10px] text-slate-400 block">{{ $d['hadir'] }}/{{ $d['total'] }}</span>
                            @else —
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center font-mono {{ $d['alpha'] > 0 ? 'text-rose-600 font-bold' : 'text-slate-400' }}">
                            {{ $d['alpha'] ?? 0 }}x
                        </td>
                        <td class="px-4 py-3 text-center font-mono text-slate-600">
                            {{ $d['avg_sikap'] ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-center font-mono text-slate-600">
                            {{ $d['avg_disiplin'] ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $statusBadge[1] }}">
                                {{ $statusBadge[0] }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                {{-- Footer rata-rata --}}
                <tfoot class="bg-slate-100 border-t-2 border-slate-300">
                    <tr>
                        <td class="px-4 py-3 font-bold text-slate-600 text-xs uppercase">Rata-rata Semester</td>
                        <td class="px-4 py-3 text-center font-bold font-mono text-teal-700">{{ $ringkasan['avg_nilai'] ?: '—' }}</td>
                        <td class="px-4 py-3 text-center font-bold font-mono text-blue-700">{{ $ringkasan['avg_hadir'] ? $ringkasan['avg_hadir'].'%' : '—' }}</td>
                        <td class="px-4 py-3 text-center font-bold font-mono text-rose-600">{{ $ringkasan['total_alpha'] }}x</td>
                        <td class="px-4 py-3 text-center font-bold font-mono text-purple-700">{{ $ringkasan['avg_sikap'] ?: '—' }}</td>
                        <td class="px-4 py-3 text-center font-bold font-mono text-orange-600">{{ $ringkasan['avg_disiplin'] ?: '—' }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- ── NILAI PER MATA PELAJARAN ─────────────────────────────────────────── --}}
    @if(count($nilaiMapelSummary))
    <div class="bg-white rounded-2xl border shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b bg-slate-50 flex items-center justify-between">
            <h3 class="font-bold text-slate-700 text-sm">Nilai per Mata Pelajaran</h3>
            <span class="text-xs text-slate-400">Rata-rata bulan yang ada data</span>
        </div>
        <div class="p-5 space-y-4">
            @foreach($nilaiMapelSummary as $mapel => $info)
            @php
                $pct  = min(100, $info['rata']);
                $barColor = match(true) {
                    $info['rata'] >= 85 => 'bg-emerald-400',
                    $info['rata'] >= 75 => 'bg-teal-400',
                    $info['rata'] >= 65 => 'bg-amber-400',
                    default             => 'bg-rose-400',
                };
                $textColor = match(true) {
                    $info['rata'] >= 85 => 'text-emerald-700',
                    $info['rata'] >= 75 => 'text-teal-700',
                    $info['rata'] >= 65 => 'text-amber-700',
                    default             => 'text-rose-700',
                };
            @endphp
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-sm font-semibold text-slate-700">{{ $mapel }}</span>
                    <div class="flex items-center gap-3">
                        {{-- mini bulan badges --}}
                        <div class="flex gap-1 flex-wrap justify-end">
                            @foreach($bulanSemester as $bln)
                                @if(isset($info['bulan'][$bln]))
                                <span class="text-[10px] px-1.5 py-0.5 rounded bg-slate-100 text-slate-500 font-mono">
                                    {{ substr($bln,0,3) }}: {{ $info['bulan'][$bln] }}
                                </span>
                                @endif
                            @endforeach
                        </div>
                        <span class="font-bold font-mono text-base {{ $textColor }} w-10 text-right">{{ $info['rata'] }}</span>
                    </div>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2.5">
                    <div class="h-2.5 rounded-full {{ $barColor }} transition-all duration-700"
                         style="width: {{ $pct }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ── REKAP ABSENSI SEMESTER ───────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b bg-slate-50">
            <h3 class="font-bold text-slate-700 text-sm">Rekap Kehadiran Semester {{ ucfirst($semesterFilter) }}</h3>
        </div>
        <div class="p-5 grid grid-cols-2 md:grid-cols-4 gap-4">
            @php
                $totalAbs = $absensiRekap->total ?? 0;
                $pctHadir = $totalAbs > 0 ? round(($absensiRekap->hadir / $totalAbs) * 100, 1) : 0;
            @endphp
            <div class="bg-emerald-50 rounded-xl p-4 border border-emerald-100 text-center">
                <p class="text-xs text-emerald-600 font-semibold uppercase">Hadir</p>
                <p class="text-3xl font-bold text-emerald-700 mt-1">{{ $absensiRekap->hadir ?? 0 }}</p>
                <p class="text-xs text-emerald-500 mt-1">{{ $pctHadir }}% dari total</p>
            </div>
            <div class="bg-blue-50 rounded-xl p-4 border border-blue-100 text-center">
                <p class="text-xs text-blue-600 font-semibold uppercase">Izin</p>
                <p class="text-3xl font-bold text-blue-700 mt-1">{{ $absensiRekap->izin ?? 0 }}</p>
            </div>
            <div class="bg-amber-50 rounded-xl p-4 border border-amber-100 text-center">
                <p class="text-xs text-amber-600 font-semibold uppercase">Sakit</p>
                <p class="text-3xl font-bold text-amber-700 mt-1">{{ $absensiRekap->sakit ?? 0 }}</p>
            </div>
            <div class="bg-rose-50 rounded-xl p-4 border border-rose-100 text-center">
                <p class="text-xs text-rose-600 font-semibold uppercase">Alpha</p>
                <p class="text-3xl font-bold text-rose-700 mt-1">{{ $absensiRekap->alpha ?? 0 }}</p>
                @if(($absensiRekap->alpha ?? 0) >= 3)
                <p class="text-[10px] text-rose-500 font-semibold mt-1">⚠ Perlu perhatian</p>
                @endif
            </div>
        </div>
    </div>

    {{-- ── SHORTCUT KE EVALUASI ─────────────────────────────────────────────── --}}
    <div class="grid md:grid-cols-2 gap-4">
        <a href="{{ route('orangtua.evaluasi.bulanan') }}"
            class="bg-gradient-to-r from-teal-600 to-emerald-600 rounded-2xl p-5 flex items-center gap-4 shadow hover:shadow-lg transition group">
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <div>
                <p class="text-white/70 text-xs font-semibold uppercase tracking-widest">Lihat Detail</p>
                <p class="text-white font-bold text-base">Evaluasi Bulanan (Fuzzy)</p>
            </div>
            <svg class="w-5 h-5 text-white/60 ml-auto" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
        <a href="{{ route('orangtua.evaluasi.semesteran') }}"
            class="bg-gradient-to-r from-indigo-600 to-violet-600 rounded-2xl p-5 flex items-center gap-4 shadow hover:shadow-lg transition group">
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <p class="text-white/70 text-xs font-semibold uppercase tracking-widest">Lihat Detail</p>
                <p class="text-white font-bold text-base">Evaluasi Semesteran (Fuzzy)</p>
            </div>
            <svg class="w-5 h-5 text-white/60 ml-auto" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    @endif

    {{-- ── CHART.JS ──────────────────────────────────────────────────────────── --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @if($siswa)
    <script>
    const labels   = @json($chartLabels);
    const dNilai   = @json($chartNilai);
    const dHadir   = @json($chartHadir);
    const dSikap   = @json($chartSikap);
    const dDisiplin= @json($chartDisiplin);

    // Ganti null dengan undefined agar Chart.js skip titik
    const clean = arr => arr.map(v => v ?? null);

    new Chart(document.getElementById('trendChart'), {
        type: 'line',
        data: {
            labels,
            datasets: [
                {
                    label: 'Nilai Akademik',
                    data: clean(dNilai),
                    borderColor: '#0d9488',
                    backgroundColor: 'rgba(13,148,136,.08)',
                    borderWidth: 2.5,
                    pointRadius: 5,
                    pointBackgroundColor: '#0d9488',
                    tension: 0.4,
                    fill: true,
                    spanGaps: false,
                },
                {
                    label: 'Kehadiran (%)',
                    data: clean(dHadir),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59,130,246,.06)',
                    borderWidth: 2,
                    pointRadius: 4,
                    pointBackgroundColor: '#3b82f6',
                    tension: 0.4,
                    spanGaps: false,
                },
                {
                    label: 'Sikap',
                    data: clean(dSikap),
                    borderColor: '#a855f7',
                    borderWidth: 2,
                    pointRadius: 4,
                    pointBackgroundColor: '#a855f7',
                    tension: 0.4,
                    borderDash: [4,3],
                    spanGaps: false,
                },
                {
                    label: 'Disiplin',
                    data: clean(dDisiplin),
                    borderColor: '#f97316',
                    borderWidth: 2,
                    pointRadius: 4,
                    pointBackgroundColor: '#f97316',
                    tension: 0.4,
                    borderDash: [4,3],
                    spanGaps: false,
                },
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { position: 'top', labels: { font: { size: 11 } } },
                tooltip: {
                    callbacks: {
                        label: ctx => {
                            if (ctx.parsed.y === null) return null;
                            const s = ctx.dataset.label;
                            const v = ctx.parsed.y;
                            return `${s}: ${v}${s.includes('%') || s.includes('Hadir') ? '%' : ''}`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    min: 0, max: 100,
                    grid: { color: 'rgba(0,0,0,.05)' },
                    ticks: { font: { size: 11 } }
                },
                x: { ticks: { font: { size: 11 } } }
            }
        }
    });
    </script>
    @endif

</x-app-layout>

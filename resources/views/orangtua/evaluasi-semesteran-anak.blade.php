<x-app-layout>

    <x-page-header
        title="Evaluasi Semesteran"
        subtitle="Hasil evaluasi selama 1 semester menggunakan Fuzzy Logic"
    />

    @if(!$siswa)
    <div class="flex flex-col items-center justify-center py-20 text-center bg-white rounded-2xl border shadow-sm">
        <p class="font-bold text-slate-700">Data siswa tidak ditemukan</p>
    </div>
    @else

    {{-- ── PROFIL ───────────────────────────────────────────────────────────── --}}
    <div class="bg-gradient-to-r from-indigo-700 to-violet-600 rounded-2xl p-5 mb-6 flex items-center gap-5 shadow-lg">
        <div class="w-14 h-14 rounded-2xl bg-white/20 text-white font-bold text-2xl flex items-center justify-center shrink-0">
            {{ strtoupper(substr($siswa->nama,0,1)) }}
        </div>
        <div class="flex-1">
            <h2 class="text-white text-xl font-bold">{{ $siswa->nama }}</h2>
            <p class="text-indigo-100 text-sm">NIS: {{ $siswa->nis }} · Kelas {{ $siswa->kelas->nama_kelas ?? '-' }}</p>
            <p class="text-indigo-100 text-xs mt-0.5">Tahun Ajaran: <strong class="text-white">{{ $tahunAktif->tahun ?? '-' }}</strong></p>
        </div>
        <div class="hidden md:flex flex-col items-end gap-1 text-xs text-indigo-100">
            <span>Rata-rata dari {{ count($bulanList) }} bulan</span>
            <span>Semester {{ ucfirst($semesterFilter) }}</span>
        </div>
    </div>

    {{-- ── FILTER SEMESTER ──────────────────────────────────────────────────── --}}
    <form method="GET" class="bg-white rounded-2xl border shadow-sm px-6 py-4 mb-6 flex flex-wrap items-end gap-4">
        <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Semester</label>
            <select name="semester" onchange="this.form.submit()"
                class="rounded-xl border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 pr-8">
                <option value="genap"  {{ strtolower($semesterFilter)==='genap'  ?'selected':'' }}>Genap (Jan–Jun)</option>
                <option value="ganjil" {{ strtolower($semesterFilter)==='ganjil' ?'selected':'' }}>Ganjil (Jul–Des)</option>
            </select>
        </div>
        <div class="text-xs text-slate-400">
            Menampilkan evaluasi semester <strong class="text-slate-600">{{ ucfirst($semesterFilter) }}</strong>
            · {{ implode(', ', array_map(fn($b)=>substr($b,0,3), $bulanList)) }}
        </div>
    </form>

    {{-- ── SKOR FUZZY UTAMA ─────────────────────────────────────────────────── --}}
    @php
        $katColor = match($hasil['kategori']) {
            'Sangat Baik'     => ['from-emerald-600','to-emerald-500'],
            'Baik'            => ['from-blue-600',   'to-blue-500'],
            'Perlu Bimbingan' => ['from-amber-600',  'to-amber-500'],
            default           => ['from-rose-600',   'to-rose-500'],
        };
    @endphp

    @if($hasil['ada_data'])
    <div class="bg-gradient-to-r {{ $katColor[0] }} {{ $katColor[1] }} rounded-2xl p-6 mb-6 shadow-lg">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-white/70 text-xs font-semibold uppercase tracking-widest">Skor Fuzzy Semester {{ ucfirst($semesterFilter) }}</p>
                <p class="text-white text-5xl font-bold font-mono mt-1">{{ $hasil['skor'] }}</p>
                <p class="text-white/80 text-sm mt-1">rata-rata dari {{ count($bulanList) }} bulan</p>
            </div>
            <div class="flex flex-col items-end gap-2">
                <span class="px-4 py-2 rounded-xl bg-white/20 text-white font-bold text-lg">
                    {{ $hasil['kategori'] }}
                </span>
                @if($hasil['total_alpha'] >= 3)
                <span class="px-3 py-1 bg-white/20 text-white text-xs rounded-full font-semibold">
                    ⚠ Alpha {{ $hasil['total_alpha'] }}x — perlu perhatian
                </span>
                @endif
            </div>
        </div>
        <div class="mt-5">
            <div class="w-full bg-white/20 rounded-full h-3">
                <div class="h-3 rounded-full bg-white/80" style="width:{{ $hasil['skor'] }}%"></div>
            </div>
        </div>
    </div>

    {{-- ── 4 KOMPONEN RATA-RATA SEMESTER ───────────────────────────────────── --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        @foreach([
            ['label'=>'Nilai Akademik','val'=>$hasil['nilai'],'mu'=>$hasil['mu_nilai'],'w'=>'40%','icon'=>'📚'],
            ['label'=>'Absensi',       'val'=>$hasil['absensi'].'%','mu'=>$hasil['mu_absensi'],'w'=>'30%','icon'=>'✅'],
            ['label'=>'Sikap',         'val'=>$hasil['sikap'],'mu'=>$hasil['mu_sikap'],'w'=>'15%','icon'=>'💛'],
            ['label'=>'Disiplin',      'val'=>$hasil['disiplin'],'mu'=>$hasil['mu_disiplin'],'w'=>'15%','icon'=>'🛡️'],
        ] as $k)
        <div class="bg-white rounded-2xl border shadow-sm p-5">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs text-slate-500 font-semibold uppercase">{{ $k['label'] }}</p>
                <span>{{ $k['icon'] }}</span>
            </div>
            <p class="text-2xl font-bold font-mono text-slate-800">{{ $k['val'] }}</p>
            <div class="mt-2 flex items-center justify-between text-xs">
                <span class="text-slate-400">μ = {{ $k['mu'] }}</span>
                <span class="bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full">bobot {{ $k['w'] }}</span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-1.5 mt-2">
                <div class="h-1.5 rounded-full bg-indigo-400"
                     style="width:{{ min(100,is_numeric(str_replace('%','',$k['val']))?str_replace('%','',$k['val']):0) }}%">
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white border rounded-2xl p-12 text-center mb-6">
        <p class="text-slate-400 text-sm">Belum ada data evaluasi untuk semester ini.</p>
    </div>
    @endif

    {{-- ── CHART TREN PER BULAN ────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b bg-slate-50 flex items-center justify-between">
            <h3 class="font-bold text-slate-700 text-sm">Tren per Bulan — Semester {{ ucfirst($semesterFilter) }}</h3>
            <div class="flex gap-3 text-xs">
                <span class="flex items-center gap-1"><span class="w-3 h-1 bg-indigo-500 inline-block rounded"></span>Nilai</span>
                <span class="flex items-center gap-1"><span class="w-3 h-1 bg-teal-500 inline-block rounded"></span>Hadir%</span>
                <span class="flex items-center gap-1"><span class="w-3 h-1 bg-purple-500 inline-block rounded"></span>Sikap</span>
                <span class="flex items-center gap-1"><span class="w-3 h-1 bg-orange-400 inline-block rounded"></span>Disiplin</span>
            </div>
        </div>
        <div class="p-5"><canvas id="semChart" height="90"></canvas></div>
    </div>

    {{-- ── DETAIL PER BULAN ─────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 bg-slate-800">
            <h3 class="font-bold text-white text-sm">Detail Per Bulan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-100 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Bulan</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">Nilai</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">Hadir</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">Izin</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">Sakit</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">Alpha</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">Sikap</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">Disiplin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($perBulan as $bln => $d)
                    <tr class="hover:bg-slate-50 {{ !$d['ada_data']?'opacity-50':'' }}">
                        <td class="px-4 py-3 font-semibold text-slate-700">{{ $bln }}</td>
                        <td class="px-4 py-3 text-center font-mono {{ $d['avg_nilai']!==null?($d['avg_nilai']>=75?'text-teal-700':'text-rose-700'):'text-slate-300' }}">
                            {{ $d['avg_nilai'] ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-center font-mono text-emerald-700">
                            {{ $d['pct_hadir'] !== null ? $d['pct_hadir'].'%' : '—' }}
                        </td>
                        <td class="px-4 py-3 text-center font-mono text-blue-600">{{ $d['izin'] ?: '0' }}</td>
                        <td class="px-4 py-3 text-center font-mono text-amber-600">{{ $d['sakit'] ?: '0' }}</td>
                        <td class="px-4 py-3 text-center font-mono {{ $d['alpha']>0?'text-rose-600 font-bold':'text-slate-400' }}">
                            {{ $d['alpha'] ?: '0' }}
                        </td>
                        <td class="px-4 py-3 text-center font-mono text-slate-600">{{ $d['avg_sikap'] ?? '—' }}</td>
                        <td class="px-4 py-3 text-center font-mono text-slate-600">{{ $d['avg_disiplin'] ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-indigo-50 border-t-2 border-indigo-200">
                    <tr>
                        <td class="px-4 py-3 font-bold text-indigo-700 text-xs uppercase">Rata-rata Semester</td>
                        <td class="px-4 py-3 text-center font-bold font-mono text-indigo-700">{{ $hasil['nilai'] ?: '—' }}</td>
                        <td class="px-4 py-3 text-center font-bold font-mono text-teal-700">{{ $hasil['absensi'] ? $hasil['absensi'].'%' : '—' }}</td>
                        <td colspan="2"></td>
                        <td class="px-4 py-3 text-center font-bold font-mono text-rose-600">{{ $hasil['total_alpha'] }}x</td>
                        <td class="px-4 py-3 text-center font-bold font-mono text-purple-700">{{ $hasil['sikap'] ?: '—' }}</td>
                        <td class="px-4 py-3 text-center font-bold font-mono text-orange-600">{{ $hasil['disiplin'] ?: '—' }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- ── NILAI PER MAPEL SEMESTER ─────────────────────────────────────────── --}}
    @if($nilaiMapel->count())
    <div class="bg-white rounded-2xl border shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b bg-slate-50">
            <h3 class="font-bold text-slate-700 text-sm">Nilai per Mata Pelajaran — Semester {{ ucfirst($semesterFilter) }}</h3>
        </div>
        <div class="p-5 space-y-3">
            @foreach($nilaiMapel as $nm)
            @php
                $avg = round($nm->avg_nilai,1);
                $bc  = $avg>=85?'bg-emerald-400':($avg>=75?'bg-teal-400':($avg>=65?'bg-amber-400':'bg-rose-400'));
                $tc  = $avg>=85?'text-emerald-700':($avg>=75?'text-teal-700':($avg>=65?'text-amber-700':'text-rose-700'));
            @endphp
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-sm font-semibold text-slate-700">{{ $nm->nama_mapel }}</span>
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-slate-400">{{ $nm->cnt }} penilaian</span>
                        <span class="font-bold font-mono {{ $tc }}">{{ $avg }}</span>
                    </div>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2">
                    <div class="h-2 rounded-full {{ $bc }}" style="width:{{ min(100,$avg) }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ── REKAP ABSENSI TOTAL SEMESTER ────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b bg-slate-50">
            <h3 class="font-bold text-slate-700 text-sm">Total Kehadiran Semester {{ ucfirst($semesterFilter) }}</h3>
        </div>
        <div class="p-5 grid grid-cols-2 md:grid-cols-4 gap-4">
            @php $tot = $absensiTotal->total ?? 0; $pct = $tot>0?round(($absensiTotal->hadir/$tot)*100,1):0; @endphp
            <div class="bg-emerald-50 rounded-xl p-4 border border-emerald-100 text-center">
                <span class="text-lg">✅</span>
                <p class="text-xs text-emerald-600 font-semibold uppercase mt-1">Hadir</p>
                <p class="text-2xl font-bold text-emerald-700 mt-1">{{ $absensiTotal->hadir ?? 0 }}</p>
                <p class="text-xs text-emerald-500 mt-1">{{ $pct }}%</p>
            </div>
            <div class="bg-blue-50 rounded-xl p-4 border border-blue-100 text-center">
                <span class="text-lg">📋</span>
                <p class="text-xs text-blue-600 font-semibold uppercase mt-1">Izin</p>
                <p class="text-2xl font-bold text-blue-700 mt-1">{{ $absensiTotal->izin ?? 0 }}</p>
            </div>
            <div class="bg-amber-50 rounded-xl p-4 border border-amber-100 text-center">
                <span class="text-lg">🏥</span>
                <p class="text-xs text-amber-600 font-semibold uppercase mt-1">Sakit</p>
                <p class="text-2xl font-bold text-amber-700 mt-1">{{ $absensiTotal->sakit ?? 0 }}</p>
            </div>
            <div class="bg-rose-50 rounded-xl p-4 border border-rose-100 text-center">
                <span class="text-lg">⚠️</span>
                <p class="text-xs text-rose-600 font-semibold uppercase mt-1">Alpha</p>
                <p class="text-2xl font-bold text-rose-700 mt-1">{{ $absensiTotal->alpha ?? 0 }}</p>
                @if(($absensiTotal->alpha??0)>=3)<p class="text-[10px] text-rose-500 mt-1 font-semibold">Perlu perhatian</p>@endif
            </div>
        </div>
    </div>

    {{-- Footer fuzzy --}}
    <div class="bg-slate-50 border rounded-2xl p-4 text-xs text-slate-500 flex flex-wrap gap-3">
        <span class="font-semibold text-slate-600">Metode:</span>
        <span>Fuzzy Trapesium · Defuzzifikasi Centroid · Bobot 40/30/15/15</span>
        <div class="flex gap-3 ml-auto">
            <span class="text-emerald-600 font-semibold">≥85 Sangat Baik</span>
            <span class="text-blue-600 font-semibold">≥70 Baik</span>
            <span class="text-amber-600 font-semibold">≥55 Perlu Bimbingan</span>
            <span class="text-rose-600 font-semibold">&lt;55 Perlu Pembinaan</span>
        </div>
    </div>

    @endif

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @if($siswa)
    <script>
    new Chart(document.getElementById('semChart'), {
        type: 'bar',
        data: {
            labels: @json($chartLabels),
            datasets: [
                { label:'Nilai', data: @json($chartNilai), backgroundColor:'rgba(99,102,241,.7)', borderRadius:4 },
                { label:'Hadir%', data: @json($chartHadir), backgroundColor:'rgba(20,184,166,.7)', borderRadius:4 },
                { label:'Sikap', data: @json($chartSikap), backgroundColor:'rgba(168,85,247,.5)', borderRadius:4 },
                { label:'Disiplin', data: @json($chartDisiplin), backgroundColor:'rgba(249,115,22,.5)', borderRadius:4 },
            ]
        },
        options: {
            responsive:true,
            interaction:{mode:'index',intersect:false},
            plugins:{legend:{position:'top',labels:{font:{size:11}}}},
            scales:{
                y:{min:0,max:100,grid:{color:'rgba(0,0,0,.05)'},ticks:{font:{size:11}}},
                x:{ticks:{font:{size:11}}}
            }
        }
    });
    </script>
    @endif

</x-app-layout>

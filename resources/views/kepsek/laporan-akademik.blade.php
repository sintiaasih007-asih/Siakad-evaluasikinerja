<x-app-layout>
    <x-page-header title="Laporan Akademik" subtitle="Rekap kinerja akademik seluruh kelas"/>

    {{-- Banner --}}
    <div class="rounded-2xl p-5 mb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 shadow-sm"
         style="background:linear-gradient(135deg,#1e3a5f,#1e40af)">
        <div>
            <p class="text-blue-200 text-[10px] font-bold uppercase tracking-widest mb-0.5">Kepala Sekolah · Overview</p>
            <h2 class="text-lg font-bold text-white">Laporan Akademik</h2>
            <p class="text-blue-200 text-xs mt-0.5">TA: <strong class="text-white">{{ $tahun->tahun ?? '-' }}</strong></p>
        </div>
        <div class="hidden sm:flex flex-col items-end text-xs text-blue-200">
            <span>{{ now()->translatedFormat('l, d F Y') }}</span>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card p-4 mb-5">
        <form method="GET" class="flex flex-wrap items-end gap-3">
            <div>
                <label class="form-label">Kelas</label>
                <select name="kelas_id" class="form-input w-40">
                    <option value="">Semua Kelas</option>
                    @foreach($kelasList as $k)
                    <option value="{{ $k->id }}" {{ $kelasId==$k->id?'selected':'' }}>{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-input w-40">
                    <option value="">Semua Bulan</option>
                    @foreach(['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $k=>$v)
                    <option value="{{ $k }}" {{ $bulan==$k?'selected':'' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-primary"><i data-lucide="search" class="w-4 h-4"></i> Tampilkan</button>
            <a href="{{ route('kepsek.laporan-akademik') }}" class="btn-secondary">Reset</a>
        </form>
    </div>

    {{-- Stat cards --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-5">
        @foreach([
            ['Total Siswa',   $ringkasan['total_siswa'],  'users',        'text-blue-700',    'bg-blue-50 border-blue-100'],
            ['Total Kelas',   $ringkasan['total_kelas'],  'layout-grid',  'text-indigo-700',  'bg-indigo-50 border-indigo-100'],
            ['Total Guru',    $ringkasan['total_guru'],   'user-check',   'text-teal-700',    'bg-teal-50 border-teal-100'],
            ['Rata Nilai',    $ringkasan['rata_nilai'],   'book-check',   $ringkasan['rata_nilai']>=75?'text-emerald-700':'text-amber-700', 'bg-white border-slate-200'],
            ['Rata Hadir',    $ringkasan['pct_hadir'].'%','check-circle', $ringkasan['pct_hadir']>=80?'text-emerald-700':'text-rose-700',  'bg-white border-slate-200'],
        ] as [$label,$val,$icon,$txt,$bg])
        <div class="rounded-2xl border p-4 shadow-sm {{ $bg }}">
            <div class="flex items-start justify-between gap-2">
                <div>
                    <p class="stat-card-label">{{ $label }}</p>
                    <p class="text-2xl font-bold {{ $txt }} mt-1">{{ $val }}</p>
                </div>
                <i data-lucide="{{ $icon }}" class="w-5 h-5 {{ $txt }} opacity-60 shrink-0 mt-0.5"></i>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Rekap per kelas --}}
    <div class="card overflow-hidden mb-5">
        <div class="px-5 py-3.5 border-b bg-slate-50 flex items-center gap-2">
            <i data-lucide="layout-grid" class="w-4 h-4 text-slate-500"></i>
            <span class="text-sm font-semibold text-slate-700">Rekap Per Kelas</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-blue-900 text-white">
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide">Kelas</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide hide-mobile">Wali Kelas</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide">Siswa</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide">Rata Nilai</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide">Kehadiran</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide hide-mobile">Total Alpha</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($rekapKelas as $r)
                    @php
                        $nilaiOk  = $r['rata_nilai'] >= 75;
                        $hadirOk  = $r['pct_hadir'] >= 80;
                        $status   = $nilaiOk && $hadirOk ? ['Baik','emerald'] : ($r['total_alpha'] > 10 ? ['Perhatian','amber'] : ['Pantauan','blue']);
                    @endphp
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-blue-800 text-white text-xs font-bold flex items-center justify-center shrink-0">
                                    {{ strtoupper(substr($r['nama_kelas'],0,1)) }}
                                </div>
                                <span class="font-bold text-slate-800">{{ $r['nama_kelas'] }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-slate-500 text-xs hide-mobile">{{ $r['wali_kelas'] }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="badge badge-neutral">{{ $r['jml_siswa'] }}</span>
                        </td>
                        <td class="px-4 py-3 text-center font-bold font-mono {{ $nilaiOk ? 'text-emerald-700' : 'text-amber-600' }}">
                            {{ $r['rata_nilai'] ?: '—' }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <div class="w-16 bg-slate-100 rounded-full h-1.5">
                                    <div class="h-1.5 rounded-full {{ $hadirOk ? 'bg-emerald-500' : 'bg-rose-500' }}"
                                         style="width:{{ min(100,$r['pct_hadir']) }}%"></div>
                                </div>
                                <span class="font-mono text-xs {{ $hadirOk ? 'text-emerald-700' : 'text-rose-600' }}">{{ $r['pct_hadir'] }}%</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center font-mono hide-mobile {{ $r['total_alpha'] > 5 ? 'text-rose-600 font-bold' : 'text-slate-500' }}">
                            {{ $r['total_alpha'] }}x
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="badge bg-{{ $status[1] }}-100 text-{{ $status[1] }}-700">{{ $status[0] }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-4 py-10 text-center text-slate-400 text-sm">Belum ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Nilai per mapel --}}
    @if($nilaiPerMapel->count())
    <div class="card overflow-hidden">
        <div class="px-5 py-3.5 border-b bg-slate-50 flex items-center gap-2">
            <i data-lucide="bar-chart-2" class="w-4 h-4 text-slate-500"></i>
            <span class="text-sm font-semibold text-slate-700">Rata-rata Nilai per Mata Pelajaran</span>
        </div>
        <div class="p-5 space-y-3">
            @foreach($nilaiPerMapel as $m)
            @php
                $pct = min(100, $m->avg_nilai);
                $bc  = $m->avg_nilai >= 80 ? 'bg-emerald-500' : ($m->avg_nilai >= 70 ? 'bg-blue-500' : ($m->avg_nilai >= 60 ? 'bg-amber-500' : 'bg-rose-500'));
                $tc  = $m->avg_nilai >= 80 ? 'text-emerald-700' : ($m->avg_nilai >= 70 ? 'text-blue-700' : ($m->avg_nilai >= 60 ? 'text-amber-700' : 'text-rose-700'));
            @endphp
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-sm font-medium text-slate-700">{{ $m->nama_mapel }}</span>
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-slate-400">{{ $m->cnt }} penilaian</span>
                        <span class="font-bold font-mono text-sm {{ $tc }}">{{ round($m->avg_nilai,1) }}</span>
                    </div>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2">
                    <div class="h-2 rounded-full {{ $bc }} transition-all" style="width:{{ $pct }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</x-app-layout>

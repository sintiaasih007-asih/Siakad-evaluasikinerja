<x-app-layout>
    <x-page-header title="Hasil Evaluasi Siswa" subtitle="Rekap evaluasi fuzzy logic seluruh siswa per semester"/>

    {{-- Banner --}}
    <div class="rounded-2xl p-5 mb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 shadow-sm"
         style="background:linear-gradient(135deg,#7c2d12,#c2410c)">
        <div>
            <p class="text-orange-200 text-[10px] font-bold uppercase tracking-widest mb-0.5">Kepala Sekolah · Fuzzy Mamdani</p>
            <h2 class="text-lg font-bold text-white">Hasil Evaluasi Siswa</h2>
            <p class="text-orange-200 text-xs mt-0.5">
                TA: <strong class="text-white">{{ $tahun->tahun ?? '-' }}</strong>
                &nbsp;·&nbsp; Semester <strong class="text-white">{{ ucfirst($semester) }}</strong>
                &nbsp;·&nbsp; {{ implode(', ', array_map(fn($b)=>substr($b,0,3), $bulanList)) }}
            </p>
        </div>
        <div class="hidden sm:flex flex-col items-end text-xs text-orange-200">
            <span>Bobot: Nilai 40% · Absensi 30%</span>
            <span>Sikap 15% · Disiplin 15%</span>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card p-4 mb-5">
        <form method="GET" class="flex flex-wrap items-end gap-3">
            <div>
                <label class="form-label">Kelas</label>
                <select name="kelas_id" class="form-input w-36">
                    <option value="">Semua Kelas</option>
                    @foreach($kelasList as $k)
                    <option value="{{ $k->id }}" {{ $kelasId==$k->id?'selected':'' }}>{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Semester</label>
                <select name="semester" class="form-input w-40">
                    <option value="genap"  {{ $semester=='genap'  ?'selected':'' }}>Genap (Jan–Jun)</option>
                    <option value="ganjil" {{ $semester=='ganjil' ?'selected':'' }}>Ganjil (Jul–Des)</option>
                </select>
            </div>
            <button type="submit" class="btn-primary"><i data-lucide="search" class="w-4 h-4"></i> Tampilkan</button>
            <a href="{{ route('kepsek.hasil-evaluasi') }}" class="btn-secondary">Reset</a>
        </form>
    </div>

    {{-- Stat --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-5">
        @foreach([
            ['Total Siswa',       $ringkasan['total'],           'users',        'text-slate-700',   'bg-white border-slate-200'],
            ['Sangat Baik',       $ringkasan['sangat_baik'],     'star',         'text-emerald-700', 'bg-emerald-50 border-emerald-100'],
            ['Baik',              $ringkasan['baik'],            'thumbs-up',    'text-blue-700',    'bg-blue-50 border-blue-100'],
            ['Perlu Bimbingan',   $ringkasan['perlu_bimbingan'], 'book-open',    'text-amber-700',   'bg-amber-50 border-amber-100'],
            ['Perlu Pembinaan',   $ringkasan['perlu_pembinaan'], 'alert-circle', 'text-rose-700',    'bg-rose-50 border-rose-100'],
            ['Rata-rata Skor',    $ringkasan['rata_skor'],       'bar-chart-2',  'text-orange-700',  'bg-orange-50 border-orange-100'],
        ] as [$label,$val,$icon,$txt,$bg])
        <div class="rounded-2xl border p-4 shadow-sm {{ $bg }}">
            <div class="flex items-start justify-between gap-2">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">{{ $label }}</p>
                    <p class="text-xl font-bold {{ $txt }} mt-1">{{ $val }}</p>
                </div>
                <i data-lucide="{{ $icon }}" class="w-4 h-4 {{ $txt }} opacity-60 shrink-0 mt-0.5"></i>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Distribusi kategori --}}
    @if($data->count())
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-5">
        @foreach(['Sangat Baik'=>['emerald','🌟'],'Baik'=>['blue','👍'],'Perlu Bimbingan'=>['amber','📘'],'Perlu Pembinaan'=>['rose','⚠️']] as $kat=>[$c,$icon])
        @php $cnt = $data->where('kategori',$kat)->count(); $pct = $data->count() > 0 ? round($cnt/$data->count()*100) : 0; @endphp
        <div class="bg-{{ $c }}-50 border border-{{ $c }}-100 rounded-2xl p-4 flex items-center gap-3">
            <span class="text-2xl">{{ $icon }}</span>
            <div>
                <p class="text-[10px] font-bold text-{{ $c }}-600 uppercase tracking-wide">{{ $kat }}</p>
                <p class="text-2xl font-bold text-{{ $c }}-700">{{ $cnt }}</p>
                <p class="text-xs text-{{ $c }}-500">{{ $pct }}% siswa</p>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Tabel --}}
    <div class="card overflow-hidden" x-data="{ search:'' }">
        <div class="px-5 py-3.5 border-b bg-slate-50 flex flex-col sm:flex-row sm:items-center gap-3 justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="award" class="w-4 h-4 text-slate-500"></i>
                <span class="text-sm font-semibold text-slate-700">Ranking Evaluasi — Semester {{ ucfirst($semester) }}</span>
            </div>
            <div class="relative">
                <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                <input type="text" x-model="search" placeholder="Cari siswa..." class="form-input pl-9 w-44 text-xs">
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-white" style="background:linear-gradient(135deg,#7c2d12,#c2410c)">
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide w-12">Rank</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide">Nama Siswa</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide hide-mobile">Kelas</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide hide-mobile">Nilai</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide hide-mobile">Hadir%</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide">Skor Fuzzy</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide">Kategori</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($data as $i => $s)
                    @php
                        $rankBg = match(true) { $i===0=>'bg-amber-400 text-white', $i===1=>'bg-slate-400 text-white', $i===2=>'bg-orange-400 text-white', default=>'bg-slate-100 text-slate-600' };
                        $katBadge = match($s['kategori']) {
                            'Sangat Baik'     => 'bg-emerald-100 text-emerald-700',
                            'Baik'            => 'bg-blue-100 text-blue-700',
                            'Perlu Bimbingan' => 'bg-amber-100 text-amber-700',
                            default           => 'bg-rose-100 text-rose-700',
                        };
                        $skorColor = match(true) { $s['skor']>=85=>'text-emerald-700', $s['skor']>=70=>'text-blue-700', $s['skor']>=55=>'text-amber-600', default=>'text-rose-600' };
                    @endphp
                    <tr class="hover:bg-slate-50/70 transition-colors {{ $i<3?'font-medium':'' }}"
                        x-show="!search || '{{ strtolower($s['nama']) }}'.includes(search.toLowerCase())">
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold {{ $rankBg }}">{{ $i+1 }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-orange-100 text-orange-700 text-xs font-bold flex items-center justify-center shrink-0">
                                    {{ strtoupper(substr($s['nama'],0,1)) }}
                                </div>
                                <div>
                                    <p class="text-slate-800 font-semibold text-xs">{{ $s['nama'] }}</p>
                                    @if(!$s['ada_data'])<p class="text-[9px] text-amber-500">belum ada data</p>@endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center hide-mobile"><span class="badge badge-neutral text-xs">{{ $s['nama_kelas'] }}</span></td>
                        <td class="px-4 py-3 text-center font-mono text-xs hide-mobile text-slate-700">{{ $s['nilai'] ?: '—' }}</td>
                        <td class="px-4 py-3 text-center font-mono text-xs hide-mobile text-slate-700">{{ $s['hadir'] ? $s['hadir'].'%' : '—' }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-base font-bold font-mono {{ $skorColor }}">{{ $s['skor'] }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="badge text-xs {{ $katBadge }}">{{ $s['kategori'] }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-4 py-12 text-center text-slate-400 text-sm">Belum ada data evaluasi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 bg-slate-50 border-t text-xs text-slate-400 flex flex-wrap gap-4">
            <span>Metode: Fuzzy Mamdani · 36 Rule Base · Defuzzifikasi Centroid</span>
            <div class="flex gap-3 ml-auto">
                <span class="text-emerald-600 font-semibold">≥85 Sangat Baik</span>
                <span class="text-blue-600 font-semibold">≥70 Baik</span>
                <span class="text-amber-600 font-semibold">≥50 Perlu Bimbingan</span>
                <span class="text-rose-600 font-semibold">&lt;50 Perlu Pembinaan</span>
            </div>
        </div>
    </div>

</x-app-layout>

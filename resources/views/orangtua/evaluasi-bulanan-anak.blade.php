<x-app-layout>

    <x-page-header
        title="Evaluasi Bulanan"
        subtitle="Hasil evaluasi bulanan dengan algoritma Fuzzy Logic"
    />

    @if(!$siswa)
    <div class="flex flex-col items-center justify-center py-20 text-center bg-white rounded-2xl border shadow-sm">
        <p class="font-bold text-slate-700">Data siswa tidak ditemukan</p>
    </div>
    @else

    {{-- ── PROFIL ───────────────────────────────────────────────────────────── --}}
    <div class="bg-gradient-to-r from-teal-700 to-emerald-600 rounded-2xl p-5 mb-6 flex items-center gap-5 shadow-lg">
        <div class="w-14 h-14 rounded-2xl bg-white/20 text-white font-bold text-2xl flex items-center justify-center shrink-0">
            {{ strtoupper(substr($siswa->nama,0,1)) }}
        </div>
        <div class="flex-1">
            <h2 class="text-white text-xl font-bold">{{ $siswa->nama }}</h2>
            <p class="text-teal-100 text-sm">NIS: {{ $siswa->nis }} · Kelas {{ $siswa->kelas->nama_kelas ?? '-' }}</p>
            <p class="text-teal-100 text-xs mt-0.5">Tahun Ajaran: <strong class="text-white">{{ $tahunAktif->tahun ?? '-' }}</strong></p>
        </div>
        <div class="hidden md:flex flex-col items-end gap-1 text-xs text-teal-100">
            <span>Nilai×40% + Absensi×30%</span>
            <span>+ Sikap×15% + Disiplin×15%</span>
        </div>
    </div>

    {{-- ── FILTER BULAN ─────────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-3 bg-slate-50 border-b flex items-center gap-2">
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
            </svg>
            <span class="text-sm font-bold text-slate-600">Pilih Bulan</span>
        </div>
        <form method="GET" class="px-6 py-4 flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Bulan</label>
                <select name="bulan" onchange="this.form.submit()"
                    class="rounded-xl border-slate-300 text-sm focus:ring-2 focus:ring-teal-500 pr-8">
                    @foreach(['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $k=>$v)
                        <option value="{{ $k }}" {{ $bulanFilter==$k?'selected':'' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <span class="text-xs text-slate-400">Menampilkan evaluasi bulan <strong class="text-slate-600">{{ $namaBulan }}</strong></span>
        </form>
    </div>

    {{-- ── HASIL FUZZY UTAMA ────────────────────────────────────────────────── --}}
    @php
        $katColor = match($hasil['kategori']) {
            'Sangat Baik'     => ['from-emerald-600','to-emerald-500','bg-emerald-100','text-emerald-700','border-emerald-200'],
            'Baik'            => ['from-blue-600',   'to-blue-500',   'bg-blue-100',   'text-blue-700',   'border-blue-200'],
            'Perlu Bimbingan' => ['from-amber-600',  'to-amber-500',  'bg-amber-100',  'text-amber-700',  'border-amber-200'],
            default           => ['from-rose-600',   'to-rose-500',   'bg-rose-100',   'text-rose-700',   'border-rose-200'],
        };
    @endphp

    @if($hasil['ada_data'])
    <div class="bg-gradient-to-r {{ $katColor[0] }} {{ $katColor[1] }} rounded-2xl p-6 mb-6 shadow-lg">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <p class="text-white/70 text-xs font-semibold uppercase tracking-widest">Skor Fuzzy — {{ $namaBulan }}</p>
                <p class="text-white text-5xl font-bold font-mono mt-1">{{ $hasil['skor'] }}</p>
                <p class="text-white/80 text-sm mt-1">dari skala 100</p>
            </div>
            <div class="text-right">
                <span class="px-4 py-2 rounded-xl bg-white/20 text-white font-bold text-lg">
                    {{ $hasil['kategori'] }}
                </span>
                <p class="text-white/60 text-xs mt-2">Metode Fuzzy Trapesium</p>
            </div>
        </div>

        {{-- Bar progress skor --}}
        <div class="mt-5">
            <div class="w-full bg-white/20 rounded-full h-3">
                <div class="h-3 rounded-full bg-white/80 transition-all duration-700"
                     style="width: {{ $hasil['skor'] }}%"></div>
            </div>
            <div class="flex justify-between text-white/50 text-[10px] mt-1">
                <span>0</span><span>Perlu Pembinaan &lt;55</span>
                <span>Bimbingan ≥55</span><span>Baik ≥70</span>
                <span>Sangat Baik ≥85</span><span>100</span>
            </div>
        </div>
    </div>

    {{-- ── 4 KOMPONEN ───────────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        @php
            $komponen = [
                ['label'=>'Nilai Akademik','val'=>$hasil['nilai'],'mu'=>$hasil['mu_nilai'],'w'=>'40%','color'=>'indigo','icon'=>'📚'],
                ['label'=>'Absensi','val'=>$hasil['absensi'].'%','mu'=>$hasil['mu_absensi'],'w'=>'30%','color'=>'teal','icon'=>'✅'],
                ['label'=>'Sikap','val'=>$hasil['sikap'],'mu'=>$hasil['mu_sikap'],'w'=>'15%','color'=>'purple','icon'=>'💛'],
                ['label'=>'Disiplin','val'=>$hasil['disiplin'],'mu'=>$hasil['mu_disiplin'],'w'=>'15%','color'=>'orange','icon'=>'🛡️'],
            ];
        @endphp
        @foreach($komponen as $k)
        @php
            $bg  = "bg-{$k['color']}-50 border-{$k['color']}-100";
            $txt = "text-{$k['color']}-700";
        @endphp
        <div class="bg-white rounded-2xl border shadow-sm p-5">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs text-slate-500 font-semibold uppercase">{{ $k['label'] }}</p>
                <span>{{ $k['icon'] }}</span>
            </div>
            <p class="text-2xl font-bold font-mono text-slate-800">{{ $k['val'] }}</p>
            <div class="mt-2 flex items-center justify-between text-xs">
                <span class="text-slate-400">μ = {{ $k['mu'] }}</span>
                <span class="bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full font-semibold">bobot {{ $k['w'] }}</span>
            </div>
            {{-- mini progress --}}
            <div class="w-full bg-slate-100 rounded-full h-1.5 mt-2">
                <div class="h-1.5 rounded-full bg-teal-400"
                     style="width:{{ is_numeric(str_replace('%','',$k['val'])) ? min(100,str_replace('%','',$k['val'])) : 0 }}%">
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white border rounded-2xl p-12 text-center mb-6">
        <p class="text-slate-400 text-sm">Belum ada data evaluasi untuk bulan <strong>{{ $namaBulan }}</strong></p>
    </div>
    @endif

    {{-- ── DETAIL NILAI PER MAPEL ───────────────────────────────────────────── --}}
    @if($nilaiPerMapel->count())
    <div class="bg-white rounded-2xl border shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b bg-slate-50">
            <h3 class="font-bold text-slate-700 text-sm">Nilai per Mata Pelajaran — {{ $namaBulan }}</h3>
        </div>
        <div class="p-5 space-y-3">
            @foreach($nilaiPerMapel as $nm)
            @php
                $avg = round($nm->avg_nilai,1);
                $bar = min(100,$avg);
                $bc  = $avg>=85?'bg-emerald-400':($avg>=75?'bg-teal-400':($avg>=65?'bg-amber-400':'bg-rose-400'));
                $tc  = $avg>=85?'text-emerald-700':($avg>=75?'text-teal-700':($avg>=65?'text-amber-700':'text-rose-700'));
            @endphp
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-sm font-semibold text-slate-700">{{ $nm->nama_mapel }}</span>
                    <span class="font-bold font-mono {{ $tc }}">{{ $avg }}</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2">
                    <div class="h-2 rounded-full {{ $bc }}" style="width:{{ $bar }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ── DETAIL ABSENSI BULAN INI ────────────────────────────────────────── --}}
    @if($totalPertemuan > 0)
    <div class="bg-white rounded-2xl border shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b bg-slate-50">
            <h3 class="font-bold text-slate-700 text-sm">Kehadiran — {{ $namaBulan }}</h3>
            <p class="text-xs text-slate-400 mt-0.5">{{ $totalPertemuan }} total pertemuan</p>
        </div>
        <div class="p-5 grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach(['hadir'=>['emerald','✅'],'izin'=>['blue','📋'],'sakit'=>['amber','🏥'],'alpha'=>['rose','⚠️']] as $status=>$cfg)
            <div class="bg-{{ $cfg[0] }}-50 rounded-xl p-4 border border-{{ $cfg[0] }}-100 text-center">
                <span class="text-lg">{{ $cfg[1] }}</span>
                <p class="text-xs text-{{ $cfg[0] }}-600 font-semibold uppercase mt-1">{{ ucfirst($status) }}</p>
                <p class="text-2xl font-bold text-{{ $cfg[0] }}-700 mt-1">{{ $absensiDetail->$status ?? 0 }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ── RIWAYAT SEMUA BULAN ──────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 bg-slate-800 flex items-center justify-between">
            <h3 class="font-bold text-white text-sm">Riwayat Evaluasi Bulanan — {{ $tahunAktif->tahun ?? '-' }}</h3>
            <span class="text-slate-300 text-xs">Semua bulan</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-100 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Bulan</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">Nilai</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">Hadir%</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">Sikap</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">Disiplin</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">Skor Fuzzy</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">Kategori</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($riwayat as $bln => $r)
                    @php
                        $isCurrent = $bln === $namaBulan;
                        $katBadge  = match($r['kategori']) {
                            'Sangat Baik'     => 'bg-emerald-100 text-emerald-700',
                            'Baik'            => 'bg-blue-100 text-blue-700',
                            'Perlu Bimbingan' => 'bg-amber-100 text-amber-700',
                            'Perlu Pembinaan' => 'bg-rose-100 text-rose-700',
                            default           => 'bg-slate-100 text-slate-400',
                        };
                        $skorColor = match(true) {
                            $r['skor'] >= 85 => 'text-emerald-600',
                            $r['skor'] >= 70 => 'text-blue-600',
                            $r['skor'] >= 55 => 'text-amber-600',
                            $r['skor'] > 0   => 'text-rose-600',
                            default          => 'text-slate-300',
                        };
                    @endphp
                    <tr class="hover:bg-slate-50 {{ $isCurrent ? 'bg-teal-50' : '' }} {{ !$r['ada_data'] ? 'opacity-50' : '' }}">
                        <td class="px-4 py-3 font-semibold text-slate-700">
                            {{ $bln }}
                            @if($isCurrent)<span class="ml-1 text-[10px] bg-teal-100 text-teal-600 px-1.5 py-0.5 rounded-full font-bold">aktif</span>@endif
                        </td>
                        <td class="px-4 py-3 text-center font-mono">{{ $r['nilai'] ?? '—' }}</td>
                        <td class="px-4 py-3 text-center font-mono">{{ $r['absensi'] !== null ? $r['absensi'].'%' : '—' }}</td>
                        <td class="px-4 py-3 text-center font-mono">{{ $r['sikap'] ?? '—' }}</td>
                        <td class="px-4 py-3 text-center font-mono">{{ $r['disiplin'] ?? '—' }}</td>
                        <td class="px-4 py-3 text-center font-bold font-mono {{ $skorColor }}">
                            {{ $r['skor'] > 0 ? $r['skor'] : '—' }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $katBadge }}">
                                {{ $r['kategori'] }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Footer info fuzzy --}}
    <div class="bg-slate-50 border rounded-2xl p-4 text-xs text-slate-500 flex flex-wrap gap-3">
        <span class="font-semibold text-slate-600">Fuzzy Logic:</span>
        <span>Membership trapesium · 3 himpunan (Rendah/Sedang/Tinggi)</span>
        <span>· Defuzzifikasi centroid berbobot</span>
        <div class="flex gap-3 ml-auto">
            <span class="text-emerald-600 font-semibold">≥85 Sangat Baik</span>
            <span class="text-blue-600 font-semibold">≥70 Baik</span>
            <span class="text-amber-600 font-semibold">≥55 Perlu Bimbingan</span>
            <span class="text-rose-600 font-semibold">&lt;55 Perlu Pembinaan</span>
        </div>
    </div>

    @endif
</x-app-layout>

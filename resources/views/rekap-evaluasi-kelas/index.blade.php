<x-app-layout>

    <x-page-header
        title="Rekap Evaluasi Kelas"
        subtitle="Rekap perkembangan akademik seluruh siswa di kelas binaan per bulan"
    />

    {{-- ── HEADER INFO ─────────────────────────────────────────────────── --}}
    <div class="bg-gradient-to-r from-slate-800 to-slate-700 rounded-2xl p-6 mb-6 flex items-center justify-between shadow-lg">
        <div>
            <p class="text-slate-300 text-xs font-semibold uppercase tracking-widest mb-1">Wali Kelas · Fuzzy Mamdani 36 Rule</p>
            <h2 class="text-xl font-bold text-white">
                Rekap Evaluasi —
                @if($kelas)
                    Kelas {{ $kelas->nama_kelas }}
                @else
                    <span class="text-slate-400">Belum ada kelas binaan</span>
                @endif
            </h2>
            <p class="text-slate-300 text-sm mt-1">
                Tahun Ajaran: <strong class="text-white">{{ $tahun->tahun ?? '-' }}</strong>
                · Guru: <strong class="text-white">{{ auth()->user()->name }}</strong>
            </p>
        </div>
        <div class="hidden md:flex flex-col items-end gap-1 text-xs text-slate-300">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-indigo-400"></span>Nilai Akademik (semua mapel)
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-teal-400"></span>Absensi (semua pertemuan)
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-cyan-400"></span>Sikap (semua guru)
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-lime-400"></span>Disiplin (semua guru)
            </div>
        </div>
    </div>

    {{-- ── TIDAK PUNYA KELAS BINAAN ──────────────────────────────────────── --}}
    @if(!$kelas)
    <div class="flex flex-col items-center justify-center py-20 text-center bg-white rounded-2xl border shadow-sm">
        <div class="w-20 h-20 bg-amber-100 rounded-3xl flex items-center justify-center mb-5">
            <svg class="w-10 h-10 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
            </svg>
        </div>
        <h2 class="text-xl font-bold text-slate-700">Belum Ada Kelas Binaan</h2>
        <p class="text-slate-500 text-sm mt-2 max-w-md">
            Akun Anda belum ditetapkan sebagai wali kelas.
            Hubungi admin untuk mengatur kelas binaan.
        </p>
    </div>

    @else

    {{-- ── FILTER BULAN ─────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden mb-6">
        <div class="px-6 py-4 bg-slate-50 border-b flex items-center gap-2">
            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
            </svg>
            <h3 class="text-sm font-bold text-slate-700">Filter Bulan</h3>
            <span class="text-xs text-slate-400">— Pilih bulan untuk melihat rekap evaluasi kelas</span>
        </div>
        <form method="GET" class="p-6">
            <div class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[180px] max-w-xs">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">
                        Bulan <span class="text-red-400">*</span>
                    </label>
                    <select name="bulan"
                        class="w-full rounded-xl border-slate-300 text-sm focus:ring-2 focus:ring-slate-500 focus:border-slate-500">
                        @foreach(['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $key=>$val)
                            <option value="{{ $key }}" {{ $bulan == $key ? 'selected' : '' }}>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="bg-slate-700 hover:bg-slate-800 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition shadow-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110 3a7.5 7.5 0 016.65 13.65z"/>
                        </svg>
                        Tampilkan
                    </button>
                    <a href="{{ route('rekap.evaluasi.kelas') }}"
                        class="px-4 py-2.5 rounded-xl border border-slate-300 hover:bg-slate-50 text-slate-500 text-sm transition">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- ── STATISTIK SUMMARY ────────────────────────────────────────────── --}}
    @if($data->count())
    @php
        $distrib = $data->groupBy('kategori');
    @endphp
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl shadow-sm border p-5">
            <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Total Siswa</p>
            <p class="text-3xl font-bold text-slate-800 mt-1">{{ $data->count() }}</p>
            <p class="text-xs text-slate-400 mt-1">di kelas {{ $kelas->nama_kelas }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border p-5">
            <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Rata-rata Skor</p>
            <p class="text-3xl font-bold text-slate-700 mt-1">{{ round($data->avg('skor'), 1) }}</p>
            <p class="text-xs text-slate-400 mt-1">bulan {{ $namaBulan }}</p>
        </div>
        <div class="bg-emerald-50 rounded-2xl shadow-sm border border-emerald-100 p-5">
            <p class="text-xs text-emerald-600 font-semibold uppercase tracking-wide">Sangat Baik</p>
            <p class="text-3xl font-bold text-emerald-700 mt-1">{{ ($distrib['Sangat Baik'] ?? collect())->count() }}</p>
            <p class="text-xs text-emerald-500 mt-1">🌟 siswa</p>
        </div>
        <div class="bg-rose-50 rounded-2xl shadow-sm border border-rose-100 p-5">
            <p class="text-xs text-rose-600 font-semibold uppercase tracking-wide">Perlu Pembinaan</p>
            <p class="text-3xl font-bold text-rose-700 mt-1">{{ ($distrib['Perlu Pembinaan'] ?? collect())->count() }}</p>
            <p class="text-xs text-rose-500 mt-1">⚠️ siswa</p>
        </div>
    </div>

    {{-- ── TOP 3 ────────────────────────────────────────────────────────── --}}
    @if($data->count() >= 1)
    <div class="grid md:grid-cols-3 gap-4 mb-6">
        @foreach($data->take(3) as $item)
        @php
            $medal = match($loop->index) {
                0 => ['bg-amber-400',  'text-amber-800',  'bg-amber-50',  'border-amber-200', '🥇'],
                1 => ['bg-slate-400',  'text-slate-800',  'bg-slate-50',  'border-slate-200', '🥈'],
                2 => ['bg-orange-400', 'text-orange-800', 'bg-orange-50', 'border-orange-200','🥉'],
                default => ['bg-slate-200','text-slate-600','bg-white','border-slate-200','']
            };
        @endphp
        <div class="rounded-2xl shadow-sm border {{ $medal[2] }} {{ $medal[3] }} p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl {{ $medal[0] }} flex items-center justify-center text-white font-bold text-xl shrink-0">
                {{ $medal[4] }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Rank #{{ $loop->iteration }}</p>
                <p class="font-bold text-slate-800 text-sm truncate mt-0.5">{{ $item['nama'] }}</p>
                <p class="text-xs text-slate-500 mt-1">Skor <span class="font-bold text-slate-700">{{ $item['skor'] }}</span></p>
            </div>
        </div>
        @endforeach
    </div>
    @endif
    @endif

    {{-- ── TABEL UTAMA ──────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">

        {{-- Header tabel --}}
        <div class="px-6 py-4 bg-slate-800 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-white text-sm">
                    Hasil Evaluasi Kelas {{ $kelas->nama_kelas }} — {{ $namaBulan ?? '' }}
                </h3>
                <p class="text-slate-300 text-xs mt-0.5">
                    Akumulasi dari semua mata pelajaran · TA {{ $tahun->tahun ?? '-' }}
                </p>
            </div>
            <div class="flex items-center gap-3 text-xs text-slate-300">
                <span>{{ $data->count() }} siswa</span>
                <span class="bg-slate-500/30 text-slate-300 px-2.5 py-1 rounded-full font-semibold">
                    Fuzzy Mamdani
                </span>
            </div>
        </div>

        {{-- Distribusi kategori --}}
        @if($data->count())
        @php $distrib2 = $data->groupBy('kategori')->map->count(); @endphp
        <div class="grid grid-cols-2 md:grid-cols-4 border-b">
            @foreach(['Sangat Baik'=>['bg-emerald-50','text-emerald-700'],'Baik'=>['bg-blue-50','text-blue-700'],'Perlu Bimbingan'=>['bg-amber-50','text-amber-700'],'Perlu Pembinaan'=>['bg-rose-50','text-rose-700']] as $kat=>$cls)
            <div class="px-5 py-3 border-r last:border-0 {{ $cls[0] }} flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold {{ $cls[1] }} uppercase tracking-wide">{{ $kat }}</p>
                    <p class="text-xl font-bold {{ $cls[1] }} mt-0.5">{{ $distrib2[$kat] ?? 0 }}</p>
                </div>
                <span class="text-base opacity-40">
                    @if($kat==='Sangat Baik') 🌟
                    @elseif($kat==='Baik') 👍
                    @elseif($kat==='Perlu Bimbingan') 📘
                    @else ⚠️
                    @endif
                </span>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Tabel data --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-100 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3.5 text-center text-xs font-bold text-slate-500 uppercase w-12">Rank</th>
                        <th class="px-4 py-3.5 text-left   text-xs font-bold text-slate-500 uppercase">Nama Siswa</th>
                        <th class="px-4 py-3.5 text-center text-xs font-bold text-slate-500 uppercase">
                            Nilai<br><span class="font-normal normal-case text-slate-400">rata-rata mapel</span>
                        </th>
                        <th class="px-4 py-3.5 text-center text-xs font-bold text-slate-500 uppercase">
                            Absensi<br><span class="font-normal normal-case text-slate-400">% kehadiran</span>
                        </th>
                        <th class="px-4 py-3.5 text-center text-xs font-bold text-slate-500 uppercase">
                            Sikap<br><span class="font-normal normal-case text-slate-400">rata-rata</span>
                        </th>
                        <th class="px-4 py-3.5 text-center text-xs font-bold text-slate-500 uppercase">
                            Disiplin<br><span class="font-normal normal-case text-slate-400">rata-rata</span>
                        </th>
                        <th class="px-4 py-3.5 text-center text-xs font-bold text-slate-500 uppercase">
                            Skor Fuzzy<br><span class="font-normal normal-case text-slate-400">Mamdani</span>
                        </th>
                        <th class="px-4 py-3.5 text-center text-xs font-bold text-slate-500 uppercase">Kategori</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($data as $i => $item)
                    @php
                        $rankBg = match(true) {
                            $i === 0 => 'bg-amber-400 text-white',
                            $i === 1 => 'bg-slate-400 text-white',
                            $i === 2 => 'bg-orange-400 text-white',
                            default  => 'bg-slate-100 text-slate-600'
                        };
                        $katBadge = match($item['kategori']) {
                            'Sangat Baik'     => 'bg-emerald-100 text-emerald-700 border border-emerald-200',
                            'Baik'            => 'bg-blue-100 text-blue-700 border border-blue-200',
                            'Perlu Bimbingan' => 'bg-amber-100 text-amber-700 border border-amber-200',
                            default           => 'bg-rose-100 text-rose-700 border border-rose-200',
                        };
                        $skorColor = match(true) {
                            $item['skor'] >= 85 => 'text-emerald-600',
                            $item['skor'] >= 70 => 'text-blue-600',
                            $item['skor'] >= 55 => 'text-amber-600',
                            default             => 'text-rose-600',
                        };
                    @endphp
                    <tr class="hover:bg-slate-50 transition {{ $i < 3 ? 'font-medium' : '' }}">
                        <td class="px-4 py-3.5 text-center">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold {{ $rankBg }}">
                                {{ $i + 1 }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-600 text-xs font-bold flex items-center justify-center shrink-0">
                                    {{ strtoupper(substr($item['nama'], 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-slate-800">{{ $item['nama'] }}</p>
                                    @if($item['nis'] !== '-')
                                        <p class="text-[10px] text-slate-400">NIS: {{ $item['nis'] }}</p>
                                    @endif
                                    @if(!$item['ada_data'])
                                        <p class="text-[10px] text-amber-500 font-medium">belum ada data bulan ini</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        {{-- Nilai --}}
                        <td class="px-4 py-3.5 text-center">
                            <span class="font-mono font-semibold text-indigo-700">{{ $item['nilai'] }}</span>
                        </td>
                        {{-- Absensi --}}
                        <td class="px-4 py-3.5 text-center">
                            @php
                                $absBg = match(true) {
                                    $item['absensi'] >= 80 => 'bg-emerald-100 text-emerald-700',
                                    $item['absensi'] >= 60 => 'bg-amber-100 text-amber-700',
                                    default                => 'bg-rose-100 text-rose-700',
                                };
                            @endphp
                            <span class="px-2 py-0.5 rounded-lg text-xs font-semibold font-mono {{ $absBg }}">
                                {{ $item['absensi'] }}%
                            </span>
                        </td>
                        {{-- Sikap --}}
                        <td class="px-4 py-3.5 text-center font-mono text-slate-700">
                            {{ $item['sikap'] }}
                        </td>
                        {{-- Disiplin --}}
                        <td class="px-4 py-3.5 text-center font-mono text-slate-700">
                            {{ $item['disiplin'] }}
                        </td>
                        {{-- Skor --}}
                        <td class="px-4 py-3.5 text-center">
                            <span class="text-lg font-bold font-mono {{ $skorColor }}">{{ $item['skor'] }}</span>
                        </td>
                        {{-- Kategori --}}
                        <td class="px-4 py-3.5 text-center">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $katBadge }}">
                                {{ $item['kategori'] }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center gap-3 text-slate-400">
                                <svg class="w-12 h-12 opacity-40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-sm font-medium">Belum ada data evaluasi untuk bulan ini</p>
                                <p class="text-xs">Pastikan guru-guru sudah menginput nilai, absensi, sikap, dan kedisiplinan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer keterangan --}}
        @if($data->count())
        <div class="px-6 py-4 bg-slate-50 border-t">
            <div class="flex flex-wrap gap-x-6 gap-y-2 text-xs text-slate-500 mb-1">
                <span class="font-semibold text-slate-600">Metode:</span>
                <span>Fuzzy Mamdani — 36 rule base dengan membership triangular</span>
            </div>
            <div class="flex flex-wrap gap-x-6 gap-y-1 text-xs text-slate-500">
                <span class="font-semibold text-slate-600">Data diakumulasi dari:</span>
                <span>semua mata pelajaran yang diajarkan di kelas {{ $kelas->nama_kelas }}</span>
            </div>
            <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-slate-500 mt-2">
                <span class="font-semibold text-slate-600">Kategori:</span>
                <span class="text-emerald-600 font-medium">≥85 Sangat Baik</span>
                <span class="text-blue-600 font-medium">≥70 Baik</span>
                <span class="text-amber-600 font-medium">≥50 Perlu Bimbingan</span>
                <span class="text-rose-600 font-medium">&lt;50 Perlu Pembinaan</span>
            </div>
        </div>
        @endif

    </div>

    @endif {{-- end $kelas --}}

</x-app-layout>

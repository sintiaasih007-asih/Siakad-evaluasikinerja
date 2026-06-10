<x-app-layout>

    <x-page-header
        title="Evaluasi Bulanan"
        subtitle="Rekap perkembangan akademik siswa per bulan menggunakan Fuzzy Logic"
    />

    {{-- ── AKSES DITUTUP ───────────────────────────────────────────────── --}}
    @if(!$evaluasiAktif)
    <div class="flex flex-col items-center justify-center py-20 text-center">
        <div class="w-20 h-20 bg-amber-100 rounded-3xl flex items-center justify-center mb-5">
            <svg class="w-10 h-10 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
            </svg>
        </div>
        <h2 class="text-xl font-bold text-slate-700">Evaluasi Bulanan Belum Dibuka</h2>
        <p class="text-slate-500 text-sm mt-2 max-w-md">
            Halaman ini hanya bisa diakses setelah admin mengaktifkan evaluasi bulanan.
            Hubungi admin sekolah untuk membuka akses.
        </p>
        <div class="mt-6 bg-amber-50 border border-amber-200 rounded-2xl px-6 py-4 text-sm text-amber-700 max-w-sm">
            <strong>Admin:</strong> Aktifkan melalui Dashboard → Kelola Evaluasi
        </div>
    </div>

    @else

    {{-- ── HEADER INFO ─────────────────────────────────────────────────── --}}
    <div class="rounded-2xl p-5 mb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 shadow-sm"
         style="background:linear-gradient(135deg,#1e40af,#1d4ed8)">
        <div>
            <p class="text-blue-200 text-[10px] font-bold uppercase tracking-widest mb-0.5">Sistem Fuzzy Logic</p>
            <h2 class="text-lg font-bold text-white">Evaluasi Bulanan Siswa</h2>
            <p class="text-blue-200 text-xs mt-0.5">
                TA: <strong class="text-white">{{ $tahun->tahun ?? '-' }}</strong>
                &nbsp;·&nbsp; {{ auth()->user()->name }}
            </p>
        </div>
        <div class="hidden sm:flex flex-col items-end gap-1 text-[11px] text-blue-200">
            <span>Nilai 40% · Absensi 30% · Sikap 15% · Disiplin 15%</span>
            <span class="text-blue-300">Membership Trapesium · Centroid Defuzz</span>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden mb-5">

        <div class="px-5 py-3.5 bg-slate-50 border-b flex items-center gap-2">
            <i data-lucide="filter" class="w-4 h-4 text-slate-400"></i>
            <h3 class="text-sm font-semibold text-slate-700">Filter Evaluasi</h3>
            <span class="text-xs text-slate-400">— Pilih semua filter untuk menampilkan hasil</span>
        </div>

        <form method="GET" class="p-6">
            <div class="grid md:grid-cols-4 gap-4">

                {{-- Mata Pelajaran --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">
                        Mata Pelajaran <span class="text-red-400">*</span>
                    </label>
                    <select name="jadwal_id"
                        class="w-full rounded-xl border-slate-300 text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        <option value="">— Pilih Mapel —</option>
                        @foreach($jadwals as $j)
                            <option value="{{ $j->id }}"
                                {{ request('jadwal_id', $jadwalId) == $j->id ? 'selected' : '' }}>
                                {{ $j->nama_mapel }} · {{ $j->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Bulan --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">
                        Bulan <span class="text-red-400">*</span>
                    </label>
                    <select name="bulan"
                        class="w-full rounded-xl border-slate-300 text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        <option value="">— Pilih Bulan —</option>
                        @foreach(['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $key=>$val)
                            <option value="{{ $key }}" {{ $bulan == $key ? 'selected' : '' }}>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Kelas --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">
                        Kelas <span class="text-red-400">*</span>
                    </label>
                    <select name="kelas_id"
                        class="w-full rounded-xl border-slate-300 text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        <option value="">— Pilih Kelas —</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id }}"
                                {{ request('kelas_id', $kelasId) == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tombol --}}
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="btn-primary flex-1">
                        <i data-lucide="search" class="w-4 h-4"></i>
                        Tampilkan
                    </button>
                    <a href="{{ route('evaluasi.bulanan') }}"
                        class="btn-secondary px-3">
                        Reset
                    </a>
                </div>

            </div>
        </form>

    </div>

    {{-- ── TABEL HASIL ──────────────────────────────────────────────────── --}}
    @if(!$filtered)

    <div class="bg-white rounded-2xl shadow-sm border p-16 text-center">
        <div class="w-16 h-16 bg-teal-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-teal-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
            </svg>
        </div>
        <h3 class="text-base font-bold text-slate-700">Pilih Filter Terlebih Dahulu</h3>
        <p class="text-sm text-slate-400 mt-1 max-w-sm mx-auto">
            Pilih Mata Pelajaran, Bulan, dan Kelas untuk melihat hasil evaluasi siswa.
        </p>
    </div>

    @else

    @php
        $namaBulanTerpilih = ['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'][$bulan] ?? $bulan;
        $jadwalTerpilih    = $jadwals->firstWhere('id', $jadwalId);
    @endphp

    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">

        {{-- Header Tabel --}}
        <div class="px-6 py-4 bg-slate-800 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-white text-sm">
                    Hasil Evaluasi — {{ $namaBulanTerpilih }}
                </h3>
                <p class="text-slate-300 text-xs mt-0.5">
                    {{ $jadwalTerpilih?->nama_mapel ?? '' }}
                    · {{ $jadwalTerpilih?->nama_kelas ?? '' }}
                    · TA {{ $tahun->tahun ?? '-' }}
                </p>
            </div>
            <div class="flex items-center gap-3 text-xs text-slate-300">
                <span>{{ count($data) }} siswa</span>
                <span class="bg-teal-500/20 text-teal-300 px-2.5 py-1 rounded-full font-semibold">
                    Fuzzy Ranking
                </span>
            </div>
        </div>

        {{-- Distribusi kategori --}}
        @if(count($data))
        @php
            $distrib = collect($data)->groupBy('kategori')->map->count();
        @endphp
        <div class="grid grid-cols-2 md:grid-cols-4 border-b">
            @foreach(['Sangat Baik'=>['bg-emerald-50','text-emerald-700','border-emerald-100'],'Baik'=>['bg-blue-50','text-blue-700','border-blue-100'],'Perlu Bimbingan'=>['bg-amber-50','text-amber-700','border-amber-100'],'Perlu Pembinaan'=>['bg-rose-50','text-rose-700','border-rose-100']] as $kat=>$cls)
            <div class="px-5 py-4 border-r last:border-0 {{ $cls[0] }} flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold {{ $cls[1] }} uppercase tracking-wide">{{ $kat }}</p>
                    <p class="text-2xl font-bold {{ $cls[1] }} mt-0.5">{{ $distrib[$kat] ?? 0 }}</p>
                </div>
                <div class="text-lg opacity-50">
                    @if($kat==='Sangat Baik') 🌟
                    @elseif($kat==='Baik') 👍
                    @elseif($kat==='Perlu Bimbingan') 📘
                    @else ⚠️
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Tabel utama --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-100 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3.5 text-center text-xs font-bold text-slate-500 uppercase w-12">Rank</th>
                        <th class="px-4 py-3.5 text-left text-xs font-bold text-slate-500 uppercase">Nama Siswa</th>
                        <th class="px-4 py-3.5 text-center text-xs font-bold text-slate-500 uppercase">
                            Nilai<br><span class="font-normal normal-case text-slate-400">40%</span>
                        </th>
                        <th class="px-4 py-3.5 text-center text-xs font-bold text-slate-500 uppercase">
                            Absensi<br><span class="font-normal normal-case text-slate-400">30%</span>
                        </th>
                        <th class="px-4 py-3.5 text-center text-xs font-bold text-slate-500 uppercase hide-mobile">
                            Sikap<br><span class="font-normal normal-case text-slate-400">15%</span>
                        </th>
                        <th class="px-4 py-3.5 text-center text-xs font-bold text-slate-500 uppercase hide-mobile">
                            Disiplin<br><span class="font-normal normal-case text-slate-400">15%</span>
                        </th>
                        <th class="px-4 py-3.5 text-center text-xs font-bold text-slate-500 uppercase">
                            Skor Fuzzy<br><span class="font-normal normal-case text-slate-400">0–100</span>
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
                                <div class="w-8 h-8 rounded-full bg-teal-100 text-teal-700 text-xs font-bold flex items-center justify-center shrink-0">
                                    {{ strtoupper(substr($item['nama'], 0, 1)) }}
                                </div>
                                <div>
                                    <span class="text-slate-800">{{ $item['nama'] }}</span>
                                    @if(!$item['ada_data'])
                                        <span class="ml-1 text-[10px] text-amber-500 font-medium">(belum ada data)</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        {{-- Nilai --}}
                        <td class="px-4 py-3.5 text-center">
                            <div class="font-mono font-semibold text-indigo-700">{{ $item['nilai'] }}</div>
                            <div class="text-[10px] text-slate-400 mt-0.5 hide-mobile">μ={{ $item['mu_nilai'] }}</div>
                        </td>
                        {{-- Absensi --}}
                        <td class="px-4 py-3.5 text-center">
                            <div class="font-mono font-semibold text-teal-700">{{ $item['absensi'] }}%</div>
                            <div class="text-[10px] text-slate-400 mt-0.5 hide-mobile">μ={{ $item['mu_absensi'] }}</div>
                        </td>
                        {{-- Sikap --}}
                        <td class="px-4 py-3.5 text-center hide-mobile">
                            <div class="font-mono text-slate-700">{{ $item['sikap'] }}</div>
                            <div class="text-[10px] text-slate-400 mt-0.5">μ={{ $item['mu_sikap'] }}</div>
                        </td>
                        {{-- Disiplin --}}
                        <td class="px-4 py-3.5 text-center hide-mobile">
                            <div class="font-mono text-slate-700">{{ $item['disiplin'] }}</div>
                            <div class="text-[10px] text-slate-400 mt-0.5">μ={{ $item['mu_disiplin'] }}</div>
                        </td>
                        {{-- Skor Fuzzy --}}
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
                                <p class="text-sm font-medium">Belum ada data evaluasi untuk filter ini</p>
                                <p class="text-xs">Pastikan nilai, absensi, sikap, dan kedisiplinan sudah diinput untuk bulan ini</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Keterangan Fuzzy Logic --}}
        @if(count($data))
        <div class="px-6 py-4 bg-slate-50 border-t">
            <div class="flex flex-wrap gap-x-6 gap-y-2 text-xs text-slate-500 mb-2">
                <span class="font-semibold text-slate-600">Metode Fuzzy Logic:</span>
                <span>Membership trapesium (Rendah: 0–55, Sedang: 40–80, Tinggi: 70–100)</span>
                <span>· Defuzzifikasi centroid berbobot</span>
            </div>
            <div class="flex flex-wrap gap-x-6 gap-y-1 text-xs text-slate-500">
                <span class="font-semibold text-slate-600">Agregasi bobot:</span>
                <span>Nilai×40% + Absensi×30% + Sikap×15% + Disiplin×15%</span>
            </div>
            <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-slate-500 mt-2">
                <span class="font-semibold text-slate-600">Kategori:</span>
                <span class="text-emerald-600 font-medium">≥85 Sangat Baik</span>
                <span class="text-blue-600 font-medium">≥70 Baik</span>
                <span class="text-amber-600 font-medium">≥55 Perlu Bimbingan</span>
                <span class="text-rose-600 font-medium">&lt;55 Perlu Pembinaan</span>
            </div>
            <div class="mt-2 text-[11px] text-slate-400">
                μ = derajat keanggotaan fuzzy (0.0000–1.0000), menunjukkan seberapa "baik" performa tiap komponen secara fuzzy.
            </div>
        </div>
        @endif

    </div>

    @endif {{-- end $filtered --}}

    @endif {{-- end $evaluasiAktif --}}

</x-app-layout>

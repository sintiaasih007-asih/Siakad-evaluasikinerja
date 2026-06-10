<x-app-layout>

    <x-page-header
        title="Evaluasi Semesteran"
        subtitle="Rekap perkembangan akademik siswa selama 1 semester menggunakan Fuzzy Logic"
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
        <h2 class="text-xl font-bold text-slate-700">Evaluasi Semesteran Belum Dibuka</h2>
        <p class="text-slate-500 text-sm mt-2 max-w-md">
            Halaman ini hanya bisa diakses setelah admin mengaktifkan evaluasi.
            Hubungi admin sekolah untuk membuka akses.
        </p>
        <div class="mt-6 bg-amber-50 border border-amber-200 rounded-2xl px-6 py-4 text-sm text-amber-700 max-w-sm">
            <strong>Admin:</strong> Aktifkan melalui Dashboard → Kelola Evaluasi
        </div>
    </div>

    @else

    {{-- ── HEADER INFO ─────────────────────────────────────────────────── --}}
    <div class="bg-gradient-to-r from-indigo-700 to-violet-600 rounded-2xl p-6 mb-6 flex items-center justify-between shadow-lg">
        <div>
            <p class="text-indigo-100 text-xs font-semibold uppercase tracking-widest mb-1">Sistem Fuzzy Logic — Agregasi Semester</p>
            <h2 class="text-xl font-bold text-white">Evaluasi Semesteran</h2>
            <p class="text-indigo-100 text-sm mt-1">
                Tahun Ajaran:
                <strong class="text-white">{{ $tahun->tahun ?? '-' }}</strong>
                · Guru: <strong class="text-white">{{ auth()->user()->name }}</strong>
            </p>
        </div>
        <div class="hidden md:flex flex-col items-end gap-1 text-xs text-indigo-100">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-indigo-300"></span>Nilai Akademik (bobot 40%)
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-violet-300"></span>Absensi (bobot 30%)
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-cyan-300"></span>Sikap (bobot 15%)
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-lime-300"></span>Disiplin (bobot 15%)
            </div>
        </div>
    </div>

    {{-- ── FILTER ───────────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden mb-6">

        <div class="px-6 py-4 bg-slate-50 border-b flex items-center gap-2">
            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
            </svg>
            <h3 class="text-sm font-bold text-slate-700">Filter Evaluasi</h3>
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
                        class="w-full rounded-xl border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">— Pilih Mapel —</option>
                        @foreach($jadwals as $j)
                            <option value="{{ $j->id }}"
                                {{ request('jadwal_id', $jadwalId) == $j->id ? 'selected' : '' }}>
                                {{ $j->nama_mapel }} · {{ $j->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Semester --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">
                        Semester <span class="text-red-400">*</span>
                    </label>
                    <select name="semester"
                        class="w-full rounded-xl border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">— Pilih Semester —</option>
                        <option value="Ganjil" {{ $semester == 'Ganjil' ? 'selected' : '' }}>
                            Ganjil (Juli – Desember)
                        </option>
                        <option value="Genap" {{ $semester == 'Genap' ? 'selected' : '' }}>
                            Genap (Januari – Juni)
                        </option>
                    </select>
                </div>

                {{-- Kelas --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">
                        Kelas <span class="text-red-400">*</span>
                    </label>
                    <select name="kelas_id"
                        class="w-full rounded-xl border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
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
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold py-2.5 rounded-xl transition shadow-sm flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110 3a7.5 7.5 0 016.65 13.65z"/>
                        </svg>
                        Tampilkan
                    </button>
                    <a href="{{ route('evaluasi.semesteran') }}"
                        class="px-3 py-2.5 rounded-xl border border-slate-300 hover:bg-slate-50 text-slate-500 text-sm transition">
                        Reset
                    </a>
                </div>

            </div>
        </form>

    </div>

    {{-- ── HASIL ────────────────────────────────────────────────────────── --}}
    @if(!$filtered)

    <div class="bg-white rounded-2xl shadow-sm border p-16 text-center">
        <div class="w-16 h-16 bg-indigo-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
            </svg>
        </div>
        <h3 class="text-base font-bold text-slate-700">Pilih Filter Terlebih Dahulu</h3>
        <p class="text-sm text-slate-400 mt-1 max-w-sm mx-auto">
            Pilih Mata Pelajaran, Semester, dan Kelas untuk melihat hasil evaluasi semesteran.
        </p>
    </div>

    @else

    @php
        $jadwalTerpilih = $jadwals->firstWhere('id', $jadwalId);
        $bulanLabel     = $semester === 'Ganjil' ? 'Juli – Desember' : 'Januari – Juni';
    @endphp

    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden" x-data="{ modalOpen: false, modalSiswa: null }">

        {{-- Header --}}
        <div class="px-6 py-4 bg-slate-800 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-white text-sm">
                    Hasil Evaluasi Semester {{ $semester }} — {{ $bulanLabel }}
                </h3>
                <p class="text-slate-300 text-xs mt-0.5">
                    {{ $jadwalTerpilih?->nama_mapel ?? '' }}
                    · {{ $jadwalTerpilih?->nama_kelas ?? '' }}
                    · TA {{ $tahun->tahun ?? '-' }}
                </p>
            </div>
            <div class="flex items-center gap-3 text-xs text-slate-300">
                <span>{{ count($data) }} siswa</span>
                <span class="bg-indigo-500/20 text-indigo-300 px-2.5 py-1 rounded-full font-semibold">
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
            @foreach(['Sangat Baik'=>['bg-emerald-50','text-emerald-700'],'Baik'=>['bg-blue-50','text-blue-700'],'Perlu Bimbingan'=>['bg-amber-50','text-amber-700'],'Perlu Pembinaan'=>['bg-rose-50','text-rose-700']] as $kat=>$cls)
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

        {{-- Info semester --}}
        <div class="px-6 py-3 bg-indigo-50 border-b border-indigo-100 flex flex-wrap items-center gap-3 text-xs text-indigo-700">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Nilai rata-rata dihitung dari <strong>{{ count($rincianBulan) }} bulan</strong> pada semester {{ $semester }}:</span>
            @foreach($rincianBulan as $bln)
                <span class="bg-indigo-100 px-2 py-0.5 rounded-full font-medium">{{ $bln }}</span>
            @endforeach
            <span class="ml-auto text-indigo-500">Klik baris siswa untuk melihat rincian per bulan</span>
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-100 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3.5 text-center text-xs font-bold text-slate-500 uppercase w-12">Rank</th>
                        <th class="px-4 py-3.5 text-left text-xs font-bold text-slate-500 uppercase">Nama Siswa</th>
                        <th class="px-4 py-3.5 text-center text-xs font-bold text-slate-500 uppercase">
                            Nilai<br><span class="font-normal normal-case text-slate-400">rata-rata · 40%</span>
                        </th>
                        <th class="px-4 py-3.5 text-center text-xs font-bold text-slate-500 uppercase">
                            Absensi<br><span class="font-normal normal-case text-slate-400">rata-rata · 30%</span>
                        </th>
                        <th class="px-4 py-3.5 text-center text-xs font-bold text-slate-500 uppercase">
                            Sikap<br><span class="font-normal normal-case text-slate-400">rata-rata · 15%</span>
                        </th>
                        <th class="px-4 py-3.5 text-center text-xs font-bold text-slate-500 uppercase">
                            Disiplin<br><span class="font-normal normal-case text-slate-400">rata-rata · 15%</span>
                        </th>
                        <th class="px-4 py-3.5 text-center text-xs font-bold text-slate-500 uppercase">
                            Skor Fuzzy<br><span class="font-normal normal-case text-slate-400">0–100</span>
                        </th>
                        <th class="px-4 py-3.5 text-center text-xs font-bold text-slate-500 uppercase">Kategori</th>
                        <th class="px-4 py-3.5 text-center text-xs font-bold text-slate-500 uppercase w-10">Detail</th>
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
                    <tr class="hover:bg-slate-50 transition cursor-pointer {{ $i < 3 ? 'font-medium' : '' }}"
                        @click="modalSiswa = {{ json_encode($item) }}; modalOpen = true">
                        <td class="px-4 py-3.5 text-center">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold {{ $rankBg }}">
                                {{ $i + 1 }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold flex items-center justify-center shrink-0">
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
                        <td class="px-4 py-3.5 text-center">
                            <div class="font-mono font-semibold text-indigo-700">{{ $item['nilai'] }}</div>
                            <div class="text-[10px] text-slate-400 mt-0.5">μ={{ $item['mu_nilai'] }}</div>
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            <div class="font-mono font-semibold text-teal-700">{{ $item['absensi'] }}%</div>
                            <div class="text-[10px] text-slate-400 mt-0.5">μ={{ $item['mu_absensi'] }}</div>
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            <div class="font-mono text-slate-700">{{ $item['sikap'] }}</div>
                            <div class="text-[10px] text-slate-400 mt-0.5">μ={{ $item['mu_sikap'] }}</div>
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            <div class="font-mono text-slate-700">{{ $item['disiplin'] }}</div>
                            <div class="text-[10px] text-slate-400 mt-0.5">μ={{ $item['mu_disiplin'] }}</div>
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            <span class="text-lg font-bold font-mono {{ $skorColor }}">{{ $item['skor'] }}</span>
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $katBadge }}">
                                {{ $item['kategori'] }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            <span class="text-indigo-400 hover:text-indigo-600 transition">
                                <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center gap-3 text-slate-400">
                                <svg class="w-12 h-12 opacity-40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-sm font-medium">Belum ada data evaluasi untuk filter ini</p>
                                <p class="text-xs">Pastikan nilai, absensi, sikap, dan kedisiplinan sudah diinput untuk semester ini</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Keterangan Fuzzy --}}
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
                μ = derajat keanggotaan fuzzy (0.0000–1.0000). Semua nilai merupakan rata-rata dari {{ count($rincianBulan) }} bulan dalam semester {{ $semester }}.
            </div>
        </div>
        @endif

        {{-- ── MODAL DETAIL PER BULAN ──────────────────────────────────── --}}
        <div x-show="modalOpen"
             x-transition.opacity
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4"
             @click.self="modalOpen = false">

            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto"
                 x-show="modalOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">

                <template x-if="modalSiswa">
                    <div>
                        {{-- Modal Header --}}
                        <div class="px-6 py-5 border-b flex items-center justify-between bg-gradient-to-r from-indigo-700 to-violet-600 rounded-t-2xl">
                            <div>
                                <p class="text-indigo-100 text-xs font-semibold uppercase tracking-widest">Rincian Evaluasi Per Bulan</p>
                                <h3 class="text-lg font-bold text-white mt-0.5" x-text="modalSiswa.nama"></h3>
                            </div>
                            <button @click="modalOpen = false"
                                class="w-8 h-8 rounded-xl bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Ringkasan Semester --}}
                        <div class="p-5 grid grid-cols-2 md:grid-cols-4 gap-3 border-b bg-slate-50">
                            <div class="bg-white rounded-xl p-3 border text-center">
                                <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wide">Rata Nilai</p>
                                <p class="text-xl font-bold text-indigo-700 mt-1 font-mono" x-text="modalSiswa.nilai"></p>
                                <p class="text-[10px] text-slate-400 mt-0.5">μ = <span x-text="modalSiswa.mu_nilai"></span></p>
                            </div>
                            <div class="bg-white rounded-xl p-3 border text-center">
                                <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wide">Rata Absensi</p>
                                <p class="text-xl font-bold text-teal-700 mt-1 font-mono"><span x-text="modalSiswa.absensi"></span>%</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">μ = <span x-text="modalSiswa.mu_absensi"></span></p>
                            </div>
                            <div class="bg-white rounded-xl p-3 border text-center">
                                <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wide">Rata Sikap</p>
                                <p class="text-xl font-bold text-slate-700 mt-1 font-mono" x-text="modalSiswa.sikap"></p>
                                <p class="text-[10px] text-slate-400 mt-0.5">μ = <span x-text="modalSiswa.mu_sikap"></span></p>
                            </div>
                            <div class="bg-white rounded-xl p-3 border text-center">
                                <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wide">Rata Disiplin</p>
                                <p class="text-xl font-bold text-slate-700 mt-1 font-mono" x-text="modalSiswa.disiplin"></p>
                                <p class="text-[10px] text-slate-400 mt-0.5">μ = <span x-text="modalSiswa.mu_disiplin"></span></p>
                            </div>
                        </div>

                        {{-- Skor Final --}}
                        <div class="px-5 py-3 flex items-center justify-between bg-white border-b">
                            <div class="flex items-center gap-3">
                                <span class="text-sm text-slate-500 font-medium">Skor Fuzzy Semester:</span>
                                <span class="text-2xl font-bold font-mono"
                                    :class="{
                                        'text-emerald-600': modalSiswa.skor >= 85,
                                        'text-blue-600':    modalSiswa.skor >= 70 && modalSiswa.skor < 85,
                                        'text-amber-600':   modalSiswa.skor >= 55 && modalSiswa.skor < 70,
                                        'text-rose-600':    modalSiswa.skor < 55
                                    }"
                                    x-text="modalSiswa.skor">
                                </span>
                            </div>
                            <span class="px-3 py-1.5 rounded-full text-sm font-bold border"
                                :class="{
                                    'bg-emerald-100 text-emerald-700 border-emerald-200': modalSiswa.kategori === 'Sangat Baik',
                                    'bg-blue-100 text-blue-700 border-blue-200':          modalSiswa.kategori === 'Baik',
                                    'bg-amber-100 text-amber-700 border-amber-200':       modalSiswa.kategori === 'Perlu Bimbingan',
                                    'bg-rose-100 text-rose-700 border-rose-200':          modalSiswa.kategori === 'Perlu Pembinaan'
                                }"
                                x-text="modalSiswa.kategori">
                            </span>
                        </div>

                        {{-- Tabel rincian per bulan --}}
                        <div class="p-5">
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-3">Rincian per Bulan</p>
                            <div class="overflow-x-auto rounded-xl border">
                                <table class="w-full text-xs">
                                    <thead class="bg-slate-100">
                                        <tr>
                                            <th class="px-4 py-2.5 text-left font-bold text-slate-500 uppercase">Bulan</th>
                                            <th class="px-4 py-2.5 text-center font-bold text-slate-500 uppercase">Nilai</th>
                                            <th class="px-4 py-2.5 text-center font-bold text-slate-500 uppercase">Absensi</th>
                                            <th class="px-4 py-2.5 text-center font-bold text-slate-500 uppercase">Sikap</th>
                                            <th class="px-4 py-2.5 text-center font-bold text-slate-500 uppercase">Disiplin</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach($rincianBulan as $bln)
                                        <tr class="hover:bg-slate-50" x-show="modalSiswa">
                                            <td class="px-4 py-2.5 font-medium text-slate-700">{{ $bln }}</td>
                                            <td class="px-4 py-2.5 text-center font-mono text-indigo-700"
                                                x-text="modalSiswa.nilai_per_bulan['{{ $bln }}'] ?? 0">
                                            </td>
                                            <td class="px-4 py-2.5 text-center font-mono text-teal-700">
                                                <span x-text="modalSiswa.absensi_per_bulan['{{ $bln }}'] ?? 0"></span>%
                                            </td>
                                            <td class="px-4 py-2.5 text-center font-mono text-slate-600"
                                                x-text="modalSiswa.sikap_per_bulan['{{ $bln }}'] ?? 0">
                                            </td>
                                            <td class="px-4 py-2.5 text-center font-mono text-slate-600"
                                                x-text="modalSiswa.disiplin_per_bulan['{{ $bln }}'] ?? 0">
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </template>
            </div>
        </div>

    </div>{{-- end card --}}

    @endif {{-- end $filtered --}}

    @endif {{-- end $evaluasiAktif --}}

</x-app-layout>

<x-app-layout>

    <x-page-header
        title="Nilai Akademik"
        subtitle="Rekap nilai anak per kategori dan mata pelajaran"
    />

    {{-- ── PROFIL SISWA ────────────────────────────────────────────────────── --}}
    <div class="bg-gradient-to-r from-blue-700 to-indigo-600 rounded-2xl p-5 mb-6 flex items-center gap-5 shadow-lg">
        <div class="w-14 h-14 rounded-2xl bg-white/20 text-white font-bold text-2xl flex items-center justify-center shrink-0">
            {{ strtoupper(substr($siswa->nama, 0, 1)) }}
        </div>
        <div class="flex-1">
            <h2 class="text-white text-xl font-bold">{{ $siswa->nama }}</h2>
            <p class="text-blue-100 text-sm">NIS: {{ $siswa->nis }} · Kelas {{ $siswa->kelas->nama_kelas ?? '-' }}</p>
            <p class="text-blue-100 text-xs mt-0.5">Tahun Ajaran: <strong class="text-white">{{ $tahunAktif->tahun ?? '-' }}</strong></p>
        </div>
        <div class="hidden md:block text-right text-blue-100 text-xs">
            <p>Orang Tua / Wali</p>
            <p class="text-white font-semibold">{{ $siswa->nama_ortu ?? '-' }}</p>
        </div>
    </div>

    {{-- ── RINGKASAN ────────────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl border shadow-sm p-5">
            <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Total Penilaian</p>
            <p class="text-3xl font-bold text-slate-800 mt-1">{{ $ringkasan['total'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border shadow-sm p-5">
            <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Rata-rata</p>
            <p class="text-3xl font-bold mt-1 {{ $ringkasan['rata'] >= 75 ? 'text-teal-600' : 'text-amber-600' }}">
                {{ $ringkasan['rata'] }}
            </p>
        </div>
        <div class="bg-emerald-50 rounded-2xl border border-emerald-100 shadow-sm p-5">
            <p class="text-xs text-emerald-600 font-semibold uppercase tracking-wide">Nilai Tertinggi</p>
            <p class="text-3xl font-bold text-emerald-700 mt-1">{{ $ringkasan['tertinggi'] }}</p>
        </div>
        <div class="bg-rose-50 rounded-2xl border border-rose-100 shadow-sm p-5">
            <p class="text-xs text-rose-600 font-semibold uppercase tracking-wide">Nilai Terendah</p>
            <p class="text-3xl font-bold text-rose-700 mt-1">{{ $ringkasan['terendah'] ?: '—' }}</p>
        </div>
    </div>

    {{-- ── FILTER ───────────────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-3 bg-slate-50 border-b flex items-center gap-2">
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
            </svg>
            <span class="text-sm font-bold text-slate-600">Filter Nilai</span>
        </div>
        <form method="GET" class="px-6 py-4 flex flex-wrap items-end gap-4">
            {{-- Bulan --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Bulan</label>
                <select name="bulan" class="rounded-xl border-slate-300 text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Bulan</option>
                    @foreach($bulanAda as $b)
                        <option value="{{ $b }}" {{ $filterBulan == $b ? 'selected' : '' }}>{{ $b }}</option>
                    @endforeach
                </select>
            </div>
            {{-- Jenis --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Jenis Nilai</label>
                <select name="jenis" class="rounded-xl border-slate-300 text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Jenis</option>
                    @foreach($jenisAda as $j)
                        <option value="{{ $j }}" {{ $filterJenis == $j ? 'selected' : '' }}>{{ $j }}</option>
                    @endforeach
                </select>
            </div>
            {{-- Mapel --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Mata Pelajaran</label>
                <select name="mapel_id" class="rounded-xl border-slate-300 text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Mapel</option>
                    @foreach($mapelList as $m)
                        <option value="{{ $m->mapel_id }}" {{ $filterMapelId == $m->mapel_id ? 'selected' : '' }}>
                            {{ $m->nama_mapel }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110 3a7.5 7.5 0 016.65 13.65z"/>
                    </svg>
                    Tampilkan
                </button>
                <a href="{{ route('orangtua.nilai') }}"
                    class="px-4 py-2.5 rounded-xl border border-slate-300 hover:bg-slate-50 text-slate-500 text-sm transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- ── TABEL PER JENIS NILAI ────────────────────────────────────────────── --}}
    @forelse($grouped as $jenis => $perMapel)

    {{-- Warna header per jenis --}}
    @php
        $jenisConfig = [
            'Tugas'  => ['from-violet-600','to-violet-500','bg-violet-50','text-violet-700','border-violet-200','bg-violet-100'],
            'Quis'   => ['from-blue-600',  'to-blue-500',  'bg-blue-50',  'text-blue-700',  'border-blue-200',  'bg-blue-100'],
            'UTS'    => ['from-amber-600', 'to-amber-500', 'bg-amber-50', 'text-amber-700', 'border-amber-200', 'bg-amber-100'],
            'UAS'    => ['from-teal-600',  'to-teal-500',  'bg-teal-50',  'text-teal-700',  'border-teal-200',  'bg-teal-100'],
        ];
        $cfg = $jenisConfig[$jenis] ?? ['from-slate-600','to-slate-500','bg-slate-50','text-slate-700','border-slate-200','bg-slate-100'];
    @endphp

    <div class="mb-8">

        {{-- Judul jenis nilai --}}
        <div class="bg-gradient-to-r {{ $cfg[0] }} {{ $cfg[1] }} rounded-2xl px-6 py-4 mb-0 flex items-center justify-between shadow">
            <div>
                <p class="text-white/70 text-xs font-semibold uppercase tracking-widest">Kategori Penilaian</p>
                <h2 class="text-white text-xl font-bold mt-0.5">{{ strtoupper($jenis) }}</h2>
            </div>
            @php
                $allNilaiJenis = collect($perMapel)->flatten(1);
                $rataJenis     = round($allNilaiJenis->avg('nilai') ?? 0, 1);
                $totalJenis    = $allNilaiJenis->count();
            @endphp
            <div class="text-right text-white/80 text-xs">
                <p>{{ $totalJenis }} penilaian</p>
                <p class="text-white font-bold text-lg mt-0.5">Rata-rata: {{ $rataJenis }}</p>
            </div>
        </div>

        {{-- Tabel per mapel dalam jenis ini --}}
        <div class="border border-t-0 rounded-b-2xl overflow-hidden shadow-sm bg-white">

            @foreach($perMapel as $namaMapel => $items)
            @php
                $rataMapel = round(collect($items)->avg('nilai') ?? 0, 1);
            @endphp

            {{-- Sub-header mapel --}}
            <div class="{{ $cfg[2] }} px-5 py-2.5 border-b {{ $cfg[4] }} flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full {{ str_replace('bg-', 'bg-', $cfg[5]) }}"></span>
                    <span class="text-sm font-bold {{ $cfg[3] }}">{{ $namaMapel }}</span>
                </div>
                <div class="flex items-center gap-4 text-xs {{ $cfg[3] }}">
                    <span>{{ count($items) }} penilaian</span>
                    <span class="font-bold">Rata-rata: {{ $rataMapel }}</span>
                </div>
            </div>

            {{-- Baris nilai --}}
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-2.5 text-left text-xs font-bold text-slate-400 uppercase w-10">#</th>
                        <th class="px-5 py-2.5 text-left text-xs font-bold text-slate-400 uppercase">Nama Penilaian</th>
                        <th class="px-5 py-2.5 text-left text-xs font-bold text-slate-400 uppercase">Guru</th>
                        <th class="px-5 py-2.5 text-left text-xs font-bold text-slate-400 uppercase">Bulan</th>
                        <th class="px-5 py-2.5 text-center text-xs font-bold text-slate-400 uppercase">Nilai</th>
                        <th class="px-5 py-2.5 text-center text-xs font-bold text-slate-400 uppercase">Predikat</th>
                        <th class="px-5 py-2.5 text-left text-xs font-bold text-slate-400 uppercase">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($items as $idx => $n)
                    @php
                        [$predLabel, $predClass] = match(true) {
                            $n->nilai >= 85 => ['A — Sangat Baik', 'bg-emerald-100 text-emerald-700'],
                            $n->nilai >= 75 => ['B — Baik',        'bg-blue-100 text-blue-700'],
                            $n->nilai >= 65 => ['C — Cukup',       'bg-amber-100 text-amber-700'],
                            default         => ['D — Kurang',      'bg-rose-100 text-rose-700'],
                        };
                        $nilaiColor = match(true) {
                            $n->nilai >= 85 => 'text-emerald-700',
                            $n->nilai >= 75 => 'text-blue-700',
                            $n->nilai >= 65 => 'text-amber-700',
                            default         => 'text-rose-700',
                        };
                    @endphp
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-5 py-3 text-slate-400 text-xs">{{ $idx + 1 }}</td>
                        <td class="px-5 py-3 text-slate-700 font-medium">{{ $n->nama_penilaian }}</td>
                        <td class="px-5 py-3 text-slate-500 text-xs">{{ $n->nama_guru }}</td>
                        <td class="px-5 py-3">
                            <span class="px-2 py-0.5 rounded-lg bg-slate-100 text-slate-600 text-xs">{{ $n->bulan }}</span>
                        </td>
                        <td class="px-5 py-3 text-center">
                            <span class="text-xl font-bold font-mono {{ $nilaiColor }}">{{ $n->nilai }}</span>
                        </td>
                        <td class="px-5 py-3 text-center">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $predClass }}">
                                {{ $predLabel }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-slate-400 text-xs">
                            {{ \Carbon\Carbon::parse($n->tanggal)->format('d M Y') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                {{-- Footer rata-rata mapel --}}
                <tfoot class="{{ $cfg[2] }} border-t {{ $cfg[4] }}">
                    <tr>
                        <td colspan="4" class="px-5 py-2 text-xs font-bold {{ $cfg[3] }}">
                            Rata-rata {{ $namaMapel }}
                        </td>
                        <td class="px-5 py-2 text-center font-bold font-mono {{ $cfg[3] }} text-base">
                            {{ $rataMapel }}
                        </td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>

            {{-- Pemisah antar mapel (kecuali terakhir) --}}
            @if(!$loop->last)
            <div class="h-px bg-slate-200 mx-0"></div>
            @endif

            @endforeach

        </div>

    </div>

    @empty

    <div class="bg-white border rounded-2xl p-16 text-center">
        <div class="flex flex-col items-center gap-3 text-slate-400">
            <svg class="w-14 h-14 opacity-30" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="text-base font-semibold">Belum ada data nilai akademik</p>
            <p class="text-sm">Nilai akan muncul setelah guru menginput penilaian</p>
        </div>
    </div>

    @endforelse

    {{-- ── KETERANGAN PREDIKAT ─────────────────────────────────────────────── --}}
    <div class="mt-6 bg-slate-50 border rounded-2xl p-5">
        <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-3">Keterangan Predikat</p>
        <div class="flex flex-wrap gap-3 text-xs">
            <span class="px-3 py-1.5 rounded-full bg-emerald-100 text-emerald-700 font-semibold">A (85–100) Sangat Baik</span>
            <span class="px-3 py-1.5 rounded-full bg-blue-100 text-blue-700 font-semibold">B (75–84) Baik</span>
            <span class="px-3 py-1.5 rounded-full bg-amber-100 text-amber-700 font-semibold">C (65–74) Cukup</span>
            <span class="px-3 py-1.5 rounded-full bg-rose-100 text-rose-700 font-semibold">D (&lt;65) Kurang</span>
        </div>
    </div>

</x-app-layout>

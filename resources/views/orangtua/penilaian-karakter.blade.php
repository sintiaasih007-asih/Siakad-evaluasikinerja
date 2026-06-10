<x-app-layout>

    <x-page-header
        title="Penilaian Karakter"
        subtitle="Rekap sikap dan kedisiplinan siswa per mata pelajaran"
    />

    {{-- ── TIDAK ADA DATA SISWA ───────────────────────────────────────────── --}}
    @if(!$siswa)
    <div class="flex flex-col items-center justify-center py-20 text-center bg-white rounded-2xl border shadow-sm">
        <div class="w-16 h-16 bg-amber-100 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75M12 15.75h.007v.008H12v-.008z"/>
            </svg>
        </div>
        <p class="font-bold text-slate-700">Data siswa tidak ditemukan</p>
        <p class="text-sm text-slate-400 mt-1">Hubungi admin sekolah untuk menghubungkan akun Anda.</p>
    </div>

    @else

    {{-- ── PROFIL SISWA ────────────────────────────────────────────────────── --}}
    <div class="bg-gradient-to-r from-purple-700 to-indigo-600 rounded-2xl p-5 mb-6 flex items-center gap-5 shadow-lg">
        <div class="w-14 h-14 rounded-2xl bg-white/20 text-white font-bold text-2xl flex items-center justify-center shrink-0">
            {{ strtoupper(substr($siswa->nama, 0, 1)) }}
        </div>
        <div class="flex-1">
            <h2 class="text-white text-xl font-bold">{{ $siswa->nama }}</h2>
            <p class="text-purple-100 text-sm">NIS: {{ $siswa->nis }} · Kelas {{ $siswa->kelas->nama_kelas ?? '-' }}</p>
            <p class="text-purple-100 text-xs mt-0.5">Tahun Ajaran: <strong class="text-white">{{ $tahunAktif->tahun ?? '-' }}</strong></p>
        </div>
        <div class="hidden md:block text-right text-purple-100 text-xs">
            <p>Orang Tua / Wali</p>
            <p class="text-white font-semibold">{{ $siswa->nama_ortu ?? '-' }}</p>
        </div>
    </div>

    {{-- ── RINGKASAN ────────────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl border shadow-sm p-5">
            <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Rata-rata Sikap</p>
            <p class="text-3xl font-bold mt-1 {{ $ringkasan['rata_sikap'] >= 75 ? 'text-blue-600' : 'text-amber-600' }}">
                {{ $ringkasan['rata_sikap'] }}
            </p>
            <p class="text-xs text-slate-400 mt-1">dari {{ $ringkasan['total_sikap'] }} penilaian · {{ $ringkasan['mapel_sikap'] }} mapel</p>
        </div>
        <div class="bg-white rounded-2xl border shadow-sm p-5">
            <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Rata-rata Disiplin</p>
            <p class="text-3xl font-bold mt-1 {{ $ringkasan['rata_disiplin'] >= 75 ? 'text-teal-600' : 'text-amber-600' }}">
                {{ $ringkasan['rata_disiplin'] }}
            </p>
            <p class="text-xs text-slate-400 mt-1">dari {{ $ringkasan['total_disiplin'] }} penilaian · {{ $ringkasan['mapel_disiplin'] }} mapel</p>
        </div>
        <div class="bg-purple-50 rounded-2xl border border-purple-100 shadow-sm p-5">
            @php
                $rataKarakter = $ringkasan['rata_sikap'] + $ringkasan['rata_disiplin'] > 0
                    ? round(($ringkasan['rata_sikap'] + $ringkasan['rata_disiplin']) / 2, 1)
                    : 0;
                [$predLabel, $predClass] = match(true) {
                    $rataKarakter >= 85 => ['Sangat Baik', 'text-emerald-700'],
                    $rataKarakter >= 75 => ['Baik',        'text-blue-700'],
                    $rataKarakter >= 65 => ['Cukup',       'text-amber-700'],
                    $rataKarakter > 0   => ['Perlu Perhatian', 'text-rose-700'],
                    default             => ['—',            'text-slate-400'],
                };
            @endphp
            <p class="text-xs text-purple-600 font-semibold uppercase tracking-wide">Rata-rata Karakter</p>
            <p class="text-3xl font-bold text-purple-700 mt-1">{{ $rataKarakter ?: '—' }}</p>
            <p class="text-xs {{ $predClass }} font-semibold mt-1">{{ $predLabel }}</p>
        </div>
    </div>

    {{-- ── FILTER BULAN ─────────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-3 bg-slate-50 border-b flex items-center gap-2">
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
            </svg>
            <span class="text-sm font-bold text-slate-600">Filter Bulan</span>
        </div>
        <form method="GET" class="px-6 py-4 flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Bulan</label>
                <select name="bulan" class="rounded-xl border-slate-300 text-sm focus:ring-2 focus:ring-purple-500">
                    <option value="">Semua Bulan</option>
                    @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $b)
                        <option value="{{ $b }}" {{ $filterBulan == $b ? 'selected' : '' }}>{{ $b }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110 3a7.5 7.5 0 016.65 13.65z"/>
                    </svg>
                    Tampilkan
                </button>
                <a href="{{ route('orangtua.karakter') }}"
                    class="px-4 py-2.5 rounded-xl border border-slate-300 hover:bg-slate-50 text-slate-500 text-sm transition">
                    Reset
                </a>
            </div>
            @if($filterBulan)
            <span class="text-xs text-purple-600 font-semibold bg-purple-50 px-3 py-2 rounded-xl border border-purple-200">
                Menampilkan data bulan: <strong>{{ $filterBulan }}</strong>
            </span>
            @endif
        </form>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    {{-- SIKAP                                                                  --}}
    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    <div class="mb-8">

        {{-- Header seksi sikap --}}
        <div class="bg-gradient-to-r from-blue-700 to-blue-500 rounded-2xl px-6 py-4 mb-0 flex items-center justify-between shadow">
            <div>
                <p class="text-white/70 text-xs font-semibold uppercase tracking-widest">Penilaian Karakter</p>
                <h2 class="text-white text-xl font-bold mt-0.5">SIKAP</h2>
            </div>
            <div class="text-right text-white/80 text-xs">
                <p>{{ $ringkasan['total_sikap'] }} penilaian · {{ $ringkasan['mapel_sikap'] }} mapel</p>
                <p class="text-white font-bold text-lg mt-0.5">Rata-rata: {{ $ringkasan['rata_sikap'] }}</p>
            </div>
        </div>

        <div class="border border-t-0 rounded-b-2xl overflow-hidden shadow-sm bg-white">

            @forelse($sikapGrouped as $namaMapel => $items)
            @php $rataMapel = round(collect($items)->avg('nilai_sikap') ?? 0, 1); @endphp

            {{-- Sub-header per mapel --}}
            <div class="bg-blue-50 px-5 py-2.5 border-b border-blue-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                    <span class="text-sm font-bold text-blue-700">{{ $namaMapel }}</span>
                </div>
                <div class="flex items-center gap-4 text-xs text-blue-600">
                    <span>{{ count($items) }} penilaian</span>
                    <span class="font-bold">Rata-rata: {{ $rataMapel }}</span>
                </div>
            </div>

            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-2.5 text-left text-xs font-bold text-slate-400 uppercase w-10">#</th>
                        <th class="px-5 py-2.5 text-left text-xs font-bold text-slate-400 uppercase">Guru Penilai</th>
                        <th class="px-5 py-2.5 text-left text-xs font-bold text-slate-400 uppercase">Bulan</th>
                        <th class="px-5 py-2.5 text-center text-xs font-bold text-slate-400 uppercase">Nilai Sikap</th>
                        <th class="px-5 py-2.5 text-center text-xs font-bold text-slate-400 uppercase">Predikat</th>
                        <th class="px-5 py-2.5 text-left text-xs font-bold text-slate-400 uppercase">Keterangan</th>
                        <th class="px-5 py-2.5 text-left text-xs font-bold text-slate-400 uppercase">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($items as $idx => $s)
                    @php
                        [$predLabel, $predClass] = match(true) {
                            $s->nilai_sikap >= 85 => ['Baik Sekali', 'bg-emerald-100 text-emerald-700'],
                            $s->nilai_sikap >= 75 => ['Baik',        'bg-blue-100 text-blue-700'],
                            $s->nilai_sikap >= 65 => ['Cukup',       'bg-amber-100 text-amber-700'],
                            default               => ['Kurang',      'bg-rose-100 text-rose-700'],
                        };
                        $nilaiColor = match(true) {
                            $s->nilai_sikap >= 85 => 'text-emerald-700',
                            $s->nilai_sikap >= 75 => 'text-blue-700',
                            $s->nilai_sikap >= 65 => 'text-amber-700',
                            default               => 'text-rose-700',
                        };
                    @endphp
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-5 py-3 text-slate-400 text-xs">{{ $idx + 1 }}</td>
                        <td class="px-5 py-3 text-slate-600 text-xs">{{ $s->nama_guru }}</td>
                        <td class="px-5 py-3">
                            <span class="px-2 py-0.5 rounded-lg bg-slate-100 text-slate-600 text-xs">{{ $s->bulan }}</span>
                        </td>
                        <td class="px-5 py-3 text-center">
                            <span class="text-xl font-bold font-mono {{ $nilaiColor }}">{{ $s->nilai_sikap }}</span>
                        </td>
                        <td class="px-5 py-3 text-center">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $predClass }}">{{ $predLabel }}</span>
                        </td>
                        <td class="px-5 py-3 text-slate-500 text-xs">{{ $s->keterangan ?: '—' }}</td>
                        <td class="px-5 py-3 text-slate-400 text-xs">
                            {{ \Carbon\Carbon::parse($s->tanggal)->format('d M Y') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-blue-50 border-t border-blue-100">
                    <tr>
                        <td colspan="3" class="px-5 py-2 text-xs font-bold text-blue-700">
                            Rata-rata Sikap — {{ $namaMapel }}
                        </td>
                        <td class="px-5 py-2 text-center font-bold font-mono text-blue-700 text-base">{{ $rataMapel }}</td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
            </table>

            @if(!$loop->last)<div class="h-px bg-slate-200"></div>@endif

            @empty
            <div class="px-6 py-10 text-center text-slate-400">
                <p class="text-sm">Belum ada data penilaian sikap{{ $filterBulan ? ' untuk bulan '.$filterBulan : '' }}.</p>
            </div>
            @endforelse

        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    {{-- KEDISIPLINAN                                                           --}}
    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    <div class="mb-8">

        {{-- Header seksi disiplin --}}
        <div class="bg-gradient-to-r from-teal-700 to-emerald-500 rounded-2xl px-6 py-4 mb-0 flex items-center justify-between shadow">
            <div>
                <p class="text-white/70 text-xs font-semibold uppercase tracking-widest">Penilaian Karakter</p>
                <h2 class="text-white text-xl font-bold mt-0.5">KEDISIPLINAN</h2>
            </div>
            <div class="text-right text-white/80 text-xs">
                <p>{{ $ringkasan['total_disiplin'] }} penilaian · {{ $ringkasan['mapel_disiplin'] }} mapel</p>
                <p class="text-white font-bold text-lg mt-0.5">Rata-rata: {{ $ringkasan['rata_disiplin'] }}</p>
            </div>
        </div>

        <div class="border border-t-0 rounded-b-2xl overflow-hidden shadow-sm bg-white">

            @forelse($disiplinGrouped as $namaMapel => $items)
            @php $rataMapel = round(collect($items)->avg('nilai_disiplin') ?? 0, 1); @endphp

            {{-- Sub-header per mapel --}}
            <div class="bg-teal-50 px-5 py-2.5 border-b border-teal-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-teal-400"></span>
                    <span class="text-sm font-bold text-teal-700">{{ $namaMapel }}</span>
                </div>
                <div class="flex items-center gap-4 text-xs text-teal-600">
                    <span>{{ count($items) }} penilaian</span>
                    <span class="font-bold">Rata-rata: {{ $rataMapel }}</span>
                </div>
            </div>

            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-2.5 text-left text-xs font-bold text-slate-400 uppercase w-10">#</th>
                        <th class="px-5 py-2.5 text-left text-xs font-bold text-slate-400 uppercase">Guru Penilai</th>
                        <th class="px-5 py-2.5 text-left text-xs font-bold text-slate-400 uppercase">Bulan</th>
                        <th class="px-5 py-2.5 text-center text-xs font-bold text-slate-400 uppercase">Nilai Disiplin</th>
                        <th class="px-5 py-2.5 text-center text-xs font-bold text-slate-400 uppercase">Predikat</th>
                        <th class="px-5 py-2.5 text-left text-xs font-bold text-slate-400 uppercase">Keterangan</th>
                        <th class="px-5 py-2.5 text-left text-xs font-bold text-slate-400 uppercase">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($items as $idx => $d)
                    @php
                        [$predLabel, $predClass] = match(true) {
                            $d->nilai_disiplin >= 85 => ['Disiplin Sekali', 'bg-emerald-100 text-emerald-700'],
                            $d->nilai_disiplin >= 75 => ['Disiplin',        'bg-teal-100 text-teal-700'],
                            $d->nilai_disiplin >= 65 => ['Cukup',           'bg-amber-100 text-amber-700'],
                            default                  => ['Kurang',          'bg-rose-100 text-rose-700'],
                        };
                        $nilaiColor = match(true) {
                            $d->nilai_disiplin >= 85 => 'text-emerald-700',
                            $d->nilai_disiplin >= 75 => 'text-teal-700',
                            $d->nilai_disiplin >= 65 => 'text-amber-700',
                            default                  => 'text-rose-700',
                        };
                    @endphp
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-5 py-3 text-slate-400 text-xs">{{ $idx + 1 }}</td>
                        <td class="px-5 py-3 text-slate-600 text-xs">{{ $d->nama_guru }}</td>
                        <td class="px-5 py-3">
                            <span class="px-2 py-0.5 rounded-lg bg-slate-100 text-slate-600 text-xs">{{ $d->bulan }}</span>
                        </td>
                        <td class="px-5 py-3 text-center">
                            <span class="text-xl font-bold font-mono {{ $nilaiColor }}">{{ $d->nilai_disiplin }}</span>
                        </td>
                        <td class="px-5 py-3 text-center">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $predClass }}">{{ $predLabel }}</span>
                        </td>
                        <td class="px-5 py-3 text-slate-500 text-xs">{{ $d->keterangan ?: '—' }}</td>
                        <td class="px-5 py-3 text-slate-400 text-xs">
                            {{ \Carbon\Carbon::parse($d->tanggal)->format('d M Y') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-teal-50 border-t border-teal-100">
                    <tr>
                        <td colspan="3" class="px-5 py-2 text-xs font-bold text-teal-700">
                            Rata-rata Disiplin — {{ $namaMapel }}
                        </td>
                        <td class="px-5 py-2 text-center font-bold font-mono text-teal-700 text-base">{{ $rataMapel }}</td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
            </table>

            @if(!$loop->last)<div class="h-px bg-slate-200"></div>@endif

            @empty
            <div class="px-6 py-10 text-center text-slate-400">
                <p class="text-sm">Belum ada data penilaian kedisiplinan{{ $filterBulan ? ' untuk bulan '.$filterBulan : '' }}.</p>
            </div>
            @endforelse

        </div>
    </div>

    {{-- ── KETERANGAN PREDIKAT ─────────────────────────────────────────────── --}}
    <div class="bg-slate-50 border rounded-2xl p-5">
        <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-3">Keterangan Predikat</p>
        <div class="flex flex-wrap gap-3 text-xs">
            <span class="px-3 py-1.5 rounded-full bg-emerald-100 text-emerald-700 font-semibold">85–100 Sangat Baik / Disiplin Sekali</span>
            <span class="px-3 py-1.5 rounded-full bg-blue-100 text-blue-700 font-semibold">75–84 Baik / Disiplin</span>
            <span class="px-3 py-1.5 rounded-full bg-amber-100 text-amber-700 font-semibold">65–74 Cukup</span>
            <span class="px-3 py-1.5 rounded-full bg-rose-100 text-rose-700 font-semibold">&lt;65 Kurang</span>
        </div>
    </div>

    @endif {{-- end $siswa --}}

</x-app-layout>

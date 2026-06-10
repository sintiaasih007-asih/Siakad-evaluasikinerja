<x-app-layout>

    <x-page-header
        title="Monitoring Kelas Binaan"
        subtitle="Pantau perkembangan akademik siswa di kelas yang Anda wali"
    />

    {{-- ── HEADER KELAS ────────────────────────────────────────────────────── --}}
    <div class="bg-gradient-to-r from-teal-700 to-emerald-600 rounded-2xl p-6 mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4 shadow-lg">
        <div>
            <p class="text-teal-100 text-xs font-semibold uppercase tracking-widest mb-1">Wali Kelas · Monitoring Akademik</p>
            <h2 class="text-2xl font-bold text-white">
                @if($kelas)
                    Kelas {{ $kelas->nama_kelas }}
                @else
                    <span class="text-teal-300">Belum ada kelas binaan</span>
                @endif
            </h2>
            <p class="text-teal-100 text-sm mt-1">
                Tahun Ajaran: <strong class="text-white">{{ $tahunAktif->tahun ?? '-' }}</strong>
                · <strong class="text-white">{{ auth()->user()->name }}</strong>
            </p>
        </div>
        @if($kelas)
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('rekap.evaluasi.kelas') }}"
                class="bg-white/20 hover:bg-white/30 text-white text-xs font-bold px-4 py-2 rounded-xl transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Rekap Evaluasi
            </a>
        </div>
        @endif
    </div>

    {{-- ── TIDAK PUNYA KELAS BINAAN ─────────────────────────────────────────── --}}
    @if(!$kelas)
    <div class="flex flex-col items-center justify-center py-20 text-center bg-white rounded-2xl border shadow-sm">
        <div class="w-20 h-20 bg-amber-100 rounded-3xl flex items-center justify-center mb-5">
            <svg class="w-10 h-10 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
            </svg>
        </div>
        <h2 class="text-xl font-bold text-slate-700">Belum Ada Kelas Binaan</h2>
        <p class="text-slate-500 text-sm mt-2 max-w-sm">Akun Anda belum ditetapkan sebagai wali kelas. Hubungi admin.</p>
    </div>

    @else

    {{-- ── FILTER BULAN ────────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden mb-6">
        <div class="px-6 py-3 bg-slate-50 border-b flex items-center gap-2">
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
            </svg>
            <span class="text-sm font-bold text-slate-600">Filter Bulan</span>
        </div>
        <form method="GET" class="px-6 py-4 flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Bulan</label>
                <select name="bulan" onchange="this.form.submit()"
                    class="rounded-xl border-slate-300 text-sm focus:ring-2 focus:ring-teal-500 pr-8">
                    @foreach(['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $k=>$v)
                        <option value="{{ $k }}" {{ $bulan==$k ? 'selected':'' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            {{-- Input search --}}
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Cari Siswa</label>
                <div class="relative">
                    <input id="searchInput" type="text" placeholder="Ketik nama siswa..."
                        class="w-full rounded-xl border-slate-300 text-sm pl-9 focus:ring-2 focus:ring-teal-500">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110 3a7.5 7.5 0 016.65 13.65z"/>
                    </svg>
                </div>
            </div>
            <a href="{{ route('rekap.nilai.kelas') }}"
                class="px-4 py-2 rounded-xl border border-slate-300 hover:bg-slate-50 text-slate-500 text-sm transition">Reset</a>
        </form>
    </div>

    {{-- ── KARTU RINGKASAN ──────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl border shadow-sm p-5">
            <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Total Siswa</p>
            <p class="text-3xl font-bold text-slate-800 mt-1">{{ $ringkasan['total_siswa'] }}</p>
            <p class="text-xs text-slate-400 mt-1">di kelas {{ $kelas->nama_kelas }}</p>
        </div>
        <div class="bg-white rounded-2xl border shadow-sm p-5">
            <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Rata-rata Nilai</p>
            <p class="text-3xl font-bold mt-1 {{ $ringkasan['avg_nilai'] >= 75 ? 'text-teal-600' : 'text-amber-600' }}">
                {{ $ringkasan['avg_nilai'] }}
            </p>
            <p class="text-xs text-slate-400 mt-1">bulan {{ $namaBulan }}</p>
        </div>
        <div class="bg-white rounded-2xl border shadow-sm p-5">
            <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Rata-rata Hadir</p>
            <p class="text-3xl font-bold mt-1 {{ $ringkasan['avg_hadir'] >= 75 ? 'text-emerald-600' : 'text-rose-600' }}">
                {{ $ringkasan['avg_hadir'] }}%
            </p>
            <p class="text-xs text-slate-400 mt-1">bulan {{ $namaBulan }}</p>
        </div>
        <div class="bg-rose-50 rounded-2xl border border-rose-100 shadow-sm p-5">
            <p class="text-xs text-rose-600 font-semibold uppercase tracking-wide">Perlu Tindakan</p>
            <p class="text-3xl font-bold text-rose-700 mt-1">{{ $ringkasan['perlu_pantau'] }}</p>
            <p class="text-xs text-rose-400 mt-1">siswa butuh perhatian</p>
        </div>
    </div>

    {{-- ── ALERT SISWA BERMASALAH ───────────────────────────────────────────── --}}
    @php $butuhTindakan = $data->where('status', 'Perlu Tindakan'); @endphp
    @if($butuhTindakan->count())
    <div class="bg-rose-50 border border-rose-200 rounded-2xl p-4 mb-6 flex items-start gap-3">
        <div class="w-8 h-8 bg-rose-100 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
            <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9.303 3.376c-.866 1.5.217 3.374-1.948 3.374H2.645c-1.73 0-2.813-1.874-1.948-3.374L10.05 3.378c.866-1.5 3.032-1.5 3.898 0l7.304 12.748z"/>
            </svg>
        </div>
        <div class="flex-1">
            <p class="text-sm font-bold text-rose-700">{{ $butuhTindakan->count() }} siswa memerlukan tindakan segera</p>
            <p class="text-xs text-rose-500 mt-0.5">
                {{ $butuhTindakan->pluck('nama')->join(', ') }}
            </p>
        </div>
    </div>
    @endif

    {{-- ── TABEL MONITORING ─────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden" x-data="{ modalOpen: false, modalSiswa: null }">

        {{-- Header tabel --}}
        <div class="px-6 py-4 bg-slate-800 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-white text-sm">Monitoring Siswa — {{ $namaBulan }} {{ $tahunAktif->tahun }}</h3>
                <p class="text-slate-300 text-xs mt-0.5">Kelas {{ $kelas->nama_kelas }} · {{ $data->count() }} siswa</p>
            </div>
            <span class="bg-teal-500/20 text-teal-300 text-xs px-2.5 py-1 rounded-full font-semibold">
                {{ count($mapelList) }} mata pelajaran
            </span>
        </div>

        {{-- Status distribusi --}}
        @if($data->count())
        @php $dist = $data->groupBy('status')->map->count(); @endphp
        <div class="grid grid-cols-2 md:grid-cols-4 border-b">
            @foreach(['Sangat Baik'=>['bg-emerald-50','text-emerald-700'],'Baik'=>['bg-blue-50','text-blue-700'],'Pantauan'=>['bg-amber-50','text-amber-700'],'Perlu Tindakan'=>['bg-rose-50','text-rose-700']] as $st=>$cls)
            <div class="px-4 py-3 border-r last:border-0 {{ $cls[0] }} flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold {{ $cls[1] }} uppercase tracking-wide">{{ $st }}</p>
                    <p class="text-xl font-bold {{ $cls[1] }} mt-0.5">{{ $dist[$st] ?? 0 }}</p>
                </div>
                <span class="text-base opacity-40">
                    @if($st==='Sangat Baik') 🌟
                    @elseif($st==='Baik') ✅
                    @elseif($st==='Pantauan') 👀
                    @else 🚨
                    @endif
                </span>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Tabel --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-100 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase w-10">#</th>
                        <th class="px-4 py-3 text-left   text-xs font-bold text-slate-500 uppercase">Nama Siswa</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">
                            Nilai<br><span class="font-normal normal-case text-slate-400 text-[10px]">rata mapel</span>
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">
                            Hadir<br><span class="font-normal normal-case text-slate-400 text-[10px]">bulan ini</span>
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">
                            Alpha<br><span class="font-normal normal-case text-slate-400 text-[10px]">kumulatif</span>
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">Sikap</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">Disiplin</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">
                            Nilai TA<br><span class="font-normal normal-case text-slate-400 text-[10px]">kumulatif</span>
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase w-10">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($data as $i => $item)
                    @php
                        $sc = match($item['status_color']) {
                            'emerald' => ['row'=>'', 'badge'=>'bg-emerald-100 text-emerald-700 border border-emerald-200'],
                            'blue'    => ['row'=>'', 'badge'=>'bg-blue-100 text-blue-700 border border-blue-200'],
                            'amber'   => ['row'=>'bg-amber-50/40', 'badge'=>'bg-amber-100 text-amber-700 border border-amber-200'],
                            'rose'    => ['row'=>'bg-rose-50/40',  'badge'=>'bg-rose-100 text-rose-700 border border-rose-200'],
                            default   => ['row'=>'', 'badge'=>'bg-slate-100 text-slate-600'],
                        };
                        $hadirColor = match(true) {
                            $item['persen_hadir'] >= 85 => 'text-emerald-600',
                            $item['persen_hadir'] >= 70 => 'text-amber-600',
                            default                     => 'text-rose-600',
                        };
                        $nilaiColor = match(true) {
                            $item['nilai_bulan'] >= 85 => 'text-emerald-700',
                            $item['nilai_bulan'] >= 75 => 'text-blue-700',
                            $item['nilai_bulan'] >= 60 => 'text-amber-700',
                            $item['nilai_bulan'] > 0   => 'text-rose-700',
                            default                    => 'text-slate-400',
                        };
                    @endphp
                    <tr class="hover:bg-slate-50/80 transition siswa-row {{ $sc['row'] }}"
                        @click="modalSiswa = {{ json_encode($item) }}; modalOpen = true"
                        style="cursor:pointer">
                        <td class="px-4 py-3 text-center text-slate-400 text-xs">{{ $i+1 }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-full bg-teal-100 text-teal-700 text-xs font-bold flex items-center justify-center shrink-0">
                                    {{ strtoupper(substr($item['nama'], 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800 nama-siswa">{{ $item['nama'] }}</p>
                                    <p class="text-[10px] text-slate-400">{{ $item['nis'] }}</p>
                                </div>
                            </div>
                        </td>
                        {{-- Nilai bulan --}}
                        <td class="px-4 py-3 text-center">
                            @if($item['nilai_bulan'] > 0)
                                <span class="font-bold font-mono text-base {{ $nilaiColor }}">{{ $item['nilai_bulan'] }}</span>
                                {{-- mini progress --}}
                                <div class="w-full bg-slate-100 rounded-full h-1 mt-1">
                                    <div class="h-1 rounded-full {{ $item['nilai_bulan'] >= 75 ? 'bg-teal-400' : 'bg-rose-400' }}"
                                         style="width: {{ min(100, $item['nilai_bulan']) }}%"></div>
                                </div>
                            @else
                                <span class="text-slate-300 text-xs">—</span>
                            @endif
                        </td>
                        {{-- Hadir bulan --}}
                        <td class="px-4 py-3 text-center">
                            @if($item['total_bulan'] > 0)
                                <span class="font-bold font-mono {{ $hadirColor }}">{{ $item['persen_hadir'] }}%</span>
                                <p class="text-[10px] text-slate-400 mt-0.5">
                                    {{ $item['hadir_bulan'] }}/{{ $item['total_bulan'] }} pertemuan
                                </p>
                            @else
                                <span class="text-slate-300 text-xs">—</span>
                            @endif
                        </td>
                        {{-- Alpha kumulatif --}}
                        <td class="px-4 py-3 text-center">
                            @if($item['alpha_kumulatif'] > 0)
                                <span class="font-bold font-mono {{ $item['alpha_kumulatif'] >= 3 ? 'text-rose-600' : 'text-amber-600' }}">
                                    {{ $item['alpha_kumulatif'] }}x
                                </span>
                            @else
                                <span class="text-emerald-500 font-bold text-xs">0x</span>
                            @endif
                        </td>
                        {{-- Sikap --}}
                        <td class="px-4 py-3 text-center font-mono text-slate-700">
                            {{ $item['sikap'] > 0 ? $item['sikap'] : '—' }}
                        </td>
                        {{-- Disiplin --}}
                        <td class="px-4 py-3 text-center font-mono text-slate-700">
                            {{ $item['disiplin'] > 0 ? $item['disiplin'] : '—' }}
                        </td>
                        {{-- Nilai kumulatif TA --}}
                        <td class="px-4 py-3 text-center">
                            @if($item['nilai_kumulatif'] > 0)
                                <span class="font-semibold font-mono {{ $item['nilai_kumulatif'] >= 75 ? 'text-teal-700' : 'text-rose-600' }}">
                                    {{ $item['nilai_kumulatif'] }}
                                </span>
                            @else
                                <span class="text-slate-300 text-xs">—</span>
                            @endif
                        </td>
                        {{-- Status --}}
                        <td class="px-4 py-3 text-center">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $sc['badge'] }}">
                                {{ $item['status'] }}
                            </span>
                        </td>
                        {{-- Detail --}}
                        <td class="px-4 py-3 text-center text-slate-300 hover:text-teal-500">
                            <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center gap-3 text-slate-400">
                                <svg class="w-12 h-12 opacity-30" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-sm font-medium">Belum ada siswa di kelas ini</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer --}}
        @if($data->count())
        <div class="px-6 py-3 bg-slate-50 border-t text-xs text-slate-400 flex flex-wrap gap-4">
            <span>Nilai tertinggi: <strong class="text-teal-700">{{ $ringkasan['nilai_tertinggi'] }}</strong></span>
            <span>Nilai terendah: <strong class="text-rose-600">{{ $ringkasan['nilai_terendah'] }}</strong></span>
            <span>Rata-rata TA kumulatif: <strong class="text-slate-600">{{ $ringkasan['avg_kumulatif'] }}</strong></span>
            <span class="ml-auto">Klik baris siswa untuk detail lengkap</span>
        </div>
        @endif

        {{-- ── MODAL DETAIL SISWA ──────────────────────────────────────────── --}}
        <div x-show="modalOpen"
             x-transition.opacity
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4"
             @click.self="modalOpen = false">

            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto"
                 x-show="modalOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">

                <template x-if="modalSiswa">
                    <div>
                        {{-- Modal header --}}
                        <div class="px-6 py-5 bg-gradient-to-r from-teal-700 to-emerald-600 rounded-t-2xl flex items-center justify-between">
                            <div>
                                <p class="text-teal-100 text-xs font-semibold uppercase tracking-widest">Detail Siswa</p>
                                <h3 class="text-lg font-bold text-white mt-0.5" x-text="modalSiswa.nama"></h3>
                                <p class="text-teal-100 text-xs mt-0.5">NIS: <span x-text="modalSiswa.nis"></span></p>
                            </div>
                            <button @click="modalOpen = false"
                                class="w-8 h-8 rounded-xl bg-white/20 hover:bg-white/30 flex items-center justify-center text-white">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Status badge --}}
                        <div class="px-6 py-3 border-b flex items-center justify-between">
                            <span class="text-sm text-slate-500">Status bulan {{ $namaBulan }}</span>
                            <span class="px-3 py-1.5 rounded-full text-sm font-bold border"
                                :class="{
                                    'bg-emerald-100 text-emerald-700 border-emerald-200': modalSiswa.status === 'Sangat Baik',
                                    'bg-blue-100 text-blue-700 border-blue-200':          modalSiswa.status === 'Baik',
                                    'bg-amber-100 text-amber-700 border-amber-200':       modalSiswa.status === 'Pantauan',
                                    'bg-rose-100 text-rose-700 border-rose-200':          modalSiswa.status === 'Perlu Tindakan'
                                }"
                                x-text="modalSiswa.status">
                            </span>
                        </div>

                        {{-- Ringkasan kartu --}}
                        <div class="p-5 grid grid-cols-2 md:grid-cols-4 gap-3 border-b bg-slate-50">
                            <div class="bg-white rounded-xl p-3 border text-center">
                                <p class="text-[10px] text-slate-400 font-semibold uppercase">Nilai Bulan</p>
                                <p class="text-2xl font-bold text-indigo-700 mt-1 font-mono" x-text="modalSiswa.nilai_bulan || '—'"></p>
                            </div>
                            <div class="bg-white rounded-xl p-3 border text-center">
                                <p class="text-[10px] text-slate-400 font-semibold uppercase">Hadir</p>
                                <p class="text-2xl font-bold text-teal-600 mt-1 font-mono"><span x-text="modalSiswa.persen_hadir"></span>%</p>
                                <p class="text-[10px] text-slate-400 mt-0.5"><span x-text="modalSiswa.hadir_bulan"></span>/<span x-text="modalSiswa.total_bulan"></span> pertemuan</p>
                            </div>
                            <div class="bg-white rounded-xl p-3 border text-center">
                                <p class="text-[10px] text-slate-400 font-semibold uppercase">Nilai TA</p>
                                <p class="text-2xl font-bold text-slate-700 mt-1 font-mono" x-text="modalSiswa.nilai_kumulatif || '—'"></p>
                            </div>
                            <div class="bg-white rounded-xl p-3 border text-center">
                                <p class="text-[10px] text-slate-400 font-semibold uppercase">Alpha TA</p>
                                <p class="text-2xl font-bold mt-1 font-mono"
                                    :class="modalSiswa.alpha_kumulatif >= 3 ? 'text-rose-600' : 'text-emerald-600'"
                                    x-text="modalSiswa.alpha_kumulatif + 'x'">
                                </p>
                            </div>
                        </div>

                        {{-- Absensi detail bulan ini --}}
                        <div class="px-6 py-4 border-b">
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-3">Kehadiran Bulan {{ $namaBulan }}</p>
                            <div class="grid grid-cols-4 gap-3">
                                <div class="bg-emerald-50 rounded-xl p-3 text-center border border-emerald-100">
                                    <p class="text-[10px] text-emerald-600 font-semibold uppercase">Hadir</p>
                                    <p class="text-2xl font-bold text-emerald-700 mt-1" x-text="modalSiswa.hadir_bulan"></p>
                                </div>
                                <div class="bg-blue-50 rounded-xl p-3 text-center border border-blue-100">
                                    <p class="text-[10px] text-blue-600 font-semibold uppercase">Izin</p>
                                    <p class="text-2xl font-bold text-blue-700 mt-1" x-text="modalSiswa.izin_bulan"></p>
                                </div>
                                <div class="bg-amber-50 rounded-xl p-3 text-center border border-amber-100">
                                    <p class="text-[10px] text-amber-600 font-semibold uppercase">Sakit</p>
                                    <p class="text-2xl font-bold text-amber-700 mt-1" x-text="modalSiswa.sakit_bulan"></p>
                                </div>
                                <div class="bg-rose-50 rounded-xl p-3 text-center border border-rose-100">
                                    <p class="text-[10px] text-rose-600 font-semibold uppercase">Alpha</p>
                                    <p class="text-2xl font-bold text-rose-700 mt-1" x-text="modalSiswa.alpha_bulan"></p>
                                </div>
                            </div>
                        </div>

                        {{-- Nilai per mapel --}}
                        <div class="px-6 py-4 border-b">
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-3">Nilai per Mata Pelajaran — {{ $namaBulan }}</p>
                            <div class="space-y-2">
                                @foreach($mapelList as $mapel)
                                <div class="flex items-center gap-3" x-show="modalSiswa">
                                    <span class="text-xs text-slate-600 w-36 shrink-0">{{ $mapel->nama_mapel }}</span>
                                    <div class="flex-1 bg-slate-100 rounded-full h-2">
                                        <div class="h-2 rounded-full transition-all duration-500"
                                             :class="(modalSiswa.nilai_per_mapel?.['{{ $mapel->nama_mapel }}'] ?? 0) >= 75 ? 'bg-teal-400' : 'bg-rose-400'"
                                             :style="'width:' + Math.min(100, modalSiswa.nilai_per_mapel?.['{{ $mapel->nama_mapel }}'] ?? 0) + '%'">
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold font-mono w-10 text-right"
                                          :class="(modalSiswa.nilai_per_mapel?.['{{ $mapel->nama_mapel }}'] ?? 0) >= 75 ? 'text-teal-700' : ((modalSiswa.nilai_per_mapel?.['{{ $mapel->nama_mapel }}'] ?? null) === null ? 'text-slate-300' : 'text-rose-600')"
                                          x-text="modalSiswa.nilai_per_mapel?.['{{ $mapel->nama_mapel }}'] ?? '—'">
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Sikap & Disiplin --}}
                        <div class="px-6 py-4">
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-3">Karakter — {{ $namaBulan }}</p>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-slate-50 rounded-xl p-4 border">
                                    <p class="text-xs text-slate-500 font-semibold">Sikap</p>
                                    <p class="text-2xl font-bold text-slate-700 mt-1 font-mono"
                                       x-text="modalSiswa.sikap > 0 ? modalSiswa.sikap : '—'"></p>
                                </div>
                                <div class="bg-slate-50 rounded-xl p-4 border">
                                    <p class="text-xs text-slate-500 font-semibold">Disiplin</p>
                                    <p class="text-2xl font-bold text-slate-700 mt-1 font-mono"
                                       x-text="modalSiswa.disiplin > 0 ? modalSiswa.disiplin : '—'"></p>
                                </div>
                            </div>
                        </div>

                    </div>
                </template>
            </div>
        </div>

    </div>{{-- end card --}}

    @endif {{-- end $kelas --}}

    {{-- Search realtime --}}
    <script>
    document.getElementById('searchInput')?.addEventListener('input', function() {
        const val = this.value.toLowerCase();
        document.querySelectorAll('.siswa-row').forEach(row => {
            const nama = row.querySelector('.nama-siswa')?.textContent?.toLowerCase() ?? '';
            row.style.display = nama.includes(val) ? '' : 'none';
        });
    });
    </script>

</x-app-layout>

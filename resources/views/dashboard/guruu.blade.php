<x-app-layout>

    {{-- ── WELCOME BANNER ─────────────────────────────────────────────── --}}
    <div class="relative overflow-hidden rounded-2xl p-6 mb-6 shadow-sm"
         style="background:linear-gradient(135deg,#1e3a5f 0%,#1e40af 60%,#1d4ed8 100%)">
        <div class="absolute inset-0 opacity-[0.04]">
            <svg width="100%" height="100%"><pattern id="dots" width="24" height="24" patternUnits="userSpaceOnUse"><circle cx="1" cy="1" r="1" fill="white"/></pattern><rect width="100%" height="100%" fill="url(#dots)"/></svg>
        </div>
        <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-blue-200 text-xs font-semibold uppercase tracking-widest mb-1">
                    {{ now()->translatedFormat('l, d F Y') }}
                </p>
                <h1 class="text-xl lg:text-2xl font-bold text-white">Selamat Datang, {{ Auth::user()->name }}</h1>
                <p class="text-blue-200 text-sm mt-1">Kelola penilaian siswa dan pantau aktivitas mengajar.</p>
                <div class="flex flex-wrap gap-2 mt-4">
                    <a href="{{ route('nilai.index') }}"
                        class="bg-white text-blue-800 text-xs font-bold px-4 py-2 rounded-lg shadow-sm hover:bg-blue-50 transition">Input Nilai</a>
                    <a href="{{ route('absensi.index') }}"
                        class="bg-white/15 border border-white/25 text-white text-xs font-bold px-4 py-2 rounded-lg hover:bg-white/25 transition">Absensi Siswa</a>
                    <a href="{{ route('absensi-guru.index') }}"
                        class="bg-white/15 border border-white/25 text-white text-xs font-bold px-4 py-2 rounded-lg hover:bg-white/25 transition">Absensi Saya</a>
                </div>
            </div>
            <div class="hidden lg:flex gap-3 shrink-0">
                <div class="bg-white/10 border border-white/15 rounded-xl px-4 py-3 text-right">
                    <p class="text-blue-300 text-[10px] font-semibold uppercase tracking-wide">Jadwal Hari Ini</p>
                    <p class="text-3xl font-bold text-white mt-0.5">{{ $jadwals->count() }}</p>
                </div>
                <div class="bg-white/10 border border-white/15 rounded-xl px-4 py-3 text-right">
                    <p class="text-blue-300 text-[10px] font-semibold uppercase tracking-wide">Siswa Diampu</p>
                    <p class="text-3xl font-bold text-white mt-0.5">{{ $totalSiswa }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ── STAT CARDS ──────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        @foreach([
            ['Jadwal Hari Ini', $jadwals->count(), $hariIni,           'calendar-days', 'bg-blue-100',  'text-blue-700'],
            ['Siswa Diampu',    $totalSiswa,       'Semua kelas',      'users',         'bg-sky-100',   'text-sky-700'],
            ['Absensi Dibuat',  $totalAbsensi,     'Total pertemuan',  'check-circle',  'bg-teal-100',  'text-teal-700'],
            ['Nilai Diinput',   $totalNilai,       'Oleh Anda',        'pen-line',      'bg-amber-100', 'text-amber-700'],
        ] as [$label, $val, $sub, $icon, $bg, $txt])
        <div class="card p-5 hover:shadow-card-hover transition-shadow duration-200">
            <div class="flex items-start justify-between gap-2">
                <div class="min-w-0">
                    <p class="stat-card-label truncate">{{ $label }}</p>
                    <p class="stat-card-value {{ $txt }}">{{ $val }}</p>
                    <p class="text-[11px] text-slate-400 mt-1 truncate">{{ $sub }}</p>
                </div>
                <div class="stat-card-icon {{ $bg }} shrink-0">
                    <i data-lucide="{{ $icon }}" class="w-5 h-5 {{ $txt }}"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ── JADWAL + SIDEBAR ────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

        {{-- Jadwal hari ini --}}
        <div class="xl:col-span-2 card overflow-hidden">
            <div class="card-header">
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Jadwal Mengajar — {{ $hariIni }}</h3>
                    <p class="text-xs text-slate-500 mt-0.5">{{ now()->translatedFormat('d F Y') }}</p>
                </div>
                @if($jadwals->isNotEmpty())
                <span class="badge badge-info">{{ $jadwals->count() }} JP</span>
                @endif
            </div>
            <div class="p-5">
                @if($jadwals->isEmpty())
                <div class="flex flex-col items-center py-10 text-slate-400">
                    <i data-lucide="calendar-x" class="w-10 h-10 mb-2 opacity-40"></i>
                    <p class="text-sm font-medium">Tidak ada jadwal hari {{ $hariIni }}</p>
                    <p class="text-xs mt-1 text-slate-300">Nikmati hari Anda 😊</p>
                </div>
                @else
                <div class="space-y-2.5">
                    @foreach($jadwals as $j)
                    @php
                        $now = now();
                        $mulai   = \Carbon\Carbon::today()->setTimeFromTimeString($j->jam_masuk);
                        $selesai = \Carbon\Carbon::today()->setTimeFromTimeString($j->jam_selesai);
                        $isActive   = $now->between($mulai, $selesai);
                        $isFinished = $now->greaterThan($selesai);
                    @endphp
                    <div class="flex items-center gap-3.5 px-4 py-3.5 rounded-xl border transition
                        {{ $isActive ? 'border-blue-200 bg-blue-50' : ($isFinished ? 'border-slate-100 bg-slate-50/60 opacity-60' : 'border-slate-100 hover:bg-slate-50') }}">
                        <div class="w-12 h-12 rounded-xl flex flex-col items-center justify-center shrink-0 text-xs font-bold
                            {{ $isActive ? 'bg-blue-700 text-white' : 'bg-slate-100 text-slate-600' }}">
                            <span>{{ substr($j->jam_masuk,0,5) }}</span>
                            <span class="text-[9px] font-normal opacity-70">{{ substr($j->jam_selesai,0,5) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-slate-800 text-sm truncate">{{ $j->mapel->nama_mapel ?? '-' }}</p>
                            <p class="text-xs text-slate-500">Kelas {{ $j->kelas->nama_kelas ?? '-' }}</p>
                        </div>
                        @if($isActive)
                        <span class="badge badge-success shrink-0">● Aktif</span>
                        @elseif($isFinished)
                        <span class="badge badge-neutral shrink-0">Selesai</span>
                        @else
                        <a href="{{ route('absensi.create', $j->id) }}" class="btn-primary py-1.5 px-3 text-xs shrink-0">Absensi</a>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- Sidebar: Pengumuman + Agenda --}}
        <div class="space-y-4">
            <div class="card p-5">
                <h3 class="text-sm font-bold text-slate-800 mb-3">Pengumuman</h3>
                <div class="space-y-3">
                    @forelse($pengumuman as $item)
                    <div class="border-b border-slate-100 pb-3 last:border-0 last:pb-0">
                        <p class="text-sm font-semibold text-slate-700 line-clamp-1">{{ $item->judul }}</p>
                        <p class="text-xs text-slate-500 mt-0.5 line-clamp-2">{{ $item->isi }}</p>
                        <p class="text-[10px] text-slate-400 mt-1">{{ $item->created_at->diffForHumans() }}</p>
                    </div>
                    @empty
                    <p class="text-xs text-slate-400 text-center py-4">Belum ada pengumuman</p>
                    @endforelse
                </div>
            </div>
            <div class="card p-5">
                <h3 class="text-sm font-bold text-slate-800 mb-3">Agenda Sekolah</h3>
                <div class="space-y-2.5">
                    @forelse($agenda as $item)
                    <div class="flex items-start gap-3">
                        <div class="bg-blue-50 text-blue-700 rounded-lg p-2 text-center min-w-[40px] shrink-0">
                            <div class="text-sm font-bold leading-none">{{ \Carbon\Carbon::parse($item->tanggal)->format('d') }}</div>
                            <div class="text-[9px]">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('M') }}</div>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-semibold text-slate-700 line-clamp-1">{{ $item->judul }}</p>
                            <p class="text-[10px] text-slate-500 line-clamp-1">{{ $item->deskripsi }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-xs text-slate-400 text-center py-4">Tidak ada agenda</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

</x-app-layout>

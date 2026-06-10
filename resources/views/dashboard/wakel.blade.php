<x-app-layout>

    {{-- ── WELCOME BANNER ─────────────────────────────────────────────── --}}
    <div class="relative overflow-hidden bg-gradient-to-135 from-blue-900 via-blue-800 to-teal-700
                rounded-2xl p-6 mb-6 shadow-sm"
         style="background: linear-gradient(135deg,#1e3a5f 0%,#1e40af 50%,#0f766e 100%)">
        <div class="absolute inset-0 opacity-5">
            <svg width="100%" height="100%"><pattern id="g" width="32" height="32" patternUnits="userSpaceOnUse"><circle cx="1" cy="1" r="1" fill="white"/></pattern><rect width="100%" height="100%" fill="url(#g)"/></svg>
        </div>
        <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-blue-200 text-xs font-semibold uppercase tracking-widest mb-1">
                    {{ now()->translatedFormat('l, d F Y') }}
                </p>
                <h1 class="text-xl lg:text-2xl font-bold text-white leading-tight">
                    Selamat Datang, {{ Auth::user()->name }}
                </h1>
                @if($kelasWali)
                <span class="inline-flex items-center gap-1.5 mt-2 bg-white/15 border border-white/20
                             text-white text-xs font-semibold px-3 py-1.5 rounded-lg">
                    <i data-lucide="users" class="w-3.5 h-3.5"></i>
                    Wali Kelas {{ $kelasWali->nama_kelas }}
                </span>
                @endif
                <div class="flex flex-wrap gap-2 mt-4">
                    <a href="{{ route('nilai.index') }}"
                        class="bg-white text-blue-800 text-xs font-bold px-4 py-2 rounded-lg shadow-sm hover:bg-blue-50 transition">
                        Input Nilai
                    </a>
                    <a href="{{ route('absensi.index') }}"
                        class="bg-white/15 border border-white/25 text-white text-xs font-bold px-4 py-2 rounded-lg hover:bg-white/25 transition">
                        Absensi Siswa
                    </a>
                    @if($kelasWali)
                    <a href="{{ route('rekap.nilai.kelas') }}"
                        class="bg-white/15 border border-white/25 text-white text-xs font-bold px-4 py-2 rounded-lg hover:bg-white/25 transition">
                        Monitoring Kelas
                    </a>
                    @endif
                </div>
            </div>
            <div class="hidden lg:flex flex-col items-end gap-2 shrink-0">
                <div class="bg-white/10 border border-white/15 rounded-xl px-5 py-4 text-right">
                    <p class="text-blue-200 text-[10px] font-semibold uppercase tracking-wide">Jadwal Hari Ini</p>
                    <p class="text-3xl font-bold text-white mt-0.5">{{ $jadwals->count() }}</p>
                    <p class="text-blue-200 text-[10px] mt-0.5">{{ $hariIni }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ── STAT CARDS ──────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        @foreach([
            ['label'=>'Jadwal Hari Ini','val'=>$jadwals->count(),'sub'=>$hariIni,        'icon'=>'calendar-days','bg'=>'bg-blue-100','text'=>'text-blue-700'],
            ['label'=>'Siswa Diampu',   'val'=>$totalSiswa,     'sub'=>'semua kelas',    'icon'=>'users',        'bg'=>'bg-sky-100', 'text'=>'text-sky-700'],
            ['label'=>'Absensi Dibuat', 'val'=>$totalAbsensi,   'sub'=>'total pertemuan','icon'=>'check-circle', 'bg'=>'bg-teal-100','text'=>'text-teal-700'],
            ['label'=>'Nilai Diinput',  'val'=>$totalNilai,     'sub'=>'oleh Anda',      'icon'=>'pen-line',     'bg'=>'bg-amber-100','text'=>'text-amber-700'],
        ] as $s)
        <div class="card p-5 hover:shadow-card-hover transition-shadow duration-200">
            <div class="flex items-start justify-between">
                <div class="min-w-0">
                    <p class="stat-card-label">{{ $s['label'] }}</p>
                    <p class="stat-card-value {{ $s['text'] }}">{{ $s['val'] }}</p>
                    <p class="text-[11px] text-slate-400 mt-1 truncate">{{ $s['sub'] }}</p>
                </div>
                <div class="stat-card-icon {{ $s['bg'] }} shrink-0">
                    <i data-lucide="{{ $s['icon'] }}" class="w-5 h-5 {{ $s['text'] }}"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ── KONTEN UTAMA ─────────────────────────────────────────────────── --}}
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
                    <p class="text-sm">Tidak ada jadwal hari {{ $hariIni }}</p>
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
                        {{ $isActive ? 'border-teal-200 bg-teal-50' : ($isFinished ? 'border-slate-100 bg-slate-50/70 opacity-60' : 'border-slate-100 hover:bg-slate-50') }}">
                        <div class="w-12 h-12 rounded-xl flex flex-col items-center justify-center shrink-0 text-xs font-bold
                            {{ $isActive ? 'bg-teal-600 text-white' : 'bg-slate-100 text-slate-600' }}">
                            <span>{{ substr($j->jam_masuk,0,5) }}</span>
                            <span class="text-[9px] font-normal opacity-70">{{ substr($j->jam_selesai,0,5) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-slate-800 text-sm truncate">{{ $j->mapel->nama_mapel ?? '-' }}</p>
                            <p class="text-xs text-slate-500">
                                Kelas {{ $j->kelas->nama_kelas ?? '-' }}
                                @if($kelasWali && isset($j->kelas_id) && $j->kelas_id == $kelasWali->id)
                                <span class="ml-1 bg-teal-100 text-teal-700 px-1.5 py-0.5 rounded text-[10px] font-semibold">Binaan</span>
                                @endif
                            </p>
                        </div>
                        @if($isActive)
                        <span class="badge badge-success shrink-0">● Berlangsung</span>
                        @elseif($isFinished)
                        <span class="badge badge-neutral shrink-0">Selesai</span>
                        @else
                        <a href="{{ route('absensi.create', $j->id) }}"
                            class="btn-primary py-1.5 px-3 text-xs shrink-0">Absensi</a>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-4">

            {{-- Kelas binaan --}}
            @if($kelasWali)
            <div class="rounded-2xl overflow-hidden shadow-sm"
                 style="background:linear-gradient(135deg,#0f766e,#047857)">
                <div class="p-5 text-white">
                    <p class="text-teal-200 text-[10px] font-bold uppercase tracking-widest">Kelas Binaan</p>
                    <h3 class="text-2xl font-bold mt-1">{{ $kelasWali->nama_kelas }}</h3>
                    <p class="text-teal-100 text-sm mt-0.5">
                        {{ $kelasWali->siswas->count() ?? 0 }} siswa terdaftar
                    </p>
                    <div class="flex gap-2 mt-4">
                        <a href="/data-wali-kelas"
                            class="flex-1 text-center bg-white/20 hover:bg-white/30 text-white text-xs font-semibold py-2 rounded-lg transition">
                            Lihat Kelas
                        </a>
                        <a href="{{ route('rekap.nilai.kelas') }}"
                            class="flex-1 text-center bg-white/20 hover:bg-white/30 text-white text-xs font-semibold py-2 rounded-lg transition">
                            Monitoring
                        </a>
                    </div>
                </div>
            </div>
            @endif

            {{-- Pengumuman --}}
            <div class="card p-5">
                <h3 class="text-sm font-bold text-slate-800 mb-3">Pengumuman</h3>
                <div class="space-y-3">
                    @forelse($pengumuman as $item)
                    <div class="border-b border-slate-100 pb-3 last:border-0 last:pb-0">
                        <p class="text-sm font-semibold text-slate-700 leading-snug line-clamp-1">{{ $item->judul }}</p>
                        <p class="text-xs text-slate-500 mt-0.5 line-clamp-2">{{ $item->isi }}</p>
                        <p class="text-[10px] text-slate-400 mt-1">{{ $item->created_at->diffForHumans() }}</p>
                    </div>
                    @empty
                    <p class="text-xs text-slate-400 text-center py-4">Belum ada pengumuman</p>
                    @endforelse
                </div>
            </div>

            {{-- Agenda --}}
            <div class="card p-5">
                <h3 class="text-sm font-bold text-slate-800 mb-3">Agenda Sekolah</h3>
                <div class="space-y-2.5">
                    @forelse($agenda as $item)
                    <div class="flex items-start gap-3">
                        <div class="bg-blue-50 text-blue-700 rounded-lg p-2 text-center min-w-[40px] shrink-0">
                            <div class="text-sm font-bold leading-none">{{ \Carbon\Carbon::parse($item->tanggal)->format('d') }}</div>
                            <div class="text-[9px] mt-0.5">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('M') }}</div>
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

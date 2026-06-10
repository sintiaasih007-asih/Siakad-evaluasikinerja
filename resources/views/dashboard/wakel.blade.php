<x-app-layout>

    <div class="space-y-6">

        {{-- ── WELCOME BANNER ───────────────────────────────────────────── --}}
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-emerald-700 via-teal-600 to-indigo-700 p-7 shadow-xl">
            <div class="absolute right-0 top-0 opacity-10 pointer-events-none">
                <svg width="280" height="280" fill="none"><circle cx="140" cy="140" r="140" fill="white"/></svg>
            </div>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-5">
                <div>
                    <p class="text-emerald-100 text-sm font-medium">
                        {{ now()->translatedFormat('l, d F Y') }}
                    </p>
                    <h1 class="text-2xl font-bold text-white mt-0.5">
                        Selamat Datang, {{ Auth::user()->name }} 👋
                    </h1>
                    @if($kelasWali)
                    <div class="inline-flex items-center gap-2 bg-white/15 border border-white/20 text-white text-sm font-semibold px-3 py-1.5 rounded-lg mt-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5V4H2v16h5m10 0v-4a3 3 0 00-6 0v4m6 0H8"/>
                        </svg>
                        Wali Kelas {{ $kelasWali->nama_kelas }}
                    </div>
                    @endif
                    <p class="text-emerald-100 text-sm mt-2 max-w-lg">
                        Kelola kelas binaan, pantau absensi, dan input nilai siswa Anda.
                    </p>
                    <div class="flex flex-wrap gap-3 mt-5">
                        <a href="{{ route('nilai.index') }}"
                            class="bg-white text-emerald-700 text-sm font-bold px-5 py-2.5 rounded-xl shadow hover:scale-105 transition">
                            Input Nilai
                        </a>
                        <a href="{{ route('absensi.index') }}"
                            class="bg-white/15 border border-white/30 text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-white/20 transition">
                            Absensi Siswa
                        </a>
                        <a href="{{ route('absensi-guru.index') }}"
                            class="bg-white/15 border border-white/30 text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-white/20 transition">
                            Absensi Saya
                        </a>
                    </div>
                </div>
                <div class="hidden lg:block shrink-0">
                    <div class="bg-white/10 border border-white/15 rounded-2xl p-5 w-56 space-y-4">
                        <div>
                            <p class="text-emerald-100 text-xs font-semibold uppercase tracking-wide">Jadwal Hari Ini</p>
                            <h3 class="text-4xl font-bold text-white mt-1">{{ $jadwals->count() }}</h3>
                            <p class="text-emerald-100 text-xs">{{ $hariIni }}</p>
                        </div>
                        <div class="border-t border-white/15 pt-3">
                            <p class="text-emerald-100 text-xs font-semibold uppercase tracking-wide">Siswa Diampu</p>
                            <h4 class="text-2xl font-bold text-white mt-0.5">{{ $totalSiswa }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── STAT CARDS ────────────────────────────────────────────────── --}}
        <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">

            {{-- Jadwal Hari Ini --}}
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-xs font-semibold uppercase tracking-wide">Jadwal Hari Ini</p>
                        <h3 class="text-3xl font-bold text-slate-800 mt-1">{{ $jadwals->count() }}</h3>
                        <p class="text-xs text-slate-400 mt-1">{{ $hariIni }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-teal-100 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Siswa Diampu --}}
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-xs font-semibold uppercase tracking-wide">Siswa Diampu</p>
                        <h3 class="text-3xl font-bold text-sky-600 mt-1">{{ $totalSiswa }}</h3>
                        <p class="text-xs text-slate-400 mt-1">
                            {{ $kelasWali ? 'Kelas '.$kelasWali->nama_kelas.' + kelas lain' : 'Di semua kelas Anda' }}
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-sky-100 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Absensi --}}
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-xs font-semibold uppercase tracking-wide">Absensi Dibuat</p>
                        <h3 class="text-3xl font-bold text-emerald-600 mt-1">{{ $totalAbsensi }}</h3>
                        <p class="text-xs text-slate-400 mt-1">Total pertemuan</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-100 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Nilai --}}
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-xs font-semibold uppercase tracking-wide">Nilai Diinput</p>
                        <h3 class="text-3xl font-bold text-amber-600 mt-1">{{ $totalNilai }}</h3>
                        <p class="text-xs text-slate-400 mt-1">Oleh Anda</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-amber-100 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                </div>
            </div>

        </div>

        {{-- ── JADWAL HARI INI + SIDEBAR ─────────────────────────────────── --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- Jadwal hari ini --}}
            <div class="xl:col-span-2 bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">

                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Jadwal Mengajar Hari Ini</h3>
                        <p class="text-sm text-slate-500">{{ now()->translatedFormat('l, d F Y') }}</p>
                    </div>
                    @if($jadwals->isNotEmpty())
                        <span class="bg-teal-100 text-teal-700 text-xs font-bold px-3 py-1.5 rounded-full">
                            {{ $jadwals->count() }} JP
                        </span>
                    @endif
                </div>

                <div class="p-6">
                    @if($jadwals->isEmpty())
                        <div class="flex flex-col items-center justify-center py-12 text-slate-400">
                            <svg class="w-14 h-14 mb-3 opacity-40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-sm font-medium">Tidak ada jadwal mengajar hari {{ $hariIni }}</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($jadwals as $j)
                            @php
                                $now     = now();
                                $mulai   = \Carbon\Carbon::today()->setTimeFromTimeString($j->jam_masuk);
                                $selesai = \Carbon\Carbon::today()->setTimeFromTimeString($j->jam_selesai);
                                $isActive   = $now->between($mulai, $selesai);
                                $isFinished = $now->greaterThan($selesai);
                            @endphp
                            <div class="flex items-center gap-4 p-4 rounded-2xl border
                                {{ $isActive ? 'border-teal-300 bg-teal-50' : ($isFinished ? 'border-slate-100 bg-slate-50 opacity-70' : 'border-slate-100 hover:bg-slate-50') }}
                                transition">

                                <div class="w-14 h-14 rounded-2xl flex flex-col items-center justify-center shrink-0 font-bold text-sm
                                    {{ $isActive ? 'bg-teal-600 text-white' : 'bg-slate-100 text-slate-700' }}">
                                    <span>{{ substr($j->jam_masuk,0,5) }}</span>
                                    <span class="text-[10px] font-normal opacity-70">–{{ substr($j->jam_selesai,0,5) }}</span>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-slate-800 truncate">
                                        {{ $j->mapel->nama_mapel ?? '-' }}
                                    </h4>
                                    <p class="text-sm text-slate-500">
                                        {{ $j->kelas->nama_kelas ?? '-' }}
                                        @if($kelasWali && $j->kelas_id == $kelasWali->id)
                                            <span class="ml-1 text-xs bg-teal-100 text-teal-700 px-1.5 py-0.5 rounded font-semibold">Kelas Binaan</span>
                                        @endif
                                    </p>
                                </div>

                                @if($isActive)
                                    <span class="bg-teal-600 text-white text-[10px] font-bold px-2.5 py-1 rounded-full shrink-0">
                                        Berlangsung
                                    </span>
                                @elseif($isFinished)
                                    <span class="bg-slate-200 text-slate-500 text-[10px] font-bold px-2.5 py-1 rounded-full shrink-0">
                                        Selesai
                                    </span>
                                @else
                                    <a href="{{ route('absensi.create', $j->id) }}"
                                        class="bg-teal-600 hover:bg-teal-700 text-white text-[10px] font-bold px-2.5 py-1 rounded-full shrink-0 transition">
                                        Isi Absensi
                                    </a>
                                @endif

                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>

            {{-- Sidebar --}}
            <div class="space-y-5">

                {{-- Info Kelas Binaan --}}
                @if($kelasWali)
                <div class="bg-gradient-to-br from-teal-600 to-emerald-700 rounded-2xl p-5 text-white">
                    <p class="text-teal-100 text-xs font-semibold uppercase tracking-wide mb-1">Kelas Binaan</p>
                    <h3 class="text-2xl font-bold">{{ $kelasWali->nama_kelas }}</h3>
                    <p class="text-teal-100 text-sm mt-1">
                        {{ $kelasWali->siswas->count() ?? 0 }} siswa terdaftar
                    </p>
                    <div class="mt-4 flex gap-2">
                        <a href="/data-wali-kelas"
                            class="bg-white/20 hover:bg-white/30 text-white text-xs font-bold px-3 py-1.5 rounded-lg transition">
                            Lihat Kelas
                        </a>
                        <a href="{{ route('rekap.nilai.kelas') }}"
                            class="bg-white/20 hover:bg-white/30 text-white text-xs font-bold px-3 py-1.5 rounded-lg transition">
                            Rekap Nilai
                        </a>
                    </div>
                </div>
                @endif

                {{-- Pengumuman --}}
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">
                    <h3 class="text-base font-bold text-slate-800 mb-4">Pengumuman</h3>
                    <div class="space-y-4">
                        @forelse($pengumuman as $item)
                        <div class="border-b border-slate-100 pb-3 last:border-0 last:pb-0">
                            <h4 class="text-sm font-semibold text-slate-700 leading-tight">{{ $item->judul }}</h4>
                            <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $item->isi }}</p>
                            <p class="text-xs text-slate-400 mt-1.5">{{ $item->created_at->diffForHumans() }}</p>
                        </div>
                        @empty
                        <p class="text-sm text-slate-400 text-center py-6">Belum ada pengumuman</p>
                        @endforelse
                    </div>
                </div>

                {{-- Agenda --}}
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">
                    <h3 class="text-base font-bold text-slate-800 mb-4">Agenda Sekolah</h3>
                    <div class="space-y-3">
                        @forelse($agenda as $item)
                        <div class="flex items-start gap-3 border-b border-slate-100 pb-3 last:border-0 last:pb-0">
                            <div class="bg-teal-50 text-teal-700 rounded-xl p-2 text-center min-w-[44px] shrink-0">
                                <div class="text-base font-bold leading-none">{{ \Carbon\Carbon::parse($item->tanggal)->format('d') }}</div>
                                <div class="text-[10px]">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('M') }}</div>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-700">{{ $item->judul }}</p>
                                <p class="text-xs text-slate-500 mt-0.5 line-clamp-1">{{ $item->deskripsi }}</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-sm text-slate-400 text-center py-6">Tidak ada agenda</p>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>

    </div>

</x-app-layout>

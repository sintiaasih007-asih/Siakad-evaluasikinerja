<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">
                    Dashboard Guru
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Monitoring aktivitas mengajar, absensi, dan perkembangan akademik siswa
                </p>
            </div>

            <div class="hidden md:flex items-center gap-3">
                <div class="bg-indigo-50 text-indigo-700 px-4 py-2 rounded-xl text-sm font-semibold">
                    {{ now()->translatedFormat('d F Y') }}
                </div>
            </div>
        </div>
    </x-slot>

    <div class="p-6 space-y-6 bg-slate-50 min-h-screen">

        {{-- WELCOME CARD --}}
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-indigo-700 to-slate-800 p-8 shadow-xl">

            <div class="absolute right-0 top-0 opacity-10">
                <svg width="320" height="320" fill="none">
                    <circle cx="160" cy="160" r="160" fill="white"/>
                </svg>
            </div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">

                <div>

                    <h1 class="text-3xl font-bold text-white leading-tight">
                        Selamat Datang,
                        {{ Auth::user()->name }}
                    </h1>

                    <p class="text-indigo-100 mt-3 max-w-2xl">
                        Kelola jadwal mengajar, input nilai siswa,
                        pantau absensi, dan aktivitas akademik secara realtime.
                    </p>

                    <div class="flex flex-wrap gap-3 mt-6">

                        <a href="{{ route('nilai.index') }}"
                            class="bg-white text-indigo-700 px-5 py-3 rounded-xl font-semibold shadow hover:scale-105 transition">
                            Kelola Nilai
                        </a>

                        <a href="{{ route('absensi.index') }}"
                            class="bg-indigo-500/30 backdrop-blur text-white border border-white/20 px-5 py-3 rounded-xl font-semibold hover:bg-indigo-500/40 transition">
                            Lihat Absensi
                        </a>

                    </div>

                </div>

                {{-- STATUS --}}
                <div class="hidden lg:block">

                    <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-3xl p-6 w-72">

                        <p class="text-indigo-100 text-sm mb-2">
                            Statistik Hari Ini
                        </p>

                        <div class="space-y-4">

                            <div>
                                <h3 class="text-4xl font-bold text-white">
                                    {{ $jadwals->count() }}
                                </h3>

                                <p class="text-indigo-100 text-sm">
                                    Jadwal Mengajar
                                </p>
                            </div>

                            <div class="border-t border-white/10 pt-4">

                                <p class="text-indigo-100 text-sm">
                                    Total Nilai
                                </p>

                                <h4 class="text-white font-semibold mt-1">
                                    {{ $totalNilai }} Nilai
                                </h4>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </div>

        {{-- STATISTIK --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

            {{-- JADWAL --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg transition">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-slate-500 text-sm">
                            Jadwal Mengajar
                        </p>

                        <h3 class="text-3xl font-bold text-slate-800 mt-2">
                            {{ $jadwals->count() }}
                        </h3>

                    </div>

                    <div class="w-14 h-14 rounded-2xl bg-indigo-100 flex items-center justify-center">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-7 h-7 text-indigo-600"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10m-11 9h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v11a2 2 0 002 2z"/>
                        </svg>

                    </div>

                </div>

            </div>

            {{-- ABSENSI --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg transition">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-slate-500 text-sm">
                            Total Absensi
                        </p>

                        <h3 class="text-3xl font-bold text-emerald-600 mt-2">
                            {{ $totalAbsensi }}
                        </h3>

                    </div>

                    <div class="w-14 h-14 rounded-2xl bg-emerald-100 flex items-center justify-center">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-7 h-7 text-emerald-600"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12l2 2 4-4"/>
                        </svg>

                    </div>

                </div>

            </div>

            {{-- NILAI --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg transition">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-slate-500 text-sm">
                            Nilai Diinput
                        </p>

                        <h3 class="text-3xl font-bold text-amber-600 mt-2">
                            {{ $totalNilai }}
                        </h3>

                    </div>

                    <div class="w-14 h-14 rounded-2xl bg-amber-100 flex items-center justify-center">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-7 h-7 text-amber-600"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8v4l3 3"/>
                        </svg>

                    </div>

                </div>

            </div>

            {{-- PENGUMUMAN --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg transition">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-slate-500 text-sm">
                            Pengumuman
                        </p>

                        <h3 class="text-3xl font-bold text-rose-600 mt-2">
                            {{ $totalPengumuman }}
                        </h3>

                    </div>

                    <div class="w-14 h-14 rounded-2xl bg-rose-100 flex items-center justify-center">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-7 h-7 text-rose-600"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 17h5l-1.4-1.4A2 2 0 0118 14.17V11a6 6 0 10-12 0v3.17c0 .53-.21 1.04-.59 1.41L4 17h5"/>
                        </svg>

                    </div>

                </div>

            </div>

        </div>

        {{-- CONTENT --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- JADWAL --}}
            <div class="xl:col-span-2 bg-white rounded-3xl shadow-sm border border-slate-100 p-6">

                <div class="flex items-center justify-between mb-6">

                    <div>

                        <h3 class="text-lg font-bold text-slate-800">
                            Jadwal Mengajar
                        </h3>

                        <p class="text-sm text-slate-500">
                            Jadwal pelajaran guru
                        </p>

                    </div>

                </div>

                <div class="space-y-4">

                    @forelse($jadwals as $jadwal)

                        <div class="flex items-center justify-between p-4 rounded-2xl border border-slate-100 hover:bg-slate-50 transition">

                            <div class="flex items-center gap-4">

                                <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center font-bold text-indigo-700 text-sm">
                                    {{ substr($jadwal->jam_masuk,0,5) }}
                                </div>

                                <div>

                                    <h4 class="font-semibold text-slate-800">
                                        {{ $jadwal->mapel->nama_mapel ?? '-' }}
                                    </h4>

                                    <p class="text-sm text-slate-500">
                                        {{ $jadwal->kelas->nama_kelas ?? '-' }}
                                        •
                                        {{ $jadwal->hari }}
                                    </p>

                                </div>

                            </div>

                            <span class="bg-indigo-100 text-indigo-700 text-xs font-semibold px-3 py-1 rounded-full">
                                {{ substr($jadwal->jam_selesai,0,5) }}
                            </span>

                        </div>

                    @empty

                        <div class="text-center text-slate-400 py-10">
                            Belum ada jadwal mengajar
                        </div>

                    @endforelse

                </div>

            </div>

            {{-- SIDEBAR --}}
            <div class="space-y-6">

                {{-- PENGUMUMAN --}}
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">

                    <div class="mb-6">

                        <h3 class="text-lg font-bold text-slate-800">
                            Pengumuman Terbaru
                        </h3>

                        <p class="text-sm text-slate-500">
                            Informasi terbaru sekolah
                        </p>

                    </div>

                    <div class="space-y-5">

                        @forelse($pengumuman as $item)

                            <div class="border-b border-slate-100 pb-4">

                                <h4 class="font-semibold text-slate-700">
                                    {{ $item->judul }}
                                </h4>

                                <p class="text-sm text-slate-500 mt-1">
                                    {{ \Illuminate\Support\Str::limit($item->isi,80) }}
                                </p>

                                <p class="text-xs text-slate-400 mt-2">
                                    {{ $item->created_at->diffForHumans() }}
                                </p>

                            </div>

                        @empty

                            <div class="text-center text-slate-400 py-10">
                                Tidak ada pengumuman
                            </div>

                        @endforelse

                    </div>

                </div>

                {{-- AGENDA --}}
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">

                    <div class="mb-5">

                        <h3 class="text-lg font-bold text-slate-800">
                            Agenda Sekolah
                        </h3>

                        <p class="text-sm text-slate-500">
                            Agenda & kegiatan terdekat
                        </p>

                    </div>

                    <div class="space-y-4">

                        @forelse($agenda as $item)

                            <div class="flex items-start gap-3 border-b border-slate-100 pb-3">

                                <div class="min-w-[50px] text-center rounded-xl bg-indigo-50 p-2">

                                    <h4 class="text-lg font-bold text-indigo-700 leading-none">
                                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d') }}
                                    </h4>

                                    <p class="text-xs text-indigo-500">
                                        {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('M') }}
                                    </p>

                                </div>

                                <div>

                                    <h5 class="font-semibold text-slate-700 text-sm">
                                        {{ $item->judul }}
                                    </h5>

                                    <p class="text-xs text-slate-500 mt-1">
                                        {{ \Illuminate\Support\Str::limit($item->deskripsi,60) }}
                                    </p>

                                </div>

                            </div>

                        @empty

                            <div class="text-center text-slate-400 py-6 text-sm">
                                Tidak ada agenda
                            </div>

                        @endforelse

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
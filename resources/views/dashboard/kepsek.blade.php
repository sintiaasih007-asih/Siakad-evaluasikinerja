<x-app-layout>

    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            
            <div>
                <h2 class="text-3xl font-bold text-slate-800">
                    Dashboard Kepala Sekolah
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Monitoring statistik sekolah, aktivitas akademik, dan informasi penting sekolah
                </p>
            </div>

            <div class="flex items-center gap-3">

                <div class="bg-white border border-slate-200 shadow-sm rounded-2xl px-5 py-3">
                    <p class="text-xs text-slate-400">
                        Hari Ini
                    </p>

                    <h4 class="font-semibold text-slate-700">
                        {{ now()->translatedFormat('l, d F Y') }}
                    </h4>
                </div>

            </div>

        </div>
    </x-slot>

    <div class="p-6 bg-slate-50 min-h-screen space-y-6">

        {{-- HERO --}}
        <div class="relative overflow-hidden rounded-[30px] bg-gradient-to-r from-slate-900 via-indigo-900 to-slate-800 shadow-2xl">

            <div class="absolute inset-0 opacity-10">
                <svg width="100%" height="100%">
                    <circle cx="90%" cy="20%" r="220" fill="white"/>
                    <circle cx="10%" cy="90%" r="180" fill="white"/>
                </svg>
            </div>

            <div class="relative z-10 p-8 md:p-10 flex flex-col xl:flex-row xl:items-center xl:justify-between gap-10">

                <div class="max-w-3xl">

                    <div class="inline-flex items-center gap-2 bg-white/10 border border-white/10 px-4 py-2 rounded-full mb-5">

                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>

                        <span class="text-sm text-slate-200">
                            Sistem Akademik Aktif
                        </span>

                    </div>

                    <h1 class="text-4xl md:text-5xl font-bold text-white leading-tight">
                        Selamat Datang,
                        {{ Auth::user()->name }}
                    </h1>

                    <p class="text-slate-300 mt-5 leading-relaxed text-base md:text-lg">
                        Pantau perkembangan akademik sekolah, aktivitas guru,
                        jumlah siswa, absensi, pengumuman, serta agenda sekolah
                        secara realtime dalam satu dashboard terintegrasi.
                    </p>

                    <div class="flex flex-wrap gap-4 mt-8">

                        <a href="{{ route('guru.index') }}"
                            class="bg-white text-indigo-700 px-6 py-3 rounded-2xl font-semibold shadow-lg hover:scale-105 transition duration-300">

                            Data Guru
                        </a>

                        <a href="{{ route('siswa.index') }}"
                            class="bg-white/10 border border-white/10 backdrop-blur text-white px-6 py-3 rounded-2xl font-semibold hover:bg-white/20 transition">

                            Data Siswa
                        </a>

                    </div>

                </div>

                {{-- MINI STATUS --}}
                <div class="grid grid-cols-2 gap-4 w-full xl:w-[380px]">

                    <div class="bg-white/10 border border-white/10 backdrop-blur rounded-3xl p-5">
                        <p class="text-slate-300 text-sm">
                            Total Siswa
                        </p>

                        <h3 class="text-4xl font-bold text-white mt-2">
                            {{ $totalSiswa }}
                        </h3>
                    </div>

                    <div class="bg-white/10 border border-white/10 backdrop-blur rounded-3xl p-5">
                        <p class="text-slate-300 text-sm">
                            Total Guru
                        </p>

                        <h3 class="text-4xl font-bold text-white mt-2">
                            {{ $totalGuru }}
                        </h3>
                    </div>

                    <div class="bg-white/10 border border-white/10 backdrop-blur rounded-3xl p-5">
                        <p class="text-slate-300 text-sm">
                            Total Kelas
                        </p>

                        <h3 class="text-4xl font-bold text-white mt-2">
                            {{ $totalKelas }}
                        </h3>
                    </div>

                    <div class="bg-white/10 border border-white/10 backdrop-blur rounded-3xl p-5">
                        <p class="text-slate-300 text-sm">
                            Mata Pelajaran
                        </p>

                        <h3 class="text-4xl font-bold text-white mt-2">
                            {{ $totalMapel }}
                        </h3>
                    </div>

                </div>

            </div>

        </div>

        {{-- STATISTIK --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

            {{-- CARD --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 hover:shadow-xl transition duration-300">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-slate-500 text-sm">
                            Total Siswa
                        </p>

                        <h3 class="text-4xl font-bold text-slate-800 mt-2">
                            {{ $totalSiswa }}
                        </h3>

                    </div>

                    <div class="w-16 h-16 rounded-2xl bg-indigo-100 flex items-center justify-center">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-8 h-8 text-indigo-600"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 20h5V4H2v16h5m10 0v-4a3 3 0 00-6 0v4m6 0H8"/>

                        </svg>

                    </div>

                </div>

            </div>

            {{-- CARD --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 hover:shadow-xl transition duration-300">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-slate-500 text-sm">
                            Total Guru
                        </p>

                        <h3 class="text-4xl font-bold text-slate-800 mt-2">
                            {{ $totalGuru }}
                        </h3>

                    </div>

                    <div class="w-16 h-16 rounded-2xl bg-emerald-100 flex items-center justify-center">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-8 h-8 text-emerald-600"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 14l9-5-9-5-9 5 9 5z"/>

                        </svg>

                    </div>

                </div>

            </div>

            {{-- CARD --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 hover:shadow-xl transition duration-300">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-slate-500 text-sm">
                            Total Kelas
                        </p>

                        <h3 class="text-4xl font-bold text-slate-800 mt-2">
                            {{ $totalKelas }}
                        </h3>

                    </div>

                    <div class="w-16 h-16 rounded-2xl bg-amber-100 flex items-center justify-center">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-8 h-8 text-amber-600"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-4 4v-4z"/>

                        </svg>

                    </div>

                </div>

            </div>

            {{-- CARD --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 hover:shadow-xl transition duration-300">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-slate-500 text-sm">
                            Mata Pelajaran
                        </p>

                        <h3 class="text-4xl font-bold text-slate-800 mt-2">
                            {{ $totalMapel }}
                        </h3>

                    </div>

                    <div class="w-16 h-16 rounded-2xl bg-rose-100 flex items-center justify-center">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-8 h-8 text-rose-600"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.483 9.246 5 7.5 5S4.168 5.483 3 6.253v13C4.168 18.483 5.754 18 7.5 18s3.332.483 4.5 1.253m0-13C13.168 5.483 14.754 5 16.5 5c1.746 0 3.332.483 4.5 1.253v13C19.832 18.483 18.246 18 16.5 18c-1.746 0-3.332.483-4.5 1.253"/>

                        </svg>

                    </div>

                </div>

            </div>

        </div>

        {{-- CONTENT --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- PENGUMUMAN --}}
            <div class="xl:col-span-2 bg-white rounded-3xl border border-slate-100 shadow-sm p-6">

                <div class="flex items-center justify-between mb-8">

                    <div>

                        <h3 class="text-xl font-bold text-slate-800">
                            Pengumuman Sekolah
                        </h3>

                        <p class="text-sm text-slate-500 mt-1">
                            Informasi dan pemberitahuan terbaru sekolah
                        </p>

                    </div>

                </div>

                <div class="space-y-5">

                    @forelse($pengumuman as $item)

                        <div class="border border-slate-100 rounded-2xl p-5 hover:bg-slate-50 transition">

                            <div class="flex items-start justify-between gap-4">

                                <div>

                                    <h4 class="font-bold text-slate-800 text-lg">
                                        {{ $item->judul }}
                                    </h4>

                                    <p class="text-slate-500 text-sm mt-2 leading-relaxed">
                                        {{ \Illuminate\Support\Str::limit($item->isi, 150) }}
                                    </p>

                                </div>

                                <span class="text-xs text-slate-400 whitespace-nowrap">
                                    {{ $item->created_at->diffForHumans() }}
                                </span>

                            </div>

                        </div>

                    @empty

                        <div class="text-center py-12 text-slate-400">
                            Tidak ada pengumuman
                        </div>

                    @endforelse

                </div>

            </div>

            {{-- SIDEBAR --}}
            <div class="space-y-6">

                {{-- AGENDA --}}
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">

                    <div class="mb-6">

                        <h3 class="text-xl font-bold text-slate-800">
                            Agenda Sekolah
                        </h3>

                        <p class="text-sm text-slate-500 mt-1">
                            Jadwal kegiatan sekolah
                        </p>

                    </div>

                    <div class="space-y-5">

                        @forelse($agenda as $item)

                            <div class="flex gap-4">

                                <div class="min-w-[60px] h-[60px] rounded-2xl bg-indigo-50 flex flex-col items-center justify-center">

                                    <h4 class="text-lg font-bold text-indigo-700 leading-none">
                                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d') }}
                                    </h4>

                                    <p class="text-xs text-indigo-500">
                                        {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('M') }}
                                    </p>

                                </div>

                                <div>

                                    <h5 class="font-semibold text-slate-700">
                                        {{ $item->judul }}
                                    </h5>

                                    <p class="text-sm text-slate-500 mt-1">
                                        {{ \Illuminate\Support\Str::limit($item->deskripsi, 70) }}
                                    </p>

                                </div>

                            </div>

                        @empty

                            <div class="text-center py-10 text-slate-400">
                                Tidak ada agenda
                            </div>

                        @endforelse

                    </div>

                </div>

                {{-- QUICK INFO --}}
                <div class="bg-gradient-to-br from-indigo-600 to-slate-900 rounded-3xl p-6 text-white shadow-xl">

                    <h3 class="text-xl font-bold">
                        Sistem Akademik
                    </h3>

                    <p class="text-slate-200 text-sm mt-2 leading-relaxed">
                        Semua data akademik sekolah telah terintegrasi dan dapat dipantau secara realtime.
                    </p>

                    <div class="mt-6 space-y-3">

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-200">
                                Status Server
                            </span>

                            <span class="text-emerald-300 font-semibold">
                                Online
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-200">
                                Database
                            </span>

                            <span class="text-emerald-300 font-semibold">
                                Connected
                            </span>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
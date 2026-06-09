<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">
                    Dashboard Orang Tua
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Monitoring perkembangan akademik siswa
                </p>
            </div>

            <div class="hidden md:flex items-center gap-3">
                <div class="bg-indigo-50 text-indigo-700 px-4 py-2 rounded-xl text-sm font-semibold">
                    {{ now()->translatedFormat('d F Y') }}
                </div>
            </div>
        </div>
    </x-slot>

    <div class="p-6 bg-slate-50 min-h-screen space-y-6">

        {{-- WELCOME --}}
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-800 via-indigo-700 to-indigo-600 p-8 shadow-xl">

            <div class="absolute right-0 top-0 opacity-10">
                <svg width="320" height="320" fill="none">
                    <circle cx="160" cy="160" r="160" fill="white"/>
                </svg>
            </div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

                <div>

                    <h1 class="text-3xl font-bold text-white">
                        Selamat Datang,
                        {{ Auth::user()->name }}
                    </h1>

                    <p class="text-indigo-100 mt-3 max-w-2xl leading-relaxed">
                        Pantau perkembangan akademik dan informasi siswa secara realtime.
                    </p>

                    <div class="flex flex-wrap gap-3 mt-6">

                        <a href="#informasi"
                            class="bg-white text-indigo-700 px-5 py-3 rounded-xl font-semibold shadow hover:scale-105 transition">
                            Informasi Siswa
                        </a>

                        <a href="#nilai"
                            class="bg-indigo-500/30 backdrop-blur text-white border border-white/20 px-5 py-3 rounded-xl font-semibold hover:bg-indigo-500/40 transition">
                            Lihat Nilai
                        </a>

                    </div>

                </div>

                {{-- STATUS --}}
                <div class="hidden lg:block">

                    <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-3xl p-6 w-72">

                        <p class="text-indigo-100 text-sm mb-2">
                            Status Akademik
                        </p>

                        <div class="space-y-4">

                            <div>
                                <h3 class="text-4xl font-bold text-white">
                                    Aktif
                                </h3>

                                <p class="text-indigo-100 text-sm">
                                    Status Siswa
                                </p>
                            </div>

                            <div class="border-t border-white/10 pt-4">

                                <p class="text-indigo-100 text-sm">
                                    Kelas Aktif
                                </p>

                                <h4 class="text-white font-semibold mt-1">
                                    {{ $siswa->kelas->nama_kelas ?? '-' }}
                                </h4>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- STATISTIK --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

            {{-- NAMA --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">

                <p class="text-slate-500 text-sm">
                    Nama Siswa
                </p>

                <h3 class="text-xl font-bold text-indigo-600 mt-3">
                    {{ $siswa->nama ?? '-' }}
                </h3>

            </div>

            {{-- NIS --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">

                <p class="text-slate-500 text-sm">
                    NIS
                </p>

                <h3 class="text-xl font-bold text-emerald-600 mt-3">
                    {{ $siswa->nis ?? '-' }}
                </h3>

            </div>

            {{-- KELAS --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">

                <p class="text-slate-500 text-sm">
                    Kelas
                </p>

                <h3 class="text-xl font-bold text-amber-600 mt-3">
                    {{ $siswa->kelas->nama_kelas ?? '-' }}
                </h3>

            </div>

            {{-- JK --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">

                <p class="text-slate-500 text-sm">
                    Jenis Kelamin
                </p>

                <h3 class="text-xl font-bold text-rose-600 mt-3">
                    {{ $siswa->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}
                </h3>

            </div>

        </div>

        {{-- CONTENT --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- INFORMASI --}}
            <div id="informasi" class="xl:col-span-2 bg-white rounded-3xl shadow-sm border border-slate-100 p-6">

                <div class="mb-6">

                    <h3 class="text-xl font-bold text-slate-800">
                        Informasi Lengkap Siswa
                    </h3>

                    <p class="text-sm text-slate-500 mt-1">
                        Data lengkap siswa dan orang tua
                    </p>

                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div class="bg-slate-50 rounded-2xl p-5">
                        <p class="text-sm text-slate-500">Nama Siswa</p>
                        <h4 class="font-bold text-slate-800 mt-1">
                            {{ $siswa->nama ?? '-' }}
                        </h4>
                    </div>

                    <div class="bg-slate-50 rounded-2xl p-5">
                        <p class="text-sm text-slate-500">NIS</p>
                        <h4 class="font-bold text-slate-800 mt-1">
                            {{ $siswa->nis ?? '-' }}
                        </h4>
                    </div>

                    <div class="bg-slate-50 rounded-2xl p-5">
                        <p class="text-sm text-slate-500">Jenis Kelamin</p>
                        <h4 class="font-bold text-slate-800 mt-1">
                            {{ $siswa->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}
                        </h4>
                    </div>

                    <div class="bg-slate-50 rounded-2xl p-5">
                        <p class="text-sm text-slate-500">Kelas</p>
                        <h4 class="font-bold text-slate-800 mt-1">
                            {{ $siswa->kelas->nama_kelas ?? '-' }}
                        </h4>
                    </div>

                    <div class="bg-slate-50 rounded-2xl p-5">
                        <p class="text-sm text-slate-500">Nama Orang Tua</p>
                        <h4 class="font-bold text-slate-800 mt-1">
                            {{ $siswa->nama_ortu ?? '-' }}
                        </h4>
                    </div>

                    <div class="bg-slate-50 rounded-2xl p-5">
                        <p class="text-sm text-slate-500">No HP Orang Tua</p>
                        <h4 class="font-bold text-slate-800 mt-1">
                            {{ $siswa->no_hp_ortu ?? '-' }}
                        </h4>
                    </div>

                </div>

            </div>

            {{-- SIDEBAR --}}
            <div class="space-y-6">

                {{-- STATUS --}}
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">

                    <div class="mb-5">

                        <h3 class="text-lg font-bold text-slate-800">
                            Status Siswa
                        </h3>

                        <p class="text-sm text-slate-500">
                            Informasi akademik siswa
                        </p>

                    </div>

                    <div class="space-y-4">

                        <div class="flex items-center justify-between p-4 rounded-2xl bg-emerald-50">
                            <span class="text-slate-600">
                                Status
                            </span>

                            <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold">
                                Aktif
                            </span>
                        </div>

                        <div class="flex items-center justify-between p-4 rounded-2xl bg-indigo-50">
                            <span class="text-slate-600">
                                Tahun Ajaran
                            </span>

                            <span class="font-bold text-indigo-700">
                                2025 / 2026
                            </span>
                        </div>

                    </div>

                </div>

                {{-- ALAMAT --}}
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">

                    <div class="mb-5">

                        <h3 class="text-lg font-bold text-slate-800">
                            Alamat Siswa
                        </h3>

                    </div>

                    <div class="bg-slate-50 rounded-2xl p-5 text-sm text-slate-700 leading-relaxed">
                        {{ $siswa->alamat ?? '-' }}
                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
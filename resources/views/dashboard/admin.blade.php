<x-app-layout>

<div
x-data="dashboardApp()"
x-init="initCalendar()"
class="max-w-7xl mx-auto px-6 py-8 space-y-8"
>

    {{-- HEADER --}}
    <!-- <div>
        <h1 class="text-2xl font-bold text-gray-800">Dashboard Admin</h1>
        <p class="text-gray-500 text-sm">Overview data sekolah</p>
    </div> -->

        {{-- ── INFO SEKOLAH (kurikulum) ───────────────────────────────── --}}
    @if($profilSekolah && ($profilSekolah->nama_sekolah || $profilSekolah->kurikulum))
    <div class="bg-gradient-to-r from-indigo-600 via-blue-600 to-cyan-600 rounded-2xl p-4 mb-6 flex flex-wrap items-center gap-4">
        @if($profilSekolah->logo_sekolah)
            <img src="{{ asset('storage/'.$profilSekolah->logo_sekolah) }}"
                class="w-12 h-12 rounded-xl bg-white object-contain p-1 shrink-0">
        @endif
        <div class="flex-1 min-w-0">
            <h3 class="text-white font-bold text-base leading-tight truncate">
                {{ $profilSekolah->nama_sekolah ?? 'Nama Sekolah' }}
            </h3>
            <div class="flex flex-wrap gap-x-4 gap-y-0.5 mt-1 text-blue-100 text-xs">
                @if($profilSekolah->jenjang)   <span>{{ $profilSekolah->jenjang }}</span> @endif
                @if($profilSekolah->akreditasi) <span>Akreditasi: {{ $profilSekolah->akreditasi }}</span> @endif
                @if($profilSekolah->npsn)       <span>NPSN: {{ $profilSekolah->npsn }}</span> @endif
            </div>
        </div>
        @if($profilSekolah->kurikulum)
        <div class="shrink-0">
            <span class="bg-white/20 border border-white/30 text-white text-xs font-bold px-3 py-1.5 rounded-full flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                {{ $profilSekolah->kurikulum }}
            </span>
        </div>
        @endif
        <a href="{{ route('profil-sekolah.index') }}"
            class="shrink-0 bg-white/20 hover:bg-white/30 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition">
            Edit Profil
        </a>
    </div>
    @endif

    
    {{-- STATISTIK --}}
    <div class="max-w-6xl mx-auto">
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-3 mb-8">

        {{-- SISWA --}}
        <div class="bg-white shadow-sm border rounded-xl overflow-hidden flex h-20">
            <div class="w-16 bg-sky-500 flex items-center justify-center shrink-0">
                <i data-lucide="users" class="w-7 h-7 text-white"></i>
            </div>
            <div class="flex-1 px-4 flex flex-col justify-center min-w-0">
                <p class="text-[11px] font-semibold text-gray-500 uppercase truncate">Siswa</p>
                <h2 class="text-2xl font-bold text-gray-800">{{ $totalSiswa ?? 0 }}</h2>
            </div>
        </div>

        {{-- GURU --}}
        <div class="bg-white shadow-sm border rounded-xl overflow-hidden flex h-20">
            <div class="w-16 bg-emerald-500 flex items-center justify-center shrink-0">
                <i data-lucide="user-check" class="w-7 h-7 text-white"></i>
            </div>
            <div class="flex-1 px-4 flex flex-col justify-center min-w-0">
                <p class="text-[11px] font-semibold text-gray-500 uppercase truncate">Guru</p>
                <h2 class="text-2xl font-bold text-gray-800">{{ $totalGuru ?? 0 }}</h2>
            </div>
        </div>

        {{-- KELAS --}}
        <div class="bg-white shadow-sm border rounded-xl overflow-hidden flex h-20">
            <div class="w-16 bg-amber-500 flex items-center justify-center shrink-0">
                <i data-lucide="school" class="w-7 h-7 text-white"></i>
            </div>
            <div class="flex-1 px-4 flex flex-col justify-center min-w-0">
                <p class="text-[11px] font-semibold text-gray-500 uppercase truncate">Kelas</p>
                <h2 class="text-2xl font-bold text-gray-800">{{ $totalKelas ?? 0 }}</h2>
            </div>
        </div>

        {{-- MAPEL --}}
        <div class="bg-white shadow-sm border rounded-xl overflow-hidden flex h-20">
            <div class="w-16 bg-rose-500 flex items-center justify-center shrink-0">
                <i data-lucide="book-open" class="w-7 h-7 text-white"></i>
            </div>
            <div class="flex-1 px-4 flex flex-col justify-center min-w-0">
                <p class="text-[11px] font-semibold text-gray-500 uppercase truncate">Mata Pelajaran</p>
                <h2 class="text-2xl font-bold text-gray-800">{{ $totalMapel ?? 0 }}</h2>
            </div>
        </div>

    </div>


        

        {{-- ── KELOLA EVALUASI ─────────────────────────────────────────── --}}
        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-teal-700 to-emerald-600 px-5 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 p-2 rounded-xl">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-sm">Kelola Tampil Evaluasi</h3>
                        <p class="text-teal-100 text-xs">Aktifkan atau nonaktifkan akses evaluasi untuk guru</p>
                    </div>
                </div>
                <a href="{{ route('profil-sekolah.index') }}#evaluasi"
                    class="text-xs bg-white/20 hover:bg-white/30 text-white px-3 py-1.5 rounded-lg transition">
                    Pengaturan Lanjut
                </a>
            </div>
            <div class="p-5 grid md:grid-cols-2 gap-4">

                {{-- Evaluasi Bulanan --}}
                @php $bulananAktif = $profilSekolah?->evaluasi_bulanan_aktif ?? false; @endphp
                <div class="border rounded-2xl p-4 flex items-center justify-between gap-4
                    {{ $bulananAktif ? 'border-teal-200 bg-teal-50' : 'border-slate-200 bg-slate-50' }}">
                    <div>
                        <h4 class="font-bold text-slate-800 text-sm">Evaluasi Bulanan</h4>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Guru dapat melihat & mengakses halaman evaluasi bulanan
                        </p>
                        <span class="inline-flex items-center gap-1 mt-2 text-xs font-semibold px-2.5 py-1 rounded-full
                            {{ $bulananAktif ? 'bg-teal-100 text-teal-700' : 'bg-slate-200 text-slate-500' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $bulananAktif ? 'bg-teal-500' : 'bg-slate-400' }}"></span>
                            {{ $bulananAktif ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                    <form action="{{ route('evaluasi.toggle') }}" method="POST">
                        @csrf
                        <input type="hidden" name="jenis" value="bulanan">
                        <button type="submit"
                            class="shrink-0 px-4 py-2 rounded-xl text-xs font-bold transition
                            {{ $bulananAktif
                                ? 'bg-red-100 hover:bg-red-200 text-red-700 border border-red-200'
                                : 'bg-teal-600 hover:bg-teal-700 text-white' }}">
                            {{ $bulananAktif ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                </div>

                {{-- Evaluasi Semesteran --}}
                @php $semesteranAktif = $profilSekolah?->evaluasi_semesteran_aktif ?? false; @endphp
                <div class="border rounded-2xl p-4 flex items-center justify-between gap-4
                    {{ $semesteranAktif ? 'border-emerald-200 bg-emerald-50' : 'border-slate-200 bg-slate-50' }}">
                    <div>
                        <h4 class="font-bold text-slate-800 text-sm">Evaluasi Semesteran</h4>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Guru dapat melihat & mengakses halaman evaluasi semesteran
                        </p>
                        <span class="inline-flex items-center gap-1 mt-2 text-xs font-semibold px-2.5 py-1 rounded-full
                            {{ $semesteranAktif ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-500' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $semesteranAktif ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                            {{ $semesteranAktif ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                    <form action="{{ route('evaluasi.toggle') }}" method="POST">
                        @csrf
                        <input type="hidden" name="jenis" value="semesteran">
                        <button type="submit"
                            class="shrink-0 px-4 py-2 rounded-xl text-xs font-bold transition
                            {{ $semesteranAktif
                                ? 'bg-red-100 hover:bg-red-200 text-red-700 border border-red-200'
                                : 'bg-emerald-600 hover:bg-emerald-700 text-white' }}">
                            {{ $semesteranAktif ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                </div>

            </div>
        </div>

        {{-- ── QR TOKEN ABSENSI GURU ──────────────────────────────────── --}}
        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden mb-6">

            {{-- Header --}}
            <div class="bg-gradient-to-r from-indigo-700 to-blue-600 px-5 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 p-2 rounded-xl">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-white font-bold text-sm">QR Token Absensi Guru</h2>
                        <p class="text-blue-100 text-xs">{{ now()->translatedFormat('l, d F Y') }}</p>
                    </div>
                </div>
                <a href="{{ route('qr-absensi-guru.index') }}"
                    class="text-xs bg-white/20 hover:bg-white/30 text-white px-3 py-1.5 rounded-lg transition flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Kelola QR
                </a>
            </div>

            <div class="p-5">
                @if($qrToken)
                    <div class="flex flex-col sm:flex-row gap-5 items-center">

                        {{-- QR Code --}}
                        <div class="bg-white border-2 border-indigo-100 rounded-2xl p-3 shadow-sm shrink-0">
                            <div class="text-center text-[10px] text-gray-400 mb-2 font-medium uppercase tracking-wide">
                                Scan untuk Absensi
                            </div>
                            {!! QrCode::size(160)->style('round')->eye('circle')
                                ->color(79, 70, 229)
                                ->generate($qrToken) !!}
                            <div class="text-center text-[10px] text-gray-400 mt-2">{{ now()->format('d/m/Y') }}</div>
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 w-full space-y-3">

                            {{-- Status aktif --}}
                            <div class="flex items-center gap-2 bg-green-50 border border-green-200 rounded-xl px-3 py-2.5">
                                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse shrink-0"></span>
                                <span class="text-sm font-semibold text-green-700">Token Aktif</span>
                                <span class="ml-auto text-xs text-green-600 bg-green-100 px-2 py-0.5 rounded-full">s/d 23:59</span>
                            </div>

                            {{-- Token string --}}
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 flex items-center gap-2">
                                <code class="flex-1 text-xs text-gray-600 font-mono truncate select-all">{{ $qrToken }}</code>
                                <button onclick="copyDashboardToken()"
                                    title="Salin token"
                                    class="shrink-0 p-1.5 hover:bg-gray-200 rounded-lg transition text-gray-500">
                                    <svg id="iconCopyDash" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                </button>
                            </div>

                            {{-- Statistik --}}
                            <div class="grid grid-cols-2 gap-3">
                                <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-3 text-center">
                                    <p class="text-2xl font-bold text-indigo-700">{{ $guruHadirHariIni }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Guru sudah absen</p>
                                </div>
                                <div class="bg-blue-50 border border-blue-100 rounded-xl p-3 text-center">
                                    <p class="text-2xl font-bold text-blue-700">{{ $totalGuru }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Total guru</p>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex gap-2 pt-1">
                                <form action="{{ route('qr-absensi-guru.generate') }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit"
                                        onclick="return confirm('Generate token baru? Token lama tidak bisa digunakan lagi.')"
                                        class="w-full flex items-center justify-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded-xl text-xs font-semibold transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                        Generate Baru
                                    </button>
                                </form>
                                <form action="{{ route('qr-absensi-guru.reset') }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit"
                                        onclick="return confirm('Reset token? Guru tidak bisa absen sampai token baru dibuat.')"
                                        class="w-full flex items-center justify-center gap-1.5 bg-white border border-red-200 hover:bg-red-50 text-red-500 px-3 py-2 rounded-xl text-xs font-semibold transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Reset Token
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>

                @else
                    {{-- Tidak ada token --}}
                    <div class="flex flex-col sm:flex-row items-center gap-4">
                        <div class="w-40 h-40 bg-gray-100 border-2 border-dashed border-gray-300 rounded-2xl flex flex-col items-center justify-center shrink-0">
                            <svg class="w-10 h-10 text-gray-300 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                            </svg>
                            <span class="text-xs text-gray-400">Belum ada token</span>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-700 mb-1">Token QR belum dibuat</p>
                            <p class="text-sm text-gray-500 mb-3">Generate token agar guru dapat melakukan absensi hari ini.</p>
                            <form action="{{ route('qr-absensi-guru.generate') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl text-sm font-bold transition shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                    </svg>
                                    Generate Token QR Hari Ini
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- MONITORING LOGIN --}}
        <div class="bg-white rounded-2xl shadow-sm border p-5 md:p-8">

            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-base md:text-lg font-semibold text-gray-700">
                        Monitoring Login Pengguna
                    </h2>

                    <p class="text-sm text-gray-400">
                        Aktivitas login 7 hari terakhir
                    </p>
                </div>
            </div>

            <div class="h-[250px] md:h-[320px] lg:h-[350px]">
                <canvas id="loginChart"></canvas>
            </div>

        </div>


    {{-- CONTENT --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- ================= PENGUMUMAN ================= --}}
        <div class="col-span-1 bg-white rounded-2xl shadow-sm border flex flex-col">

            <div class="flex justify-between items-center p-4 border-b">
                <h2 class="font-semibold text-gray-700">Pengumuman</h2>
                <button @click="openPengumuman=true"
                    class="inline-flex items-center gap-1 bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah
                </button>
            </div>

            <div class="p-4 space-y-3 overflow-y-auto h-[300px] md:h-[400px] lg:h-[450px]">

                @forelse($pengumuman as $p)
                    <div 
                        @click="openPengumumanDetail({{ $p }})"
                        class="p-3 border rounded-xl hover:shadow transition bg-gray-50 cursor-pointer"
                    >
                        <h3 class="font-semibold text-indigo-600 text-sm">
                            {{ $p->judul }}
                        </h3>

                        <p class="text-xs text-gray-400 mb-1">
                            {{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}
                        </p>

                        <p class="text-sm text-gray-600 line-clamp-2">
                            {{ $p->isi }}
                        </p>
                    </div>
                @empty
                    <p class="text-gray-400 text-sm text-center">Belum ada pengumuman</p>
                @endforelse

            </div>
        </div>

        {{-- ================= AGENDA ================= --}}
        <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border overflow-hidden">

            <div class="flex justify-between items-center p-4 border-b">
                <h2 class="font-semibold text-gray-700">Agenda Sekolah</h2>
                <button @click="openAgenda=true"
                    class="inline-flex items-center gap-1 bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah
                </button>
            </div>

            {{-- Kalender --}}
            <div class="p-4" style="min-height:420px">
                <div id="calendar"></div>
            </div>

            {{-- Daftar agenda mendatang --}}
            @if($agenda->isNotEmpty())
            <div class="border-t px-4 py-3">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">Agenda Mendatang</p>
                <div class="grid md:grid-cols-2 gap-2 max-h-[180px] overflow-y-auto pr-1">
                    @foreach($agenda->sortBy('tanggal')->take(6) as $ag)
                    <div class="flex items-start gap-3 bg-slate-50 border border-slate-100 rounded-xl px-3 py-2.5 cursor-pointer hover:border-emerald-300 hover:bg-emerald-50 transition"
                         @click="selectedAgenda={title:'{{ addslashes($ag->judul) }}',date:'{{ $ag->tanggal }}',lokasi:'{{ addslashes($ag->lokasi ?? '') }}',description:'{{ addslashes($ag->deskripsi ?? '') }}'};window.selectedId='{{ $ag->id }}';detailAgenda=true">
                        <div class="bg-emerald-100 text-emerald-700 rounded-lg px-2 py-1 text-center min-w-[40px] shrink-0">
                            <div class="text-sm font-bold leading-none">{{ \Carbon\Carbon::parse($ag->tanggal)->format('d') }}</div>
                            <div class="text-[9px] font-semibold mt-0.5">{{ \Carbon\Carbon::parse($ag->tanggal)->translatedFormat('M') }}</div>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-semibold text-slate-700 line-clamp-1">{{ $ag->judul }}</p>
                            @if($ag->lokasi)
                            <p class="text-[10px] text-slate-400 mt-0.5 line-clamp-1">📍 {{ $ag->lokasi }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

    </div>

    {{-- ================= MODAL TAMBAH PENGUMUMAN ================= --}}
    <div x-show="openPengumuman"
        x-transition
        @click.self="openPengumuman=false"
        @keydown.escape.window="openPengumuman=false"
        class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">

        <div class="bg-white rounded-2xl p-5 md:p-6 w-[95%] max-w-md shadow-lg">

            <h2 class="font-bold text-lg mb-4">Tambah Pengumuman</h2>

            <form method="POST" action="{{ route('pengumuman.store') }}">
                @csrf

                <input type="text" name="judul" placeholder="Judul"
                    class="w-full border rounded-lg p-2 mb-3">

                <textarea name="isi" placeholder="Isi"
                    class="w-full border rounded-lg p-2 mb-3"></textarea>

                <input type="date" name="tanggal"
                    class="w-full border rounded-lg p-2 mb-4">

                <div class="flex justify-end gap-2">
                    <button type="button" @click="openPengumuman=false"
                        class="px-3 py-1 rounded bg-gray-200">
                        Batal
                    </button>

                    <button type="submit"
                        class="px-3 py-1 rounded bg-indigo-600 text-white">
                        Simpan
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- ================= MODAL TAMBAH AGENDA ================= --}}
    <div x-show="openAgenda"
        x-transition
        @click.self="openAgenda=false"
        @keydown.escape.window="openAgenda=false"
        class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">

        <div class="bg-white rounded-2xl p-6 w-[400px] shadow-lg">

            <h2 class="font-bold text-lg mb-4">Tambah Agenda</h2>

            <form method="POST" action="{{ route('agenda.store') }}">
                @csrf

                <input type="text" name="judul" placeholder="Judul"
                    class="w-full border rounded-lg p-2 mb-3">

                <textarea name="deskripsi" placeholder="Deskripsi"
                    class="w-full border rounded-lg p-2 mb-3"></textarea>

                <input type="date" name="tanggal"
                    class="w-full border rounded-lg p-2 mb-3">

                <input type="text" name="lokasi" placeholder="Lokasi"
                    class="w-full border rounded-lg p-2 mb-4">

                <div class="flex justify-end gap-2">
                    <button type="button" @click="openAgenda=false"
                        class="px-3 py-1 rounded bg-gray-200">
                        Batal
                    </button>

                    <button type="submit"
                        class="px-3 py-1 rounded bg-green-600 text-white">
                        Simpan
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- ================= MODAL DETAIL PENGUMUMAN ================= --}}
    <div x-show="detailPengumuman"
        x-transition
        @click.self="detailPengumuman=false"
        class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">

        <div class="bg-white rounded-2xl p-6 w-[400px] shadow-lg">

            <h2 class="font-bold mb-4">Detail Pengumuman</h2>

            <input x-model="selectedPengumuman.judul" class="w-full border p-2 mb-2 rounded">

            <input type="date" x-model="selectedPengumuman.tanggal" class="w-full border p-2 mb-2 rounded">

            <textarea x-model="selectedPengumuman.isi" class="w-full border p-2 mb-3 rounded"></textarea>

            <div class="flex justify-between">

                <button @click="deletePengumuman()" 
                    class="bg-red-600 text-white px-3 py-1 rounded">
                    Hapus
                </button>

                <div class="flex gap-2">
                    <button @click="detailPengumuman=false" 
                        class="bg-gray-200 px-3 py-1 rounded">
                        Batal
                    </button>

                    <button @click="updatePengumuman()" 
                        class="bg-indigo-600 text-white px-3 py-1 rounded">
                        Update
                    </button>
                </div>

            </div>

        </div>
    </div>

    {{-- ================= MODAL DETAIL AGENDA ================= --}}
    <div x-show="detailAgenda"
        x-transition
        @click.self="detailAgenda=false"
        class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">

        <div class="bg-white rounded-2xl p-6 w-[400px] shadow-lg">

            <h2 class="font-bold mb-4">Detail Agenda</h2>

            <input x-model="selectedAgenda.title" class="w-full border p-2 mb-2 rounded">
            <input type="date" x-model="selectedAgenda.date" class="w-full border p-2 mb-2 rounded">
            <input x-model="selectedAgenda.lokasi" class="w-full border p-2 mb-2 rounded">
            <textarea x-model="selectedAgenda.description" class="w-full border p-2 mb-3 rounded"></textarea>

            <div class="flex justify-between">
                <button @click="deleteAgenda()" class="bg-red-600 text-white px-3 py-1 rounded">
                    Hapus
                </button>

                <div class="flex gap-2">
                    <button @click="detailAgenda=false" class="bg-gray-200 px-3 py-1 rounded">
                        Batal
                    </button>

                    <button @click="updateAgenda()" class="bg-green-600 text-white px-3 py-1 rounded">
                        Update
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- AKTIVITAS GURU / STAF -->
        <!-- <div class="bg-white rounded-2xl shadow-sm border mt-6">
            <div class="p-4 border-b flex justify-between items-center">
                <h2 class="font-semibold text-slate-700">Aktivitas Guru / Staf</h2>
                <span class="text-xs text-slate-400">Realtime</span>
            </div>

            <div class="p-4 space-y-3 max-h-[300px] overflow-y-auto"> -->

                <!-- contoh data, nanti bisa dari database -->
                <!-- <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-white hover:shadow transition">
                    <div>
                        <p class="text-sm font-semibold text-slate-700">Guru A</p>
                        <p class="text-xs text-slate-500">Login ke sistem</p>
                    </div>
                    <span class="text-xs text-emerald-500">1 menit lalu</span>
                </div> -->

                <!-- <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-white hover:shadow transition">
                    <div>
                        <p class="text-sm font-semibold text-slate-700">Staf TU</p>
                        <p class="text-xs text-slate-500">Update data siswa</p>
                    </div>
                    <span class="text-xs text-emerald-500">5 menit lalu</span>
                </div>

                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-white hover:shadow transition">
                    <div>
                        <p class="text-sm font-semibold text-slate-700">Guru B</p>
                        <p class="text-xs text-slate-500">Input nilai</p>
                    </div>
                    <span class="text-xs text-slate-400">10 menit lalu</span>
                </div> -->

</div>

{{-- ================= SCRIPT ================= --}}
<script>
function dashboardApp() {
    return {
        openPengumuman:false,
        openAgenda:false,
        detailAgenda:false,
        detailPengumuman:false,
        selectedAgenda:{},
        selectedPengumuman:{},

        initCalendar() {
            // FullCalendar di-load dengan defer — tunggu sampai tersedia
            const renderCal = () => {
                if (typeof FullCalendar === 'undefined') {
                    setTimeout(renderCal, 100);
                    return;
                }
                let calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                    initialView: 'dayGridMonth',
                    height: window.innerWidth < 768 ? 400 : 450,
                    locale: 'id',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,listMonth'
                    },
                    buttonText: {
                        today: 'Hari Ini',
                        month: 'Bulan',
                        list:  'Daftar'
                    },

                    events: [
                        @foreach($agenda as $a)
                        {
                            id: "{{ $a->id }}",
                            title: "{{ $a->judul }}",
                            start: "{{ $a->tanggal }}",
                            description: "{{ $a->deskripsi }}",
                            lokasi: "{{ $a->lokasi }}"
                        },
                        @endforeach
                    ],

                    eventClick: (info) => {
                        this.selectedAgenda = {
                            title: info.event.title,
                            date: info.event.startStr,
                            lokasi: info.event.extendedProps.lokasi,
                            description: info.event.extendedProps.description
                        };
                        window.selectedId = info.event.id;
                        this.detailAgenda = true;
                    }
                });
                calendar.render();
            };
            renderCal();
        },
                    this.selectedAgenda = {
                        title: info.event.title,
                        date: info.event.startStr,
        openPengumumanDetail(data) {
            this.selectedPengumuman = {
                id: data.id,
                judul: data.judul,
                tanggal: data.tanggal,
                isi: data.isi
            };

            window.selectedPengumumanId = data.id;
            this.detailPengumuman = true;
        }
    }
}

// ================= AGENDA =================
function updateAgenda() {
    fetch('/agenda/' + window.selectedId, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            judul: document.querySelector('[x-model="selectedAgenda.title"]').value,
            tanggal: document.querySelector('[x-model="selectedAgenda.date"]').value,
            lokasi: document.querySelector('[x-model="selectedAgenda.lokasi"]').value,
            deskripsi: document.querySelector('[x-model="selectedAgenda.description"]').value
        })
    }).then(() => location.reload());
}

function deleteAgenda() {
    if (!confirm('Yakin hapus agenda?')) return;

    fetch('/agenda/' + window.selectedId, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    }).then(() => location.reload());
}

// ================= PENGUMUMAN =================
window.updatePengumuman = function () {
    let root = document.querySelector('[x-data]').__x.$data;

    console.log('UPDATE ID:', window.selectedPengumumanId); // debug

    fetch('/pengumuman/' + window.selectedPengumumanId, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            judul: root.selectedPengumuman.judul,
            tanggal: root.selectedPengumuman.tanggal,
            isi: root.selectedPengumuman.isi
        })
    })
    .then(res => res.json())
    .then(res => {
        console.log(res);
        location.reload();
    });
};


window.deletePengumuman = function () {
    console.log('DELETE ID:', window.selectedPengumumanId); // debug

    if (!confirm('Yakin hapus?')) return;

    fetch('/pengumuman/' + window.selectedPengumumanId, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(res => res.json())
    .then(() => location.reload());
};

// ================= QR TOKEN COPY =================
function copyDashboardToken() {
    const token = @json($qrToken ?? '');
    if (!token) return;
    navigator.clipboard.writeText(token).then(() => {
        const icon = document.getElementById('iconCopyDash');
        icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>`;
        setTimeout(() => {
            icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>`;
        }, 2000);
    });
}
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const loginData = @json($loginPerHari);

new Chart(
    document.getElementById('loginChart'),
    {
        type: 'line',

        data: {
            labels: loginData.map(x => x.tanggal),

            datasets: [
                {
                    label: 'Guru',
                    data: loginData.map(x => x.guru)
                },
                {
                    label: 'Guru & Wali Kelas',
                    data: loginData.map(x => x.walikelas)
                },
                {
                    label: 'Kepala Sekolah',
                    data: loginData.map(x => x.kepsek)
                },
                {
                    label: 'Orang Tua',
                    data: loginData.map(x => x.orangtua)
                }
            ]
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,

            plugins: {
                legend: {
                    position: window.innerWidth < 768 ? 'bottom' : 'top'
                }
            },

            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    }
);

</script>
</x-app-layout>
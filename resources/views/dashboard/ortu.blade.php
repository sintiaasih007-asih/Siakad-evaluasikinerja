<x-app-layout>

    <x-page-header
        title="Dashboard Orang Tua"
        subtitle="Pantau perkembangan akademik anak secara real-time"
    />

    @if(!$siswa)
    <div class="flex flex-col items-center justify-center py-20 text-center bg-white rounded-2xl border shadow-sm">
        <div class="w-16 h-16 bg-amber-100 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75M12 15.75h.007v.008H12v-.008z"/>
            </svg>
        </div>
        <p class="font-bold text-slate-700">Data siswa belum terhubung</p>
        <p class="text-sm text-slate-400 mt-1">Hubungi admin sekolah untuk menghubungkan akun Anda.</p>
    </div>

    @else

    {{-- ── HERO ─────────────────────────────────────────────────────────────── --}}
    <div class="relative overflow-hidden bg-gradient-to-r from-slate-900 via-indigo-800 to-indigo-600 rounded-3xl p-7 mb-6 shadow-xl">
        {{-- dekorasi --}}
        <div class="absolute right-0 top-0 opacity-10 pointer-events-none">
            <svg width="300" height="300"><circle cx="150" cy="150" r="150" fill="white"/></svg>
        </div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <p class="text-indigo-200 text-xs font-semibold uppercase tracking-widest mb-1">Selamat Datang</p>
                <h1 class="text-2xl md:text-3xl font-bold text-white">{{ Auth::user()->name }}</h1>
                <p class="text-indigo-200 text-sm mt-2">
                    Memantau perkembangan akademik
                    <strong class="text-white">{{ $siswa->nama }}</strong>
                </p>
                <div class="flex flex-wrap gap-3 mt-5">
                    <a href="{{ route('orangtua.perkembangan') }}"
                        class="bg-white text-indigo-700 px-4 py-2.5 rounded-xl font-bold text-sm shadow hover:scale-105 transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                        Perkembangan Anak
                    </a>
                    <a href="{{ route('orangtua.evaluasi.bulanan') }}"
                        class="bg-white/20 text-white border border-white/30 px-4 py-2.5 rounded-xl font-bold text-sm hover:bg-white/30 transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Evaluasi Bulanan
                    </a>
                </div>
            </div>
            {{-- kartu stats kecil --}}
            <div class="hidden md:block">
                <div class="bg-white/10 backdrop-blur border border-white/10 rounded-2xl p-5 w-64 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-indigo-100 text-xs">Kelas</span>
                        <span class="text-white font-bold">{{ $siswa->kelas->nama_kelas ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between border-t border-white/10 pt-3">
                        <span class="text-indigo-100 text-xs">Tahun Ajaran</span>
                        <span class="text-white font-bold">{{ $tahunAktif->tahun ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between border-t border-white/10 pt-3">
                        <span class="text-indigo-100 text-xs">Wali Kelas</span>
                        <span class="text-white font-semibold text-xs text-right">{{ $waliKelas->nama_guru ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between border-t border-white/10 pt-3">
                        <span class="text-indigo-100 text-xs">Nilai Rata-rata</span>
                        <span class="text-white font-bold text-lg">{{ $rataNilai }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── STATISTIK 4 KARTU ────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl border shadow-sm p-5">
            <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Rata-rata Nilai</p>
            <p class="text-3xl font-bold mt-2 {{ $rataNilai >= 75 ? 'text-teal-600' : 'text-amber-600' }}">
                {{ $rataNilai }}
            </p>
            <p class="text-xs text-slate-400 mt-1">dari seluruh penilaian</p>
        </div>
        <div class="bg-white rounded-2xl border shadow-sm p-5">
            <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Kehadiran</p>
            <p class="text-3xl font-bold mt-2 {{ $persentaseHadir >= 75 ? 'text-emerald-600' : 'text-rose-600' }}">
                {{ $persentaseHadir }}%
            </p>
            <p class="text-xs text-slate-400 mt-1">{{ $hadir }} dari total pertemuan</p>
        </div>
        <div class="bg-rose-50 rounded-2xl border border-rose-100 shadow-sm p-5">
            <p class="text-xs text-rose-600 font-semibold uppercase tracking-wide">Alpha</p>
            <p class="text-3xl font-bold text-rose-700 mt-2">{{ $alpha }}</p>
            <p class="text-xs text-rose-400 mt-1">{{ $alpha >= 3 ? '⚠ Perlu perhatian' : 'pertemuan tidak hadir' }}</p>
        </div>
        <div class="bg-blue-50 rounded-2xl border border-blue-100 shadow-sm p-5">
            <p class="text-xs text-blue-600 font-semibold uppercase tracking-wide">Izin + Sakit</p>
            <p class="text-3xl font-bold text-blue-700 mt-2">{{ $izin + $sakit }}</p>
            <p class="text-xs text-blue-400 mt-1">{{ $izin }} izin · {{ $sakit }} sakit</p>
        </div>
    </div>

    {{-- ── KONTEN UTAMA ─────────────────────────────────────────────────────── --}}
    <div class="grid xl:grid-cols-3 gap-6">

        {{-- Informasi Siswa --}}
        <div class="xl:col-span-2 bg-white rounded-3xl shadow-sm border p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-5">Informasi Siswa</h3>
            <div class="grid md:grid-cols-2 gap-4">
                @foreach([
                    ['label'=>'Nama Siswa',      'val'=>$siswa->nama],
                    ['label'=>'NIS',             'val'=>$siswa->nis],
                    ['label'=>'Jenis Kelamin',   'val'=>$siswa->jk=='L'?'Laki-laki':'Perempuan'],
                    ['label'=>'Kelas',           'val'=>$siswa->kelas->nama_kelas??'-'],
                    ['label'=>'Nama Orang Tua',  'val'=>$siswa->nama_ortu??'-'],
                    ['label'=>'No. HP Orang Tua','val'=>$siswa->no_hp_ortu??'-'],
                ] as $info)
                <div class="bg-slate-50 rounded-2xl p-4">
                    <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">{{ $info['label'] }}</p>
                    <p class="font-bold text-slate-800 mt-1">{{ $info['val'] }}</p>
                </div>
                @endforeach

                {{-- Wali Kelas -- info penting! --}}
                <div class="bg-indigo-50 rounded-2xl p-4 border border-indigo-100">
                    <p class="text-xs text-indigo-600 font-semibold uppercase tracking-wide">Wali Kelas</p>
                    <p class="font-bold text-indigo-800 mt-1">{{ $waliKelas->nama_guru ?? '—' }}</p>
                    @if($waliKelas)
                    <p class="text-xs text-indigo-500 mt-0.5">Kelas {{ $waliKelas->nama_kelas }}</p>
                    @endif
                </div>
                <div class="bg-teal-50 rounded-2xl p-4 border border-teal-100">
                    <p class="text-xs text-teal-600 font-semibold uppercase tracking-wide">Tahun Ajaran</p>
                    <p class="font-bold text-teal-800 mt-1">{{ $tahunAktif->tahun ?? '-' }}</p>
                    <p class="text-xs text-teal-500 mt-0.5">{{ ucfirst($tahunAktif->semester ?? '-') }}</p>
                </div>
            </div>

            {{-- Alamat --}}
            @if($siswa->alamat)
            <div class="mt-4 bg-slate-50 rounded-2xl p-4">
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide mb-1">Alamat</p>
                <p class="text-slate-700 text-sm">{{ $siswa->alamat }}</p>
            </div>
            @endif
        </div>

        {{-- Sidebar: akses cepat + status --}}
        <div class="space-y-5">

            {{-- Akses Cepat --}}
            <div class="bg-white rounded-3xl shadow-sm border p-6">
                <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wide mb-4">Akses Cepat</h3>
                <div class="space-y-2">
                    @foreach([
                        ['route'=>'orangtua.perkembangan',      'label'=>'Perkembangan Anak',     'icon'=>'trending-up',   'color'=>'teal'],
                        ['route'=>'orangtua.evaluasi.bulanan',  'label'=>'Evaluasi Bulanan',       'icon'=>'calendar-check-2','color'=>'emerald'],
                        ['route'=>'orangtua.evaluasi.semesteran','label'=>'Evaluasi Semesteran',   'icon'=>'book-marked',   'color'=>'indigo'],
                        ['route'=>'orangtua.nilai',             'label'=>'Nilai Akademik',          'icon'=>'book-check',    'color'=>'blue'],
                        ['route'=>'orangtua.karakter',          'label'=>'Penilaian Karakter',      'icon'=>'heart-handshake','color'=>'purple'],
                        ['route'=>'orangtua.absensi',           'label'=>'Rekap Kehadiran',         'icon'=>'clipboard-list','color'=>'amber'],
                    ] as $link)
                    <a href="{{ route($link['route']) }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 transition border border-transparent hover:border-slate-200 group">
                        <div class="w-8 h-8 rounded-lg bg-{{ $link['color'] }}-100 flex items-center justify-center shrink-0 group-hover:bg-{{ $link['color'] }}-200 transition">
                            <i data-lucide="{{ $link['icon'] }}" class="w-4 h-4 text-{{ $link['color'] }}-600"></i>
                        </div>
                        <span class="text-sm font-medium text-slate-700">{{ $link['label'] }}</span>
                        <svg class="w-4 h-4 text-slate-300 ml-auto group-hover:text-slate-500 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Status akademik --}}
            <div class="bg-white rounded-3xl shadow-sm border p-6">
                <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wide mb-4">Status Akademik</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 rounded-xl bg-emerald-50 border border-emerald-100">
                        <span class="text-xs text-slate-600 font-medium">Status Siswa</span>
                        <span class="bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full text-xs font-bold">Aktif</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-xl {{ $persentaseHadir >= 75 ? 'bg-teal-50 border border-teal-100' : 'bg-rose-50 border border-rose-100' }}">
                        <span class="text-xs text-slate-600 font-medium">Kehadiran</span>
                        <span class="font-bold text-sm {{ $persentaseHadir >= 75 ? 'text-teal-700' : 'text-rose-700' }}">
                            {{ $persentaseHadir }}%
                        </span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-xl {{ $rataNilai >= 75 ? 'bg-blue-50 border border-blue-100' : 'bg-amber-50 border border-amber-100' }}">
                        <span class="text-xs text-slate-600 font-medium">Nilai Rata-rata</span>
                        <span class="font-bold text-sm {{ $rataNilai >= 75 ? 'text-blue-700' : 'text-amber-700' }}">
                            {{ $rataNilai }}
                        </span>
                    </div>
                    @if($waliKelas)
                    <div class="p-3 rounded-xl bg-indigo-50 border border-indigo-100">
                        <p class="text-xs text-indigo-500 font-medium">Wali Kelas</p>
                        <p class="font-bold text-indigo-800 text-sm mt-0.5">{{ $waliKelas->nama_guru }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    @endif

</x-app-layout>

{{-- ═══════════════════════════════════════════════════════════
     SIAKAD — Sidebar Navigation
     Warna: from-blue-950 to-blue-900 (biru tua profesional)
     ═══════════════════════════════════════════════════════════ --}}

@php
    // Helper: cek apakah route aktif
    $isActive = fn($pattern) => request()->routeIs($pattern) || request()->is(ltrim($pattern, '/'));
@endphp

<div
    x-data="{ akademikOpen: true, laporanOpen: false }"
    :class="open ? 'w-64' : 'w-[70px]'"
    class="bg-gradient-to-b from-blue-950 to-blue-900
           text-white h-screen flex flex-col
           transition-all duration-300 ease-in-out
           shadow-2xl overflow-hidden select-none">

    {{-- ── LOGO / BRAND ───────────────────────────────────────── --}}
    <div class="flex items-center gap-3 px-4 py-5 border-b border-white/10 shrink-0">
        <div class="w-10 h-10 rounded-xl bg-white/10 border border-white/20
                    flex items-center justify-center shrink-0 overflow-hidden">
            <img src="{{ asset('images/logo-dilan.png') }}"
                 class="w-9 h-9 object-contain"
                 onerror="this.style.display='none'">
        </div>
        <div x-show="open" x-transition:enter="transition duration-200"
             x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             class="min-w-0">
            <h2 class="text-sm font-bold text-white leading-tight truncate">SIAKAD</h2>
            <p class="text-[10px] text-blue-300 leading-tight truncate">SMP Dianto Landong</p>
        </div>
    </div>

    {{-- ── PROFIL USER ────────────────────────────────────────── --}}
    <div x-show="open" x-transition.opacity
         class="flex items-center gap-3 px-4 py-3.5 mx-3 mt-4
                bg-white/8 rounded-xl border border-white/10 shrink-0">
        <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center
                    text-white font-bold text-sm uppercase shrink-0">
            {{ substr(Auth::user()->name, 0, 1) }}
        </div>
        <div class="min-w-0 flex-1">
            <p class="text-xs font-semibold text-white truncate">{{ Auth::user()->name }}</p>
            <p class="text-[10px] text-blue-300 capitalize truncate">
                {{ str_replace('_', ' ', Auth::user()->role) }}
            </p>
        </div>
    </div>

    {{-- ── MENU ───────────────────────────────────────────────── --}}
    <div class="flex-1 overflow-y-auto scrollbar-hide py-3 px-3 space-y-0.5">

{{-- ════════════════════════════════ ADMIN ════════════════════════════════ --}}
@if(Auth::user()->role == 'admin')

    {{-- Profil Sekolah --}}
    <a href="{{ route('profil-sekolah.index') }}"
       class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium
              transition-all duration-150
              {{ request()->routeIs('profil-sekolah.index') ? 'bg-white/15 text-white' : 'text-blue-200 hover:bg-white/8 hover:text-white' }}">
        <i data-lucide="building-2" class="w-4 h-4 shrink-0"></i>
        <span x-show="open" class="truncate">Profil Sekolah</span>
    </a>

    {{-- Divider --}}
    <div x-show="open" class="px-3 pt-4 pb-1">
        <p class="text-[10px] font-bold uppercase tracking-widest text-blue-400">Data Akademik</p>
    </div>

    {{-- DATA AKADEMIK dropdown --}}
    <div x-data="{ sub: true }">
        <button @click="sub = !sub"
            class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium
                   text-blue-200 hover:bg-white/8 hover:text-white transition-all duration-150">
            <div class="flex items-center gap-2.5">
                <i data-lucide="graduation-cap" class="w-4 h-4 shrink-0"></i>
                <span x-show="open" class="truncate">Data Akademik</span>
            </div>
            <svg x-show="open" :class="sub?'rotate-180':''"
                 class="w-3.5 h-3.5 transition-transform shrink-0"
                 fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div x-show="sub && open" x-transition class="ml-4 mt-1 space-y-0.5 pl-3 border-l border-white/10">
            @foreach([
                ['/siswa',       'users',          'Data Siswa'],
                ['/guru',        'user-check',     'Data Guru'],
                ['/kelas',       'layout-grid',    'Data Kelas'],
                ['/mapel',       'book-open',      'Mata Pelajaran'],
                ['/jadwal',      'calendar-days',  'Jadwal Pelajaran'],
                ['/tahun-ajaran','calendar-range', 'Tahun Ajaran'],
                ['/riwayat',     'history',        'Riwayat Kelas'],
                ['/alumni',      'user-minus',     'Alumni'],
            ] as [$href, $icon, $label])
            <a href="{{ $href }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-medium transition-all duration-150
                      {{ request()->is(ltrim($href,'/')) ? 'bg-white/15 text-white' : 'text-blue-300 hover:bg-white/8 hover:text-white' }}">
                <i data-lucide="{{ $icon }}" class="w-3.5 h-3.5 shrink-0"></i>
                {{ $label }}
            </a>
            @endforeach
        </div>
    </div>

    {{-- Divider --}}
    <div x-show="open" class="px-3 pt-4 pb-1">
        <p class="text-[10px] font-bold uppercase tracking-widest text-blue-400">Laporan</p>
    </div>

    {{-- LAPORAN dropdown --}}
    <div x-data="{ sub: false }">
        <button @click="sub = !sub"
            class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium
                   text-blue-200 hover:bg-white/8 hover:text-white transition-all duration-150">
            <div class="flex items-center gap-2.5">
                <i data-lucide="file-bar-chart" class="w-4 h-4 shrink-0"></i>
                <span x-show="open" class="truncate">Laporan Akademik</span>
            </div>
            <svg x-show="open" :class="sub?'rotate-180':''"
                 class="w-3.5 h-3.5 transition-transform shrink-0"
                 fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div x-show="sub && open" x-transition class="ml-4 mt-1 space-y-0.5 pl-3 border-l border-white/10">
            <a href="{{ route('laporan-absensi-guru.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-medium transition-all duration-150
                      {{ request()->routeIs('laporan-absensi-guru.index') ? 'bg-white/15 text-white' : 'text-blue-300 hover:bg-white/8 hover:text-white' }}">
                <i data-lucide="user-check" class="w-3.5 h-3.5 shrink-0"></i> Absensi Guru
            </a>
            <a href="{{ route('laporan-absensi-siswa.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-medium transition-all duration-150
                      {{ request()->routeIs('laporan-absensi-siswa.index') ? 'bg-white/15 text-white' : 'text-blue-300 hover:bg-white/8 hover:text-white' }}">
                <i data-lucide="users" class="w-3.5 h-3.5 shrink-0"></i> Absensi Siswa
            </a>
        </div>
    </div>

    {{-- Divider --}}
    <div x-show="open" class="px-3 pt-4 pb-1">
        <p class="text-[10px] font-bold uppercase tracking-widest text-blue-400">Sistem</p>
    </div>

    <a href="{{ route('users.index') }}"
       class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
              {{ request()->routeIs('users.*') ? 'bg-white/15 text-white' : 'text-blue-200 hover:bg-white/8 hover:text-white' }}">
        <i data-lucide="users-round" class="w-4 h-4 shrink-0"></i>
        <span x-show="open" class="truncate">Manajemen Users</span>
    </a>

@endif

{{-- ════════════════════════════════ GURU ════════════════════════════════ --}}
@if(Auth::user()->role == 'guru')

    <a href="{{ route('absensi-guru.index') }}"
       class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
              {{ request()->routeIs('absensi-guru.index') ? 'bg-white/15 text-white' : 'text-blue-200 hover:bg-white/8 hover:text-white' }}">
        <i data-lucide="user-check" class="w-4 h-4 shrink-0"></i>
        <span x-show="open" class="truncate">Absensi Saya</span>
    </a>

    <div x-show="open" class="px-3 pt-4 pb-1">
        <p class="text-[10px] font-bold uppercase tracking-widest text-blue-400">Penilaian Siswa</p>
    </div>

    @foreach([
        ['absensi.index',   '/absensi',         'clipboard-check',  'Absensi Siswa'],
        ['nilai.index',     '/nilai',            'book-check',       'Nilai Akademik'],
        ['sikap.index',     '/sikap',            'heart-handshake',  'Nilai Sikap'],
        ['kedisiplinan.index','/kedisiplinan',   'shield-check',     'Kedisiplinan'],
    ] as [$route, $href, $icon, $label])
    <a href="{{ $href }}"
       class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
              {{ request()->routeIs($route) ? 'bg-white/15 text-white' : 'text-blue-200 hover:bg-white/8 hover:text-white' }}">
        <i data-lucide="{{ $icon }}" class="w-4 h-4 shrink-0"></i>
        <span x-show="open" class="truncate">{{ $label }}</span>
    </a>
    @endforeach

    <div x-show="open" class="px-3 pt-4 pb-1">
        <p class="text-[10px] font-bold uppercase tracking-widest text-blue-400">Evaluasi</p>
    </div>

    <a href="{{ route('evaluasi.bulanan') }}"
       class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
              {{ request()->routeIs('evaluasi.bulanan') ? 'bg-white/15 text-white' : 'text-blue-200 hover:bg-white/8 hover:text-white' }}">
        <i data-lucide="calendar-check-2" class="w-4 h-4 shrink-0"></i>
        <span x-show="open" class="truncate">Evaluasi Bulanan</span>
    </a>
    <a href="{{ route('evaluasi.semesteran') }}"
       class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
              {{ request()->routeIs('evaluasi.semesteran') ? 'bg-white/15 text-white' : 'text-blue-200 hover:bg-white/8 hover:text-white' }}">
        <i data-lucide="book-marked" class="w-4 h-4 shrink-0"></i>
        <span x-show="open" class="truncate">Evaluasi Semesteran</span>
    </a>

@endif

{{-- ════════════════════════════ GURU & WALI KELAS ════════════════════════ --}}
@if(Auth::user()->role == 'guru&wali_kelas')

    <a href="{{ route('absensi-guru.index') }}"
       class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
              {{ request()->routeIs('absensi-guru.index') ? 'bg-white/15 text-white' : 'text-blue-200 hover:bg-white/8 hover:text-white' }}">
        <i data-lucide="user-check" class="w-4 h-4 shrink-0"></i>
        <span x-show="open" class="truncate">Absensi Saya</span>
    </a>

    <div x-show="open" class="px-3 pt-4 pb-1">
        <p class="text-[10px] font-bold uppercase tracking-widest text-blue-400">Penilaian Siswa</p>
    </div>

    @foreach([
        ['absensi.index',      '/absensi',      'clipboard-list',   'Absensi Siswa'],
        ['nilai.index',        '/nilai',         'book-check',       'Nilai Akademik'],
        ['sikap.index',        '/sikap',         'heart-handshake',  'Nilai Sikap'],
        ['kedisiplinan.index', '/kedisiplinan',  'shield-check',     'Kedisiplinan'],
    ] as [$route, $href, $icon, $label])
    <a href="{{ $href }}"
       class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
              {{ request()->routeIs($route) ? 'bg-white/15 text-white' : 'text-blue-200 hover:bg-white/8 hover:text-white' }}">
        <i data-lucide="{{ $icon }}" class="w-4 h-4 shrink-0"></i>
        <span x-show="open" class="truncate">{{ $label }}</span>
    </a>
    @endforeach

    <div x-show="open" class="px-3 pt-4 pb-1">
        <p class="text-[10px] font-bold uppercase tracking-widest text-blue-400">Evaluasi</p>
    </div>

    <a href="{{ route('evaluasi.bulanan') }}"
       class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
              {{ request()->routeIs('evaluasi.bulanan') ? 'bg-white/15 text-white' : 'text-blue-200 hover:bg-white/8 hover:text-white' }}">
        <i data-lucide="calendar-check-2" class="w-4 h-4 shrink-0"></i>
        <span x-show="open" class="truncate">Evaluasi Bulanan</span>
    </a>
    <a href="{{ route('evaluasi.semesteran') }}"
       class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
              {{ request()->routeIs('evaluasi.semesteran') ? 'bg-white/15 text-white' : 'text-blue-200 hover:bg-white/8 hover:text-white' }}">
        <i data-lucide="book-marked" class="w-4 h-4 shrink-0"></i>
        <span x-show="open" class="truncate">Evaluasi Semesteran</span>
    </a>

    <div x-show="open" class="px-3 pt-4 pb-1">
        <p class="text-[10px] font-bold uppercase tracking-widest text-blue-400">Wali Kelas</p>
    </div>

    @foreach([
        ['',                     '/data-wali-kelas',   'users-round',    'Data Kelas Binaan'],
        ['rekap.nilai.kelas',    '',                   'monitor-check',  'Monitoring Kelas'],
        ['rekap.evaluasi.kelas', '',                   'clipboard-list', 'Rekap Evaluasi'],
    ] as [$routeName, $href, $icon, $label])
    @php
        $isActiveItem = $routeName
            ? request()->routeIs($routeName)
            : request()->is(ltrim($href,'/'));
    @endphp
    <a href="{{ $routeName ? route($routeName) : $href }}"
       class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
              {{ $isActiveItem ? 'bg-white/15 text-white' : 'text-blue-200 hover:bg-white/8 hover:text-white' }}">
        <i data-lucide="{{ $icon }}" class="w-4 h-4 shrink-0"></i>
        <span x-show="open" class="truncate">{{ $label }}</span>
    </a>
    @endforeach

@endif

{{-- ════════════════════════════ KEPALA SEKOLAH ═══════════════════════════ --}}
@if(Auth::user()->role == 'kepala_sekolah')

    <div x-show="open" class="px-3 pb-1">
        <p class="text-[10px] font-bold uppercase tracking-widest text-blue-400">Monitoring</p>
    </div>

    <a href="/laporan"
       class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
              {{ request()->is('laporan*') ? 'bg-white/15 text-white' : 'text-blue-200 hover:bg-white/8 hover:text-white' }}">
        <i data-lucide="file-bar-chart" class="w-4 h-4 shrink-0"></i>
        <span x-show="open" class="truncate">Laporan Akademik</span>
    </a>
    <a href="/monitoring"
       class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
              {{ request()->is('monitoring*') ? 'bg-white/15 text-white' : 'text-blue-200 hover:bg-white/8 hover:text-white' }}">
        <i data-lucide="monitor-check" class="w-4 h-4 shrink-0"></i>
        <span x-show="open" class="truncate">Monitoring Guru</span>
    </a>

@endif

{{-- ════════════════════════════ ORANG TUA ════════════════════════════════ --}}
@if(Auth::user()->role == 'orang_tua')

    <div x-show="open" class="px-3 pb-1">
        <p class="text-[10px] font-bold uppercase tracking-widest text-blue-400">Kehadiran</p>
    </div>

    <a href="{{ route('orangtua.absensi') }}"
       class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
              {{ request()->routeIs('orangtua.absensi') ? 'bg-white/15 text-white' : 'text-blue-200 hover:bg-white/8 hover:text-white' }}">
        <i data-lucide="clipboard-list" class="w-4 h-4 shrink-0"></i>
        <span x-show="open" class="truncate">Rekap Kehadiran</span>
    </a>

    <div x-show="open" class="px-3 pt-4 pb-1">
        <p class="text-[10px] font-bold uppercase tracking-widest text-blue-400">Nilai & Karakter</p>
    </div>

    <a href="{{ route('orangtua.nilai') }}"
       class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
              {{ request()->routeIs('orangtua.nilai') ? 'bg-white/15 text-white' : 'text-blue-200 hover:bg-white/8 hover:text-white' }}">
        <i data-lucide="book-check" class="w-4 h-4 shrink-0"></i>
        <span x-show="open" class="truncate">Nilai Akademik</span>
    </a>
    <a href="{{ route('orangtua.karakter') }}"
       class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
              {{ request()->routeIs('orangtua.karakter') ? 'bg-white/15 text-white' : 'text-blue-200 hover:bg-white/8 hover:text-white' }}">
        <i data-lucide="heart-handshake" class="w-4 h-4 shrink-0"></i>
        <span x-show="open" class="truncate">Penilaian Karakter</span>
    </a>

    <div x-show="open" class="px-3 pt-4 pb-1">
        <p class="text-[10px] font-bold uppercase tracking-widest text-blue-400">Perkembangan</p>
    </div>

    <a href="{{ route('orangtua.perkembangan') }}"
       class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
              {{ request()->routeIs('orangtua.perkembangan') ? 'bg-white/15 text-white' : 'text-blue-200 hover:bg-white/8 hover:text-white' }}">
        <i data-lucide="trending-up" class="w-4 h-4 shrink-0"></i>
        <span x-show="open" class="truncate">Perkembangan Anak</span>
    </a>
    <a href="{{ route('orangtua.evaluasi.bulanan') }}"
       class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
              {{ request()->routeIs('orangtua.evaluasi.bulanan') ? 'bg-white/15 text-white' : 'text-blue-200 hover:bg-white/8 hover:text-white' }}">
        <i data-lucide="calendar-check-2" class="w-4 h-4 shrink-0"></i>
        <span x-show="open" class="truncate">Evaluasi Bulanan</span>
    </a>
    <a href="{{ route('orangtua.evaluasi.semesteran') }}"
       class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
              {{ request()->routeIs('orangtua.evaluasi.semesteran') ? 'bg-white/15 text-white' : 'text-blue-200 hover:bg-white/8 hover:text-white' }}">
        <i data-lucide="book-marked" class="w-4 h-4 shrink-0"></i>
        <span x-show="open" class="truncate">Evaluasi Semesteran</span>
    </a>

@endif

    </div>{{-- end menu --}}

    {{-- ── FOOTER ─────────────────────────────────────────────── --}}
    <div class="px-3 py-4 border-t border-white/10 shrink-0">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium
                       text-blue-300 hover:bg-red-500/20 hover:text-red-300 transition-all duration-150">
                <i data-lucide="log-out" class="w-4 h-4 shrink-0"></i>
                <span x-show="open" class="truncate">Keluar</span>
            </button>
        </form>
    </div>

</div>

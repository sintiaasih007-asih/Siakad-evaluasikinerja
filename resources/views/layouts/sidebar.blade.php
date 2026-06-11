{{-- ═══════════════════════════════════════════════════════════
     SIAKAD — Sidebar Navigation
     Submenu state disimpan di localStorage agar tidak reset saat reload
     ═══════════════════════════════════════════════════════════ --}}

@php
    $role = Auth::user()->role;
    $onAkademikPage = request()->is('siswa*','guru*','kelas*','mapel*','jadwal*','tahun-ajaran*','riwayat*','alumni*');
    $onLaporanPage  = request()->routeIs('laporan-absensi-guru.*','laporan-absensi-siswa.*');
@endphp

<div
    :class="sidebarOpen ? 'w-64' : 'w-[70px]'"
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
        <div x-show="sidebarOpen"
             x-transition:enter="transition duration-200 delay-75"
             x-transition:enter-start="opacity-0 -translate-x-2"
             x-transition:enter-end="opacity-100 translate-x-0"
             class="min-w-0">
            <h2 class="text-sm font-bold text-white leading-tight truncate">SIAKAD</h2>
            <p class="text-[10px] text-blue-300 leading-tight truncate">SMP Dianto Landong</p>
        </div>
    </div>

    {{-- ── PROFIL USER ────────────────────────────────────────── --}}
    <div x-show="sidebarOpen" x-transition.opacity
         class="flex items-center gap-3 px-4 py-3 mx-3 mt-3
                bg-white/8 rounded-xl border border-white/10 shrink-0">
        <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center
                    text-white font-bold text-sm uppercase shrink-0 shadow-sm">
            {{ substr(Auth::user()->name, 0, 1) }}
        </div>
        <div class="min-w-0 flex-1">
            <p class="text-xs font-semibold text-white truncate leading-tight">
                {{ Auth::user()->name }}
            </p>
            <p class="text-[10px] text-blue-300 capitalize truncate leading-tight mt-0.5">
                {{ str_replace('_', ' ', Auth::user()->role) }}
            </p>
        </div>
    </div>

    {{-- ── MENU AREA ───────────────────────────────────────────── --}}
    <div class="flex-1 overflow-y-auto scrollbar-hide py-3 px-3 space-y-0.5">

{{-- ══════════════════════════════════ ADMIN ══════════════════════════════ --}}
@if($role == 'admin')

    {{-- Profil Sekolah --}}
    <a href="{{ route('profil-sekolah.index') }}" @class([
        'nav-link',
        'active' => request()->routeIs('profil-sekolah.index'),
    ])>
        <i data-lucide="building-2" class="nav-icon"></i>
        <span x-show="sidebarOpen" class="truncate">Profil Sekolah</span>
    </a>

    {{-- SECTION: Data Akademik --}}
    <div x-show="sidebarOpen" class="section-label">Data Akademik</div>

    <div>
        {{-- Toggle button --}}
        <button @click="toggleSub('akademik')"
            class="nav-link w-full justify-between"
            :class="{'text-white bg-white/10': subOpen.akademik}">
            <div class="flex items-center gap-2.5">
                <i data-lucide="graduation-cap" class="nav-icon"></i>
                <span x-show="sidebarOpen" class="truncate">Data Akademik</span>
            </div>
            <svg x-show="sidebarOpen"
                 :class="subOpen.akademik ? 'rotate-180' : ''"
                 class="w-3.5 h-3.5 shrink-0 transition-transform duration-200 text-blue-400"
                 fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        {{-- Submenu --}}
        <div x-show="subOpen.akademik && sidebarOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-1"
             class="ml-4 mt-1 space-y-0.5 pl-3 border-l border-white/10">
            @foreach([
                ['/siswa',        'users',          'Data Siswa'],
                ['/guru',         'user-check',     'Data Guru'],
                ['/kelas',        'layout-grid',    'Data Kelas'],
                ['/mapel',        'book-open',      'Mata Pelajaran'],
                ['/jadwal',       'calendar-days',  'Jadwal Pelajaran'],
                ['/tahun-ajaran', 'calendar-range', 'Tahun Ajaran'],
                ['/riwayat',      'history',        'Riwayat Kelas'],
                ['/alumni',       'user-minus',     'Alumni'],
            ] as [$href, $icon, $label])
            <a href="{{ $href }}" @class([
                'sub-link',
                'active' => request()->is(ltrim($href, '/')),
            ])>
                <i data-lucide="{{ $icon }}" class="sub-icon"></i>
                {{ $label }}
            </a>
            @endforeach
        </div>
    </div>

    {{-- SECTION: Laporan --}}
    <div x-show="sidebarOpen" class="section-label mt-2">Laporan</div>

    <div>
        <button @click="toggleSub('laporan')"
            class="nav-link w-full justify-between"
            :class="{'text-white bg-white/10': subOpen.laporan}">
            <div class="flex items-center gap-2.5">
                <i data-lucide="file-bar-chart" class="nav-icon"></i>
                <span x-show="sidebarOpen" class="truncate">Laporan Akademik</span>
            </div>
            <svg x-show="sidebarOpen"
                 :class="subOpen.laporan ? 'rotate-180' : ''"
                 class="w-3.5 h-3.5 shrink-0 transition-transform duration-200 text-blue-400"
                 fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div x-show="subOpen.laporan && sidebarOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-1"
             class="ml-4 mt-1 space-y-0.5 pl-3 border-l border-white/10">
            <a href="{{ route('laporan-absensi-guru.index') }}" @class([
                'sub-link',
                'active' => request()->routeIs('laporan-absensi-guru.*'),
            ])>
                <i data-lucide="user-check" class="sub-icon"></i>Absensi Guru
            </a>
            <a href="{{ route('laporan-absensi-siswa.index') }}" @class([
                'sub-link',
                'active' => request()->routeIs('laporan-absensi-siswa.*'),
            ])>
                <i data-lucide="users" class="sub-icon"></i>Absensi Siswa
            </a>
        </div>
    </div>

    {{-- SECTION: Sistem --}}
    <div x-show="sidebarOpen" class="section-label mt-2">Sistem</div>

    <a href="{{ route('users.index') }}" @class([
        'nav-link',
        'active' => request()->routeIs('users.*'),
    ])>
        <i data-lucide="users-round" class="nav-icon"></i>
        <span x-show="sidebarOpen" class="truncate">Manajemen Users</span>
    </a>

@endif

{{-- ════════════════════════════════ GURU ════════════════════════════════ --}}
@if($role == 'guru')

    <a href="{{ route('absensi-guru.index') }}" @class(['nav-link','active'=>request()->routeIs('absensi-guru.index')])>
        <i data-lucide="fingerprint" class="nav-icon"></i>
        <span x-show="sidebarOpen" class="truncate">Absensi Saya</span>
    </a>

    <div x-show="sidebarOpen" class="section-label mt-2">Penilaian Siswa</div>

    @foreach([
        ['absensi.index',      '/absensi',      'clipboard-check', 'Absensi Siswa'],
        ['nilai.index',        '/nilai',         'book-check',      'Nilai Akademik'],
        ['sikap.index',        '/sikap',         'heart-handshake', 'Nilai Sikap'],
        ['kedisiplinan.index', '/kedisiplinan',  'shield-check',    'Kedisiplinan'],
    ] as [$rt,$href,$icon,$label])
    <a href="{{ $href }}" @class(['nav-link','active'=>request()->routeIs($rt)])>
        <i data-lucide="{{ $icon }}" class="nav-icon"></i>
        <span x-show="sidebarOpen" class="truncate">{{ $label }}</span>
    </a>
    @endforeach

    <div x-show="sidebarOpen" class="section-label mt-2">Evaluasi</div>

    <a href="{{ route('evaluasi.bulanan') }}" @class(['nav-link','active'=>request()->routeIs('evaluasi.bulanan')])>
        <i data-lucide="calendar-check-2" class="nav-icon"></i>
        <span x-show="sidebarOpen" class="truncate">Evaluasi Bulanan</span>
    </a>
    <a href="{{ route('evaluasi.semesteran') }}" @class(['nav-link','active'=>request()->routeIs('evaluasi.semesteran')])>
        <i data-lucide="book-marked" class="nav-icon"></i>
        <span x-show="sidebarOpen" class="truncate">Evaluasi Semesteran</span>
    </a>

@endif

{{-- ════════════════════════════ GURU & WALI KELAS ════════════════════════ --}}
@if($role == 'guru&wali_kelas')

    <a href="{{ route('absensi-guru.index') }}" @class(['nav-link','active'=>request()->routeIs('absensi-guru.index')])>
        <i data-lucide="fingerprint" class="nav-icon"></i>
        <span x-show="sidebarOpen" class="truncate">Absensi Saya</span>
    </a>

    <div x-show="sidebarOpen" class="section-label mt-2">Penilaian Siswa</div>

    @foreach([
        ['absensi.index',      '/absensi',      'clipboard-list',  'Absensi Siswa'],
        ['nilai.index',        '/nilai',         'book-check',      'Nilai Akademik'],
        ['sikap.index',        '/sikap',         'heart-handshake', 'Nilai Sikap'],
        ['kedisiplinan.index', '/kedisiplinan',  'shield-check',    'Kedisiplinan'],
    ] as [$rt,$href,$icon,$label])
    <a href="{{ $href }}" @class(['nav-link','active'=>request()->routeIs($rt)])>
        <i data-lucide="{{ $icon }}" class="nav-icon"></i>
        <span x-show="sidebarOpen" class="truncate">{{ $label }}</span>
    </a>
    @endforeach

    <div x-show="sidebarOpen" class="section-label mt-2">Evaluasi</div>

    <a href="{{ route('evaluasi.bulanan') }}" @class(['nav-link','active'=>request()->routeIs('evaluasi.bulanan')])>
        <i data-lucide="calendar-check-2" class="nav-icon"></i>
        <span x-show="sidebarOpen" class="truncate">Evaluasi Bulanan</span>
    </a>
    <a href="{{ route('evaluasi.semesteran') }}" @class(['nav-link','active'=>request()->routeIs('evaluasi.semesteran')])>
        <i data-lucide="book-marked" class="nav-icon"></i>
        <span x-show="sidebarOpen" class="truncate">Evaluasi Semesteran</span>
    </a>

    <div x-show="sidebarOpen" class="section-label mt-2">Wali Kelas</div>

    <a href="/data-wali-kelas" @class(['nav-link','active'=>request()->is('data-wali-kelas')])>
        <i data-lucide="users-round" class="nav-icon"></i>
        <span x-show="sidebarOpen" class="truncate">Data Kelas Binaan</span>
    </a>
    <a href="{{ route('rekap.nilai.kelas') }}" @class(['nav-link','active'=>request()->routeIs('rekap.nilai.kelas')])>
        <i data-lucide="monitor-check" class="nav-icon"></i>
        <span x-show="sidebarOpen" class="truncate">Monitoring Kelas</span>
    </a>
    <a href="{{ route('rekap.evaluasi.kelas') }}" @class(['nav-link','active'=>request()->routeIs('rekap.evaluasi.kelas')])>
        <i data-lucide="clipboard-list" class="nav-icon"></i>
        <span x-show="sidebarOpen" class="truncate">Rekap Evaluasi</span>
    </a>

@endif

{{-- ════════════════════════════ KEPALA SEKOLAH ═══════════════════════════ --}}
@if($role == 'kepala_sekolah')

    <div x-show="sidebarOpen" class="section-label">Akademik</div>

    <a href="{{ route('kepsek.laporan-akademik') }}" @class(['nav-link','active'=>request()->routeIs('kepsek.laporan-akademik')])>
        <i data-lucide="file-bar-chart" class="nav-icon"></i>
        <span x-show="sidebarOpen" class="truncate">Laporan Akademik</span>
    </a>
    <a href="{{ route('kepsek.hasil-evaluasi') }}" @class(['nav-link','active'=>request()->routeIs('kepsek.hasil-evaluasi')])>
        <i data-lucide="award" class="nav-icon"></i>
        <span x-show="sidebarOpen" class="truncate">Hasil Evaluasi Siswa</span>
    </a>

    <div x-show="sidebarOpen" class="section-label mt-2">Monitoring</div>

    <a href="{{ route('kepsek.monitoring-guru') }}" @class(['nav-link','active'=>request()->routeIs('kepsek.monitoring-guru')])>
        <i data-lucide="user-check" class="nav-icon"></i>
        <span x-show="sidebarOpen" class="truncate">Monitoring Guru</span>
    </a>
    <a href="{{ route('kepsek.monitoring-siswa') }}" @class(['nav-link','active'=>request()->routeIs('kepsek.monitoring-siswa')])>
        <i data-lucide="users" class="nav-icon"></i>
        <span x-show="sidebarOpen" class="truncate">Monitoring Siswa</span>
    </a>

@endif

{{-- ════════════════════════════ ORANG TUA ════════════════════════════════ --}}
@if($role == 'orang_tua')

    <div x-show="sidebarOpen" class="section-label">Kehadiran</div>

    <a href="{{ route('orangtua.absensi') }}" @class(['nav-link','active'=>request()->routeIs('orangtua.absensi')])>
        <i data-lucide="clipboard-list" class="nav-icon"></i>
        <span x-show="sidebarOpen" class="truncate">Rekap Kehadiran</span>
    </a>

    <div x-show="sidebarOpen" class="section-label mt-2">Nilai & Karakter</div>

    <a href="{{ route('orangtua.nilai') }}" @class(['nav-link','active'=>request()->routeIs('orangtua.nilai')])>
        <i data-lucide="book-check" class="nav-icon"></i>
        <span x-show="sidebarOpen" class="truncate">Nilai Akademik</span>
    </a>
    <a href="{{ route('orangtua.karakter') }}" @class(['nav-link','active'=>request()->routeIs('orangtua.karakter')])>
        <i data-lucide="heart-handshake" class="nav-icon"></i>
        <span x-show="sidebarOpen" class="truncate">Penilaian Karakter</span>
    </a>

    <div x-show="sidebarOpen" class="section-label mt-2">Perkembangan</div>

    <a href="{{ route('orangtua.perkembangan') }}" @class(['nav-link','active'=>request()->routeIs('orangtua.perkembangan')])>
        <i data-lucide="trending-up" class="nav-icon"></i>
        <span x-show="sidebarOpen" class="truncate">Perkembangan Anak</span>
    </a>
    <a href="{{ route('orangtua.evaluasi.bulanan') }}" @class(['nav-link','active'=>request()->routeIs('orangtua.evaluasi.bulanan')])>
        <i data-lucide="calendar-check-2" class="nav-icon"></i>
        <span x-show="sidebarOpen" class="truncate">Evaluasi Bulanan</span>
    </a>
    <a href="{{ route('orangtua.evaluasi.semesteran') }}" @class(['nav-link','active'=>request()->routeIs('orangtua.evaluasi.semesteran')])>
        <i data-lucide="book-marked" class="nav-icon"></i>
        <span x-show="sidebarOpen" class="truncate">Evaluasi Semesteran</span>
    </a>

@endif

    </div>{{-- /menu area --}}

    {{-- ── FOOTER: LOGOUT ─────────────────────────────────────── --}}
    <div class="px-3 py-4 border-t border-white/10 shrink-0">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="nav-link w-full text-blue-300 hover:bg-red-500/20 hover:text-red-300">
                <i data-lucide="log-out" class="nav-icon"></i>
                <span x-show="sidebarOpen" class="truncate">Keluar</span>
            </button>
        </form>
    </div>

</div>

{{-- ── STYLE: class utilitas sidebar ─────────────────────────────────────── --}}
<style>
/* Link navigasi utama */
.nav-link {
    display: flex;
    align-items: center;
    gap: 0.625rem;
    padding: 0.5rem 0.75rem;
    border-radius: 0.75rem;
    font-size: 0.8125rem;
    font-weight: 500;
    color: #bfdbfe; /* blue-200 */
    transition: background-color 0.15s, color 0.15s;
    cursor: pointer;
    background: transparent;
    border: none;
    text-align: left;
    width: 100%;
    white-space: nowrap;
    overflow: hidden;
}
.nav-link:hover,
.nav-link.active {
    background: rgba(255,255,255,0.12);
    color: #ffffff;
}
.nav-link.active {
    background: rgba(255,255,255,0.15);
    font-weight: 600;
}
.nav-icon {
    width: 1rem;
    height: 1rem;
    flex-shrink: 0;
}
/* Sub-link (di dalam dropdown) */
.sub-link {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.4rem 0.75rem;
    border-radius: 0.625rem;
    font-size: 0.75rem;
    font-weight: 500;
    color: #93c5fd; /* blue-300 */
    transition: background-color 0.15s, color 0.15s;
    width: 100%;
    white-space: nowrap;
    overflow: hidden;
}
.sub-link:hover,
.sub-link.active {
    background: rgba(255,255,255,0.12);
    color: #ffffff;
}
.sub-link.active {
    background: rgba(255,255,255,0.15);
    font-weight: 600;
}
.sub-icon {
    width: 0.875rem;
    height: 0.875rem;
    flex-shrink: 0;
}
/* Label section */
.section-label {
    padding: 0.875rem 0.75rem 0.25rem;
    font-size: 0.625rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: #60a5fa; /* blue-400 */
}
</style>

<div
    x-data="{
        akademikOpen:true,
        laporanOpen:false
    }"
    :class="open ? 'w-64' : 'w-20'"
    class="bg-gradient-to-b from-slate-900 to-slate-800 text-gray-200 h-screen p-6 transition-all duration-300 ease-in-out shadow-xl flex flex-col">

    {{-- LOGO --}}
    <div class="flex items-center gap-4 mb-10">
        <div class="flex items-center justify-center w-16 h-18 bg-white/10 rounded-xl shadow-inner">
            <img src="{{ asset('images/logo-dilan.png') }}" class="w-20 h-18 object-contain">
        </div>

        <div x-show="open" x-transition.opacity>
            <h2 class="text-sm font-bold text-white">SIAKAD</h2>
            <p class="text-xs text-gray-400">Evaluasi Kinerja Siswa</p>
            <p class="text-[11px] text-gray-500">SMP Dianto Landong</p>
        </div>
    </div>

    {{-- PROFILE --}}
    <div class="mb-6 pb-4 border-b border-slate-700" x-show="open">
        <p class="font-medium text-white">{{ Auth::user()->name }}</p>
        <p class="text-sm text-gray-400 capitalize">{{ Auth::user()->role }}</p>
    </div>

    <div class="flex-1 overflow-y-auto scrollbar-hide pr-1">
    <ul class="space-y-2 text-sm">

        {{-- ================================================= --}}
        {{-- ROLE ADMIN --}}
        {{-- ================================================= --}}
        @if(Auth::user()->role == 'admin')

            <!-- {{-- Dashboard --}}
            <li>
                <a href="/admin"
                   class="flex items-center gap-3 p-2 rounded-lg transition-all
                   {{ request()->is('admin*') ? 'bg-slate-700 text-white' : 'hover:bg-slate-700' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span x-show="open">Dashboard</span>
                </a>
            </li> -->

                    <li>
                        <a href="/profil-sekolah"
                           class="flex items-center gap-2 p-2 rounded hover:bg-slate-700">
                            <i data-lucide="users" class="w-4 h-4"></i>
                            <span>Profil Sekolah</span>
                        </a>
                    </li>

            {{-- DATA AKADEMIK --}}
            <li>
                <button @click="akademikOpen = !akademikOpen"
                    class="w-full flex items-center justify-between p-2 rounded-lg hover:bg-slate-700">

                    <div class="flex items-center gap-3">
                        <i data-lucide="graduation-cap" class="w-5 h-5"></i>
                        <span x-show="open">Data Akademik</span>
                    </div>

                    <span
                        x-show="open"
                        :class="akademikOpen ? 'rotate-180' : ''"
                        class="transition-transform duration-300">
                        ^
                    </span>
                </button>

                {{-- SUBMENU --}}
                <ul x-show="akademikOpen && open" x-transition class="ml-7 mt-2 space-y-1">

                    <li>
                        <a href="/siswa"
                           class="flex items-center gap-2 p-2 rounded hover:bg-slate-700">
                            <i data-lucide="users" class="w-4 h-4"></i>
                            <span>Data Siswa</span>
                        </a>
                    </li>

                    <li>
                        <a href="/guru"
                           class="flex items-center gap-2 p-2 rounded hover:bg-slate-700">
                            <i data-lucide="user-check" class="w-4 h-4"></i>
                            <span>Data Guru</span>
                        </a>
                    </li>

                    <li>
                        <a href="/kelas"
                           class="flex items-center gap-2 p-2 rounded hover:bg-slate-700">
                            <i data-lucide="school" class="w-4 h-4"></i>
                            <span>Data Kelas</span>
                        </a>
                    </li>

                    <li>
                        <a href="/mapel"
                           class="flex items-center gap-2 p-2 rounded hover:bg-slate-700">
                            <i data-lucide="book-open" class="w-4 h-4"></i>
                            <span>Data Mapel</span>
                        </a>
                    </li>

                    <li>
                        <a href="/jadwal"
                           class="flex items-center gap-2 p-2 rounded hover:bg-slate-700">
                            <i data-lucide="calendar-days" class="w-4 h-4"></i>
                            <span>Jadwal Pelajaran</span>
                        </a>
                    </li>

                    <li>
                        <a href="/tahun-ajaran"
                           class="flex items-center gap-2 p-2 rounded hover:bg-slate-700">
                            <i data-lucide="calendar-range" class="w-4 h-4"></i>
                            <span>Tahun Ajaran</span>
                        </a>
                    </li>

                    <li>
                        <a href="/riwayat"
                           class="flex items-center gap-2 p-2 rounded hover:bg-slate-700">
                            <i data-lucide="calendar-range" class="w-4 h-4"></i>
                            <span>Riwayat Kelas</span>
                        </a>
                    </li>

                    <li>
                        <a href="/alumni"
                           class="flex items-center gap-2 p-2 rounded hover:bg-slate-700">
                            <i data-lucide="calendar-range" class="w-4 h-4"></i>
                            <span>Alumni</span>
                        </a>
                    </li>

                </ul>
            </li>

            {{-- Laporan --}}
            <li>
                <button @click="laporanOpen = !laporanOpen"
                    class="w-full flex items-center justify-between p-2 rounded-lg hover:bg-slate-700">

                    <div class="flex items-center gap-3">
                        <i data-lucide="graduation-cap" class="w-5 h-5"></i>
                        <span x-show="open">Laporan Akademik</span>
                    </div>

                    <span
                        x-show="open"
                        :class="laporanOpen ? 'rotate-180' : ''"
                        class="transition-transform duration-300">
                        ^
                    </span>
                </button>

                {{-- SUBMENU --}}
                <ul x-show="laporanOpen && open" x-transition class="ml-7 mt-2 space-y-1">

                    <li>
                        <a href="/laporan-absensi-guru"
                           class="flex items-center gap-2 p-2 rounded hover:bg-slate-700">
                            <i data-lucide="users" class="w-4 h-4"></i>
                            <span>Data Absensi Guru</span>
                        </a>
                    </li>

                    <li>
                        <a href="/laporan-absensi-siswa"
                           class="flex items-center gap-2 p-2 rounded hover:bg-slate-700">
                            <i data-lucide="users" class="w-4 h-4"></i>
                            <span>Data Absensi Siswa</span>
                        </a>
                    </li>

                    </ul>
            </li>

            {{-- USERS --}}
            <li>
                <a href="/users"
                   class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-700">
                    <i data-lucide="users-round" class="w-5 h-5"></i>
                    <span x-show="open">Users</span>
                </a>
            </li>

        @endif


        {{-- ================================================= --}}
        {{-- ROLE GURU --}}
        {{-- ================================================= --}}
        @if(Auth::user()->role == 'guru')

            <!-- <li>
                <a href="/dashboard-guru"
                   class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-700">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span x-show="open">Dashboard</span>
                </a>
            </li> -->

            <li>
                <a href="/absensi-guru"
                   class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-700">
                    <i data-lucide="user-check" class="w-5 h-5"></i>
                    <span x-show="open">Absensi Guru</span>
                </a>
            </li>

            {{-- MENU PENILAIAN --}}
        <li x-data="{ penilaianOpen: true }">

            <button @click="penilaianOpen = !penilaianOpen"
                class="w-full flex items-center justify-between p-2 rounded-lg hover:bg-slate-700">

                <div class="flex items-center gap-3">
                    <i data-lucide="clipboard-check" class="w-5 h-5"></i>
                    <span x-show="open">Penilaian Siswa</span>
                </div>

                <span
                    x-show="open"
                    :class="penilaianOpen ? 'rotate-180' : ''"
                    class="transition-transform duration-300">
                    ^
                </span>
            </button>

            <ul x-show="penilaianOpen && open" x-transition class="ml-7 mt-2 space-y-1">


            <li>
                <a href="/absensi"
                   class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-700">
                    <i data-lucide="clipboard-check" class="w-5 h-5"></i>
                    <span x-show="open">Absensi Siswa</span>
                </a>
            </li>

            <li>
                <a href="/nilai"
                   class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-700">
                    <i data-lucide="book-check" class="w-5 h-5"></i>
                    <span x-show="open">Nilai Akademik</span>
                </a>
            </li>

            <li>
                <a href="/nilai-sikap"
                   class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-700">
                    <i data-lucide="heart-handshake" class="w-5 h-5"></i>
                    <span x-show="open">Nilai Sikap</span>
                </a>
            </li>

            <li>
                <a href="/nilai-kedisiplinan"
                   class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-700">
                    <i data-lucide="shield-check" class="w-5 h-5"></i>
                    <span x-show="open">Nilai Kedisiplinan</span>
                </a>
            </li>




        {{-- MENU EVALUASI --}}
        <li x-data="{ walikelasOpen: true }">

            <button @click="walikelasOpen = !walikelasOpen"
                class="w-full flex items-center justify-between p-2 rounded-lg hover:bg-slate-700">

                <div class="flex items-center gap-3">
                    <i data-lucide="book-check" class="w-5 h-5"></i>
                    <span x-show="open">Evaluasi Siswa</span>
                </div>

                <span
                    x-show="open"
                    :class="walikelasOpen ? 'rotate-180' : ''"
                    class="transition-transform duration-300">
                    ^
                </span>
            </button>

            <ul x-show="walikelasOpen && open" x-transition class="ml-7 mt-2 space-y-1">

                 <li>
                    <a href="/evaluasi-bulanan"
                        class="flex items-center gap-2 p-2 rounded hover:bg-slate-700">
                        <i data-lucide="calendar-check-2" class="w-4 h-4"></i>
                        <span>Evaluasi Bulanan</span>
                    </a>
                </li>

                <li>
                    <a href="/hasil-evaluasi-semesteran"
                        class="flex items-center gap-2 p-2 rounded hover:bg-slate-700">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                        <span>Evaluasi Semesteran</span>
                    </a>
                </li>

            </ul>
        </li>

        @endif

        {{-- ================================================= --}}
        {{-- ROLE GURU & WALI KELAS --}}
        {{-- ================================================= --}}
        @if(Auth::user()->role == 'guru&wali_kelas')

        <!-- <li>
            <a href="/dashboard-wakel"
                class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-700">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                <span x-show="open">Dashboard</span>
            </a>
        </li> -->

            <li>
                <a href="/absensi-guru"
                   class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-700">
                    <i data-lucide="user-check" class="w-5 h-5"></i>
                    <span x-show="open">Absensi Guru</span>
                </a>
            </li>

        {{-- MENU PENILAIAN --}}
        <li x-data="{ penilaianOpen: true }">

            <button @click="penilaianOpen = !penilaianOpen"
                class="w-full flex items-center justify-between p-2 rounded-lg hover:bg-slate-700">

                <div class="flex items-center gap-3">
                    <i data-lucide="clipboard-check" class="w-5 h-5"></i>
                    <span x-show="open">Penilaian Siswa</span>
                </div>

                <span
                    x-show="open"
                    :class="penilaianOpen ? 'rotate-180' : ''"
                    class="transition-transform duration-300">
                    ^
                </span>
            </button>

            <ul x-show="penilaianOpen && open" x-transition class="ml-7 mt-2 space-y-1">

                <li>
                    <a href="/absensi"
                        class="flex items-center gap-2 p-2 rounded hover:bg-slate-700">
                        <i data-lucide="clipboard-list" class="w-4 h-4"></i>
                        <span>Absensi Siswa</span>
                    </a>
                </li>

                <li>
                    <a href="/nilai"
                        class="flex items-center gap-2 p-2 rounded hover:bg-slate-700">
                        <i data-lucide="book-check" class="w-4 h-4"></i>
                        <span>Nilai Akademik</span>
                    </a>
                </li>

                <li>
                    <a href="/sikap"
                        class="flex items-center gap-2 p-2 rounded hover:bg-slate-700">
                        <i data-lucide="heart-handshake" class="w-4 h-4"></i>
                        <span>Nilai Sikap</span>
                    </a>
                </li>

                <li>
                    <a href="/kedisiplinan"
                        class="flex items-center gap-2 p-2 rounded hover:bg-slate-700">
                        <i data-lucide="shield-check" class="w-4 h-4"></i>
                        <span>Nilai Kedisiplinan</span>
                    </a>
                </li>

            </ul>
        </li>


        {{-- MENU EVALUASI --}}
        <li x-data="{ walikelasOpen: true }">

            <button @click="walikelasOpen = !walikelasOpen"
                class="w-full flex items-center justify-between p-2 rounded-lg hover:bg-slate-700">

                <div class="flex items-center gap-3">
                    <i data-lucide="book-check" class="w-5 h-5"></i>
                    <span x-show="open">Evaluasi Siswa</span>
                </div>

                <span
                    x-show="open"
                    :class="walikelasOpen ? 'rotate-180' : ''"
                    class="transition-transform duration-300">
                    ^
                </span>
            </button>

            <ul x-show="walikelasOpen && open" x-transition class="ml-7 mt-2 space-y-1">

                 <li>
                    <a href="/evaluasi-bulanan"
                        class="flex items-center gap-2 p-2 rounded hover:bg-slate-700">
                        <i data-lucide="calendar-check-2" class="w-4 h-4"></i>
                        <span>Evaluasi Bulanan</span>
                    </a>
                </li>

                <li>
                    <a href="/hasil-evaluasi-semesteran"
                        class="flex items-center gap-2 p-2 rounded hover:bg-slate-700">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                        <span>Evaluasi Semesteran</span>
                    </a>
                </li>

            </ul>
        </li>

        {{-- MENU WALI KELAS --}}
        <li x-data="{ walikelasOpen: true }">

            <button @click="walikelasOpen = !walikelasOpen"
                class="w-full flex items-center justify-between p-2 rounded-lg hover:bg-slate-700">

                <div class="flex items-center gap-3">
                    <i data-lucide="users" class="w-5 h-5"></i>
                    <span x-show="open">Wali Kelas</span>
                </div>

                <span
                    x-show="open"
                    :class="walikelasOpen ? 'rotate-180' : ''"
                    class="transition-transform duration-300">
                    ^
                </span>
            </button>

            <ul x-show="walikelasOpen && open" x-transition class="ml-7 mt-2 space-y-1">

                <li>
                    <a href="/data-wali-kelas"
                        class="flex items-center gap-2 p-2 rounded hover:bg-slate-700">
                        <i data-lucide="users-round" class="w-4 h-4"></i>
                        <span>Data Kelas Binaan</span>
                    </a>
                </li>

                <li>
                    <a href="/rekap-nilai-kelas"
                        class="flex items-center gap-2 p-2 rounded hover:bg-slate-700">
                        <i data-lucide="file-bar-chart" class="w-4 h-4"></i>
                        <span>Rekap Nilai Kelas</span>
                    </a>
                </li>

                <li>
                    <a href="/rekap-evaluasi-kelas"
                        class="flex items-center gap-2 p-2 rounded hover:bg-slate-700">
                        <i data-lucide="clipboard-list" class="w-4 h-4"></i>
                        <span>Rekap Evaluasi Kelas</span>
                    </a>
                </li>

                <li>
                    <a href="/laporan-wali-kelas"
                        class="flex items-center gap-2 p-2 rounded hover:bg-slate-700">
                        <i data-lucide="printer" class="w-4 h-4"></i>
                        <span>Laporan Wali Kelas</span>
                    </a>
                </li>

            </ul>
        </li>

        @endif


        {{-- ================================================= --}}
        {{-- ROLE KEPALA SEKOLAH --}}
        {{-- ================================================= --}}
        @if(Auth::user()->role == 'kepala_sekolah')

            <!-- <li>
                <a href="/dashboard-kepsek"
                   class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-700">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span x-show="open">Dashboard</span>
                </a>
            </li> -->

            <li>
                <a href="/laporan"
                   class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-700">
                    <i data-lucide="file-bar-chart" class="w-5 h-5"></i>
                    <span x-show="open">Laporan Akademik</span>
                </a>
            </li>

            <li>
                <a href="/monitoring"
                   class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-700">
                    <i data-lucide="monitor-check" class="w-5 h-5"></i>
                    <span x-show="open">Monitoring Guru</span>
                </a>
            </li>

        @endif


        {{-- ================================================= --}}
        {{-- ROLE ORANG TUA --}}
        {{-- ================================================= --}}
        @if(Auth::user()->role == 'orang_tua')

            <!-- <li>
                <a href="/dashboard-orangtua"
                   class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-700">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span x-show="open">Dashboard</span>
                </a>
            </li> -->


            <li>
                <a href="/orang-tua/absensi-anak"
                   class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-700">
                    <i data-lucide="clipboard-list" class="w-5 h-5"></i>
                    <span x-show="open">Rekap Kehadiran Siswa</span>
                </a>
            </li>

            
            {{-- MENU NILAI SISWA --}}
            <li x-data="{ nilaiOpen: true }">

                <button @click="nilaiOpen = !nilaiOpen"
                    class="w-full flex items-center justify-between p-2 rounded-lg hover:bg-slate-700">

                    <div class="flex items-center gap-3">
                        <i data-lucide="book-open-check" class="w-5 h-5"></i>
                        <span x-show="open">Nilai Siswa</span>
                    </div>

                    <span
                        x-show="open"
                        :class="nilaiOpen ? 'rotate-180' : ''"
                        class="transition-transform duration-300">
                        ^
                    </span>

                </button>

                {{-- SUB MENU --}}
                <ul x-show="nilaiOpen && open"
                    x-transition
                    class="ml-7 mt-2 space-y-1">

                    <li>
                        <a href="/orang-tua/nilai-anak"
                            class="flex items-center gap-2 p-2 rounded hover:bg-slate-700">

                            <i data-lucide="book-check" class="w-4 h-4"></i>

                            <span>Nilai Akademik</span>
                        </a>
                    </li>

                    <li>
                        <a href="/orang-tua/penilaian-karakter"
                            class="flex items-center gap-2 p-2 rounded hover:bg-slate-700">

                            <i data-lucide="heart-handshake" class="w-4 h-4"></i>

                            <span>Penilaian Karakter</span>
                        </a>
                    </li>

                </ul>

            </li>

            <li>
                <a href="/perkembangan-anak"
                   class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-700">
                    <i data-lucide="line-chart" class="w-5 h-5"></i>
                    <span x-show="open">Perkembangan Anak</span>
                </a>
            </li>

            <li>
                <a href="/hasil-evaluasi-bulanan"
                class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-700">
                    <i data-lucide="calendar-check-2" class="w-5 h-5"></i>
                    <span x-show="open">Hasil Evaluasi Bulanan</span>
                </a>
            </li>

            <li>
                <a href="/hasil-evaluasi-semesteran"
                class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-700">
                    <i data-lucide="file-text" class="w-5 h-5"></i>
                    <span x-show="open">Hasil Evaluasi Semesteran</span>
                </a>
            </li>

        @endif

    </ul>

</div>
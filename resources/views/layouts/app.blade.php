<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SIAKAD') }} — Evaluasi Kinerja Siswa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

@php
    // Nama tampilan: orang tua pakai nama_ortu
    $displayName = Auth::user()->name;
    if (Auth::user()->role === 'orang_tua' && Auth::user()->siswa_id) {
        $ortuName = \DB::table('siswas')->where('id', Auth::user()->siswa_id)->value('nama_ortu');
        if ($ortuName) $displayName = $ortuName;
    }
    $roleLabel = match(Auth::user()->role) {
        'admin'           => 'Administrator',
        'guru'            => 'Guru',
        'guru&wali_kelas' => 'Guru & Wali Kelas',
        'kepala_sekolah'  => 'Kepala Sekolah',
        'orang_tua'       => 'Orang Tua',
        default           => ucwords(str_replace('_', ' ', Auth::user()->role)),
    };
@endphp

<body
    x-data="{
        sidebarOpen: window.innerWidth >= 1024,
        mobileOpen: false,
        init() {
            this.$watch('mobileOpen', v => {
                document.body.style.overflow = v ? 'hidden' : '';
            });
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 1024) this.mobileOpen = false;
            });
        }
    }"
    class="h-full bg-slate-50 antialiased"
    style="font-family: 'Inter', sans-serif;">

    {{-- Overlay mobile --}}
    <div x-show="mobileOpen"
         x-transition:enter="transition duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="mobileOpen = false"
         class="fixed inset-0 z-30 bg-blue-950/60 backdrop-blur-sm lg:hidden"
         style="display:none;">
    </div>

    <div class="flex h-screen overflow-hidden">

        {{-- ── SIDEBAR ────────────────────────────────────────────────── --}}
        <aside
            :class="{
                'w-64': sidebarOpen,
                'w-[70px]': !sidebarOpen,
                'translate-x-0': mobileOpen || sidebarOpen,
                '-translate-x-full': !mobileOpen && !sidebarOpen
            }"
            class="fixed inset-y-0 left-0 z-40 flex-shrink-0
                   transition-all duration-300 ease-in-out
                   lg:relative lg:translate-x-0 lg:flex">
            @include('layouts.sidebar')
        </aside>

        {{-- ── MAIN WRAPPER ───────────────────────────────────────────── --}}
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

            {{-- ── NAVBAR ─────────────────────────────────────────────── --}}
            <header class="shrink-0 z-20 bg-white border-b border-slate-200 shadow-sm">
                <div class="flex items-center justify-between h-[60px] px-4 lg:px-5">

                    {{-- KIRI: toggle + breadcrumb --}}
                    <div class="flex items-center gap-3">

                        {{-- Mobile hamburger --}}
                        <button @click="mobileOpen = !mobileOpen"
                            class="lg:hidden p-2 rounded-lg text-slate-500 hover:bg-slate-100 hover:text-slate-700 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>

                        {{-- Desktop collapse --}}
                        <button @click="sidebarOpen = !sidebarOpen"
                            class="hidden lg:flex p-2 rounded-lg text-slate-500 hover:bg-slate-100 hover:text-slate-700 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>

                        {{-- Logo & nama sistem --}}
                        <a href="{{ route('dashboard') }}"
                           class="flex items-center gap-2.5 group">
                            <div class="w-8 h-8 rounded-lg bg-blue-800 flex items-center justify-center
                                        shadow-sm shadow-blue-900/30 transition-transform group-hover:scale-105">
                                <x-application-logo class="w-4.5 h-4.5 text-white fill-current"/>
                            </div>
                            <div class="hidden sm:block">
                                <p class="text-[13px] font-bold text-slate-800 leading-none">SIAKAD</p>
                                <p class="text-[10px] text-slate-400 leading-none mt-0.5">SMP Dianto Landong</p>
                            </div>
                        </a>

                        {{-- Breadcrumb separator (desktop) --}}
                        <div class="hidden lg:flex items-center gap-2 text-slate-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>

                        {{-- Dashboard link (desktop) --}}
                        <a href="{{ route('dashboard') }}"
                           class="hidden lg:flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium transition-colors
                                  {{ request()->routeIs('dashboard')
                                     ? 'bg-blue-50 text-blue-700'
                                     : 'text-slate-500 hover:bg-slate-100 hover:text-slate-700' }}">
                            <i data-lucide="layout-dashboard" class="w-3.5 h-3.5"></i>
                            Dashboard
                        </a>

                    </div>

                    {{-- KANAN: tanggal + user dropdown --}}
                    <div class="flex items-center gap-3">

                        {{-- Tanggal (desktop) --}}
                        <div class="hidden md:flex items-center gap-1.5 text-xs text-slate-500
                                    bg-slate-100 px-3 py-1.5 rounded-lg">
                            <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400"></i>
                            {{ now()->translatedFormat('d M Y') }}
                        </div>

                        {{-- User dropdown --}}
                        <div x-data="{ open: false }" class="relative">

                            <button @click="open = !open"
                                class="flex items-center gap-2.5 pl-1 pr-2.5 py-1.5
                                       bg-slate-50 hover:bg-slate-100
                                       border border-slate-200 hover:border-slate-300
                                       rounded-xl transition-all duration-200">
                                {{-- Avatar --}}
                                <div class="w-7 h-7 rounded-lg bg-blue-800 text-white
                                            flex items-center justify-center
                                            text-xs font-bold uppercase">
                                    {{ substr($displayName, 0, 1) }}
                                </div>
                                {{-- Nama & role --}}
                                <div class="hidden sm:block text-left leading-tight">
                                    <p class="text-xs font-semibold text-slate-700 max-w-[120px] truncate">
                                        {{ $displayName }}
                                    </p>
                                    <p class="text-[10px] text-slate-400">{{ $roleLabel }}</p>
                                </div>
                                {{-- Chevron --}}
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-slate-400 hidden sm:block"></i>
                            </button>

                            {{-- Dropdown panel --}}
                            <div x-show="open"
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 top-full mt-1.5 w-56
                                        bg-white rounded-xl shadow-lg shadow-slate-200/80
                                        border border-slate-200 overflow-hidden z-50"
                                 style="display:none;">

                                {{-- Header dropdown --}}
                                <div class="px-4 py-3.5 bg-gradient-to-br from-blue-900 to-blue-800">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-9 h-9 rounded-lg bg-white/20 text-white
                                                    flex items-center justify-center
                                                    text-sm font-bold uppercase border border-white/20">
                                            {{ substr($displayName, 0, 1) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-white truncate">{{ $displayName }}</p>
                                            <p class="text-[11px] text-blue-200 truncate">{{ Auth::user()->email }}</p>
                                            <p class="text-[10px] text-blue-300 font-medium mt-0.5">{{ $roleLabel }}</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Menu --}}
                                <div class="p-1.5">
                                    <a href="{{ route('profile.edit') }}"
                                       class="flex items-center gap-2.5 px-3 py-2 rounded-lg
                                              text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-800
                                              transition-colors">
                                        <i data-lucide="user" class="w-4 h-4 text-slate-400"></i>
                                        Edit Profil
                                    </a>
                                    <div class="my-1 border-t border-slate-100"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="w-full flex items-center gap-2.5 px-3 py-2 rounded-lg
                                                   text-sm text-red-600 hover:bg-red-50
                                                   transition-colors">
                                            <i data-lucide="log-out" class="w-4 h-4"></i>
                                            Keluar
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </header>

            {{-- ── KONTEN UTAMA ────────────────────────────────────────── --}}
            <main class="flex-1 overflow-y-auto overflow-x-hidden p-4 lg:p-6">
                {{ $slot }}
            </main>

        </div>
    </div>

    {{-- Icons --}}
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <script>if(typeof lucide !== 'undefined') lucide.createIcons();</script>

    {{-- FullCalendar (lazy load) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js" defer></script>

    {{-- Toast --}}
    <div id="toast"
         class="fixed bottom-4 right-4 z-[60] hidden
                bg-slate-900 text-white text-sm
                px-4 py-2.5 rounded-xl shadow-xl
                flex items-center gap-2">
    </div>

    {{-- Flash session alert --}}
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const t = document.getElementById('toast');
            t.innerHTML = `<svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>${@json(session('success'))}`;
            t.classList.remove('hidden');
            setTimeout(() => t.classList.add('hidden'), 3500);
        });
    </script>
    @endif

</body>
</html>

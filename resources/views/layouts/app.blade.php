<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body x-data="{ open: true }"
      class="font-sans antialiased bg-slate-100 overflow-hidden">

    <div class="flex h-screen overflow-hidden">

        {{-- SIDEBAR --}}
        <aside
            :class="open ? 'w-64' : 'w-20'"
            class="fixed left-0 top-0 z-40 h-screen transition-all duration-300 ease-in-out">

            @include('layouts.sidebar')

        </aside>

        {{-- CONTENT --}}
        <div
            :class="open ? 'ml-64' : 'ml-20'"
            class="flex-1 flex flex-col transition-all duration-300 ease-in-out">

            {{-- NAVBAR --}}
            <nav
                :class="open ? 'left-64' : 'left-20'"
                class="fixed top-0 right-0 z-30
                       bg-white/90 backdrop-blur-xl
                       border-b border-slate-200
                       shadow-sm
                       transition-all duration-300 ease-in-out">

                <div class="px-6 lg:px-8">

                    <div class="flex items-center justify-between h-[72px]">

                        {{-- LEFT --}}
                        <div class="flex items-center gap-8">

                            {{-- LOGO --}}
                            <a href="{{ route('dashboard') }}"
                               class="flex items-center gap-4 group">

                                {{-- ICON --}}
                                <div class="w-12 h-12 rounded-2xl
                                            bg-gradient-to-br from-indigo-600 via-indigo-500 to-slate-800
                                            flex items-center justify-center
                                            shadow-lg shadow-indigo-200/50
                                            transition-all duration-300
                                            group-hover:scale-105">

                                    <x-application-logo
                                        class="w-6 h-6 text-white fill-current"/>

                                </div>

                                {{-- TITLE --}}
                                <div class="leading-tight">

                                    <h1 class="text-[15px] font-bold tracking-wide text-slate-800">
                                        Sistem Informasi SMP
                                    </h1>

                                    <p class="text-xs text-slate-500 font-medium">
                                        Evaluasi Kinerja Siswa
                                    </p>

                                </div>

                            </a>

                            {{-- DIVIDER --}}
                            <div class="hidden lg:block h-8 w-px bg-slate-200"></div>

                            {{-- MENU --}}
                            <div class="hidden md:flex items-center gap-3">

                                <a href="{{ route('dashboard') }}"
                                   class="flex items-center gap-2
                                          px-4 py-2.5 rounded-2xl
                                          text-sm font-semibold
                                          transition-all duration-300

                                          {{ request()->routeIs('dashboard')
                                            ? 'bg-gradient-to-r from-indigo-600 to-indigo-500 text-white shadow-lg shadow-indigo-200/50'
                                            : 'text-slate-600 hover:bg-slate-100 hover:text-indigo-600'
                                          }}">

                                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i>

                                    Dashboard

                                </a>

                            </div>

                        </div>

                        {{-- RIGHT --}}
                        <div class="flex items-center gap-4">

                            {{-- USER INFO --}}
                            <div class="hidden md:block text-right">

                                <h2 class="text-sm font-semibold text-slate-800 leading-tight">
                                    {{ Auth::user()->name }}
                                </h2>

                                <p class="text-xs text-slate-500 capitalize">
                                    {{ str_replace('_', ' ', Auth::user()->role) }}
                                </p>

                            </div>

                            {{-- DROPDOWN --}}
                            <div x-data="{ dropdownOpen: false }" class="relative">

                                {{-- BUTTON --}}
                                <button
                                    @click="dropdownOpen = !dropdownOpen"

                                    class="flex items-center gap-3
                                           pl-2 pr-3 py-2
                                           rounded-2xl
                                           bg-white/80
                                           border border-slate-200
                                           hover:border-indigo-300
                                           hover:shadow-lg hover:shadow-slate-200/50
                                           transition-all duration-300">

                                    {{-- AVATAR --}}
                                    <div class="w-10 h-10 rounded-xl
                                                bg-gradient-to-br from-indigo-500 to-slate-700
                                                text-white
                                                flex items-center justify-center
                                                font-bold uppercase
                                                shadow-md">

                                        {{ substr(Auth::user()->name,0,1) }}

                                    </div>

                                    {{-- ICON --}}
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="w-4 h-4 text-slate-500"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor">

                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M19 9l-7 7-7-7"/>

                                    </svg>

                                </button>

                                {{-- DROPDOWN CONTENT --}}
                                <div
                                    x-show="dropdownOpen"
                                    @click.away="dropdownOpen = false"
                                    x-transition

                                    class="absolute right-0 mt-4 w-64
                                           bg-white/95 backdrop-blur-xl
                                           rounded-3xl
                                           shadow-2xl
                                           border border-slate-100
                                           overflow-hidden z-50">

                                    {{-- HEADER --}}
                                    <div class="px-5 py-5
                                                bg-gradient-to-r from-slate-50 to-white
                                                border-b border-slate-100">

                                        <div class="flex items-center gap-3">

                                            <div class="w-12 h-12 rounded-2xl
                                                        bg-gradient-to-br from-indigo-500 to-slate-700
                                                        text-white
                                                        flex items-center justify-center
                                                        font-bold text-lg uppercase">

                                                {{ substr(Auth::user()->name,0,1) }}

                                            </div>

                                            <div>

                                                <h3 class="text-sm font-bold text-slate-800">
                                                    {{ Auth::user()->name }}
                                                </h3>

                                                <p class="text-xs text-slate-500">
                                                    {{ Auth::user()->email }}
                                                </p>

                                            </div>

                                        </div>

                                    </div>

                                    {{-- MENU --}}
                                    <div class="p-3 space-y-1">

                                        <a href="{{ route('profile.edit') }}"
                                           class="flex items-center gap-3
                                                  px-4 py-3 rounded-2xl
                                                  text-sm text-slate-700
                                                  hover:bg-slate-100
                                                  transition-all duration-200">

                                            <i data-lucide="user-circle" class="w-4 h-4"></i>

                                            Profile

                                        </a>

                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf

                                            <button type="submit"
                                                    class="w-full flex items-center gap-3
                                                           px-4 py-3 rounded-2xl
                                                           text-sm text-red-500
                                                           hover:bg-red-50
                                                           transition-all duration-200">

                                                <i data-lucide="log-out" class="w-4 h-4"></i>

                                                Logout

                                            </button>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </nav>

            {{-- MAIN CONTENT --}}
            <main class="flex-1 overflow-y-auto pt-[88px] p-6">
                {{ $slot }}
            </main>

        </div>

    </div>

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

    <div id="toast" class="fixed bottom-5 right-5 hidden bg-black text-white px-4 py-2 rounded shadow"></div>

</body>
</html>
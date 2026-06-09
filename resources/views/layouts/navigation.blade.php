<nav class="bg-white border-b border-slate-200 shadow-sm">

    <div class="px-6 lg:px-8">

        <div class="flex items-center justify-between h-16">

            {{-- LEFT --}}
            <div class="flex items-center gap-10">

                {{-- LOGO --}}
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-4">

                    <div class="w-11 h-11 rounded-2xl
                                bg-gradient-to-br from-indigo-600 to-slate-800
                                flex items-center justify-center
                                shadow-lg">

                        <x-application-logo
                            class="w-6 h-6 text-white fill-current"/>
                    </div>

                    <div>
                        <h1 class="text-sm font-bold text-slate-800">
                            Sistem Informasi SMP
                        </h1>

                        <p class="text-xs text-slate-500">
                            Evaluasi Kinerja Siswa
                        </p>
                    </div>
                </a>

                {{-- MENU --}}
                <div class="hidden md:flex items-center gap-2">

                    <a href="{{ route('dashboard') }}"
                       class="flex items-center gap-2
                              px-4 py-2 rounded-xl
                              text-sm font-semibold
                              transition-all duration-200

                              {{ request()->routeIs('dashboard')
                                ? 'bg-indigo-600 text-white shadow-md'
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

                    <h2 class="text-sm font-semibold text-slate-800">
                        {{ Auth::user()->name }}
                    </h2>

                    <p class="text-xs text-slate-500 capitalize">
                        {{ Auth::user()->role }}
                    </p>

                </div>

                {{-- DROPDOWN --}}
                <div x-data="{ dropdownOpen: false }" class="relative">

                    <button
                        @click="dropdownOpen = !dropdownOpen"
                        class="flex items-center gap-3
                               px-3 py-2 rounded-2xl
                               border border-slate-200
                               hover:border-indigo-300
                               hover:shadow-md
                               bg-white transition-all">

                        {{-- AVATAR --}}
                        <div class="w-10 h-10 rounded-xl
                                    bg-gradient-to-br from-indigo-500 to-slate-700
                                    text-white flex items-center justify-center
                                    font-bold uppercase">

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
                        class="absolute right-0 mt-3 w-60
                               bg-white rounded-2xl
                               shadow-2xl border border-slate-100
                               overflow-hidden z-50">

                        {{-- HEADER --}}
                        <div class="px-5 py-4 border-b border-slate-100">

                            <h3 class="text-sm font-semibold text-slate-800">
                                {{ Auth::user()->name }}
                            </h3>

                            <p class="text-xs text-slate-500">
                                {{ Auth::user()->email }}
                            </p>

                        </div>

                        {{-- MENU --}}
                        <div class="p-2">

                            <a href="{{ route('profile.edit') }}"
                               class="flex items-center gap-3
                                      px-4 py-3 rounded-xl
                                      text-sm text-slate-700
                                      hover:bg-slate-100 transition">

                                <i data-lucide="user-circle" class="w-4 h-4"></i>

                                Profile
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <button type="submit"
                                        class="w-full flex items-center gap-3
                                               px-4 py-3 rounded-xl
                                               text-sm text-red-500
                                               hover:bg-red-50 transition">

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
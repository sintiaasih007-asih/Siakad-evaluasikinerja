<x-guest-layout>

    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-100 via-blue-50 to-slate-200 px-4 py-10">

        <div class="w-full max-w-md">

            {{-- LOGIN CARD --}}
            <div class="bg-white rounded-[28px] shadow-[0_20px_60px_rgba(15,23,42,0.12)] border border-slate-200 overflow-hidden">

                {{-- TOP HEADER --}}
                <div class="relative bg-gradient-to-r from-blue-900 via-indigo-800 to-slate-900 px-8 py-10 text-center overflow-hidden">

                    {{-- BACKGROUND DECORATION --}}
                    <div class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full blur-2xl"></div>

                    <div class="absolute bottom-0 left-0 w-32 h-32 bg-cyan-400/10 rounded-full blur-2xl"></div>

                    {{-- LOGO --}}
                    <div class="relative w-20 h-21 mx-auto rounded-3xl bg-blur shadow-lg flex items-center justify-center overflow-hidden p-2">

                        <img
                            src="{{ asset('images/logo-dilan.png') }}"
                            alt="Logo Sekolah"
                            class="w-full h-full object-contain"
                        >

                    </div>

                    {{-- TITLE --}}
                    <h1 class="relative mt-3 text-2xl font-bold text-white tracking-tight">
                        SMP Dianto Landong
                    </h1>

                    <p class="relative mt-2 text-sm text-blue-100 leading-relaxed">
                        Login untuk mengakses dashboard sistem sekolah
                    </p>

                </div>

                {{-- FORM AREA --}}
                <div class="p-8">

                    {{-- SESSION STATUS --}}
                    <x-auth-session-status
                        class="mb-5 text-sm text-emerald-600"
                        :status="session('status')"
                    />

                    <form method="POST" action="{{ route('login') }}" class="space-y-6">

                        @csrf

                        {{-- EMAIL --}}
                        <div>

                            <x-input-label
                                for="email"
                                :value="__('Email')"
                                class="text-slate-700 font-semibold"
                            />

                            <x-text-input
                                id="email"
                                class="block mt-2 w-full rounded-2xl border-slate-200 bg-slate-50 focus:border-indigo-500 focus:ring-indigo-500 py-3 px-4 shadow-sm"
                                type="email"
                                name="email"
                                :value="old('email')"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="Masukkan email"
                            />

                            <x-input-error
                                :messages="$errors->get('email')"
                                class="mt-2 text-red-500"
                            />

                        </div>

                        {{-- PASSWORD --}}
                        <div>

                            <x-input-label
                                for="password"
                                :value="__('Password')"
                                class="text-slate-700 font-semibold"
                            />

                            <x-text-input
                                id="password"
                                class="block mt-2 w-full rounded-2xl border-slate-200 bg-slate-50 focus:border-indigo-500 focus:ring-indigo-500 py-3 px-4 shadow-sm"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="Masukkan password"
                            />

                            <x-input-error
                                :messages="$errors->get('password')"
                                class="mt-2 text-red-500"
                            />

                        </div>

                        {{-- REMEMBER --}}
                        <div class="flex items-center justify-between">

                            <label for="remember_me" class="inline-flex items-center">

                                <input
                                    id="remember_me"
                                    type="checkbox"
                                    class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                    name="remember"
                                >

                                <span class="ms-2 text-sm text-slate-600">
                                    {{ __('Remember me') }}
                                </span>

                            </label>

                        </div>

                        {{-- LOGIN BUTTON --}}
                        <div class="pt-2">

                            <x-primary-button
                                class="w-full justify-center !bg-gradient-to-r !from-blue-800 !to-indigo-700 hover:!from-blue-900 hover:!to-indigo-800 !text-white !rounded-2xl py-3 text-sm font-semibold shadow-lg transition-all duration-300"
                            >
                                {{ __('Log in') }}
                            </x-primary-button>

                        </div>

                        {{-- FORGOT PASSWORD --}}
                        @if (Route::has('password.request'))

                            <div class="text-center pt-2">

                                <a
                                    class="text-sm text-slate-500 hover:text-indigo-700 transition duration-300"
                                    href="{{ route('password.request') }}"
                                >
                                    {{ __('Forgot your password?') }}
                                </a>

                            </div>

                        @endif

                    </form>

                </div>

            </div>

            {{-- FOOTER --}}
            <div class="mt-6 text-center text-sm text-slate-500">

                © {{ date('Y') }} Sistem Informasi Akademik SMP DILAN

            </div>

        </div>

    </div>

</x-guest-layout>
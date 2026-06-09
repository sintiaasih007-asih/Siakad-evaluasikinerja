<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SMP DIANTO LANDONG</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body{
            font-family: 'Inter', sans-serif;
        }

        .hero-bg{
            background:
                linear-gradient(rgba(15,23,42,.88), rgba(15,23,42,.92)),
                url('https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=1600&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
        }

        .glass{
            backdrop-filter: blur(12px);
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.12);
        }

        .animate-float{
            animation: float 4s ease-in-out infinite;
        }

        @keyframes float{
            0%,100%{
                transform: translateY(0px);
            }
            50%{
                transform: translateY(-8px);
            }
        }
    </style>
</head>

<body class="bg-slate-100 text-slate-800">

    {{-- HERO --}}
    <section class="hero-bg min-h-screen flex flex-col">

        {{-- NAVBAR --}}
        <header class="w-full">
            <div class="max-w-7xl mx-auto px-6 py-5 flex items-center justify-between">

                {{-- LOGO --}}
                <div class="flex items-center gap-4">

                    <div class="w-14 h-14 rounded-2xl overflow-hidden shadow-xl bg-white p-1">
                        <img
                            src="{{ asset('images/logo-dilan.png') }}"
                            alt="Logo SMP"
                            class="w-full h-full object-contain"
                        >
                    </div>

                    <div>
                        <h1 class="text-white font-extrabold text-xl tracking-wide">
                            SMP DIANTO LANDONG
                        </h1>

                        <p class="text-slate-300 text-sm">
                            Sistem Informasi Akademik & Monitoring Sekolah
                        </p>
                    </div>
                </div>

                {{-- LOGIN BUTTON --}}
                @auth

                    <a href="{{ route('dashboard') }}"
                        class="bg-white text-slate-900 px-6 py-3 rounded-xl font-semibold shadow-lg hover:scale-105 transition duration-300">
                        Dashboard
                    </a>

                @else

                    <a href="{{ route('login') }}"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-semibold shadow-xl transition duration-300">
                        Login
                    </a>

                @endauth

            </div>
        </header>

        {{-- HERO CONTENT --}}
        <div class="flex-1 flex items-center">

            <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-16 items-center">

                {{-- LEFT CONTENT --}}
                <div>

                    <div class="inline-flex items-center gap-2 bg-white/10 border border-white/10 text-slate-200 px-4 py-2 rounded-full text-sm mb-6">
                        <span class="w-2 h-2 bg-emerald-400 rounded-full"></span>
                        Sekolah Modern • Disiplin • Berprestasi
                    </div>

                    <h1 class="text-5xl lg:text-6xl font-extrabold text-white leading-tight mb-6">
                        Sistem Informasi Akademik
                        <span class="text-indigo-400">
                            SMP DIANTO LANDONG
                        </span>
                    </h1>

                    <p class="text-slate-300 text-lg leading-relaxed mb-10 max-w-2xl">
                        Platform digital sekolah yang dirancang untuk membantu
                        pengelolaan data akademik, monitoring perkembangan siswa,
                        absensi, nilai, jadwal pelajaran, serta komunikasi
                        antara guru, siswa, orang tua, dan pihak sekolah
                        secara profesional dan terintegrasi.
                    </p>

                    {{-- BUTTON --}}
                    <div class="flex flex-wrap gap-4">

                        <a href="{{ route('login') }}"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-4 rounded-2xl font-semibold shadow-2xl transition duration-300">
                            Masuk Sistem
                        </a>

                    </div>

                    {{-- INFO --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-5 mt-14">

                        {{-- STATUS SEKOLAH --}}
                        <div class="glass rounded-2xl p-5 text-center">
                            <div class="w-12 h-12 mx-auto mb-4 rounded-2xl bg-emerald-500/20 flex items-center justify-center">
                                <svg class="w-6 h-6 text-emerald-300"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12l2 2 4-4m5-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>

                            <h3 class="text-white font-bold text-lg">
                                Akreditasi
                            </h3>

                            <p class="text-slate-300 text-sm mt-1">
                                Terakreditasi Baik
                            </p>
                        </div>

                        {{-- DIGITAL --}}
                        <div class="glass rounded-2xl p-5 text-center">
                            <div class="w-12 h-12 mx-auto mb-4 rounded-2xl bg-blue-500/20 flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-300"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 4h14a2 2 0 012 2v7H3V6a2 2 0 012-2z"/>
                                </svg>
                            </div>

                            <h3 class="text-white font-bold text-lg">
                                Sistem Digital
                            </h3>

                            <p class="text-slate-300 text-sm mt-1">
                                Akademik Terintegrasi
                            </p>
                        </div>

                        {{-- KEAMANAN --}}
                        <div class="glass rounded-2xl p-5 text-center">
                            <div class="w-12 h-12 mx-auto mb-4 rounded-2xl bg-orange-500/20 flex items-center justify-center">
                                <svg class="w-6 h-6 text-orange-300"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 11c0 .552-.448 1-1 1s-1-.448-1-1 .448-1 1-1 1 .448 1 1zm0 0V9m0 8a8 8 0 100-16 8 8 0 000 16z"/>
                                </svg>
                            </div>

                            <h3 class="text-white font-bold text-lg">
                                Monitoring
                            </h3>

                            <p class="text-slate-300 text-sm mt-1">
                                Absensi & Nilai Real-time
                            </p>
                        </div>

                        {{-- LAYANAN --}}
                        <div class="glass rounded-2xl p-5 text-center">
                            <div class="w-12 h-12 mx-auto mb-4 rounded-2xl bg-pink-500/20 flex items-center justify-center">
                                <svg class="w-6 h-6 text-pink-300"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M18.364 5.636l-1.414 1.414M15 11a3 3 0 11-6 0 3 3 0 016 0zm6 1a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>

                            <h3 class="text-white font-bold text-lg">
                                Layanan Sekolah
                            </h3>

                            <p class="text-slate-300 text-sm mt-1">
                                Cepat & Profesional
                            </p>
                        </div>

                    </div>

                </div>

                {{-- RIGHT CONTENT --}}
                <div class="relative hidden lg:block">

                    <div class="animate-float">

                        <div class="glass rounded-[32px] p-8 shadow-2xl">

                            {{-- HEADER --}}
                            <div class="flex items-center justify-between mb-8">

                                <div>
                                    <h2 class="text-white font-bold text-2xl">
                                        Dashboard Akademik
                                    </h2>

                                    <p class="text-slate-300 text-sm">
                                        Monitoring Data Sekolah
                                    </p>
                                </div>

                                <div class="bg-indigo-500/20 text-indigo-300 px-4 py-2 rounded-xl text-sm font-semibold">
                                    Online
                                </div>

                            </div>

                            {{-- CARD --}}
                            <div class="space-y-5">

                                {{-- SISWA --}}
                                <div class="bg-white/10 rounded-2xl p-5 border border-white/10">
                                    <div class="flex items-center justify-between">

                                        <div>
                                            <p class="text-slate-300 text-sm">
                                                Data Siswa
                                            </p>

                                            <h3 class="text-white text-3xl font-bold mt-1">
                                                {{ \App\Models\Siswa::count() }}
                                            </h3>
                                        </div>

                                        <div class="w-14 h-14 rounded-2xl bg-blue-500/20 flex items-center justify-center">
                                            <svg class="w-7 h-7 text-blue-300"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M17 20h5V4H2v16h5m10 0v-2a4 4 0 00-8 0v2m8 0H9m4-10a4 4 0 100-8 4 4 0 000 8z"/>
                                            </svg>
                                        </div>

                                    </div>
                                </div>

                                {{-- GURU --}}
                                <div class="bg-white/10 rounded-2xl p-5 border border-white/10">
                                    <div class="flex items-center justify-between">

                                        <div>
                                            <p class="text-slate-300 text-sm">
                                                Data Guru
                                            </p>

                                            <h3 class="text-white text-3xl font-bold mt-1">
                                                {{ \App\Models\Guru::count() }}
                                            </h3>
                                        </div>

                                        <div class="w-14 h-14 rounded-2xl bg-emerald-500/20 flex items-center justify-center">
                                            <svg class="w-7 h-7 text-emerald-300"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422A12.083 12.083 0 0112 20.055a12.083 12.083 0 01-6.16-9.477L12 14z"/>
                                            </svg>
                                        </div>

                                    </div>
                                </div>

                                {{-- KELAS --}}
                                <div class="bg-white/10 rounded-2xl p-5 border border-white/10">
                                    <div class="flex items-center justify-between">

                                        <div>
                                            <p class="text-slate-300 text-sm">
                                                Data Kelas
                                            </p>

                                            <h3 class="text-white text-3xl font-bold mt-1">
                                                {{ \App\Models\Kelas::count() }}
                                            </h3>
                                        </div>

                                        <div class="w-14 h-14 rounded-2xl bg-orange-500/20 flex items-center justify-center">
                                            <svg class="w-7 h-7 text-orange-300"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 6.253v13m0-13C10.832 5.483 9.246 5 7.5 5S4.168 5.483 3 6.253v13C4.168 18.483 5.754 18 7.5 18s3.332.483 4.5 1.253m0-13C13.168 5.483 14.754 5 16.5 5c1.746 0 3.332.483 4.5 1.253v13C19.832 18.483 18.246 18 16.5 18c-1.746 0-3.332.483-4.5 1.253"/>
                                            </svg>
                                        </div>

                                    </div>
                                </div>

                                {{-- MAPEL --}}
                                <div class="bg-white/10 rounded-2xl p-5 border border-white/10">
                                    <div class="flex items-center justify-between">

                                        <div>
                                            <p class="text-slate-300 text-sm">
                                                Mata Pelajaran
                                            </p>

                                            <h3 class="text-white text-3xl font-bold mt-1">
                                                {{ \App\Models\Mapel::count() }}
                                            </h3>
                                        </div>

                                        <div class="w-14 h-14 rounded-2xl bg-pink-500/20 flex items-center justify-center">
                                            <svg class="w-7 h-7 text-pink-300"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 6v6l4 2"/>
                                            </svg>
                                        </div>

                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- FOOTER --}}
        <footer class="border-t border-white/10 py-6">
            <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4">

                <p class="text-slate-400 text-sm">
                    © {{ date('Y') }} SMP DIANTO LANDONG — Sistem Informasi Akademik Sekolah
                </p>

                <div class="flex items-center gap-6 text-sm text-slate-400">
                    <span>Disiplin</span>
                    <span>•</span>
                    <span>Berprestasi</span>
                    <span>•</span>
                    <span>Berkarakter</span>
                </div>

            </div>
        </footer>

    </section>

</body>
</html>
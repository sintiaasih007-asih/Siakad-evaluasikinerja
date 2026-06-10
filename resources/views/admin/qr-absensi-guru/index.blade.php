<x-app-layout>

    <x-page-header
        title="QR Token Absensi Guru"
        subtitle="Dashboard / Admin / QR Absensi Guru"
    />

    <div class="max-w-3xl space-y-6">

        {{-- ALERT --}}
        @if(session('success'))
            <div class="flex items-center gap-3 bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-xl text-sm">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- CARD UTAMA --}}
        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">

            {{-- Header --}}
            <div class="bg-gradient-to-r from-indigo-700 to-blue-600 px-6 py-5 flex items-center gap-3">
                <div class="bg-white/20 p-2.5 rounded-xl">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-white font-bold text-lg leading-tight">QR Token Absensi Guru</h2>
                    <p class="text-blue-100 text-sm">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>

            {{-- Body --}}
            <div class="p-6">

                @if($token)

                    {{-- STATUS: Token aktif --}}
                    <div class="flex items-center gap-2 mb-6 bg-green-50 border border-green-200 rounded-xl px-4 py-3">
                        <span class="w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse inline-block"></span>
                        <span class="text-sm font-semibold text-green-700">Token QR aktif untuk hari ini</span>
                        <span class="ml-auto text-xs text-green-600 bg-green-100 px-2 py-0.5 rounded-full">
                            Berlaku hingga 23:59
                        </span>
                    </div>

                    {{-- QR CODE + INFO --}}
                    <div class="flex flex-col sm:flex-row gap-6 items-center">

                        {{-- QR Code --}}
                        <div class="bg-white border-2 border-indigo-200 rounded-2xl p-4 shadow-sm shrink-0">
                            <div class="text-center text-xs text-gray-400 mb-3 font-medium uppercase tracking-wide">
                                Scan untuk Absensi
                            </div>
                            <div class="flex justify-center">
                                {!! QrCode::size(200)->style('round')->eye('circle')
                                    ->color(59, 130, 246)
                                    ->generate($token) !!}
                            </div>
                            <div class="text-center text-xs text-gray-400 mt-3">
                                {{ now()->format('d/m/Y') }}
                            </div>
                        </div>

                        {{-- Info Token --}}
                        <div class="flex-1 space-y-4 w-full">

                            {{-- Token string --}}
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Token</p>
                                <div class="flex items-center gap-2">
                                    <code class="flex-1 bg-gray-100 text-gray-700 text-sm px-3 py-2 rounded-lg font-mono break-all select-all border border-gray-200">
                                        {{ $token }}
                                    </code>
                                    <button
                                        onclick="copyToken()"
                                        title="Salin token"
                                        class="shrink-0 bg-gray-100 hover:bg-indigo-100 border border-gray-200 hover:border-indigo-300 text-gray-600 hover:text-indigo-700 p-2 rounded-lg transition"
                                    >
                                        <svg id="iconCopy" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            {{-- Statistik hari ini --}}
                            <div class="bg-blue-50 border border-blue-100 rounded-xl px-4 py-3 flex items-center gap-3">
                                <div class="bg-blue-600 text-white text-2xl font-bold w-12 h-12 rounded-xl flex items-center justify-center shrink-0">
                                    {{ $totalHadir }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">Guru sudah absen hari ini</p>
                                    <p class="text-xs text-gray-500">Data diperbarui secara real-time</p>
                                </div>
                            </div>

                            {{-- Panduan --}}
                            <div class="bg-yellow-50 border border-yellow-200 rounded-xl px-4 py-3 text-xs text-yellow-700 space-y-1">
                                <p class="font-semibold mb-1.5">Cara penggunaan:</p>
                                <p>1. Tampilkan QR ini ke guru yang akan absen</p>
                                <p>2. Guru scan menggunakan halaman absensi mereka</p>
                                <p>3. Token otomatis kedaluwarsa pukul 23:59</p>
                                <p>4. Generate token baru setiap hari</p>
                            </div>

                        </div>

                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-wrap gap-3 mt-6 pt-5 border-t border-gray-100">

                        {{-- Generate Ulang --}}
                        <form action="{{ route('qr-absensi-guru.generate') }}" method="POST">
                            @csrf
                            <button type="submit"
                                onclick="return confirm('Generate token baru? Token lama tidak bisa digunakan lagi.')"
                                class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Generate Token Baru
                            </button>
                        </form>

                        {{-- Print / Fullscreen --}}
                        <button
                            onclick="printQR()"
                            class="flex items-center gap-2 bg-white border border-gray-300 hover:border-indigo-400 hover:bg-indigo-50 text-gray-700 hover:text-indigo-700 px-5 py-2.5 rounded-xl text-sm font-semibold transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            Cetak QR
                        </button>

                        {{-- Reset --}}
                        <form action="{{ route('qr-absensi-guru.reset') }}" method="POST">
                            @csrf
                            <button type="submit"
                                onclick="return confirm('Hapus token aktif? Guru tidak bisa absen sampai token baru dibuat.')"
                                class="flex items-center gap-2 bg-white border border-red-200 hover:border-red-400 hover:bg-red-50 text-red-500 hover:text-red-700 px-5 py-2.5 rounded-xl text-sm font-semibold transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Reset Token
                            </button>
                        </form>

                    </div>

                @else

                    {{-- STATUS: Tidak ada token --}}
                    <div class="text-center py-10">
                        <div class="w-24 h-24 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-700 mb-1">Belum Ada Token Aktif</h3>
                        <p class="text-sm text-gray-500 mb-6">
                            Generate token QR untuk mengizinkan guru melakukan absensi hari ini.
                        </p>

                        <form action="{{ route('qr-absensi-guru.generate') }}" method="POST" class="inline-block">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl text-sm font-bold transition shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                </svg>
                                Generate Token QR Hari Ini
                            </button>
                        </form>

                    </div>

                @endif

            </div>

        </div>

    </div>

    {{-- Print area --}}
    <div id="printArea" class="hidden">
        <div style="text-align:center; font-family:sans-serif; padding:32px">
            <h2 style="font-size:20px; margin-bottom:4px">QR Code Absensi Guru</h2>
            <p style="font-size:14px; color:#555; margin-bottom:20px">{{ now()->format('l, d F Y') }}</p>
            <div>
                {!! isset($token) && $token ? QrCode::size(300)->style('round')->eye('circle')->color(59,130,246)->generate($token) : '' !!}
            </div>
            <p style="font-size:11px; color:#888; margin-top:16px">Scan menggunakan halaman absensi guru</p>
        </div>
    </div>

    <script>
        function copyToken() {
            const token = @json($token ?? '');
            if (!token) return;
            navigator.clipboard.writeText(token).then(() => {
                const icon = document.getElementById('iconCopy');
                icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>`;
                setTimeout(() => {
                    icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>`;
                }, 2000);
            });
        }

        function printQR() {
            const printContent = document.getElementById('printArea').innerHTML;
            const win = window.open('', '_blank', 'width=600,height=700');
            win.document.write(`<html><head><title>QR Absensi Guru</title></head><body>${printContent}</body></html>`);
            win.document.close();
            win.focus();
            win.print();
            win.close();
        }
    </script>

</x-app-layout>

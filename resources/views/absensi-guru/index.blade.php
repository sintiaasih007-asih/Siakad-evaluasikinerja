<x-app-layout>

    <x-page-header
        title="Absensi Guru"
        subtitle="Presensi Harian — {{ now()->translatedFormat('l, d F Y') }}"
    />

    <div class="max-w-5xl space-y-6">

        {{-- ── ALERT ────────────────────────────────────────────────────── --}}
        @if(session('success'))
            <div class="flex items-center gap-3 bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-xl text-sm">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="flex items-center gap-3 bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-xl text-sm">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- ── JADWAL HARI INI ──────────────────────────────────────────── --}}
        @if($jadwalHariIni->isNotEmpty())
        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
            <div class="px-5 py-3 bg-blue-50 border-b border-blue-100 flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <h3 class="font-bold text-sm text-blue-700">
                    Jadwal Mengajar Hari Ini — {{ now()->translatedFormat('l, d F Y') }}
                </h3>
            </div>
            <div class="p-4 flex flex-wrap gap-3">
                @foreach($jadwalHariIni as $j)
                <div class="flex items-center gap-3 bg-blue-50 border border-blue-200 rounded-xl px-4 py-2.5 text-sm">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white text-xs font-bold shrink-0">
                        {{ $loop->iteration }}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">{{ $j->mapel->nama_mapel }}</p>
                        <p class="text-xs text-gray-500">
                            {{ $j->kelas->nama_kelas }} —
                            {{ substr($j->jam_masuk,0,5) }}–{{ substr($j->jam_selesai,0,5) }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ── PROFIL + STATUS ──────────────────────────────────────────── --}}
        <div class="grid md:grid-cols-3 gap-5">

            {{-- Profil Guru --}}
            <div class="bg-white rounded-2xl shadow-sm border p-6 flex flex-col items-center text-center">

                @if($guru->foto_wajah)
                    <img src="{{ asset('storage/'.$guru->foto_wajah) }}"
                        class="w-24 h-24 rounded-full object-cover border-4 border-blue-100 shadow-sm">
                @else
                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-100 to-indigo-100
                                flex items-center justify-center text-4xl shadow-sm border border-blue-200">
                        👨‍🏫
                    </div>
                @endif

                <h3 class="text-base font-bold text-gray-800 mt-3">{{ $guru->nama }}</h3>
                <p class="text-sm text-gray-500">{{ $guru->nip ?? 'Guru' }}</p>

                <div class="mt-3 w-full bg-blue-50 rounded-xl px-3 py-2 text-xs text-blue-700">
                    {{ now()->format('H:i') }} WIB
                </div>

            </div>

            {{-- Status Kehadiran --}}
            <div class="md:col-span-2 bg-white rounded-2xl shadow-sm border p-6">

                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    STATUS KEHADIRAN HARI INI
                </h3>

                @if($absensiHariIni)
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <div class="bg-green-50 rounded-xl p-3 border border-green-200 text-center">
                            <p class="text-xs text-gray-500 mb-1">Status</p>
                            <p class="text-sm font-bold text-green-700">{{ $absensiHariIni->status }}</p>
                        </div>
                        <div class="bg-blue-50 rounded-xl p-3 border border-blue-200 text-center">
                            <p class="text-xs text-gray-500 mb-1">Masuk</p>
                            <p class="text-sm font-bold text-blue-700">
                                {{ $absensiHariIni->jam_masuk ? substr($absensiHariIni->jam_masuk,0,5) : '-' }}
                            </p>
                        </div>
                        <div class="bg-orange-50 rounded-xl p-3 border border-orange-200 text-center">
                            <p class="text-xs text-gray-500 mb-1">Pulang</p>
                            <p class="text-sm font-bold text-orange-700">
                                {{ $absensiHariIni->jam_pulang ? substr($absensiHariIni->jam_pulang,0,5) : '-' }}
                            </p>
                        </div>
                        <div class="bg-purple-50 rounded-xl p-3 border border-purple-200 text-center">
                            <p class="text-xs text-gray-500 mb-1">Kondisi</p>
                            <p class="text-sm font-bold text-purple-700">
                                {{ $absensiHariIni->jam_pulang ? 'Selesai' : 'Bertugas' }}
                            </p>
                        </div>
                    </div>

                    @if($absensiHariIni->alamat)
                        <div class="mt-3 bg-gray-50 border rounded-xl p-3 text-sm text-gray-600">
                            <span class="font-semibold text-gray-700">📍 Lokasi:</span>
                            {{ $absensiHariIni->alamat }}
                        </div>
                    @endif
                @else
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-sm text-yellow-700">
                        Anda belum melakukan absensi hari ini. Silakan isi form di bawah.
                    </div>
                @endif

            </div>

        </div>

        {{-- ── FORM ABSENSI ─────────────────────────────────────────────── --}}
        @if(!($absensiHariIni && $absensiHariIni->jam_pulang))

        @if(!$punya_jadwal)
        {{-- Tidak ada jadwal hari ini --}}
        <div class="bg-white rounded-2xl shadow-sm border p-8 text-center">
            <div class="w-20 h-20 bg-amber-50 border-2 border-amber-200 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-amber-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-gray-700 mb-1">Tidak Ada Jadwal Hari Ini</h3>
            <p class="text-sm text-gray-500 max-w-sm mx-auto">
                Absensi hanya tersedia pada hari Anda memiliki jadwal mengajar.
                Hari ini <strong>{{ now()->translatedFormat('l, d F Y') }}</strong> tidak ada jadwal untuk Anda.
            </p>
        </div>

        @else

        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">

            {{-- Card Header --}}
            <div class="bg-gradient-to-r from-blue-700 to-blue-500 px-6 py-4 flex items-center gap-3">
                <div class="bg-white/20 p-2 rounded-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-white font-bold text-sm">
                        {{ !$absensiHariIni ? 'Form Absen Masuk' : 'Form Absen Pulang' }}
                    </h3>
                    <p class="text-blue-100 text-xs">Lengkapi token QR, GPS, dan kamera</p>
                </div>
            </div>

            <div class="p-6">

                <form
                    action="{{ route('absensi-guru.store') }}"
                    method="POST"
                    id="formAbsensiGuru"
                    onsubmit="return jalankanSubmit()"
                >
                    @csrf

                    {{-- Hidden fields --}}
                    <input type="hidden" name="latitude"    id="latitude">
                    <input type="hidden" name="longitude"   id="longitude">
                    <input type="hidden" name="alamat"      id="alamat">
                    <input type="hidden" name="foto_base64" id="foto_base64">

                    <div class="grid md:grid-cols-2 gap-6">

                        {{-- KIRI: Token QR + GPS --}}
                        <div class="space-y-4">

                            {{-- Token QR --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">
                                    Token QR
                                    <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input
                                        type="text"
                                        name="qr_token"
                                        id="qr_token"
                                        placeholder="Masukkan token dari admin atau scan QR"
                                        required
                                        class="w-full border border-gray-300 rounded-xl text-sm px-4 py-2.5
                                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition
                                               font-mono tracking-widest"
                                    >
                                </div>
                                <p class="text-xs text-gray-400 mt-1">
                                    Token diberikan oleh admin setiap hari
                                </p>
                            </div>

                            {{-- GPS --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Lokasi GPS</label>
                                <div id="gpsInfo"
                                    class="bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 text-sm text-blue-700 min-h-[60px] flex items-center">
                                    <span class="animate-pulse">⏳ Mendeteksi lokasi…</span>
                                </div>
                            </div>

                        </div>

                        {{-- KANAN: Kamera Selfie --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Kamera Selfie</label>

                            <div class="relative rounded-xl overflow-hidden border-2 border-blue-200 bg-gray-900"
                                style="aspect-ratio: 4/3;">
                                <video id="videoAbsensi" autoplay playsinline muted
                                    class="w-full h-full object-cover"></video>

                                {{-- overlay captured --}}
                                <canvas id="canvasAbsensi" class="hidden absolute inset-0 w-full h-full"></canvas>

                                {{-- live indicator --}}
                                <div id="liveBadge"
                                    class="absolute top-2 left-2 bg-blue-600 text-white text-xs px-2 py-0.5 rounded-full flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span>
                                    Live
                                </div>

                                {{-- snap indicator --}}
                                <div id="snapBadge"
                                    class="hidden absolute top-2 right-2 bg-green-600 text-white text-xs px-2 py-0.5 rounded-full">
                                    ✅ Foto diambil
                                </div>
                            </div>

                            <p class="text-xs text-gray-400 mt-1">
                                Foto akan diambil otomatis saat submit
                            </p>
                        </div>

                    </div>

                    {{-- TOMBOL SUBMIT --}}
                    <div class="mt-6 pt-5 border-t border-gray-100 flex justify-end">

                        @if(!$absensiHariIni)
                            <button type="submit" id="btnAbsen"
                                class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700
                                       text-white px-6 py-3 rounded-xl font-bold text-sm shadow-sm transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                📍 Absen Masuk
                            </button>

                        @elseif(!$absensiHariIni->jam_pulang)
                            <button type="submit" id="btnAbsen"
                                class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-700
                                       text-white px-6 py-3 rounded-xl font-bold text-sm shadow-sm transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                🏠 Absen Pulang
                            </button>
                        @endif

                    </div>

                </form>

            </div>

        </div>

        @endif {{-- @if(!$punya_jadwal) --}}

        @else

        {{-- Absensi sudah selesai hari ini --}}
        <div class="bg-white rounded-2xl shadow-sm border p-8 text-center">
            <div class="text-5xl mb-3">✅</div>
            <h3 class="text-lg font-bold text-gray-800">Absensi Hari Ini Selesai</h3>
            <p class="text-sm text-gray-500 mt-1">Terima kasih, sampai jumpa besok!</p>
        </div>

        @endif {{-- @if(!($absensiHariIni && ...)) --}}

        {{-- ── RIWAYAT ──────────────────────────────────────────────────── --}}
        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">

            <div class="px-6 py-4 border-b bg-gray-50 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="font-bold text-sm text-gray-700">Riwayat 10 Absensi Terakhir</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                        <tr>
                            <th class="px-5 py-3 text-left">Tanggal</th>
                            <th class="px-5 py-3 text-left">Masuk</th>
                            <th class="px-5 py-3 text-left">Pulang</th>
                            <th class="px-5 py-3 text-left">Status</th>
                            <th class="px-5 py-3 text-left">Foto</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($riwayat as $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-5 py-3 font-medium text-gray-700">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                                </td>
                                <td class="px-5 py-3 text-blue-700 font-mono">
                                    {{ $item->jam_masuk ? substr($item->jam_masuk,0,5) : '-' }}
                                </td>
                                <td class="px-5 py-3 text-orange-600 font-mono">
                                    {{ $item->jam_pulang ? substr($item->jam_pulang,0,5) : '-' }}
                                </td>
                                <td class="px-5 py-3">
                                    @php
                                        $statusColor = match($item->status) {
                                            'Hadir'     => 'bg-green-100 text-green-700 border-green-200',
                                            'Terlambat' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                            'Izin'      => 'bg-blue-100 text-blue-700 border-blue-200',
                                            'Sakit'     => 'bg-orange-100 text-orange-700 border-orange-200',
                                            default     => 'bg-red-100 text-red-700 border-red-200',
                                        };
                                    @endphp
                                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold border {{ $statusColor }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-3">
                                    @if($item->foto_absensi)
                                        <img src="{{ asset('storage/'.$item->foto_absensi) }}"
                                            class="w-12 h-12 rounded-lg object-cover border cursor-pointer hover:scale-110 transition"
                                            onclick="lihatFoto('{{ asset('storage/'.$item->foto_absensi) }}')"
                                            title="Klik untuk perbesar">
                                    @else
                                        <span class="text-gray-400 text-xs">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-gray-400 text-sm">
                                    Belum ada riwayat absensi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

    </div>

    {{-- ── MODAL LIHAT FOTO ──────────────────────────────────────────── --}}
    <div id="modalFoto"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 backdrop-blur-sm p-4">
        <div class="relative">
            <button onclick="tutupFoto()"
                class="absolute -top-3 -right-3 bg-white rounded-full w-8 h-8 flex items-center justify-center shadow-lg text-gray-700 hover:bg-red-50 hover:text-red-600 transition">
                ✕
            </button>
            <img id="modalFotoImg" src="" class="max-w-sm max-h-[80vh] rounded-2xl shadow-2xl object-contain">
        </div>
    </div>

    {{-- ── SCRIPTS ──────────────────────────────────────────────────── --}}
    <script>
    /* ── GPS ──────────────────────────────────────────────────────── */
    navigator.geolocation.getCurrentPosition(
        async function (pos) {
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;

            document.getElementById('latitude').value  = lat;
            document.getElementById('longitude').value = lng;

            try {
                const res  = await fetch(
                    `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`
                );
                const data = await res.json();
                const alamat = data.display_name ?? 'Alamat tidak ditemukan';

                document.getElementById('alamat').value = alamat;

                document.getElementById('gpsInfo').innerHTML = `
                    <div class="space-y-1 text-xs">
                        <div class="text-green-700 font-semibold">✅ GPS Terdeteksi</div>
                        <div><strong>Lat:</strong> ${lat.toFixed(6)}, <strong>Lng:</strong> ${lng.toFixed(6)}</div>
                        <div class="text-gray-600 leading-relaxed">${alamat}</div>
                    </div>`;
            } catch {
                document.getElementById('gpsInfo').innerHTML =
                    '<span class="text-orange-600 text-xs">⚠️ GPS aktif, gagal mendapatkan alamat.</span>';
            }
        },
        function () {
            document.getElementById('gpsInfo').innerHTML =
                '<span class="text-red-600 text-xs">❌ GPS tidak aktif. Aktifkan lokasi di browser Anda.</span>';
        },
        { timeout: 10000, enableHighAccuracy: true }
    );

    /* ── KAMERA ───────────────────────────────────────────────────── */
    const video  = document.getElementById('videoAbsensi');
    const canvas = document.getElementById('canvasAbsensi');

    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } })
        .then(stream => { video.srcObject = stream; })
        .catch(() => {
            document.getElementById('liveBadge').textContent = '❌ Kamera gagal';
        });

    /* ── SUBMIT: ambil foto lalu kirim form ──────────────────────── */
    function jalankanSubmit() {
        // Ambil snapshot dari video
        canvas.width  = video.videoWidth  || 640;
        canvas.height = video.videoHeight || 480;
        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

        document.getElementById('foto_base64').value = canvas.toDataURL('image/jpeg', 0.85);
        document.getElementById('snapBadge').classList.remove('hidden');

        // Matikan stream setelah capture
        if (video.srcObject) {
            video.srcObject.getTracks().forEach(t => t.stop());
        }

        // Nonaktifkan tombol agar tidak double submit
        const btn = document.getElementById('btnAbsen');
        if (btn) {
            btn.disabled = true;
            btn.textContent = '⏳ Mengirim…';
        }

        return true; // lanjutkan form submit
    }

    /* ── MODAL FOTO ──────────────────────────────────────────────── */
    function lihatFoto(src) {
        document.getElementById('modalFotoImg').src = src;
        const modal = document.getElementById('modalFoto');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function tutupFoto() {
        const modal = document.getElementById('modalFoto');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    document.getElementById('modalFoto').addEventListener('click', function(e){
        if (e.target === this) tutupFoto();
    });

    /* ── Matikan kamera saat tinggalkan halaman ──────────────────── */
    window.addEventListener('beforeunload', () => {
        if (video.srcObject) video.srcObject.getTracks().forEach(t => t.stop());
    });
    </script>

</x-app-layout>

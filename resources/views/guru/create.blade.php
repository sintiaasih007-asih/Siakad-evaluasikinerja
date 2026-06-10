<x-app-layout>

    {{-- HEADER --}}
    <x-page-header
        title="Tambah Data Guru"
        subtitle="Dashboard / Guru / Tambah Data"
    />

    {{-- CARD UTAMA --}}
    <div class="max-w-4xl">

        {{-- Header Card --}}
        <div class="bg-gradient-to-r from-blue-700 to-blue-500 rounded-t-xl px-6 py-4 flex items-center gap-3">
            <div class="bg-white bg-opacity-20 p-2 rounded-lg">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3M6 12a4 4 0 118 0 4 4 0 01-8 0zM3 20a9 9 0 0118 0" />
                </svg>
            </div>
            <div>
                <h2 class="text-white font-semibold text-base leading-tight">Tambah Data Guru</h2>
                <p class="text-blue-100 text-xs">Lengkapi informasi guru dan rekam data wajah</p>
            </div>
        </div>

        {{-- Body Card --}}
        <div class="bg-white rounded-b-xl shadow-sm border border-t-0 border-blue-100 p-6">

            <form
                action="{{ route('guru.store') }}"
                method="POST"
                id="guruForm"
                class="space-y-5"
            >
                @csrf

                {{-- SECTION: Informasi Dasar --}}
                <div>
                    <h3 class="text-xs font-semibold text-blue-600 uppercase tracking-wider mb-3 flex items-center gap-2">
                        <span class="inline-block w-4 h-0.5 bg-blue-400 rounded"></span>
                        Informasi Guru
                        <span class="flex-1 h-0.5 bg-blue-50 rounded"></span>
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        {{-- NAMA --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Guru
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor"
                                        stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <input
                                    type="text"
                                    name="nama"
                                    value="{{ old('nama') }}"
                                    placeholder="Masukkan nama lengkap guru"
                                    required
                                    class="w-full pl-9 border border-gray-300 rounded-lg shadow-sm text-sm
                                        focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                        transition placeholder-gray-400"
                                >
                            </div>
                            @error('nama')
                                <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10A8 8 0 11 2 10a8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- NIP --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                NIP
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor"
                                        stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0" />
                                    </svg>
                                </div>
                                <input
                                    type="text"
                                    name="nip"
                                    value="{{ old('nip') }}"
                                    placeholder="Masukkan NIP (opsional)"
                                    class="w-full pl-9 border border-gray-300 rounded-lg shadow-sm text-sm
                                        focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                        transition placeholder-gray-400"
                                >
                            </div>
                            @error('nip')
                                <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10A8 8 0 11 2 10a8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- EMAIL --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Email
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor"
                                        stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <input
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="contoh@email.com (opsional)"
                                    class="w-full pl-9 border border-gray-300 rounded-lg shadow-sm text-sm
                                        focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                        transition placeholder-gray-400"
                                >
                            </div>
                            @error('email')
                                <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10A8 8 0 11 2 10a8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- SECTION: Face Recognition --}}
                <div>
                    <h3 class="text-xs font-semibold text-blue-600 uppercase tracking-wider mb-3 flex items-center gap-2">
                        <span class="inline-block w-4 h-0.5 bg-blue-400 rounded"></span>
                        Registrasi Wajah
                        <span class="flex-1 h-0.5 bg-blue-50 rounded"></span>
                    </h3>

                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-5">

                        {{-- Info Banner --}}
                        <div class="flex items-start gap-3 mb-4 bg-blue-100 border border-blue-200 rounded-lg px-4 py-3">
                            <svg class="w-4 h-4 text-blue-600 mt-0.5 shrink-0" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                            </svg>
                            <p class="text-sm text-blue-700">
                                Arahkan wajah ke kamera dengan pencahayaan yang cukup, lalu klik
                                <strong>Ambil Wajah</strong> untuk merekam data wajah guru.
                            </p>
                        </div>

                        {{-- Camera & Preview Area --}}
                        <div class="flex flex-col md:flex-row gap-4 items-start">

                            {{-- Video --}}
                            <div class="relative">
                                <video
                                    id="video"
                                    width="360"
                                    height="270"
                                    autoplay
                                    playsinline
                                    muted
                                    class="rounded-xl border-2 border-blue-300 shadow-md bg-gray-900 block"
                                ></video>
                                <div class="absolute top-2 left-2 bg-blue-600 text-white text-xs px-2 py-0.5 rounded-full flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse inline-block"></span>
                                    Live
                                </div>
                            </div>

                            {{-- Status & Action --}}
                            <div class="flex flex-col gap-3 justify-between h-full">

                                {{-- Status Box --}}
                                <div id="statusFaceBox"
                                    class="bg-white border-2 border-red-200 rounded-xl px-5 py-4 flex items-center gap-3 min-w-[200px]">
                                    <div id="statusIcon" class="text-2xl">❌</div>
                                    <div>
                                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Status Wajah</p>
                                        <p id="statusFace" class="text-sm font-semibold text-red-500">Belum direkam</p>
                                    </div>
                                </div>

                                {{-- Panduan --}}
                                <ul class="text-xs text-blue-700 space-y-1.5">
                                    <li class="flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5 text-blue-400 shrink-0" fill="none" stroke="currentColor"
                                            stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Pastikan wajah terlihat jelas
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5 text-blue-400 shrink-0" fill="none" stroke="currentColor"
                                            stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Gunakan pencahayaan yang baik
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5 text-blue-400 shrink-0" fill="none" stroke="currentColor"
                                            stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Hindari penggunaan masker
                                    </li>
                                </ul>

                                {{-- Button Ambil --}}
                                <button
                                    type="button"
                                    id="captureFace"
                                    class="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white px-5 py-2.5 rounded-lg
                                        flex items-center gap-2 text-sm font-medium shadow-sm transition"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Ambil Wajah
                                </button>

                            </div>
                        </div>

                        {{-- Hidden descriptor --}}
                        <input type="hidden" name="face_descriptor" id="face_descriptor">

                        @error('face_descriptor')
                            <p class="text-red-500 text-xs mt-3 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10A8 8 0 11 2 10a8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror

                    </div>
                </div>

                {{-- DIVIDER --}}
                <div class="border-t border-blue-100"></div>

                {{-- BUTTON ACTIONS --}}
                <div class="flex justify-end gap-3 pt-1">

                    <a
                        href="{{ route('guru.index') }}"
                        class="px-5 py-2.5 rounded-lg border border-gray-300 text-sm text-gray-600
                            hover:bg-gray-50 hover:border-gray-400 transition flex items-center gap-2"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Batal
                    </a>

                    <button
                        type="submit"
                        id="btnSubmit"
                        class="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white px-6 py-2.5 rounded-lg
                            shadow-sm transition text-sm font-medium flex items-center gap-2"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Data
                    </button>

                </div>

            </form>

        </div>
    </div>

    {{-- FACE API — tanpa defer agar tersedia sebelum script inline dijalankan --}}
    <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>

    <script>
        const video        = document.getElementById('video');
        const captureBtn   = document.getElementById('captureFace');
        const statusBox    = document.getElementById('statusFaceBox');
        const statusText   = document.getElementById('statusFace');
        const statusIcon   = document.getElementById('statusIcon');
        const liveDot      = document.querySelector('.animate-pulse');

        /* ── helper: set status UI ─────────────────────────────────── */
        function setStatus(icon, text, colorClass, borderClass) {
            statusIcon.textContent = icon;
            statusText.textContent = text;
            statusText.className   = 'text-sm font-semibold ' + colorClass;
            statusBox.className    = statusBox.className
                .replace(/border-\S+/g, '')
                .trim() + ' ' + borderClass;
        }

        /* ── matikan tombol Ambil Wajah sampai model & kamera siap ─── */
        captureBtn.disabled = true;
        captureBtn.classList.add('opacity-50', 'cursor-not-allowed');

        /* ── mulai kamera ──────────────────────────────────────────── */
        async function startCamera() {
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                setStatus('⚠️', 'Browser tidak mendukung kamera', 'text-yellow-600', 'border-yellow-300');
                return;
            }
            try {
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: { width: { ideal: 640 }, height: { ideal: 480 }, facingMode: 'user' }
                });
                video.srcObject = stream;
                video.setAttribute('playsinline', true); // penting untuk Safari/iOS
                await video.play();

                /* aktifkan tombol setelah kamera menyala */
                captureBtn.disabled = false;
                captureBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                setStatus('🎥', 'Kamera aktif, siap rekam', 'text-blue-600', 'border-blue-300');
            } catch (err) {
                console.error('Kamera error:', err);
                liveDot && liveDot.classList.remove('animate-pulse');

                let pesan = 'Kamera tidak dapat diakses.';
                if (err.name === 'NotAllowedError' || err.name === 'PermissionDeniedError') {
                    pesan = 'Izin kamera ditolak. Izinkan akses kamera di browser lalu muat ulang halaman.';
                } else if (err.name === 'NotFoundError') {
                    pesan = 'Kamera tidak ditemukan pada perangkat ini.';
                } else if (err.name === 'NotReadableError') {
                    pesan = 'Kamera sedang digunakan aplikasi lain. Tutup aplikasi tersebut lalu muat ulang.';
                }
                setStatus('❌', pesan, 'text-red-500', 'border-red-200');
            }
        }

        /* ── muat model face-api lalu nyalakan kamera ──────────────── */
        setStatus('⏳', 'Memuat model AI…', 'text-gray-500', 'border-gray-200');

        Promise.all([
            faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
            faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
            faceapi.nets.faceRecognitionNet.loadFromUri('/models')
        ])
        .then(startCamera)
        .catch(err => {
            console.error('Gagal memuat model:', err);
            setStatus('❌', 'Gagal memuat model AI. Periksa koneksi.', 'text-red-500', 'border-red-200');
        });

        /* ── tombol Ambil Wajah ────────────────────────────────────── */
        captureBtn.addEventListener('click', async () => {
            captureBtn.disabled = true;
            captureBtn.textContent = '⏳ Mendeteksi…';

            try {
                const detection = await faceapi
                    .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions({ inputSize: 320, scoreThreshold: 0.5 }))
                    .withFaceLandmarks()
                    .withFaceDescriptor();

                if (!detection) {
                    alert('Wajah tidak terdeteksi. Pastikan wajah terlihat jelas dan pencahayaan cukup.');
                    captureBtn.disabled = false;
                    captureBtn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg> Ambil Wajah`;
                    return;
                }

                const descriptor = Array.from(detection.descriptor);
                document.getElementById('face_descriptor').value = JSON.stringify(descriptor);

                setStatus('✅', 'Wajah berhasil direkam', 'text-green-600', 'border-green-300');
                captureBtn.innerHTML = `✅ Rekam Ulang`;
                captureBtn.disabled = false;

            } catch (err) {
                console.error('Deteksi error:', err);
                alert('Terjadi kesalahan saat mendeteksi wajah. Coba lagi.');
                captureBtn.disabled = false;
                captureBtn.textContent = 'Ambil Wajah';
            }
        });

        /* ── validasi form sebelum submit ──────────────────────────── */
        document.getElementById('guruForm').addEventListener('submit', function (e) {
            const descriptor = document.getElementById('face_descriptor').value;
            if (!descriptor) {
                e.preventDefault();
                alert('Silakan rekam wajah guru terlebih dahulu sebelum menyimpan.');
            }
        });

        /* ── matikan stream saat halaman ditutup / pindah ──────────── */
        window.addEventListener('beforeunload', () => {
            if (video.srcObject) {
                video.srcObject.getTracks().forEach(t => t.stop());
            }
        });
    </script>

</x-app-layout>

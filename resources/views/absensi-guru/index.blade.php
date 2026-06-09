<x-app-layout>

    <x-page-header
        title="Absensi Guru"
        subtitle="Presensi Berbasis GPS dan Verifikasi Wajah"
    />

    <div class="space-y-6">

        {{-- ALERT --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-xl">
                {{ session('error') }}
            </div>
        @endif

        {{-- HEADER CARD --}}
        <div class="grid md:grid-cols-3 gap-6">

            {{-- PROFIL GURU --}}
            <div class="bg-white rounded-2xl shadow-sm border p-6">

                <div class="text-center">

                    @if($guru->foto_wajah)
                        <img
                            src="{{ asset('storage/'.$guru->foto_wajah) }}"
                            class="w-32 h-32 rounded-full mx-auto object-cover border-4 border-blue-100">
                    @else
                        <div class="w-32 h-32 rounded-full bg-blue-100 flex items-center justify-center mx-auto text-4xl">
                            👨‍🏫
                        </div>
                    @endif

                    <h3 class="text-xl font-bold text-gray-800 mt-4">
                        {{ $guru->nama }}
                    </h3>

                    <p class="text-gray-500">
                        Guru
                    </p>

                </div>

            </div>

            {{-- STATUS ABSENSI --}}
            <div class="md:col-span-2 bg-white rounded-2xl shadow-sm border p-6">

                <h3 class="font-bold text-lg mb-5">
                    Status Kehadiran Hari Ini
                </h3>

                @if($absensiHariIni)

                <div class="grid md:grid-cols-4 gap-4">

                    <div class="bg-green-50 rounded-xl p-4 border border-green-200">
                        <div class="text-sm text-gray-500">Status</div>
                        <div class="text-xl font-bold text-green-700">
                            {{ $absensiHariIni->status }}
                        </div>
                    </div>

                    <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
                        <div class="text-sm text-gray-500">Jam Masuk</div>
                        <div class="text-xl font-bold text-blue-700">
                            {{ $absensiHariIni->jam_masuk }}
                        </div>
                    </div>

                    <div class="bg-orange-50 rounded-xl p-4 border border-orange-200">
                        <div class="text-sm text-gray-500">Jam Pulang</div>
                        <div class="text-xl font-bold text-orange-700">
                            {{ $absensiHariIni->jam_pulang ?? '-' }}
                        </div>
                    </div>

                    <div class="bg-purple-50 rounded-xl p-4 border border-purple-200">
                        <div class="text-sm text-gray-500">Kehadiran</div>

                        <div class="text-xl font-bold text-purple-700">

                            @if(!$absensiHariIni->jam_pulang)
                                Sedang Bertugas
                            @else
                                Selesai
                            @endif

                        </div>
                    </div>

                </div>

                @if($absensiHariIni->alamat)

                <div class="mt-4 bg-gray-50 border rounded-xl p-4">

                    <div class="text-sm text-gray-500 mb-1">
                        Lokasi Absensi
                    </div>

                    <div class="font-medium text-gray-800">
                        {{ $absensiHariIni->alamat }}
                    </div>

                </div>

                @endif

                @else

                <div class="bg-yellow-50 border border-yellow-200 p-5 rounded-xl">
                    Anda belum melakukan absensi hari ini.
                </div>

                @endif

            </div>

        </div>

        {{-- FORM ABSENSI --}}
        <div class="bg-white rounded-2xl shadow-sm border p-6">

            <h3 class="font-bold text-lg mb-6">
                Form Absensi
            </h3>

            <video
        id="camera"
        autoplay
        playsinline
        width="100%"
    ></video>

    <canvas
        id="canvas"
        style="display:none"
    ></canvas>

    <input
        type="hidden"
        name="foto_base64"
        id="foto_base64"
    >

            <script>

            navigator.mediaDevices
            .getUserMedia({
                video:true
            })
            .then(stream => {

                document
                .getElementById('camera')
                .srcObject = stream;

            });

            function capturePhoto()
            {
                let video =
                    document.getElementById('camera');

                let canvas =
                    document.getElementById('canvas');

                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;

                let ctx =
                    canvas.getContext('2d');

                ctx.drawImage(
                    video,
                    0,
                    0
                );

                document
                .getElementById('foto_base64')
                .value =
                canvas.toDataURL(
                    'image/jpeg'
                );
            }

            </script>

            <form
                action="{{ route('absensi-guru.store') }}"
                method="POST"
                onsubmit="capturePhoto()"
            >

                @csrf

                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">

                <script>
                navigator.geolocation.getCurrentPosition(function(position){

                    document.getElementById('latitude').value =
                        position.coords.latitude;

                    document.getElementById('longitude').value =
                        position.coords.longitude;

                });
                </script>

                <input type="hidden" name="alamat" id="alamat">

                <div class="grid md:grid-cols-2 gap-6">

                    {{-- GPS --}}
                    <div>

                        <label class="block font-medium mb-2">
                            Lokasi GPS
                        </label>

                        <div
                            id="gps-info"
                            class="bg-blue-50 border border-blue-200 rounded-xl p-4">

                            Mendeteksi lokasi...

                        </div>

                    </div>

                    {{-- SELFIE --}}
                    <div>

                        <label class="block font-medium mb-2">
                            Selfie Verifikasi Wajah
                        </label>

                        <input
                            type="file"
                            name="foto"
                            id="foto"
                            accept="image/*"
                            capture="user"
                            class="w-full rounded-lg border">

                    </div>

                </div>

                {{-- PREVIEW FOTO --}}
                <div class="mt-6">

                    <img
                        id="preview"
                        class="hidden w-48 rounded-xl border shadow-sm">

                </div>

                <div class="bg-white rounded-xl border p-4">

                    <h3 class="font-semibold mb-3">
                        Kamera Absensi
                    </h3>

                    <video
                        id="video"
                        autoplay
                        playsinline
                        class="w-full rounded-lg border"
                        style="max-height:350px"
                    ></video>

                    <canvas
                        id="canvas"
                        style="display:none"
                    ></canvas>

                    <input
                        type="hidden"
                        name="foto_base64"
                        id="foto_base64"
                    >

                </div>

                <div class="mt-8">

                    @if(!$absensiHariIni)

                    <button
                        type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-semibold">

                        📍 Absen Masuk

                    </button>

                    @elseif(!$absensiHariIni->jam_pulang)

                    <button
                        type="submit"
                        class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-xl font-semibold">

                        🏠 Absen Pulang

                    </button>

                    @else

                    <button
                        disabled
                        class="bg-gray-400 text-white px-6 py-3 rounded-xl cursor-not-allowed">

                        ✔ Absensi Hari Ini Selesai

                    </button>

                    @endif

                </div>

            </form>

        </div>

        {{-- RIWAYAT --}}
        <div class="bg-white rounded-2xl shadow-sm border p-6">

            <h3 class="font-bold text-lg mb-5">
                Riwayat Absensi Terakhir
            </h3>

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead>

                        <tr class="bg-gray-100">

                            <th class="p-3 text-left">Tanggal</th>
                            <th class="p-3 text-left">Masuk</th>
                            <th class="p-3 text-left">Pulang</th>
                            <th class="p-3 text-left">Status</th>
                            <th class="p-3 text-left">Foto</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($riwayat as $item)

                            <tr class="border-b">

                                <td class="p-3">
                                    {{ $item->tanggal }}
                                </td>

                                <td class="p-3">
                                    {{ $item->jam_masuk }}
                                </td>

                                <td class="p-3">
                                    {{ $item->jam_pulang ?? '-' }}
                                </td>

                                <td class="p-3">

                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg">
                                        {{ $item->status }}
                                    </span>

                                </td>

                                <td>
                                <img
                                    src="{{ asset('storage/'.$item->foto_absensi) }}"
                                    width="120"
                                >
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4" class="p-5 text-center text-gray-500">
                                    Belum ada riwayat absensi.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    {{-- GPS --}}
   <script>

        navigator.geolocation.getCurrentPosition(

        async function(position){

            let lat = position.coords.latitude;
            let lng = position.coords.longitude;

            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;

            try {

                const response = await fetch(
                    `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`
                );

                const data = await response.json();

                let alamat = data.display_name;

                document.getElementById('alamat').value = alamat;

                document.getElementById('gps-info').innerHTML = `

                    <div class="space-y-2">

                        <div>
                            <span class="font-semibold text-green-600">
                                GPS Terdeteksi ✓
                            </span>
                        </div>

                        <div>
                            <strong>Latitude :</strong>
                            ${lat}
                        </div>

                        <div>
                            <strong>Longitude :</strong>
                            ${lng}
                        </div>

                        <div>
                            <strong>Alamat :</strong><br>
                            ${alamat}
                        </div>

                    </div>

                `;

            }
            catch(error){

                document.getElementById('gps-info').innerHTML = `
                    <div class="text-red-600">
                        Gagal mengambil alamat lokasi.
                    </div>
                `;
            }

        },

        function(){

            document.getElementById('gps-info').innerHTML = `
                <div class="text-red-600">
                    GPS tidak aktif.
                </div>
            `;
        }

        );

        </script>

    {{-- PREVIEW FOTO --}}
    <script>

        document
            .getElementById('foto')
            .addEventListener('change', function(e){

                const preview =
                    document.getElementById('preview');

                preview.src =
                    URL.createObjectURL(
                        e.target.files[0]
                    );

                preview.classList.remove('hidden');

            });

    </script>

    <script>
    const video =
        document.getElementById('video');

    navigator.mediaDevices
    .getUserMedia({
        video:true
    })
    .then(stream => {

        video.srcObject = stream;

    })
    .catch(error => {

        alert(
            'Kamera tidak dapat diakses'
        );

    });
    </script>

    <script>

    function capturePhoto(){

        const canvas =
            document.getElementById('canvas');

        canvas.width =
            video.videoWidth;

        canvas.height =
            video.videoHeight;

        canvas
            .getContext('2d')
            .drawImage(
                video,
                0,
                0
            );

        document
            .getElementById('foto_base64')
            .value =
            canvas.toDataURL(
                'image/png'
            );

        return true;
    }

    </script>

    {!! QrCode::size(200)
    ->generate($token) !!}


</x-app-layout>
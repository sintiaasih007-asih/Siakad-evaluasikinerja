<x-app-layout>

    <x-page-header
        title="Laporan Absensi Guru"
        subtitle="Dashboard / Akademik / Laporan Absensi Guru"
    />

    <div class="bg-white rounded-2xl shadow-sm border p-6">

        {{-- FILTER --}}
        <form method="GET" class="grid md:grid-cols-5 gap-4 mb-6">

            <div>
                <label class="text-sm font-medium">Tanggal Awal</label>
                <input
                    type="date"
                    name="tanggal_awal"
                    value="{{ request('tanggal_awal') }}"
                    class="w-full rounded-lg border-gray-300"
                >
            </div>

            <div>
                <label class="text-sm font-medium">Tanggal Akhir</label>
                <input
                    type="date"
                    name="tanggal_akhir"
                    value="{{ request('tanggal_akhir') }}"
                    class="w-full rounded-lg border-gray-300"
                >
            </div>

            <div>
                <label class="text-sm font-medium">Guru</label>
                <select
                    name="guru"
                    class="w-full rounded-lg border-gray-300"
                >
                    <option value="">Semua Guru</option>

                    @foreach($guru as $g)
                        <option
                            value="{{ $g->id }}"
                            {{ request('guru') == $g->id ? 'selected' : '' }}
                        >
                            {{ $g->nama }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div>
                <label class="text-sm font-medium">Status</label>
                <select
                    name="status"
                    class="w-full rounded-lg border-gray-300"
                >
                    <option value="">Semua</option>
                    <option value="Hadir" {{ request('status')=='Hadir'?'selected':'' }}>Hadir</option>
                    <option value="Terlambat" {{ request('status')=='Terlambat'?'selected':'' }}>Terlambat</option>
                    <option value="Izin" {{ request('status')=='Izin'?'selected':'' }}>Izin</option>
                    <option value="Sakit" {{ request('status')=='Sakit'?'selected':'' }}>Sakit</option>
                    <option value="Alpa" {{ request('status')=='Alpa'?'selected':'' }}>Alpa</option>
                </select>
            </div>

            <div class="flex items-end gap-2">

                <button
                    type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg"
                >
                    Filter
                </button>

                <a
                    href="{{ route('laporan-absensi-guru.index') }}"
                    class="bg-gray-500 text-white px-4 py-2 rounded-lg"
                >
                    Reset
                </a>

            </div>

        </form>

        {{-- REKAP --}}
        <div class="grid md:grid-cols-4 gap-4 mb-6">

            <div class="bg-green-50 border rounded-xl p-4">
                <p class="text-sm text-gray-500">Hadir</p>
                <h2 class="text-2xl font-bold text-green-600">
                    {{ $hadir }}
                </h2>
            </div>

            <div class="bg-yellow-50 border rounded-xl p-4">
                <p class="text-sm text-gray-500">Terlambat</p>
                <h2 class="text-2xl font-bold text-yellow-600">
                    {{ $terlambat }}
                </h2>
            </div>

            <div class="bg-blue-50 border rounded-xl p-4">
                <p class="text-sm text-gray-500">Izin/Sakit</p>
                <h2 class="text-2xl font-bold text-blue-600">
                    {{ $izin }}
                </h2>
            </div>

            <div class="bg-red-50 border rounded-xl p-4">
                <p class="text-sm text-gray-500">Alpa</p>
                <h2 class="text-2xl font-bold text-red-600">
                    {{ $alpa }}
                </h2>
            </div>

        </div>

        {{-- TABEL --}}
        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Guru</th>
                        <th class="px-4 py-3">Jam Masuk</th>
                        <th class="px-4 py-3">Jam Pulang</th>
                        <th class="px-4 py-3">Lokasi</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Foto</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($absensi as $item)

                    <tr class="border-b">

                        <td class="px-4 py-3">
                            {{ $loop->iteration }}
                        </td>

                        <td class="px-4 py-3">
                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $item->guru->nama }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $item->jam_masuk }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $item->jam_pulang }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $item->latitude }},
                            {{ $item->longitude }}
                        </td>

                        <td class="px-4 py-3">

                            @if($item->status == 'Hadir')
                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full">
                                    Hadir
                                </span>
                            @elseif($item->status == 'Terlambat')
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full">
                                    Terlambat
                                </span>
                            @elseif($item->status == 'Izin')
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full">
                                    Izin
                                </span>
                            @elseif($item->status == 'Sakit')
                                <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full">
                                    Sakit
                                </span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full">
                                    Alpa
                                </span>
                            @endif

                        </td>

                        <td class="px-4 py-3">

                            @if($item->foto)
                                <img
                                    src="{{ asset('storage/'.$item->foto) }}"
                                    class="w-14 h-14 rounded-lg object-cover"
                                >
                            @endif

                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="8" class="text-center py-8 text-gray-500">
                            Data tidak ditemukan
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-6">
            {{ $absensi->links() }}
        </div>

        {{-- CETAK --}}
        <div class="mt-6 flex gap-3">

            <a
                href="{{ route('laporan-absensi-guru.pdf', request()->all()) }}"
                target="_blank"
                class="bg-red-600 text-white px-5 py-2 rounded-lg"
            >
                Export PDF
            </a>

            <a
                href="{{ route('laporan-absensi-guru.excel', request()->all()) }}"
                class="bg-green-600 text-white px-4 py-2 rounded-xl"
            >
                Export Excel
            </a>

        </div>

    </div>


    <video id="video" autoplay></video>

    <div id="faceStatus">
        Menunggu verifikasi wajah...
    </div>

    <input
        type="hidden"
        id="face_verified"
        name="face_verified"
        value="0"
    >

    <button
        id="btnAbsen"
        type="submit"
        disabled
    >
        Absen Sekarang
    </button>

    <script src="https://cdn.jsdelivr.net/npm/face-api.js"></script>

    <script>

    const guruDescriptor =
    {!! $guru->face_descriptor !!};


    // STEP 11
    const storedDescriptor =
    new Float32Array(guruDescriptor);

    const labeledDescriptor =
    new faceapi.LabeledFaceDescriptors(
        'Guru',
        [storedDescriptor]
    );

    const matcher =
    new faceapi.FaceMatcher(
        labeledDescriptor,
        0.6
    );


    // STEP 6
    Promise.all([

        faceapi.nets.tinyFaceDetector.loadFromUri('/models'),

        faceapi.nets.faceLandmark68Net.loadFromUri('/models'),

        faceapi.nets.faceRecognitionNet.loadFromUri('/models')

    ]).then(startCamera);


    async function startCamera()
    {
        const stream =
        await navigator.mediaDevices.getUserMedia({
            video:true
        });

        video.srcObject = stream;
    }


    // STEP 12
    setInterval(async ()=>{

        const detection =
        await faceapi
        .detectSingleFace(
            video,
            new faceapi.TinyFaceDetectorOptions()
        )
        .withFaceLandmarks()
        .withFaceDescriptor();

        if(!detection) return;

        const result =
        matcher.findBestMatch(
            detection.descriptor
        );

        if(result.label == 'Guru')
        {
            faceStatus.innerHTML =
            '✅ Wajah Cocok';

            face_verified.value = 1;

            btnAbsen.disabled = false;
        }
        else
        {
            faceStatus.innerHTML =
            '❌ Wajah Tidak Cocok';

            face_verified.value = 0;

            btnAbsen.disabled = true;
        }

    },1000);

    </script>

    

</x-app-layout>
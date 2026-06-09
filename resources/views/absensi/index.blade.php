{{-- resources/views/absensi/index.blade.php --}}

<x-app-layout>

    <x-page-header
        title="Absensi Siswa"
        subtitle="Jadwal Hari Ini ({{ $hariIni }})"
    />

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- BUTTON --}}
            <div class="flex justify-end mb-5">

                <button
                    onclick="openRiwayatModal()"
                    class="inline-flex items-center gap-2 bg-white border border-slate-200 hover:border-indigo-300 hover:bg-indigo-50 text-slate-700 hover:text-indigo-700 px-5 py-2.5 rounded-2xl text-sm font-semibold shadow-sm transition-all duration-300"
                >

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>

                    Riwayat Absensi

                </button>

            </div>

            {{-- CARD --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

                @forelse($jadwals as $j)

                    @php
                        $now = \Carbon\Carbon::now();

                        $mulai = \Carbon\Carbon::today()->setTimeFromTimeString($j->jam_masuk);
                        $selesai = \Carbon\Carbon::today()->setTimeFromTimeString($j->jam_selesai);

                        $isActive = $now->between($mulai, $selesai);
                        $isFinished = $now->greaterThan($selesai);
                        $isUpcoming = $now->lessThan($mulai);

                        $sudahAbsen = \App\Models\Absensi::where('jadwal_id', $j->id)
                            ->whereDate('tanggal', now()->toDateString())
                            ->exists();
                    @endphp

                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 p-6 relative overflow-hidden">

                        {{-- TOP LINE --}}
                        <div class="absolute top-0 left-0 w-full h-1
                            {{ $sudahAbsen ? 'bg-indigo-500' : ($isActive ? 'bg-green-500' : ($isFinished ? 'bg-gray-400' : 'bg-blue-500')) }}">
                        </div>

                        {{-- HEADER --}}
                        <div class="flex justify-between items-start mb-4">

                            <h3 class="text-lg font-semibold text-gray-800">
                                {{ $j->mapel->nama_mapel }}
                            </h3>

                            @if($sudahAbsen)

                                <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-1 rounded-full">
                                    Sudah Diisi
                                </span>

                            @elseif($isActive)

                                <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">
                                    Berlangsung
                                </span>

                            @elseif($isFinished)

                                <span class="text-xs bg-gray-200 text-gray-600 px-2 py-1 rounded-full">
                                    Selesai
                                </span>

                            @else

                                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">
                                    Akan Datang
                                </span>

                            @endif

                        </div>

                        {{-- INFO --}}
                        <div class="space-y-2 text-sm text-gray-600 mb-5">

                            <div class="flex justify-between">
                                <span>Kelas</span>

                                <span class="font-medium text-gray-800">
                                    {{ $j->kelas->nama_kelas }}
                                </span>
                            </div>

                            <div class="flex justify-between">
                                <span>Guru</span>

                                <span class="font-medium text-gray-800">
                                    {{ $j->guru->nama }}
                                </span>
                            </div>

                            <div class="flex justify-between">
                                <span>Waktu</span>

                                <span class="font-medium">
                                    {{ substr($j->jam_masuk,0,5) }}
                                    -
                                    {{ substr($j->jam_selesai,0,5) }}
                                </span>
                            </div>

                        </div>

                        {{-- BUTTON --}}
                        @if($sudahAbsen)

                            <button disabled
                                class="w-full bg-indigo-100 text-indigo-500 py-2.5 rounded-xl text-sm font-semibold cursor-not-allowed">
                                Sudah Diisi Hari Ini
                            </button>

                        @elseif($isFinished)

                            <button disabled
                                class="w-full bg-gray-300 text-gray-600 py-2.5 rounded-xl text-sm font-semibold cursor-not-allowed">
                                Sudah Selesai
                            </button>

                        @elseif($isUpcoming)

                            <button disabled
                                class="w-full bg-blue-100 text-blue-400 py-2.5 rounded-xl text-sm font-semibold cursor-not-allowed">
                                Belum Mulai
                            </button>

                        @else

                            <a href="{{ route('absensi.create', $j->id) }}"
                                class="block w-full text-center bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-indigo-600 hover:to-blue-600 text-white py-2.5 rounded-xl text-sm font-semibold transition-all duration-300">

                                Isi Absensi

                            </a>

                        @endif

                    </div>

                @empty

                    <div class="col-span-3 text-center py-10">
                        <p class="text-gray-400 text-sm">
                            Tidak ada jadwal hari ini
                        </p>
                    </div>

                @endforelse

            </div>

        </div>
    </div>

    {{-- MODAL KELAS --}}
    <div id="riwayatModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4">

        <div class="bg-white w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden">

            {{-- HEADER --}}
            <div class="flex justify-between items-center px-6 py-5 border-b">

                <div>
                    <h2 class="text-xl font-bold text-slate-800">
                        Riwayat Absensi
                    </h2>

                    <p class="text-sm text-slate-500">
                        Pilih kelas untuk melihat riwayat absensi
                    </p>
                </div>

                <button
                    onclick="closeRiwayatModal()"
                    class="w-10 h-10 rounded-xl hover:bg-slate-100">
                    ✕
                </button>

            </div>

            {{-- BODY --}}
            <div class="p-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    @foreach($kelasDiampu as $kelas)

                        <button
                            onclick="openDetailModal({{ $kelas->id }})"
                            class="flex items-center justify-between p-4 rounded-2xl border border-slate-200 hover:border-indigo-400 hover:bg-indigo-50 transition-all duration-300">

                            <div class="text-left">

                                <h3 class="font-semibold text-slate-800">
                                    {{ $kelas->nama_kelas }}
                                </h3>

                                <p class="text-sm text-slate-500">
                                    Lihat riwayat absensi siswa
                                </p>

                            </div>

                            <span class="text-xl">
                                →
                            </span>

                        </button>

                    @endforeach

                </div>

            </div>

        </div>

    </div>

    {{-- DETAIL MODAL --}}
    @foreach($kelasDiampu as $kelas)

        <div
            id="detailModal{{ $kelas->id }}"
            class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4"
        >

            <div class="bg-white w-full max-w-7xl rounded-3xl shadow-2xl overflow-hidden">

                {{-- HEADER --}}
                <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-6 py-5 flex items-center justify-between">

                    <div>

                        <h2 class="text-2xl font-bold text-white">
                            Riwayat Absensi
                        </h2>

                        <p class="text-indigo-100 text-sm mt-1">
                            Kelas {{ $kelas->nama_kelas }}
                        </p>

                    </div>

                    <button
                        onclick="closeDetailModal({{ $kelas->id }})"
                        class="w-10 h-10 rounded-xl bg-white/20 hover:bg-white/30 text-white">
                        ✕
                    </button>

                </div>

                {{-- SEARCH --}}
                <div class="p-6 border-b bg-slate-50">

                    <div class="relative">

                        <input
                            type="text"
                            id="search{{ $kelas->id }}"
                            onkeyup="searchSiswa({{ $kelas->id }})"
                            placeholder="Cari nama siswa..."
                            class="w-full rounded-2xl border-slate-200 pl-12 py-3 focus:ring-2 focus:ring-indigo-500"
                        >

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5 absolute left-4 top-3.5 text-slate-400"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110 3a7.5 7.5 0 016.65 13.65z"/>

                        </svg>

                    </div>

                </div>

                {{-- BODY --}}
                <div class="p-6 max-h-[75vh] overflow-y-auto bg-slate-50">

                    @php

                        $guruId = auth()->user()->guru_id;

                        $riwayatKelas = \App\Models\AbsensiDetail::with([
                                'siswa',
                                'absensi'
                            ])
                            ->whereHas('siswa', function($q) use ($kelas) {

                                $q->where('kelas_id', $kelas->id);

                            })

                            ->whereHas('absensi.jadwal', function($q) use ($guruId) {

                                $q->where('guru_id', $guruId);

                            })

                            ->orderBy('created_at', 'desc')

                            ->get()

                            ->groupBy(function($item) {

                                return $item->absensi->pertemuan;

                            });

                    @endphp

                    @forelse($riwayatKelas as $pertemuan => $items)

                        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden mb-8">

                            {{-- PERTEMUAN --}}
                            <div class="bg-gradient-to-r from-indigo-50 to-blue-50 px-6 py-4 border-b">

                                <div class="flex items-center justify-between">

                                    <div>

                                        <h3 class="text-lg font-bold text-slate-800">
                                            Pertemuan {{ $pertemuan }}
                                        </h3>

                                        <p class="text-sm text-slate-500">
                                            {{ \Carbon\Carbon::parse($items->first()->absensi->tanggal)->translatedFormat('d F Y') }}
                                        </p>

                                    </div>

                                </div>

                            </div>

                            {{-- TABLE --}}
                            <div class="overflow-x-auto">

                                <table class="w-full">

                                    <thead class="bg-slate-100">

                                        <tr>

                                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase">
                                                No
                                            </th>

                                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase">
                                                Nama Siswa
                                            </th>

                                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase">
                                                Status
                                            </th>

                                        </tr>

                                    </thead>

                                    <tbody class="divide-y divide-slate-100">

                                        @foreach($items as $i => $detail)

                                            <tr class="hover:bg-slate-50 siswa-row{{ $kelas->id }}">

                                                <td class="px-6 py-4 text-sm text-slate-600">
                                                    {{ $i + 1 }}
                                                </td>

                                                <td class="px-6 py-4">

                                                    <div class="font-semibold text-slate-800 siswa-name">
                                                        {{ $detail->siswa->nama }}
                                                    </div>

                                                </td>

                                                <td class="px-6 py-4">

                                                    <select
                                                        onchange="updateStatus(this)"
                                                        data-id="{{ $detail->id }}"
                                                        class="rounded-xl border-slate-200 text-sm font-medium">

                                                        <option value="hadir"
                                                            {{ $detail->status == 'hadir' ? 'selected' : '' }}>
                                                            Hadir
                                                        </option>

                                                        <option value="izin"
                                                            {{ $detail->status == 'izin' ? 'selected' : '' }}>
                                                            Izin
                                                        </option>

                                                        <option value="sakit"
                                                            {{ $detail->status == 'sakit' ? 'selected' : '' }}>
                                                            Sakit
                                                        </option>

                                                        <option value="alpha"
                                                            {{ $detail->status == 'alpha' ? 'selected' : '' }}>
                                                            Alpha
                                                        </option>

                                                    </select>

                                                </td>

                                            </tr>

                                        @endforeach

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    @empty

                        <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center">

                            <div class="text-6xl mb-4">
                                📋
                            </div>

                            <h3 class="text-lg font-semibold text-slate-700">
                                Belum Ada Riwayat Absensi
                            </h3>

                            <p class="text-sm text-slate-500 mt-2">
                                Data riwayat absensi belum tersedia
                            </p>

                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    @endforeach

    {{-- SCRIPT --}}
    <script>

        /*
        |--------------------------------------------------------------------------
        | MODAL KELAS
        |--------------------------------------------------------------------------
        */

        function openRiwayatModal() {

            document.getElementById('riwayatModal')
                .classList.remove('hidden');

            document.getElementById('riwayatModal')
                .classList.add('flex');
        }

        function closeRiwayatModal() {

            document.getElementById('riwayatModal')
                .classList.remove('flex');

            document.getElementById('riwayatModal')
                .classList.add('hidden');
        }

        /*
        |--------------------------------------------------------------------------
        | MODAL DETAIL
        |--------------------------------------------------------------------------
        */

        function openDetailModal(id) {

            document.getElementById('detailModal' + id)
                .classList.remove('hidden');

            document.getElementById('detailModal' + id)
                .classList.add('flex');
        }

        function closeDetailModal(id) {

            document.getElementById('detailModal' + id)
                .classList.remove('flex');

            document.getElementById('detailModal' + id)
                .classList.add('hidden');
        }

        /*
        |--------------------------------------------------------------------------
        | SEARCH SISWA
        |--------------------------------------------------------------------------
        */

        function searchSiswa(kelasId) {

            let input = document.getElementById('search' + kelasId);

            let filter = input.value.toLowerCase();

            let rows = document.querySelectorAll('.siswa-row' + kelasId);

            rows.forEach(row => {

                let nama = row.querySelector('.siswa-name')
                    .innerText
                    .toLowerCase();

                row.style.display = nama.includes(filter)
                    ? ''
                    : 'none';
            });
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE STATUS
        |--------------------------------------------------------------------------
        */

        function updateStatus(select) {

            let detailId = select.dataset.id;
            let status = select.value;

            fetch("{{ route('absensi.update-detail') }}", {

                method: "POST",

                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },

                body: JSON.stringify({
                    detail_id: detailId,
                    status: status
                })

            })
            .then(res => res.json())
            .then(data => {

                if(data.success) {

                    select.classList.add(
                        'ring-2',
                        'ring-green-500'
                    );

                    setTimeout(() => {

                        select.classList.remove(
                            'ring-2',
                            'ring-green-500'
                        );

                    }, 1000);

                }

            });

        }

    </script>

</x-app-layout>
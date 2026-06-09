<x-app-layout>

    {{-- HEADER --}}
    <x-page-header
        title="Riwayat Akademik Siswa"
        subtitle="Dashboard / Akademik / Riwayat Kelas"
    />

    @php

        /*
        |--------------------------------------------------------------------------
        | LOGIKA OTOMATIS
        |--------------------------------------------------------------------------
        | Jika tabel riwayat_kelas masih kosong,
        | maka sistem otomatis mengambil data dari tabel siswas
        | agar siswa aktif langsung tampil di halaman riwayat.
        */

        $dataRiwayat = $riwayat;

        if ($dataRiwayat->count() == 0) {

            $dataRiwayat = $siswas->map(function ($siswa) {

                return (object)[
                    'siswa' => $siswa,
                    'kelas' => $siswa->kelas,
                    'tahun_ajaran' => date('Y') . '/' . (date('Y') + 1),
                    'status' => $siswa->status ?? 'aktif',
                    'updated_at' => $siswa->updated_at,
                ];
            });

        }

    @endphp

    <div
        x-data="{
            search: '',
            statusFilter: '',
            kelasFilter: ''
        }"
        class="space-y-6"
    >

        {{-- =============================== --}}
        {{-- STATISTIK --}}
        {{-- =============================== --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5">

            {{-- TOTAL RIWAYAT --}}
            <div class="bg-white rounded-2xl shadow-sm border p-5">
                <div class="flex items-center justify-between">

                    <div>
                        <p class="text-sm text-gray-500">
                            Total Riwayat
                        </p>

                        <h2 class="text-3xl font-bold text-slate-800 mt-1">
                            {{ $dataRiwayat->count() }}
                        </h2>
                    </div>

                    <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center">
                        <i data-lucide="history"
                           class="w-7 h-7 text-blue-600">
                        </i>
                    </div>

                </div>
            </div>

            {{-- AKTIF --}}
            <div class="bg-white rounded-2xl shadow-sm border p-5">
                <div class="flex items-center justify-between">

                    <div>
                        <p class="text-sm text-gray-500">
                            Siswa Aktif
                        </p>

                        <h2 class="text-3xl font-bold text-blue-700 mt-1">
                            {{ $dataRiwayat->where('status', 'aktif')->count() }}
                        </h2>
                    </div>

                    <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center">
                        <i data-lucide="users"
                           class="w-7 h-7 text-blue-600">
                        </i>
                    </div>

                </div>
            </div>

            {{-- NAIK KELAS --}}
            <div class="bg-white rounded-2xl shadow-sm border p-5">
                <div class="flex items-center justify-between">

                    <div>
                        <p class="text-sm text-gray-500">
                            Naik Kelas
                        </p>

                        <h2 class="text-3xl font-bold text-green-700 mt-1">
                            {{ $dataRiwayat->where('status', 'naik_kelas')->count() }}
                        </h2>
                    </div>

                    <div class="w-14 h-14 rounded-2xl bg-green-100 flex items-center justify-center">
                        <i data-lucide="arrow-up-circle"
                           class="w-7 h-7 text-green-600">
                        </i>
                    </div>

                </div>
            </div>

            {{-- LULUS --}}
            <div class="bg-white rounded-2xl shadow-sm border p-5">
                <div class="flex items-center justify-between">

                    <div>
                        <p class="text-sm text-gray-500">
                            Alumni
                        </p>

                        <h2 class="text-3xl font-bold text-purple-700 mt-1">
                            {{ $dataRiwayat->where('status', 'lulus')->count() }}
                        </h2>
                    </div>

                    <div class="w-14 h-14 rounded-2xl bg-purple-100 flex items-center justify-center">
                        <i data-lucide="graduation-cap"
                           class="w-7 h-7 text-purple-600">
                        </i>
                    </div>

                </div>
            </div>

        </div>


        {{-- =============================== --}}
        {{-- FILTER --}}
        {{-- =============================== --}}
        <div class="bg-white rounded-2xl shadow-sm border p-6">

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                <div>
                    <h2 class="text-lg font-semibold text-gray-800">
                        Data Riwayat Akademik
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Menampilkan seluruh histori kelas siswa dari tahun ke tahun
                    </p>
                </div>

                <div class="flex flex-col md:flex-row gap-3">

                    {{-- SEARCH --}}
                    <div class="relative">

                        <input
                            type="text"
                            x-model="search"
                            placeholder="Cari nama siswa / NIS..."
                            class="w-full md:w-72 border border-gray-300 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >

                        <i data-lucide="search"
                           class="w-4 h-4 absolute left-3 top-3 text-gray-400">
                        </i>

                    </div>

                    {{-- FILTER STATUS --}}
                    <select
                        x-model="statusFilter"
                        class="border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                        <option value="">Semua Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="naik_kelas">Naik Kelas</option>
                        <option value="lulus">Lulus</option>
                    </select>

                    {{-- FILTER KELAS --}}
                    <select
                        x-model="kelasFilter"
                        class="border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                        <option value="">Semua Kelas</option>

                        @foreach(
                            $dataRiwayat
                                ->filter(fn($item) => $item->kelas)
                                ->pluck('kelas.nama_kelas')
                                ->unique()
                                ->sort()
                            as $kelas
                        )

                            <option value="{{ $kelas }}">
                                {{ $kelas }}
                            </option>

                        @endforeach

                    </select>

                </div>

            </div>

        </div>


        {{-- =============================== --}}
        {{-- TABLE --}}
        {{-- =============================== --}}
        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full text-sm text-left">

                    <thead class="bg-slate-50 border-b text-slate-600 uppercase text-xs">

                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Siswa</th>
                            <th class="px-6 py-4">NIS</th>
                            <th class="px-6 py-4">Kelas</th>
                            <th class="px-6 py-4">Tahun Ajaran</th>
                            <th class="px-6 py-4">Status Akademik</th>
                            <th class="px-6 py-4">Terakhir Update</th>
                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse($dataRiwayat as $r)

                        <tr
                            class="hover:bg-slate-50 transition"

                            x-show="
                                (
                                    '{{ strtolower($r->siswa->nama ?? '') }}'
                                    .includes(search.toLowerCase())

                                    ||

                                    '{{ strtolower($r->siswa->nis ?? '') }}'
                                    .includes(search.toLowerCase())
                                )
                                &&
                                (
                                    statusFilter == '' ||
                                    statusFilter == '{{ $r->status }}'
                                )
                                &&
                                (
                                    kelasFilter == '' ||
                                    kelasFilter == '{{ $r->kelas->nama_kelas ?? '' }}'
                                )
                            "
                        >

                            {{-- NO --}}
                            <td class="px-6 py-4 text-gray-500">
                                {{ $loop->iteration }}
                            </td>

                            {{-- SISWA --}}
                            <td class="px-6 py-4">

                                <div class="flex items-center gap-3">

                                    <div class="w-11 h-11 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-semibold">
                                        {{ strtoupper(substr($r->siswa->nama ?? 'S',0,1)) }}
                                    </div>

                                    <div>

                                        <h3 class="font-semibold text-gray-800">
                                            {{ $r->siswa->nama ?? '-' }}
                                        </h3>

                                        <p class="text-xs text-gray-500">
                                            Data riwayat akademik siswa
                                        </p>

                                    </div>

                                </div>

                            </td>

                            {{-- NIS --}}
                            <td class="px-6 py-4 text-gray-600">
                                {{ $r->siswa->nis ?? '-' }}
                            </td>

                            {{-- KELAS --}}
                            <td class="px-6 py-4">

                                <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-medium">
                                    {{ $r->kelas->nama_kelas ?? '-' }}
                                </span>

                            </td>

                            {{-- TAHUN AJARAN --}}
                            <td class="px-6 py-4 text-gray-600">
                                {{ $r->tahun_ajaran ?? '-' }}
                            </td>

                            {{-- STATUS --}}
                            <td class="px-6 py-4">

                                @if($r->status == 'aktif')

                                    <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                                        Aktif
                                    </span>

                                @elseif($r->status == 'naik_kelas')

                                    <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                        Naik Kelas
                                    </span>

                                @elseif($r->status == 'lulus')

                                    <span class="px-3 py-1 rounded-full bg-purple-100 text-purple-700 text-xs font-semibold">
                                        Lulus
                                    </span>

                                @else

                                    <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-semibold">
                                        {{ $r->status ?? '-' }}
                                    </span>

                                @endif

                            </td>

                            {{-- UPDATED --}}
                            <td class="px-6 py-4 text-gray-500 text-sm">

                                @if($r->updated_at)
                                    {{ \Carbon\Carbon::parse($r->updated_at)->format('d M Y') }}
                                @else
                                    -
                                @endif

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="7"
                                class="text-center py-14">

                                <div class="flex flex-col items-center justify-center">

                                    <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center mb-4">

                                        <i data-lucide="database"
                                           class="w-10 h-10 text-slate-400">
                                        </i>

                                    </div>

                                    <h3 class="text-lg font-semibold text-gray-700">
                                        Belum Ada Riwayat Akademik
                                    </h3>

                                    <p class="text-sm text-gray-500 mt-1">
                                        Data riwayat kelas siswa belum tersedia
                                    </p>

                                </div>

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</x-app-layout>
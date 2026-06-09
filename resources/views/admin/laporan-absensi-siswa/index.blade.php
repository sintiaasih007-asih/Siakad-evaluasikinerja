<x-app-layout>

    <x-page-header
        title="Laporan Absensi Siswa"
        subtitle="Dashboard / Akademik / Laporan Absensi Siswa"
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
                <label class="text-sm font-medium">Kelas</label>
                <select
                    name="kelas_id"
                    class="w-full rounded-lg border-gray-300"
                >

                <option value="">
                    Semua Kelas
                </option>

                @foreach($kelas as $item)

                <option
                    value="{{ $item->id }}"
                    {{ request('kelas_id') == $item->id ? 'selected' : '' }}
                >
                    {{ $item->nama_kelas }}
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
                    href="{{ route('laporan-absensi-siswa.index') }}"
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

            <div class="bg-blue-50 border rounded-xl p-4">
                <p class="text-sm text-gray-500">Izin</p>
                <h2 class="text-2xl font-bold text-blue-600">
                    {{ $izin }}
                </h2>
            </div>

            <div class="bg-indigo-50 border rounded-xl p-4">
                <p class="text-sm text-gray-500">Sakit</p>
                <h2 class="text-2xl font-bold text-indigo-600">
                    {{ $sakit }}
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
                        <th class="px-4 py-3">Jam</th>
                        <th class="px-4 py-3">Nama Siswa</th>
                        <th class="px-4 py-3">Mata Pelajaran</th>
                        <th class="px-4 py-3">Nama Guru</th>
                        <th class="px-4 py-3">Status</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($absensi as $item)

                <tr class="border-b">

                    <td class="px-4 py-3">
                        {{ $loop->iteration }}
                    </td>

                    <td class="px-4 py-3">
                        {{ \Carbon\Carbon::parse($item->absensi->tanggal)->format('d M Y') }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $item->absensi->jadwal->jam_masuk ?? '-' }}
                        -
                        {{ $item->absensi->jadwal->jam_selesai ?? '-' }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $item->siswa->nama ?? '-' }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $item->absensi->jadwal->mapel->nama_mapel ?? '-' }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $item->absensi->guru->nama ?? '-' }}
                    </td>

                    <td class="px-4 py-3">

                        @if(strtolower($item->status) == 'hadir')
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full">
                                Hadir
                            </span>

                        @elseif(strtolower($item->status) == 'izin')
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full">
                                Izin
                            </span>

                        @elseif(strtolower($item->status) == 'sakit')
                            <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full">
                                Sakit
                            </span>

                        @else
                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full">
                                Alpa
                            </span>
                        @endif

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="7" class="text-center py-8 text-gray-500">
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
                href="{{ route('laporan-absensi-siswa.pdf', request()->all()) }}"
                target="_blank"
                class="bg-red-600 text-white px-5 py-2 rounded-lg"
            >
                Export PDF
            </a>

            <a
                href="{{ route('laporan-absensi-siswa.excel', request()->all()) }}"
                class="bg-green-600 text-white px-4 py-2 rounded-xl"
            >
                Export Excel
            </a>

        </div>

    </div>

</x-app-layout>
<x-app-layout>

<div class="bg-gradient-to-br from-slate-50 to-white min-h-screen">

    @php
        $total = $absensi->count();

        $hadir = $absensi->where('status','hadir')->count();
        $izin  = $absensi->where('status','izin')->count();
        $sakit = $absensi->where('status','sakit')->count();
        $alpha = $absensi->where('status','alpha')->count();

        $persen = $total > 0
            ? round(($hadir / $total) * 100, 1)
            : 0;
    @endphp

    {{-- HEADER --}}
    <div class="mb-6">

        <div class="bg-white border rounded-2xl shadow-sm p-6">

            <h1 class="text-2xl font-bold text-slate-800">
                Rekap Kehadiran Siswa
            </h1>

            <p class="text-slate-500 mt-1">
                Monitoring Kehadiran Anak oleh Orang Tua
            </p>

        </div>

    </div>

    {{-- PROFIL SISWA --}}
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden mb-6">

        <div class="bg-gradient-to-r from-indigo-600 to-blue-500 p-6">

            <h2 class="text-2xl font-bold text-white">
                {{ $siswa->nama }}
            </h2>

            <p class="text-blue-100 mt-1">
                NIS : {{ $siswa->nis }}
            </p>

        </div>

        <div class="grid md:grid-cols-4 gap-6 p-6">

            <div>
                <p class="text-xs uppercase text-gray-500">
                    Kelas
                </p>

                <p class="font-semibold text-slate-800">
                    {{ $siswa->kelas->nama_kelas ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-500">
                    Orang Tua
                </p>

                <p class="font-semibold text-slate-800">
                    {{ $siswa->nama_ortu }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-500">
                    No HP Orang Tua
                </p>

                <p class="font-semibold text-slate-800">
                    {{ $siswa->no_hp_ortu ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-500">
                    Status
                </p>

                <span
                    class="inline-flex px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm font-medium">
                    {{ ucfirst($siswa->status) }}
                </span>
            </div>

        </div>

    </div>

    {{-- STATISTIK --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">

        <div class="bg-white rounded-xl border p-5 shadow-sm">

            <p class="text-sm text-gray-500">
                Total Absensi
            </p>

            <h2 class="text-3xl font-bold text-slate-800">
                {{ $total }}
            </h2>

        </div>

        <div class="bg-white rounded-xl border p-5 shadow-sm">

            <p class="text-sm text-green-600">
                Hadir
            </p>

            <h2 class="text-3xl font-bold text-green-600">
                {{ $hadir }}
            </h2>

        </div>

        <div class="bg-white rounded-xl border p-5 shadow-sm">

            <p class="text-sm text-blue-600">
                Izin
            </p>

            <h2 class="text-3xl font-bold text-blue-600">
                {{ $izin }}
            </h2>

        </div>

        <div class="bg-white rounded-xl border p-5 shadow-sm">

            <p class="text-sm text-yellow-600">
                Sakit
            </p>

            <h2 class="text-3xl font-bold text-yellow-600">
                {{ $sakit }}
            </h2>

        </div>

        <div class="bg-white rounded-xl border p-5 shadow-sm">

            <p class="text-sm text-red-600">
                Alpha
            </p>

            <h2 class="text-3xl font-bold text-red-600">
                {{ $alpha }}
            </h2>

        </div>

    </div>

    {{-- PROGRESS --}}
    <div class="bg-white rounded-xl border shadow-sm p-6 mb-6">

        <div class="flex justify-between items-center mb-3">

            <h3 class="font-semibold text-slate-800">
                Persentase Kehadiran
            </h3>

            <span class="font-bold text-green-600">
                {{ $persen }}%
            </span>

        </div>

        <div class="w-full h-4 bg-slate-200 rounded-full">

            <div
                class="h-4 rounded-full bg-green-500"
                style="width: {{ $persen }}%">
            </div>

        </div>

    </div>

    {{-- TABEL ABSENSI --}}
    <div class="bg-white rounded-2xl border shadow-sm overflow-hidden">

        <div class="px-6 py-4 border-b bg-slate-50">

            <h2 class="font-semibold text-slate-800">
                Detail Kehadiran Siswa
            </h2>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-slate-100">

                    <tr class="text-slate-700 uppercase text-xs">

                        <th class="px-4 py-3 text-center">
                            No
                        </th>

                        <th class="px-4 py-3">
                            Tanggal
                        </th>

                        <th class="px-4 py-3">
                            Mata Pelajaran
                        </th>

                        <th class="px-4 py-3">
                            Guru
                        </th>

                        <th class="px-4 py-3 text-center">
                            Jam Masuk
                        </th>

                        <th class="px-4 py-3 text-center">
                            Jam Selesai
                        </th>

                        <th class="px-4 py-3 text-center">
                            Status
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse($absensi as $i => $item)

                    <tr class="hover:bg-slate-50 transition">

                        <td class="px-4 py-3 text-center font-medium">
                            {{ $i + 1 }}
                        </td>

                        <td class="px-4 py-3">
                            {{ \Carbon\Carbon::parse($item->absensi->tanggal)->format('d M Y') }}
                        </td>

                        <td class="px-4 py-3 font-medium text-slate-700">
                            {{ $item->absensi->jadwal->mapel->nama_mapel ?? '-' }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $item->absensi->jadwal->guru->nama ?? '-' }}
                        </td>

                        <td class="px-4 py-3 text-center">
                            {{ \Carbon\Carbon::parse($item->absensi->jadwal->jam_masuk)->format('H:i') }}
                        </td>

                        <td class="px-4 py-3 text-center">
                            {{ \Carbon\Carbon::parse($item->absensi->jadwal->jam_selesai)->format('H:i') }}
                        </td>

                        <td class="px-4 py-3 text-center">

                            @if($item->status == 'hadir')

                                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                    Hadir
                                </span>

                            @elseif($item->status == 'izin')

                                <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                                    Izin
                                </span>

                            @elseif($item->status == 'sakit')

                                <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">
                                    Sakit
                                </span>

                            @else

                                <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                                    Alpha
                                </span>

                            @endif

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="7" class="text-center py-10 text-slate-500">

                            Belum ada data absensi

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

</x-app-layout>
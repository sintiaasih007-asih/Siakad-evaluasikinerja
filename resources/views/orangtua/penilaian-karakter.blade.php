<x-app-layout>

    {{-- HEADER --}}
    <x-page-header
        title="Penilaian Karakter Siswa"
        subtitle="Dashboard / Karakter Siswa"
    />

    {{-- FILTER BULAN --}}
    <div class="bg-white rounded-xl border shadow-sm p-5 mb-6">

        <form method="GET" class="flex flex-col md:flex-row md:items-end gap-4">

            <div class="flex-1">
                <label class="text-sm text-gray-600 font-medium">Filter Bulan</label>
                <select name="bulan"
                        class="mt-1 w-full md:w-72 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                    <option value="">Semua Bulan</option>

                    @php
                        $listBulan = [
                            'Januari','Februari','Maret','April','Mei','Juni',
                            'Juli','Agustus','September','Oktober','November','Desember'
                        ];
                    @endphp

                    @foreach($listBulan as $b)
                        <option value="{{ $b }}" {{ request('bulan') == $b ? 'selected' : '' }}>
                            {{ $b }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div class="flex gap-2">
                <button class="px-5 py-2 bg-blue-600 text-white rounded-lg shadow-sm hover:bg-blue-700 transition">
                    Filter
                </button>

                <a href="{{ url()->current() }}"
                   class="px-5 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                    Reset
                </a>
            </div>

        </form>

    </div>

    {{-- RINGKASAN --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">

        <div class="bg-white rounded-xl border shadow-sm p-6 hover:shadow-md transition">
            <p class="text-sm text-gray-500">Rata-rata Sikap</p>
            <h2 class="text-3xl font-bold text-blue-600 mt-2">
                {{ round($sikaps->avg('nilai_sikap') ?? 0, 1) }}
            </h2>
        </div>

        <div class="bg-white rounded-xl border shadow-sm p-6 hover:shadow-md transition">
            <p class="text-sm text-gray-500">Total Penilaian Sikap</p>
            <h2 class="text-3xl font-bold text-green-600 mt-2">
                {{ $sikaps->count() }}
            </h2>
        </div>

        <div class="bg-white rounded-xl border shadow-sm p-6 hover:shadow-md transition">
            <p class="text-sm text-gray-500">Rata-rata Disiplin</p>
            <h2 class="text-3xl font-bold text-orange-600 mt-2">
                {{ round($kedisiplinans->avg('nilai_disiplin') ?? 0, 1) }}
            </h2>
        </div>

    </div>

    {{-- PROFIL SISWA --}}
    <div class="bg-white rounded-xl border shadow-sm overflow-hidden mb-6">

        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-6">
            <h2 class="text-white text-2xl font-semibold">
                {{ $siswa->nama ?? '-' }}
            </h2>
            <p class="text-blue-100 text-sm mt-1">
                NIS : {{ $siswa->nis ?? '-' }}
            </p>
        </div>

        <div class="p-6 grid md:grid-cols-3 gap-6">

            <div>
                <p class="text-sm text-gray-500">Kelas</p>
                <p class="font-semibold text-gray-800 mt-1">
                    {{ $siswa->kelas->nama_kelas ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Orang Tua / Wali</p>
                <p class="font-semibold text-gray-800 mt-1">
                    {{ $siswa->nama_ortu ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Status</p>
                <span class="inline-flex mt-1 px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-medium">
                    Aktif
                </span>
            </div>

        </div>

    </div>

    {{-- SIKAP --}}
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden mb-6">

        <div class="px-6 py-4 bg-gray-50 border-b">
            <h2 class="text-lg font-semibold text-gray-800">SIKAP</h2>
            <p class="text-sm text-gray-500">Rekap penilaian sikap siswa</p>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-100 text-gray-600 text-xs uppercase">
                    <tr>
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Nilai Sikap</th>
                        <th class="px-4 py-3">Keterangan</th>
                        <th class="px-4 py-3">Semester</th>
                        <th class="px-4 py-3">Tanggal</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    @forelse($sikaps as $i => $n)
                    <tr class="hover:bg-gray-50 transition">

                        <td class="px-4 py-3">{{ $i + 1 }}</td>

                        <td class="px-4 py-3 font-semibold">

                            @if($n->nilai_sikap >= 85)
                                <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">
                                    {{ $n->nilai_sikap }} (Baik Sekali)
                                </span>
                            @elseif($n->nilai_sikap >= 75)
                                <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-700">
                                    {{ $n->nilai_sikap }} (Baik)
                                </span>
                            @elseif($n->nilai_sikap >= 65)
                                <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-700">
                                    {{ $n->nilai_sikap }} (Cukup)
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-700">
                                    {{ $n->nilai_sikap }} (Kurang)
                                </span>
                            @endif

                        </td>

                        <td class="px-4 py-3 text-gray-600">
                            {{ $n->keterangan ?? '-' }}
                        </td>

                        <td class="px-4 py-3 text-gray-600">
                            {{ $n->semester }}
                        </td>

                        <td class="px-4 py-3 text-gray-500">
                            {{ \Carbon\Carbon::parse($n->tanggal)->format('d-m-Y') }}
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-6 text-gray-500">
                            Belum ada data sikap.
                        </td>
                    </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    {{-- KEDISIPLINAN --}}
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden mb-6">

        <div class="px-6 py-4 bg-gray-50 border-b">
            <h2 class="text-lg font-semibold text-gray-800">KEDISIPLINAN</h2>
            <p class="text-sm text-gray-500">Rekap penilaian kedisiplinan siswa</p>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-100 text-gray-600 text-xs uppercase">
                    <tr>
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Nilai Disiplin</th>
                        <th class="px-4 py-3">Keterangan</th>
                        <th class="px-4 py-3">Semester</th>
                        <th class="px-4 py-3">Tanggal</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    @forelse($kedisiplinans as $i => $n)
                    <tr class="hover:bg-gray-50 transition">

                        <td class="px-4 py-3">{{ $i + 1 }}</td>

                        <td class="px-4 py-3 font-semibold">

                            @if($n->nilai_disiplin >= 85)
                                <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">
                                    {{ $n->nilai_disiplin }} (Disiplin)
                                </span>
                            @elseif($n->nilai_disiplin >= 75)
                                <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-700">
                                    {{ $n->nilai_disiplin }} (Baik)
                                </span>
                            @elseif($n->nilai_disiplin >= 65)
                                <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-700">
                                    {{ $n->nilai_disiplin }} (Cukup)
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-700">
                                    {{ $n->nilai_disiplin }} (Kurang)
                                </span>
                            @endif

                        </td>

                        <td class="px-4 py-3 text-gray-600">
                            {{ $n->keterangan ?? '-' }}
                        </td>

                        <td class="px-4 py-3 text-gray-600">
                            {{ $n->semester }}
                        </td>

                        <td class="px-4 py-3 text-gray-500">
                            {{ \Carbon\Carbon::parse($n->tanggal)->format('d-m-Y') }}
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-6 text-gray-500">
                            Belum ada data kedisiplinan.
                        </td>
                    </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    {{-- FOOTER --}}
    <div class="bg-blue-50 border border-blue-100 rounded-xl p-5">

        <h3 class="font-semibold text-blue-700 mb-2">
            Catatan Sistem Karakter
        </h3>

        <p class="text-sm text-gray-600 leading-relaxed">
            Penilaian karakter siswa mencakup aspek sikap dan kedisiplinan.
            Data ini digunakan untuk pemantauan perkembangan siswa oleh orang tua dan sekolah.
        </p>

    </div>

</x-app-layout>
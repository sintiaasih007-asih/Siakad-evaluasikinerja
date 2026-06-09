<x-app-layout>

    {{-- HEADER --}}
    <x-page-header
        title="Monitoring Akademik Siswa"
        subtitle="Dashboard / Nilai Akademik"
    />

    {{-- AMBIL DATA GROUP --}}
    @php
        $groupedNilai = $nilai->groupBy('jenis_nilai');
    @endphp

    {{-- RINGKASAN --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

        <div class="bg-white rounded-xl border shadow-sm p-5">
            <p class="text-sm text-gray-500">Rata-rata Nilai</p>
            <h2 class="text-3xl font-bold text-blue-600">
                {{ round($nilai->avg('nilai') ?? 0, 1) }}
            </h2>
        </div>

        <div class="bg-white rounded-xl border shadow-sm p-5">
            <p class="text-sm text-gray-500">Total Penilaian</p>
            <h2 class="text-3xl font-bold text-green-600">
                {{ $nilai->count() }}
            </h2>
        </div>

        <div class="bg-white rounded-xl border shadow-sm p-5">
            <p class="text-sm text-gray-500">Nilai Tertinggi</p>
            <h2 class="text-3xl font-bold text-orange-600">
                {{ $nilai->max('nilai') ?? 0 }}
            </h2>
        </div>

    </div>

    {{-- PROFIL SISWA --}}
    <div class="bg-white rounded-xl shadow-sm border mb-6">

        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-t-xl px-6 py-5">
            <h2 class="text-white text-2xl font-semibold">
                {{ $siswa->nama }}
            </h2>

            <p class="text-blue-100">
                NIS : {{ $siswa->nis }}
            </p>
        </div>

        <div class="p-6 grid md:grid-cols-3 gap-6">

            <div>
                <p class="text-sm text-gray-500">Kelas</p>
                <p class="font-semibold text-gray-800">
                    {{ $siswa->kelas->nama_kelas ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Orang Tua / Wali</p>
                <p class="font-semibold text-gray-800">
                    {{ $siswa->nama_ortu ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Status</p>
                <span class="inline-flex px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm">
                    Aktif
                </span>
            </div>

        </div>
    </div>

    {{-- ============================= --}}
    {{-- NILAI BERDASARKAN JENIS --}}
    {{-- ============================= --}}

    <div class="space-y-6">

        @forelse($groupedNilai as $jenis => $items)

        <div class="bg-white rounded-xl shadow-sm border overflow-hidden">

            {{-- HEADER SECTION --}}
            <div class="px-6 py-4 bg-gray-50 border-b">
                <h2 class="text-lg font-semibold text-gray-800">
                    {{ strtoupper($jenis) }}
                </h2>
                <p class="text-sm text-gray-500">
                    Rekap nilai {{ strtolower($jenis) }} siswa
                </p>
            </div>

            {{-- TABLE --}}
            <div class="overflow-x-auto">

                <table class="w-full text-sm text-left">

                    <thead class="text-xs uppercase bg-gray-100 text-gray-600">

                        <tr>
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3">Mata Pelajaran</th>
                            <th class="px-4 py-3">Nama Penilaian</th>
                            <th class="px-4 py-3">Nilai</th>
                            <th class="px-4 py-3">Predikat</th>
                            <th class="px-4 py-3">Tanggal</th>
                        </tr>

                    </thead>

                    <tbody class="divide-y">

                        @foreach($items as $i => $n)

                        <tr class="hover:bg-gray-50">

                            <td class="px-4 py-3">
                                {{ $i + 1 }}
                            </td>

                            <td class="px-4 py-3 font-medium text-gray-700">
                                {{ $n->jadwal->mapel->nama_mapel ?? '-' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $n->nama_penilaian ?? '-' }}
                            </td>

                            <td class="px-4 py-3 font-semibold">
                                {{ $n->nilai }}
                            </td>

                            <td class="px-4 py-3">

                                @if($n->nilai >= 85)
                                    <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">
                                        A (Sangat Baik)
                                    </span>

                                @elseif($n->nilai >= 75)
                                    <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-700">
                                        B (Baik)
                                    </span>

                                @elseif($n->nilai >= 65)
                                    <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-700">
                                        C (Cukup)
                                    </span>

                                @else
                                    <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-700">
                                        D (Kurang)
                                    </span>
                                @endif

                            </td>

                            <td class="px-4 py-3 text-gray-600">
                                {{ \Carbon\Carbon::parse($n->tanggal)->format('d-m-Y') }}
                            </td>

                        </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

        @empty

        <div class="bg-white border rounded-xl p-10 text-center text-gray-500">
            Belum ada data nilai akademik.
        </div>

        @endforelse

    </div>

    {{-- FOOTER NOTE --}}
    <div class="mt-6 bg-blue-50 border border-blue-100 rounded-xl p-5">

        <h3 class="font-semibold text-blue-700 mb-2">
            Catatan Sistem Akademik
        </h3>

        <p class="text-sm text-gray-600 leading-relaxed">
            Nilai siswa ditampilkan berdasarkan kategori penilaian
            seperti Tugas, Quiz, UTS, dan UAS. Data ini dapat digunakan
            oleh orang tua dan wali kelas untuk memantau perkembangan
            akademik secara lebih terstruktur dan transparan.
        </p>

    </div>

</x-app-layout>
{{-- resources/views/nilai/index.blade.php --}}

<x-app-layout>

    <div class="py-6">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- INFORMASI --}}
            <div class="mb-8">

                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">

                    {{-- TOP --}}
                    <div class="bg-slate-900 px-8 py-7">

                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

                            <div>

                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-slate-200 text-xs font-medium tracking-wide mb-4">
                                    Sistem Akademik SMP
                                </div>

                                <h2 class="text-2xl font-bold text-white">
                                    Pengelolaan Nilai Akademik
                                </h2>

                                <p class="text-slate-300 mt-2 text-sm leading-relaxed max-w-2xl">
                                    Halaman ini digunakan untuk mengelola nilai siswa berdasarkan
                                    mata pelajaran dan kelas yang diampu.
                                </p>

                            </div>

                            {{-- INFO SINGKAT --}}
                            <div class="bg-white/10 border border-white/10 rounded-2xl px-6 py-5 min-w-[280px]">

                                <div class="space-y-4">

                                    <div>

                                        <p class="text-xs uppercase tracking-widest text-slate-300 font-medium">
                                            Guru Pengampu
                                        </p>

                                        <h3 class="text-lg font-semibold text-white mt-1">
                                            {{ auth()->user()->name }}
                                        </h3>

                                    </div>

                                    <div class="border-t border-white/10 pt-4">

                                        <p class="text-xs uppercase tracking-widest text-slate-300 font-medium">
                                            Total Mata Pelajaran
                                        </p>

                                        <h3 class="text-2xl font-bold text-white mt-1">
                                            {{ $jadwals->count() }}
                                        </h3>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            {{-- GRID CARD --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

                @forelse($jadwals as $j)

                    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm hover:shadow-md hover:border-indigo-200 transition-all duration-300 overflow-hidden">

                        {{-- HEADER CARD --}}
                        <div class="border-b border-slate-100 px-6 py-5 bg-slate-50">

                            <div class="flex items-start justify-between gap-4">

                                <div>

                                    <p class="text-xs uppercase tracking-widest text-slate-400 font-semibold mb-2">
                                        Mata Pelajaran
                                    </p>

                                    <h3 class="text-lg font-bold text-slate-800 leading-snug">
                                        {{ $j->mapel->nama_mapel }}
                                    </h3>

                                </div>

                                <div class="w-11 h-11 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-700 text-lg font-bold">
                                    📘
                                </div>

                            </div>

                        </div>

                        {{-- BODY --}}
                        <div class="p-6">

                            {{-- KELAS --}}
                            <div class="flex items-center justify-between mb-6">

                                <div>

                                    <p class="text-xs uppercase tracking-widest text-slate-400 font-semibold">
                                        Kelas
                                    </p>

                                    <h4 class="text-base font-semibold text-slate-800 mt-1">
                                        {{ $j->kelas->nama_kelas }}
                                    </h4>

                                </div>

                                <div class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-sm font-medium">
                                    Aktif
                                </div>

                            </div>

                            {{-- BUTTON --}}
                            <div class="space-y-3">

                                {{-- KELOLA NILAI --}}
                                <a href="{{ route('nilai.create',$j->id) }}"
                                    class="flex items-center justify-center gap-2 w-full bg-slate-900 hover:bg-indigo-700 text-white py-3 rounded-xl text-sm font-semibold transition-all duration-300">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="w-5 h-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor">

                                        <path stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 5l7 7-7 7" />

                                    </svg>

                                    Kelola Nilai

                                </a>

                                {{-- RIWAYAT NILAI --}}
                                <button
                                    onclick="openRiwayatModal({{ $j->id }})"
                                    class="flex items-center justify-center gap-2 w-full border border-slate-300 hover:border-indigo-300 hover:bg-indigo-50 text-slate-700 hover:text-indigo-700 py-3 rounded-xl text-sm font-semibold transition-all duration-300">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="w-5 h-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor">

                                        <path stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />

                                    </svg>

                                    Riwayat Nilai

                                </button>

                            </div>

                        </div>

                    </div>

                    {{-- MODAL RIWAYAT NILAI --}}
                    <div id="riwayatModal{{ $j->id }}"
                        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4">

                        <div class="bg-white w-full max-w-6xl rounded-3xl shadow-2xl overflow-hidden">

                            {{-- HEADER --}}
                            <div class="bg-slate-900 px-8 py-6 flex items-center justify-between">

                                <div>

                                    <h2 class="text-2xl font-bold text-white">
                                        Riwayat Nilai Siswa
                                    </h2>

                                    <p class="text-slate-300 text-sm mt-1">
                                        {{ $j->mapel->nama_mapel }} • Kelas {{ $j->kelas->nama_kelas }}
                                    </p>

                                </div>

                                <button
                                    onclick="closeRiwayatModal({{ $j->id }})"
                                    class="w-10 h-10 rounded-xl bg-white/10 hover:bg-white/20 text-white transition">

                                    ✕

                                </button>

                            </div>

                            {{-- BODY --}}
                            <div class="p-6 max-h-[75vh] overflow-y-auto bg-slate-50">

                                @php
                                    $riwayatNilai = \App\Models\Nilai::with('siswa')
                                        ->where('jadwal_id', $j->id)
                                        ->where('guru_id', auth()->user()->guru_id)
                                        ->orderBy('tanggal', 'desc')
                                        ->get()
                                        ->groupBy('jenis_nilai');
                                @endphp

                                @forelse($riwayatNilai as $jenis => $items)

                                    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden mb-6">

                                        {{-- HEADER JENIS NILAI --}}
                                        <div class="bg-slate-100 px-6 py-4 border-b border-slate-200">

                                            <div class="flex items-center justify-between">

                                                <div>

                                                    <h3 class="text-lg font-bold text-slate-800 uppercase">
                                                        {{ $jenis }}
                                                    </h3>

                                                    <p class="text-sm text-slate-500 mt-1">
                                                        Total {{ $items->count() }} Data Nilai
                                                    </p>

                                                </div>

                                            </div>

                                        </div>

                                        {{-- TABLE --}}
                                        <div class="overflow-x-auto">

                                            <table class="w-full">

                                                <thead class="bg-slate-50">

                                                    <tr>

                                                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">
                                                            No
                                                        </th>

                                                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">
                                                            Nama Siswa
                                                        </th>

                                                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">
                                                            Penilaian
                                                        </th>

                                                        <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase">
                                                            Nilai
                                                        </th>

                                                        <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase">
                                                            Tanggal
                                                        </th>

                                                    </tr>

                                                </thead>

                                                <tbody class="divide-y divide-slate-100">

                                                    @foreach($items as $i => $nilai)

                                                        <tr class="hover:bg-slate-50">

                                                            <td class="px-6 py-4 text-sm text-slate-600">
                                                                {{ $i + 1 }}
                                                            </td>

                                                            <td class="px-6 py-4">

                                                                <div class="font-semibold text-slate-800">
                                                                    {{ $nilai->siswa->nama }}
                                                                </div>

                                                            </td>

                                                            <td class="px-6 py-4 text-sm text-slate-700">
                                                                {{ $nilai->nama_penilaian }}
                                                            </td>

                                                            <td class="px-6 py-4 text-center">

                                                                <span class="px-3 py-1 rounded-lg bg-indigo-100 text-indigo-700 text-sm font-bold">
                                                                    {{ $nilai->nilai }}
                                                                </span>

                                                            </td>

                                                            <td class="px-6 py-4 text-center text-sm text-slate-500">
                                                                {{ \Carbon\Carbon::parse($nilai->tanggal)->translatedFormat('d M Y') }}
                                                            </td>

                                                        </tr>

                                                    @endforeach

                                                </tbody>

                                            </table>

                                        </div>

                                    </div>

                                @empty

                                    <div class="bg-white border border-slate-200 rounded-2xl p-16 text-center">

                                        <div class="text-5xl mb-4">
                                            📚
                                        </div>

                                        <h3 class="text-xl font-bold text-slate-700">
                                            Belum Ada Riwayat Nilai
                                        </h3>

                                        <p class="text-slate-500 mt-2">
                                            Data nilai siswa belum tersedia.
                                        </p>

                                    </div>

                                @endforelse

                            </div>

                        </div>

                    </div>

                @empty

                    {{-- EMPTY --}}
                    <div class="col-span-3">

                        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-16 text-center">

                            <div class="w-24 h-24 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-5xl mb-6">
                                📚
                            </div>

                            <h3 class="text-2xl font-bold text-slate-700">
                                Data Tidak Tersedia
                            </h3>

                            <p class="text-slate-500 mt-3 max-w-xl mx-auto leading-relaxed">
                                Belum terdapat mata pelajaran atau kelas yang diampu
                                oleh guru yang sedang login.
                            </p>

                        </div>

                    </div>

                @endforelse

            </div>

        </div>

    </div>

    {{-- SCRIPT --}}
    <script>

        function openRiwayatModal(id) {

            document.getElementById('riwayatModal' + id)
                .classList.remove('hidden');

            document.getElementById('riwayatModal' + id)
                .classList.add('flex');
        }

        function closeRiwayatModal(id) {

            document.getElementById('riwayatModal' + id)
                .classList.remove('flex');

            document.getElementById('riwayatModal' + id)
                .classList.add('hidden');
        }

    </script>

</x-app-layout>
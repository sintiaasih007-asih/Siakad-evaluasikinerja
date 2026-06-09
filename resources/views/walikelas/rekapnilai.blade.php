<x-app-layout>

<div class="p-6 bg-slate-50 min-h-screen">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">

        <div>
            <h1 class="text-3xl font-bold text-slate-800">
                Rekap Nilai Kelas
            </h1>

            <p class="text-slate-500">
                 {{ $kelas->nama_kelas }} • {{ $tahunAktif->tahun }} • {{ ucfirst($tahunAktif->semester) }}
            </p>

            @if($kelas)
            <p class="text-slate-500 mt-1">
                Monitoring siswa kelas binaan {{ $kelas->nama_kelas }}
            </p>
            @endif
        </div>

        <div class="flex gap-2">
            <a href="#" class="px-4 py-2 bg-emerald-600 text-white rounded-xl shadow hover:bg-emerald-700 text-sm">
                Export Excel
            </a>

            <a href="#" class="px-4 py-2 bg-red-600 text-white rounded-xl shadow hover:bg-red-700 text-sm">
                Cetak PDF
            </a>
        </div>

    </div>

    @if(!$kelas)

        <div class="bg-yellow-100 text-yellow-700 p-4 rounded-xl">
            Anda belum memiliki kelas binaan.
        </div>

    @else

    {{-- STATISTIK --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

        <div class="bg-white p-5 rounded-2xl shadow border">
            <p class="text-sm text-slate-500">Total Siswa</p>
            <h2 class="text-3xl font-bold text-slate-800 mt-1">
                {{ count($data) }}
            </h2>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow border">
            <p class="text-sm text-slate-500">Nilai Rata-rata</p>
            <h2 class="text-3xl font-bold text-blue-600 mt-1">
                {{ round(collect($data)->avg('nilai')) }}
            </h2>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow border">
            <p class="text-sm text-slate-500">Kehadiran</p>
            <h2 class="text-3xl font-bold text-green-600 mt-1">
                {{ round(collect($data)->avg('hadir')) }}%
            </h2>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow border">
            <p class="text-sm text-slate-500">Perlu Pantauan</p>
            <h2 class="text-3xl font-bold text-red-600 mt-1">
                {{ collect($data)->where('nilai','<',75)->count() }}
            </h2>
        </div>

    </div>

    {{-- TOP SISWA --}}
    <div class="grid md:grid-cols-3 gap-4 mb-6">

        @php
            $nilaiMaks = collect($data)->max('nilai');

            $top = $nilaiMaks > 0
                ? collect($data)->sortByDesc('nilai')->take(3)->values()
                : collect([]);
        @endphp

        @for($i = 1; $i <= 3; $i++)

            @php
                $item = $top[$i - 1] ?? null;
            @endphp

            <div class="bg-white rounded-2xl shadow border p-5">

                <div class="flex items-center gap-4">

                    @if($item)
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($item['nama']) }}&background=random"
                             class="w-14 h-14 rounded-full">
                    @else
                        <div class="w-14 h-14 rounded-full bg-slate-200"></div>
                    @endif

                    <div>
                        <p class="text-xs text-slate-400">
                            Ranking #{{ $i }}
                        </p>

                        <h3 class="font-bold text-slate-800">
                            {{ $item['nama'] ?? '-' }}
                        </h3>

                        <p class="text-sm text-blue-600 font-semibold">
                            Nilai {{ $item['nilai'] ?? '-' }}
                        </p>
                    </div>

                </div>

            </div>

        @endfor

    </div>

    {{-- SEARCH --}}
    <div class="bg-white rounded-2xl shadow border p-4 mb-6">

        <input
            type="text"
            id="searchInput"
            placeholder="Cari nama siswa..."
            class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"
        >

    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow border overflow-hidden">

        <div class="px-5 py-4 border-b flex justify-between items-center">
            <h3 class="font-semibold text-slate-700">
                Monitoring Siswa
            </h3>

            <span class="text-sm text-slate-400">
                {{ count($data) }} siswa
            </span>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-slate-100 text-slate-700">

                    <tr>
                        <th class="px-4 py-3 text-left">No</th>
                        <th class="px-4 py-3 text-left">Foto</th>
                        <th class="px-4 py-3 text-left">Nama</th>
                        <th class="px-4 py-3 text-left">Kehadiran</th>
                        <th class="px-4 py-3 text-left">Nilai</th>
                        <th class="px-4 py-3 text-left">Sikap</th>
                        <th class="px-4 py-3 text-left">Disiplin</th>
                        <th class="px-4 py-3 text-left">Status</th>
                    </tr>

                </thead>

                <tbody>

                    @foreach($data as $item)

                    @php

                        if($item['nilai'] >= 85 && $item['hadir'] >= 90){
                            $status = 'Sangat Baik';
                            $warna = 'green';
                        }elseif($item['nilai'] >= 75){
                            $status = 'Stabil';
                            $warna = 'blue';
                        }elseif($item['nilai'] >= 65){
                            $status = 'Pantauan';
                            $warna = 'yellow';
                        }else{
                            $status = 'Pembinaan';
                            $warna = 'red';
                        }

                    @endphp

                    <tr class="border-t hover:bg-slate-50 siswaRow">

                        <td class="px-4 py-3">{{ $loop->iteration }}</td>

                        <td class="px-4 py-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($item['nama']) }}&background=random"
                                 class="w-10 h-10 rounded-full">
                        </td>

                        <td class="px-4 py-3 font-semibold nama">
                            {{ $item['nama'] }}
                        </td>

                        <td class="px-4 py-3">
                            <span class="font-semibold text-green-600">
                                {{ $item['hadir'] }}%
                            </span>
                        </td>

                        <td class="px-4 py-3 font-bold
                            @if($item['nilai'] >= 75)
                                text-green-600
                            @else
                                text-red-600
                            @endif
                        ">
                            {{ $item['nilai'] }}
                        </td>

                        <td class="px-4 py-3 text-blue-600">
                            {{ $item['sikap'] }}
                        </td>

                        <td class="px-4 py-3 text-purple-600">
                            {{ $item['disiplin'] }}
                        </td>

                        <td class="px-4 py-3">

                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                @if($warna=='green') bg-green-100 text-green-700
                                @elseif($warna=='blue') bg-blue-100 text-blue-700
                                @elseif($warna=='yellow') bg-yellow-100 text-yellow-700
                                @else bg-red-100 text-red-700
                                @endif">

                                {{ $status }}

                            </span>

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

    @endif

</div>

{{-- SEARCH REALTIME --}}
<script>
document.getElementById('searchInput')?.addEventListener('keyup', function () {

    let value = this.value.toLowerCase();

    document.querySelectorAll('.siswaRow').forEach(function(row){

        let nama = row.querySelector('.nama').innerText.toLowerCase();

        row.style.display = nama.includes(value) ? '' : 'none';

    });

});
</script>

</x-app-layout>
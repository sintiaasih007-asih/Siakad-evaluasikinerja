<x-app-layout>

<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 p-6">

    {{-- HEADER --}}
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">
                Evaluasi Bulanan
            </h1>

            <p class="text-slate-500 mt-1">
                Tahun Ajaran:
                <span class="font-semibold text-slate-700">
                    {{ $tahun->tahun ?? '-' }}
                </span>
            </p>
        </div>

        <div class="bg-white px-4 py-2 rounded-xl shadow-sm border text-sm text-slate-600">
            📊 Ranking Otomatis Sistem Fuzzy
        </div>

    </div>

    {{-- FILTER CARD --}}
    <div class="bg-white/80 backdrop-blur border shadow-sm rounded-2xl p-5 mb-6">

        <form method="GET" class="grid md:grid-cols-4 gap-4 items-end">

            {{-- JADWAL --}}
            <div>
                <label class="text-xs text-slate-500 font-medium">Mata Pelajaran</label>

                <select name="jadwal_id"
                    class="w-full mt-1 border-slate-200 rounded-xl p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                    @foreach($jadwals as $j)
                        <option value="{{ $j->id }}"
                            {{ request('jadwal_id', $jadwalId) == $j->id ? 'selected' : '' }}>
                            {{ $j->nama_mapel }} • {{ $j->nama_kelas }}
                        </option>
                    @endforeach

                </select>
            </div>

            {{-- BULAN --}}
            <div>
                <label class="text-xs text-slate-500 font-medium">Bulan</label>

                <select name="bulan"
                    class="w-full mt-1 border-slate-200 rounded-xl p-2.5 focus:ring-2 focus:ring-blue-500">

                    @php
                        $bulanList = [
                            '01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April',
                            '05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus',
                            '09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'
                        ];
                    @endphp

                    @foreach($bulanList as $key => $val)
                        <option value="{{ $key }}" {{ $bulan == $key ? 'selected' : '' }}>
                            {{ $val }}
                        </option>
                    @endforeach

                </select>
            </div>

            {{-- KELAS --}}
            <div>
                <label class="text-xs text-slate-500 font-medium">Kelas</label>

                <select name="kelas_id"
                    class="w-full mt-1 border-slate-200 rounded-xl p-2.5 focus:ring-2 focus:ring-blue-500">

                    <option value="">Semua Kelas</option>

                    @foreach($kelasList as $k)
                        <option value="{{ $k->id }}"
                            {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                    @endforeach

                </select>
            </div>

            {{-- BUTTON --}}
            <div>
                <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2.5 rounded-xl shadow-sm transition">
                    Terapkan Filter
                </button>
            </div>

        </form>

    </div>

    {{-- TABLE CARD --}}
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">

        {{-- TABLE HEADER --}}
        <div class="px-6 py-4 border-b flex items-center justify-between bg-slate-50">

            <h3 class="font-semibold text-slate-700">
                Hasil Evaluasi Siswa
            </h3>

            <span class="text-xs text-slate-500">
                Auto sorted by skor fuzzy
            </span>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-slate-100 text-slate-600 text-xs uppercase tracking-wider">

                    <tr>
                        <th class="px-4 py-3 text-center">Rank</th>
                        <th class="px-4 py-3 text-left">Nama</th>
                        <th class="px-4 py-3 text-center">Nilai</th>
                        <th class="px-4 py-3 text-center">Absensi</th>
                        <th class="px-4 py-3 text-center">Sikap</th>
                        <th class="px-4 py-3 text-center">Disiplin</th>
                        <th class="px-4 py-3 text-center">Skor</th>
                        <th class="px-4 py-3 text-center">Kategori</th>
                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse($data as $i => $item)

                    <tr class="hover:bg-slate-50 transition">

                        {{-- RANK --}}
                        <td class="text-center font-bold text-slate-700 py-3">
                            {{ $i + 1 }}
                        </td>

                        {{-- NAMA --}}
                        <td class="px-4 py-3 font-semibold text-slate-700">
                            {{ $item['nama'] }}
                        </td>

                        {{-- NILAI --}}
                        <td class="text-center">
                            {{ $item['nilai'] }}
                        </td>

                        {{-- ABSENSI --}}
                        <td class="text-center">
                            <span class="px-2 py-1 rounded-lg bg-slate-100">
                                {{ $item['absensi'] }}%
                            </span>
                        </td>

                        {{-- SIKAP --}}
                        <td class="text-center">{{ $item['sikap'] }}</td>

                        {{-- DISIPLIN --}}
                        <td class="text-center">{{ $item['disiplin'] }}</td>

                        {{-- SKOR --}}
                        <td class="text-center font-bold text-blue-600">
                            {{ $item['skor'] }}
                        </td>

                        {{-- KATEGORI --}}
                        <td class="text-center">

                            @php
                                $kategori = $item['kategori'];
                            @endphp

                            @if($kategori=='Sangat Baik')
                                <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-700">
                                    {{ $kategori }}
                                </span>

                            @elseif($kategori=='Baik')
                                <span class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-700">
                                    {{ $kategori }}
                                </span>

                            @elseif($kategori=='Perlu Bimbingan')
                                <span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">
                                    {{ $kategori }}
                                </span>

                            @else
                                <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-700">
                                    {{ $kategori }}
                                </span>
                            @endif

                        </td>

                    </tr>

                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-10 text-slate-500">
                                Tidak ada data evaluasi
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

</x-app-layout>
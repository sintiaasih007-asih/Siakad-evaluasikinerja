<x-app-layout>

<div class="p-6">

    {{-- HEADER --}}
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">
                Data Kelas Binaan
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                    Wali Kelas : {{ auth()->user()->name }}
                </p>
        </div>

        {{-- Tahun ajaran aktif --}}
        <div class="bg-white border shadow-sm rounded-2xl px-5 py-4 min-w-[260px]">
            @php
                $ta = \App\Models\TahunAjaran::where('is_active',1)->first();
            @endphp

            <p class="text-xs uppercase tracking-wider text-slate-400">
                Tahun Ajaran Aktif
            </p>

            @if($ta)
                <h3 class="font-bold text-slate-800 text-lg mt-1">
                    {{ $ta->tahun }}
                </h3>
                <p class="text-sm text-indigo-600 capitalize">
                    Semester {{ $ta->semester }}
                </p>
            @else
                <p class="text-sm text-red-500 mt-1">
                    Belum diatur
                </p>
            @endif
        </div>
    </div>

    {{-- Jika belum punya kelas --}}
    @if(!$kelas)

        <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-5 py-4 rounded-2xl">
            Anda belum memiliki kelas binaan.
        </div>

    @else

    {{-- Statistik --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white p-5 rounded-2xl shadow">
            <p class="text-sm opacity-80">Total Siswa</p>
            <h2 class="text-3xl font-bold">{{ $total }}</h2>
        </div>

        <div class="bg-gradient-to-r from-emerald-500 to-green-600 text-white p-5 rounded-2xl shadow">
            <p class="text-sm opacity-80">Laki-Laki</p>
            <h2 class="text-3xl font-bold">{{ $laki }}</h2>
        </div>

        <div class="bg-gradient-to-r from-pink-500 to-rose-600 text-white p-5 rounded-2xl shadow">
            <p class="text-sm opacity-80">Perempuan</p>
            <h2 class="text-3xl font-bold">{{ $perempuan }}</h2>
        </div>

        <div class="bg-gradient-to-r from-slate-700 to-slate-900 text-white p-5 rounded-2xl shadow">
            <p class="text-sm opacity-80">Nama Kelas</p>
            <h2 class="text-2xl font-bold">{{ $kelas->nama_kelas }}</h2>
        </div>

    </div>

    
    {{-- SEARCH --}}
    <div class="mb-4">
        <input
            type="text"
            id="searchSiswa"
            placeholder="Cari nama siswa / NIS / orang tua..."
            class="w-full md:w-96 px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
        >
    </div>

    {{-- TABEL --}}
    <div class="bg-white rounded-2xl shadow border overflow-hidden">

        <div class="px-5 py-4 border-b bg-slate-50">
            <h3 class="font-semibold text-slate-700">
                Daftar Siswa Kelas {{ $kelas->nama_kelas }}
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">

                <thead class="bg-slate-100 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 text-left">No</th>
                        <th class="px-4 py-3 text-left">NIS</th>
                        <th class="px-4 py-3 text-left">Nama</th>
                        <th class="px-4 py-3 text-left">JK</th>
                        <th class="px-4 py-3 text-left">Orang Tua</th>
                        <th class="px-4 py-3 text-left">No HP</th>
                    </tr>
                </thead>

                <tbody id="tableSiswa">

                    @forelse($siswa as $item)
                    <tr class="border-t hover:bg-slate-50 data-row">
                        <td class="px-4 py-3">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3">{{ $item->nis }}</td>
                        <td class="px-4 py-3 font-medium">{{ $item->nama }}</td>
                        <td class="px-4 py-3">{{ $item->jk }}</td>
                        <td class="px-4 py-3">{{ $item->nama_ortu }}</td>
                        <td class="px-4 py-3">{{ $item->no_hp_ortu }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-slate-400">
                            Belum ada siswa
                        </td>
                    </tr>
                    @endforelse

                </tbody>

            </table>
        </div>

    </div>

    @endif

</div>

{{-- SEARCH SCRIPT --}}
<script>
document.addEventListener("DOMContentLoaded", function () {

    const input = document.getElementById("searchSiswa");
    const rows = document.querySelectorAll(".data-row");

    if(input){
        input.addEventListener("keyup", function () {

            let value = this.value.toLowerCase();

            rows.forEach(row => {
                row.style.display =
                    row.innerText.toLowerCase().includes(value)
                    ? ""
                    : "none";
            });

        });
    }

});
</script>

</x-app-layout>
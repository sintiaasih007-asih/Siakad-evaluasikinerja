<x-app-layout>

<x-page-header 
title="Input Nilai Akademik"
subtitle="{{ $jadwal->mapel->nama_mapel }} - {{ $jadwal->kelas->nama_kelas }}"
/>

{{-- INFO PEMBELAJARAN --}}
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">

    {{-- KELAS --}}
    <div class="bg-white border rounded-2xl shadow-sm p-5">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-6 h-6 text-blue-600"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 11H5m14-7H5a2 2 0 00-2 2v12a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2z"/>
                </svg>
            </div>

            <div>
                <p class="text-xs uppercase tracking-wide text-gray-500">
                    Nama Kelas
                </p>
                <h3 class="text-lg font-bold text-gray-800">
                    {{ $jadwal->kelas->nama_kelas }}
                </h3>
            </div>
        </div>
    </div>

    {{-- MAPEL --}}
    <div class="bg-white border rounded-2xl shadow-sm p-5">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-6 h-6 text-green-600"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.483 8.965 5 7 5c-1.965 0-3.832.483-5 1.253v13C3.168 18.483 5.035 18 7 18c1.965 0 3.832.483 5 1.253"/>
                </svg>
            </div>

            <div>
                <p class="text-xs uppercase tracking-wide text-gray-500">
                    Mata Pelajaran
                </p>
                <h3 class="text-lg font-bold text-gray-800">
                    {{ $jadwal->mapel->nama_mapel }}
                </h3>
            </div>
        </div>
    </div>

    {{-- TAHUN AJARAN --}}
    <div class="bg-white border rounded-2xl shadow-sm p-5">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-6 h-6 text-amber-600"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10m-13 9h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v11a2 2 0 002 2z"/>
                </svg>
            </div>

            <div>
                <p class="text-xs uppercase tracking-wide text-gray-500">
                    Tahun Ajaran
                </p>
                <h3 class="text-lg font-bold text-gray-800">
                    {{ $jadwal->kelas->tahunAjaran->tahun ?? '-' }}
                </h3>
            </div>
        </div>
    </div>

    {{-- GURU --}}
    <div class="bg-white border rounded-2xl shadow-sm p-5">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-6 h-6 text-purple-600"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5.121 17.804A9 9 0 1118.88 17.8M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>

            <div>
                <p class="text-xs uppercase tracking-wide text-gray-500">
                    Guru Pengampu
                </p>
                <h3 class="text-lg font-bold text-gray-800">
                    {{ $jadwal->guru->nama ?? '-' }}
                </h3>
            </div>
        </div>
    </div>

</div>


{{-- FORM INPUT NILAI --}}
<div class="bg-white rounded-xl shadow-sm border p-6">

<form action="{{ route('nilai.store') }}" method="POST">
@csrf

<input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">

<div class="grid md:grid-cols-2 gap-4 mb-5">

<div>
<label>Jenis Nilai</label>
<select name="jenis_nilai" class="w-full rounded-lg border-gray-300">
<option value="Tugas">Tugas</option>
<option value="Quis">Quis</option>
<option value="UTS">UTS</option>
<option value="UAS">UAS</option>
</select>
</div>

<div>
<label>Nama Penilaian</label>
<input type="text" name="nama_penilaian"
placeholder="Contoh: Tugas Bab 1"
class="w-full rounded-lg border-gray-300">
</div>

</div>

<table class="w-full border">

<thead class="bg-gray-100">
<tr>
<th class="p-3 border">No</th>
<th class="p-3 border">Nama Siswa</th>
<th class="p-3 border">Nilai</th>
</tr>
</thead>

<tbody>

@foreach($siswas as $s)

<tr>
<td class="p-3 border">{{ $loop->iteration }}</td>

<td class="p-3 border">
{{ $s->nama }}
<input type="hidden" name="siswa_id[]" value="{{ $s->id }}">
</td>

<td class="p-3 border">
<input type="number"
name="nilai[]"
min="0"
max="100"
class="w-full rounded-lg border-gray-300"
required>
</td>

</tr>

@endforeach

</tbody>

</table>

<div class="flex justify-end mt-5">
<button type="submit"
class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg">
Simpan Nilai
</button>
</div>

</form>

</div>

</x-app-layout>
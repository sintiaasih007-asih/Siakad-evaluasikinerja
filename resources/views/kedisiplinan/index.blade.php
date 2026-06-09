<x-app-layout>

<x-page-header 
title="Input Kedisiplinan"
subtitle="Pilih Mata Pelajaran / Kelas"
/>

<div class="py-6">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

@forelse($jadwals as $j)

<div class="bg-white rounded-2xl border border-gray-200 hover:border-indigo-300 transition shadow-sm hover:shadow-md p-5">

<div class="flex items-start justify-between mb-4">

<div>
<h3 class="text-base font-semibold text-gray-800">
{{ $j->mapel->nama_mapel }}
</h3>

<p class="text-xs text-gray-400 mt-1">
{{ $j->kelas->nama_kelas }}
</p>
</div>

<div class="w-10 h-10 flex items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 text-lg">
📌
</div>

</div>

<div class="text-sm text-gray-600 space-y-3 mb-5">

<div class="flex justify-between">
<span class="text-gray-400">Guru</span>
<span class="font-medium text-gray-800">
{{ $j->guru->nama }}
</span>
</div>

<div class="flex justify-between">
<span class="text-gray-400">Jam</span>
<span class="font-medium text-gray-700">
{{ substr($j->jam_masuk,0,5) }} - {{ substr($j->jam_selesai,0,5) }}
</span>
</div>

</div>

<div class="mt-4">
<a href="{{ route('kedisiplinan.create',$j->id) }}"
style="display:block;
width:100%;
background:#1e3a8a;
color:white;
text-align:center;
padding:10px;
border-radius:8px;
font-weight:600;">
Nilai Kedisiplinan
</a>
</div>

</div>

@empty

<div class="col-span-3 text-center py-16 text-gray-400">
Tidak ada jadwal
</div>

@endforelse

</div>
</div>
</div>

</x-app-layout>
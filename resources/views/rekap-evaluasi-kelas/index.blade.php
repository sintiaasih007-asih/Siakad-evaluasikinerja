<x-app-layout>

<div class="p-6 bg-slate-50 min-h-screen">

<div class="flex justify-between items-center mb-6">

<div>
<h1 class="text-3xl font-bold text-slate-800">
Rekap Evaluasi Kelas
</h1>

@if($kelas)
<p class="text-slate-500 mt-1">
{{ $kelas->nama_kelas }} • {{ $tahun->tahun }} • {{ ucfirst($tahun->semester) }}
</p>
@endif
</div>

<form method="GET">
<select name="bulan"
class="px-4 py-2 border rounded-xl"
onchange="this.form.submit()">

@php
$bulanList = [
'01'=>'Januari','02'=>'Februari','03'=>'Maret',
'04'=>'April','05'=>'Mei','06'=>'Juni',
'07'=>'Juli','08'=>'Agustus','09'=>'September',
'10'=>'Oktober','11'=>'November','12'=>'Desember'
];
@endphp

@foreach($bulanList as $k=>$v)
<option value="{{ $k }}" {{ $bulan==$k ? 'selected':'' }}>
{{ $v }}
</option>
@endforeach

</select>
</form>

</div>

@if(!$kelas)

<div class="bg-yellow-100 text-yellow-700 p-4 rounded-xl">
Belum memiliki kelas binaan
</div>

@else

{{-- STATISTIK --}}
<div class="grid md:grid-cols-4 gap-4 mb-6">

<div class="bg-white p-5 rounded-2xl shadow border">
<p class="text-sm text-slate-500">Total Siswa</p>
<h2 class="text-3xl font-bold">{{ count($data) }}</h2>
</div>

<div class="bg-white p-5 rounded-2xl shadow border">
<p class="text-sm text-slate-500">Rata-rata Evaluasi</p>
<h2 class="text-3xl font-bold text-blue-600">
{{ round($data->avg('hasil')) }}
</h2>
</div>

<div class="bg-white p-5 rounded-2xl shadow border">
<p class="text-sm text-slate-500">Perlu Pembinaan</p>
<h2 class="text-3xl font-bold text-red-600">
{{ $data->where('kategori','Perlu Pembinaan')->count() }}
</h2>
</div>

<div class="bg-white p-5 rounded-2xl shadow border">
<p class="text-sm text-slate-500">Sangat Baik</p>
<h2 class="text-3xl font-bold text-green-600">
{{ $data->where('kategori','Sangat Baik')->count() }}
</h2>
</div>

</div>

{{-- TOP 3 --}}
<div class="grid md:grid-cols-3 gap-4 mb-6">

@foreach($data->take(3) as $item)

<div class="bg-white rounded-2xl shadow border p-5">

<p class="text-xs text-slate-400">
Ranking #{{ $loop->iteration }}
</p>

<h3 class="font-bold text-lg mt-2">
{{ $item['nama'] }}
</h3>

<p class="text-blue-600 font-semibold">
Skor {{ $item['hasil'] }}
</p>

</div>

@endforeach

</div>

{{-- TABLE --}}
<div class="bg-white rounded-2xl shadow border overflow-hidden">

<div class="px-5 py-4 border-b">
<h3 class="font-semibold text-slate-700">
Evaluasi Bulanan Siswa
</h3>
</div>

<div class="overflow-x-auto">

<table class="w-full text-sm">

<thead class="bg-slate-100">
<tr>
<th class="px-4 py-3">Rank</th>
<th class="px-4 py-3">Nama</th>
<th class="px-4 py-3">Nilai</th>
<th class="px-4 py-3">Absensi</th>
<th class="px-4 py-3">Sikap</th>
<th class="px-4 py-3">Disiplin</th>
<th class="px-4 py-3">Skor</th>
<th class="px-4 py-3">Kategori</th>
</tr>
</thead>

<tbody>

@foreach($data as $item)

<tr class="border-t hover:bg-slate-50">

<td class="px-4 py-3 font-bold">
{{ $loop->iteration }}
</td>

<td class="px-4 py-3 font-semibold">
{{ $item['nama'] }}
</td>

<td class="px-4 py-3">{{ $item['nilai'] }}</td>
<td class="px-4 py-3">{{ $item['absensi'] }}%</td>
<td class="px-4 py-3">{{ $item['sikap'] }}</td>
<td class="px-4 py-3">{{ $item['disiplin'] }}</td>

<td class="px-4 py-3 font-bold text-blue-600">
{{ $item['hasil'] }}
</td>

<td class="px-4 py-3">

@if($item['kategori']=='Sangat Baik')
<span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs">
Sangat Baik
</span>

@elseif($item['kategori']=='Baik')
<span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs">
Baik
</span>

@elseif($item['kategori']=='Perlu Bimbingan')
<span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs">
Perlu Bimbingan
</span>

@else
<span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs">
Perlu Pembinaan
</span>
@endif

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

</div>

@endif

</div>

</x-app-layout>
<x-app-layout>

<x-page-header 
title="Input Nilai Kedisiplinan"
subtitle="Penilaian Disiplin Siswa"
/>

<div class="bg-white rounded-xl shadow-sm border p-6">

<form action="{{ route('kedisiplinan.store') }}" method="POST">
@csrf

<input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">

<div class="mb-4 font-semibold text-gray-700">
{{ $jadwal->mapel->nama_mapel }} - {{ $jadwal->kelas->nama_kelas }}
</div>

<div class="overflow-x-auto">

<table class="w-full border">

<thead class="bg-gray-100">
<tr>
<th class="p-3 border">No</th>
<th class="p-3 border">Nama</th>
<th class="p-3 border">Nilai</th>
<th class="p-3 border">Keterangan</th>
</tr>
</thead>

<tbody>

@foreach($siswas as $s)

<tr>
<td class="p-2 border">{{ $loop->iteration }}</td>

<td class="p-2 border">
{{ $s->nama }}
<input type="hidden" name="siswa_id[]" value="{{ $s->id }}">
</td>

<td class="p-2 border">
<input type="number"
name="nilai_disiplin[]"
min="0"
max="100"
class="nilai-input w-full rounded border-gray-300"
required>
</td>

<td class="p-2 border">
<input type="text"
name="keterangan[]"
class="ket-input w-full rounded border-gray-300 bg-gray-50"
readonly>
</td>

</tr>

@endforeach

</tbody>

</table>

</div>

<div class="mt-5 bg-blue-50 border border-blue-200 rounded-lg p-4 text-sm">
81 - 100 : Sangat Disiplin<br>
71 - 80 : Disiplin<br>
61 - 70 : Cukup Disiplin<br>
0 - 60 : Kurang Disiplin
</div>

<div class="flex justify-end mt-5">
<button type="submit"
class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg">
Simpan
</button>
</div>

</form>

</div>

<script>
document.querySelectorAll('.nilai-input').forEach(function(input){

input.addEventListener('keyup', function(){

let nilai = parseInt(this.value);
let row = this.closest('tr');
let ket = row.querySelector('.ket-input');

if(nilai >= 81){
ket.value = 'Sangat Disiplin';
}
else if(nilai >= 71){
ket.value = 'Disiplin';
}
else if(nilai >= 61){
ket.value = 'Cukup Disiplin';
}
else if(nilai >= 0){
ket.value = 'Kurang Disiplin';
}
else{
ket.value = '';
}

});

});
</script>

</x-app-layout>
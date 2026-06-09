{{-- resources/views/absensi/create.blade.php --}}

<x-app-layout>

    {{-- HEADER --}}
    <x-page-header 
        title="Input Absensi"
        subtitle="{{ $jadwal->mapel->nama_mapel }} - {{ $jadwal->kelas->nama_kelas }}"
    />

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-2xl shadow-sm border p-6">

                {{-- INFO --}}
                <div class="mb-6 flex flex-wrap gap-6 text-sm text-gray-600">
                    <div>📚 Kelas: <b>{{ $jadwal->kelas->nama_kelas }}</b></div>
                    <div>👨‍🏫 Guru: <b>{{ $jadwal->guru->nama }}</b></div>
                    <div>📘 Mapel: <b>{{ $jadwal->mapel->nama_mapel }}</b></div>
                    <div>⏰ {{ substr($jadwal->jam_masuk,0,5) }} - {{ substr($jadwal->jam_selesai,0,5) }}</div>
                    <div>📅 {{ now()->format('d-m-Y') }}</div>
                </div>

                {{-- FORM --}}
                <form id="formAbsensi">
                    @csrf

                    <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">
                    <input type="hidden" name="tanggal" value="{{ date('Y-m-d') }}">

                    {{-- PERTEMUAN --}}
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Pertemuan
                        </label>

                        <input type="text"
                            name="pertemuan"
                            value="Pertemuan {{ $pertemuan ?? 1 }}"
                            class="w-full md:w-72 rounded-xl border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    {{-- TABLE --}}
                    <div class="overflow-x-auto">
                        <table class="w-full border rounded-xl overflow-hidden">

                            <thead class="bg-gray-50 text-sm">
                                <tr>
                                    <th class="p-3 text-left w-16">NO</th>
                                    <th class="p-3 text-left">Nama Siswa</th>
                                    <th class="p-3 text-center">Hadir</th>
                                    <th class="p-3 text-center">Izin</th>
                                    <th class="p-3 text-center">Sakit</th>
                                    <th class="p-3 text-center">Alpha</th>
                                </tr>
                            </thead>

                            <tbody class="text-sm">

                                @foreach($siswas as $s)

                                <tr class="border-t hover:bg-gray-50">

                                    <td class="px-4 py-3">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="p-3 font-medium text-gray-800">
                                        {{ $s->nama }}
                                    </td>

                                    @foreach(['hadir','izin','sakit','alpha'] as $status)
                                    <td class="text-center p-3">

                                        <label class="cursor-pointer">
                                            <input type="radio"
                                                name="status[{{ $s->id }}]"
                                                value="{{ $status }}"
                                                class="status-radio w-4 h-4 text-blue-600"
                                                {{ $status == 'hadir' ? 'checked' : '' }}>
                                        </label>

                                    </td>
                                    @endforeach

                                </tr>

                                @endforeach

                            </tbody>

                        </table>
                    </div>

                    {{-- STATUS SAVE --}}
                    <div class="mt-4 text-sm text-gray-500" id="statusSave">
                        Belum disimpan
                    </div>

                    {{-- BUTTON --}}
                    <div class="mt-5 flex justify-end gap-3">

                        <a href="{{ route('absensi.index') }}"
                           class="px-5 py-2 rounded-xl border text-gray-600 hover:bg-gray-100">
                            Kembali
                        </a>

                        <button type="button"
                            onclick="simpanManual()"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl font-semibold">
                            Simpan Sekarang
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>

</x-app-layout>


{{-- AUTO SAVE --}}
<script>

function kirimAbsensi()
{
    let form = document.getElementById('formAbsensi');
    let formData = new FormData(form);

    fetch("{{ route('absensi.store') }}", {
        method: "POST",
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
        },
        body: formData
    })
    .then(res => res.json())
    .then(res => {

        document.getElementById('statusSave').innerHTML =
        "<span class='text-green-600'>✔ Tersimpan otomatis</span>";

    })
    .catch(() => {

        document.getElementById('statusSave').innerHTML =
        "<span class='text-red-500'>❌ Gagal menyimpan</span>";

    });
}

document.querySelectorAll('.status-radio').forEach(radio => {

    radio.addEventListener('change', function () {
        kirimAbsensi();
    });

});

function simpanManual()
{
    kirimAbsensi();
}

</script>
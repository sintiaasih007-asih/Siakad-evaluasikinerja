<div x-data="modalJadwal()">

<x-app-layout>

    {{-- HEADER --}}
    <x-page-header 
        title="Data Jadwal Pelajaran" 
        subtitle="Dashboard / Jadwal" 
    />

    {{-- CARD --}}
    <div class="bg-white rounded-xl shadow-sm border p-6">

        {{-- ACTION --}}
        <div class="flex justify-between items-center mb-6">

            <h2 class="text-lg font-medium text-gray-700">
                Daftar Jadwal
            </h2>

            <a href="{{ route('jadwal.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">
                + Tambah Jadwal
            </a>

        </div>

        {{-- ================================================= --}}
        {{-- JADWAL PER KELAS --}}
        {{-- ================================================= --}}

        @foreach($kelas as $k)

        <div class="mb-8">

            {{-- HEADER KELAS --}}
            <div class="flex items-center justify-between mb-3">

                <div>
                    <h3 class="text-lg font-semibold text-slate-800">
                        Kelas {{ $k->nama_kelas }}
                    </h3>

                    <p class="text-sm text-slate-500">
                        Jadwal Pelajaran Siswa
                    </p>
                </div>

                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs font-medium">
                    {{
                        $jadwals->where('kelas_id', $k->id)->count()
                    }} Jadwal
                </span>

            </div>

            {{-- TABLE --}}
            <div class="overflow-x-auto border rounded-xl">

                <table class="w-full text-sm text-left">

                    <thead class="bg-slate-50 text-slate-600 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3">Hari</th>
                            <th class="px-4 py-3">Jam</th>
                            <th class="px-4 py-3">Mapel</th>
                            <th class="px-4 py-3">Guru</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y">

                        @php
                            $jadwalKelas = $jadwals->where('kelas_id', $k->id);
                        @endphp

                        @forelse($jadwalKelas as $j)

                        <tr class="hover:bg-slate-50 transition">

                            <td class="px-4 py-3">
                                {{ $loop->iteration }}
                            </td>

                            {{-- HARI --}}
                            <td class="px-4 py-3 font-medium text-slate-700">
                                <span class="px-2 py-1 bg-slate-100 rounded-lg text-xs">
                                    {{ $j->hari }}
                                </span>
                            </td>

                            {{-- JAM --}}
                            <td class="px-4 py-3 text-slate-600">
                                <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded-lg text-xs">
                                    {{ $j->jam_masuk }} - {{ $j->jam_selesai }}
                                </span>
                            </td>

                            {{-- MAPEL --}}
                            <td class="px-4 py-3 font-medium text-slate-800">
                                {{ $j->mapel->nama_mapel ?? '-' }}
                            </td>

                            {{-- GURU --}}
                            <td class="px-4 py-3 text-slate-600">
                                {{ $j->guru->nama ?? '-' }}
                            </td>

                            {{-- AKSI --}}
                            <td class="px-4 py-3 flex gap-2">

                                <button 
                                    @click="openModal = true; setData({{ json_encode($j) }})"
                                    class="text-blue-600 hover:underline text-sm">
                                    Edit
                                </button>

                                <form action="{{ route('jadwal.destroy', $j->id) }}" method="POST"
                                      onsubmit="return confirm('Yakin hapus data ini?')">
                                    @csrf
                                    @method('DELETE')

                                    <button class="text-red-500 hover:underline text-sm">
                                        Hapus
                                    </button>
                                </form>

                            </td>

                        </tr>

                        @empty

                        <tr>
                            <td colspan="6" class="text-center py-6 text-slate-500">
                                Belum ada jadwal untuk kelas ini
                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        @endforeach

        {{-- MODAL EDIT JADWAL --}}
        <div x-show="openModal"
            x-transition
            class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">

            <div @click.away="openModal = false"
                class="bg-white rounded-xl shadow-lg w-full max-w-4xl p-6 overflow-y-auto max-h-[90vh]">

                <h2 class="text-lg font-semibold mb-4">Edit Jadwal</h2>

                <form :action="'/jadwal/' + form.id" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-2 gap-4">

                        {{-- HARI --}}
                        <div>
                            <label class="text-sm">Hari</label>
                            <select name="hari" x-model="form.hari"
                                class="w-full border rounded-lg p-2">
                                <option value="Senin">Senin</option>
                                <option value="Selasa">Selasa</option>
                                <option value="Rabu">Rabu</option>
                                <option value="Kamis">Kamis</option>
                                <option value="Jumat">Jumat</option>
                                <option value="Sabtu">Sabtu</option>
                            </select>
                        </div>

                        {{-- KELAS --}}
                        <div>
                            <label class="text-sm">Kelas</label>
                            <select name="kelas_id" x-model="form.kelas_id"
                                class="w-full border rounded-lg p-2">
                                @foreach($kelas as $k)
                                    <option value="{{ $k->id }}">
                                        {{ $k->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- JAM MASUK --}}
                        <div>
                            <label class="text-sm">Jam Masuk</label>
                            <input type="time" name="jam_masuk" x-model="form.jam_masuk"
                                class="w-full border rounded-lg p-2">
                        </div>

                        {{-- JAM SELESAI --}}
                        <div>
                            <label class="text-sm">Jam Selesai</label>
                            <input type="time" name="jam_selesai" x-model="form.jam_selesai"
                                class="w-full border rounded-lg p-2">
                        </div>

                        {{-- MAPEL --}}
                        <div>
                            <label class="text-sm">Mapel</label>
                            <select name="mapel_id" x-model="form.mapel_id"
                                class="w-full border rounded-lg p-2">
                                @foreach($mapels as $m)
                                    <option value="{{ $m->id }}">
                                        {{ $m->nama_mapel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- GURU --}}
                        <div>
                            <label class="text-sm">Guru</label>
                            <select name="guru_id" x-model="form.guru_id"
                                class="w-full border rounded-lg p-2">
                                @foreach($gurus as $g)
                                    <option value="{{ $g->id }}">
                                        {{ $g->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    {{-- BUTTON --}}
                    <div class="flex justify-end gap-2 mt-6">
                        <button type="button"
                            @click="openModal = false"
                            class="px-4 py-2 border rounded-lg">
                            Batal
                        </button>

                        <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                            Update
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

</x-app-layout>

<script>
function modalJadwal() {
    return {
        openModal: false,

        form: {
            id: '',
            hari: '',
            jam_masuk: '',
            jam_selesai: '',
            kelas_id: '',
            mapel_id: '',
            guru_id: ''
        },

        setData(data) {
            this.form.id = data.id
            this.form.hari = data.hari
            this.form.jam_masuk = data.jam_masuk
            this.form.jam_selesai = data.jam_selesai
            this.form.kelas_id = data.kelas_id
            this.form.mapel_id = data.mapel_id
            this.form.guru_id = data.guru_id
        }
    }
}
</script>
</div>
<div x-data="modalMapel()">

<x-app-layout>

    {{-- HEADER --}}
    <x-page-header 
        title="Data Mata Pelajaran" 
        subtitle="Dashboard / Mapel" 
    />

    {{-- CARD --}}
    <div class="bg-white rounded-xl shadow-sm border p-6">

        {{-- ACTION --}}
        <div class="flex justify-between items-center mb-4">

            <h2 class="text-lg font-medium text-gray-700">
                Daftar Mata Pelajaran
            </h2>

            <a href="{{ route('mapel.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">
                + Tambah
            </a>

        </div>

        {{-- TABLE --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">

                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Kode</th>
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">Guru</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    @forelse($mapels as $m)
                    <tr class="hover:bg-gray-50 transition">

                    <td class="px-4 py-3">
                        {{ $loop-> iteration }}
                    </td>

                        <td class="px-4 py-3 font-medium text-gray-700">
                            {{ $m->kode_mapel }}
                        </td>

                        <td class="px-4 py-3 text-gray-800">
                            {{ $m->nama_mapel }}
                        </td>

                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-600">
                                {{ $m->guru->nama ?? '-' }}
                            </span>
                        </td>

                        {{-- AKSI --}}
                        <td class="px-4 py-3 flex gap-2">

                                <button 
                                    @click='openModal = true; setData(@json($m))'
                                    class="text-blue-600 hover:underline text-sm">
                                    Edit
                                </button>

                            <form action="{{ route('mapel.destroy', $m->id) }}" method="POST"
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
                        <td colspan="4" class="text-center py-4 text-gray-500">
                            Data mata pelajaran belum tersedia
                        </td>
                    </tr>
                    @endforelse

                </tbody>

            </table>
        </div>

        {{-- MODAL EDIT MAPEL --}}
        <div x-show="openModal"
            x-transition
            class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">

            <div @click.away="openModal = false"
                class="bg-white rounded-xl shadow-lg w-full max-w-xl p-6">

                <h2 class="text-lg font-semibold mb-4">Edit Mata Pelajaran</h2>

                <form :action="'/mapel/' + form.id" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- KODE --}}
                    <div class="mb-3">
                        <label class="text-sm">Kode Mapel</label>
                        <input type="text" name="kode_mapel" x-model="form.kode_mapel"
                            class="w-full border rounded-lg p-2">
                    </div>

                    {{-- NAMA --}}
                    <div class="mb-3">
                        <label class="text-sm">Nama Mapel</label>
                        <input type="text" name="nama_mapel" x-model="form.nama_mapel"
                            class="w-full border rounded-lg p-2">
                    </div>

                    {{-- GURU --}}
                    <div class="mb-3">
                        <label class="text-sm">Guru Pengajar</label>
                        <select name="guru_id" x-model="form.guru_id"
                            class="w-full border rounded-lg p-2">

                            <option value="">-- Pilih Guru --</option>

                            @foreach($gurus as $g)
                                <option value="{{ $g->id }}">
                                    {{ $g->nama }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    {{-- BUTTON --}}
                    <div class="flex justify-end gap-2 mt-4">
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
function modalMapel() {
    return {
        openModal: false,

        form: {
            id: '',
            kode_mapel: '',
            nama_mapel: '',
            guru_id: ''
        },

        setData(data) {
            this.form.id = data.id
            this.form.kode_mapel = data.kode_mapel
            this.form.nama_mapel = data.nama_mapel
            this.form.guru_id = data.guru_id
        }
    }
}
</script>

</div>
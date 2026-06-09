<div x-data="modalKelas()">

<x-app-layout>

    {{-- HEADER --}}
    <x-page-header 
        title="Data Kelas" 
        subtitle="Dashboard / Kelas" 
    />

    {{-- CARD --}}
    <div class="bg-white rounded-xl shadow-sm border p-6">

        {{-- ACTION --}}
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-medium text-gray-700">
                Daftar Kelas
            </h2>

            <a href="{{ route('kelas.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">
                + Tambah Kelas
            </a>
        </div>

        {{-- TABLE --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">

                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Nama Kelas</th>
                        <th class="px-4 py-3">Wali Kelas</th>
                        <th class="px-4 py-3">Tahun Ajaran</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    @forelse($kelas as $k)
                    <tr class="hover:bg-gray-50 transition">

                        <td class="px-4 py-3">
                            {{ $loop->iteration }}
                        </td>

                        <td class="px-4 py-3 font-medium text-gray-800">
                            {{ $k->nama_kelas }}
                        </td>

                        {{-- WALI KELAS --}}
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 bg-blue-100 text-blue-600 text-xs rounded">
                                {{ $k->guru->nama ?? '-' }}
                            </span>
                        </td>

                        {{-- TAHUN AJARAN --}}
                        <td class="px-4 py-3 text-gray-600">
                            {{ $k->tahunAjaran->tahun ?? '-' }}
                            <span class="text-xs text-gray-400">
                                ({{ $k->tahunAjaran->semester ?? '-' }})
                            </span>
                        </td>

                        {{-- AKSI --}}
                        <td class="px-4 py-3 flex gap-2">

                            <button 
                                @click='openModal = true; setData(@json($k))'
                                class="text-blue-600 hover:underline text-sm">
                                Edit
                            </button>

                            <form action="{{ route('kelas.destroy', $k->id) }}" method="POST"
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
                        <td colspan="5" class="text-center py-4 text-gray-500">
                            Data kelas belum tersedia
                        </td>
                    </tr>
                    @endforelse

                </tbody>

            </table>
        </div>

        {{-- MODAL EDIT KELAS --}}
        <div x-show="openModal"
            x-transition
            class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">

            <div @click.away="openModal = false"
                class="bg-white rounded-xl shadow-lg w-full max-w-xl p-6">

                <h2 class="text-lg font-semibold mb-4">Edit Data Kelas</h2>

                <form :action="'/kelas/' + form.id" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- NAMA KELAS --}}
                    <div class="mb-3">
                        <label class="text-sm">Nama Kelas</label>
                        <input type="text" name="nama_kelas" x-model="form.nama_kelas"
                            class="w-full border rounded-lg p-2">
                    </div>

                    {{-- WALI KELAS --}}
                    <div class="mb-3">
                        <label class="text-sm">Wali Kelas</label>
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

                    {{-- TAHUN AJARAN --}}
                    <div class="mb-3">
                        <label class="text-sm">Tahun Ajaran</label>
                        <select name="tahun_ajaran_id" x-model="form.tahun_ajaran_id"
                            class="w-full border rounded-lg p-2">

                            <option value="">-- Pilih Tahun Ajaran --</option>

                            @foreach($tahunAjarans as $t)
                                <option value="{{ $t->id }}">
                                    {{ $t->tahun }} ({{ $t->semester }})
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
function modalKelas() {
    return {
        openModal: false,

        form: {
            id: '',
            nama_kelas: '',
            guru_id: '',
            tahun_ajaran_id: ''
        },

        setData(data) {
            this.form.id = data.id
            this.form.nama_kelas = data.nama_kelas
            this.form.guru_id = data.guru_id
            this.form.tahun_ajaran_id = data.tahun_ajaran_id
        }
    }
}
</script>

</div>
<div x-data="modalTahunAjaran()">

<x-app-layout>

    {{-- HEADER --}}
    <x-page-header 
        title="Data Tahun Ajaran" 
        subtitle="Dashboard / Tahun Ajaran" 
    />

    {{-- CARD --}}
    <div class="bg-white rounded-xl shadow-sm border p-6">

        {{-- ACTION --}}
        <div class="flex justify-between items-center mb-4">

            <h2 class="text-lg font-medium text-gray-700">
                Daftar Tahun Ajaran
            </h2>

            <a href="{{ route('tahun-ajaran.create') }}"
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
                        <th class="px-4 py-3">Tahun</th>
                        <th class="px-4 py-3">Semester</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    @forelse ($data as $d)
                    <tr class="hover:bg-gray-50 transition">

                        <td class="px-4 py-3">
                            {{ $loop->iteration }}
                        </td>

                        <td class="px-4 py-3 font-medium text-gray-800">
                            {{ $d->tahun }}
                        </td>

                        <td class="px-4 py-3">
                            {{ ucfirst($d->semester) }}
                        </td>

                        {{-- STATUS --}}
                        <td class="px-4 py-3">
                            @if($d->is_active)
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-600">
                                    Aktif
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-500">
                                    Tidak Aktif
                                </span>
                            @endif
                        </td>

                        {{-- AKSI --}}
                        <td class="px-4 py-3 flex gap-2">

                            <button 
                                @click="openModal = true; setData({{ json_encode($d) }})"
                                class="text-blue-600 hover:underline text-sm">
                                Edit
                            </button>

                            <form action="{{ route('tahun-ajaran.destroy', $d->id) }}" method="POST"
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
                            Data belum tersedia
                        </td>
                    </tr>
                    @endforelse

                </tbody>

            </table>
        </div>

        {{-- MODAL EDIT TAHUN AJARAN --}}
        <div x-show="openModal"
            x-transition
            class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">

            <div @click.away="openModal = false"
                class="bg-white rounded-xl shadow-lg w-full max-w-xl p-6">

                <h2 class="text-lg font-semibold mb-4">Edit Tahun Ajaran</h2>

                <form :action="'/tahun-ajaran/' + form.id" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- TAHUN --}}
                    <div class="mb-3">
                        <label class="text-sm">Tahun Ajaran</label>
                        <input type="text" name="tahun" x-model="form.tahun"
                            class="w-full border rounded-lg p-2"
                            placeholder="Contoh: 2025/2026">
                    </div>

                    {{-- SEMESTER --}}
                    <div class="mb-3">
                        <label class="text-sm">Semester</label>
                        <select name="semester" x-model="form.semester"
                            class="w-full border rounded-lg p-2">
                            <option value="ganjil">Ganjil</option>
                            <option value="genap">Genap</option>
                        </select>
                    </div>

                    {{-- STATUS --}}
                    <div class="mb-3">
                        <label class="text-sm">Status</label>
                        <select name="is_active" x-model="form.is_active"
                            class="w-full border rounded-lg p-2">
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
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
function modalTahunAjaran() {
    return {
        openModal: false,

        form: {
            id: '',
            tahun: '',
            semester: '',
            is_active: ''
        },

        setData(data) {
            this.form.id = data.id
            this.form.tahun = data.tahun
            this.form.semester = data.semester
            this.form.is_active = data.is_active ? '1' : '0'
        }
    }
}
</script>

</div>


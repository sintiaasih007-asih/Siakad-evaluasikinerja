<div x-data="modalGuru()">

<x-app-layout>

    {{-- HEADER --}}
    <x-page-header 
        title="Data Guru" 
        subtitle="Dashboard / Guru" 
    />

    {{-- CARD --}}
    <div class="bg-white rounded-xl shadow-sm border p-6">

        {{-- ACTION --}}
        <div class="flex justify-between items-center mb-4">

            <h2 class="text-lg font-medium text-gray-700">
                Daftar Guru
            </h2>

            <a href="{{ route('guru.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">
                + Tambah Guru
            </a>

        </div>

        {{-- TABLE --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">

                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">NIP</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    @forelse ($gurus as $g)
                    <tr class="hover:bg-gray-50 transition">

                        <td class="px-4 py-3">
                            {{ $loop->iteration }}
                        </td>

                        <td class="px-4 py-3 font-medium text-gray-800">
                            {{ $g->nama }}
                        </td>

                        <td class="px-4 py-3 text-gray-600">
                            {{ $g->nip }}
                        </td>

                        {{-- EMAIL --}}
                        <td class="px-4 py-3">
                            <span class="text-blue-600 text-sm">
                                {{ $g->email }}
                            </span>
                        </td>

                        {{-- AKSI --}}
                        <td class="px-4 py-3 flex gap-2">

                            <button 
                                @click="openModal = true; setData({{ json_encode($g) }})"
                                class="text-blue-500 hover:underline">
                                Edit
                            </button>

                            <form action="{{ route('guru.destroy', $g->id) }}" method="POST"
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
                            Data guru belum tersedia
                        </td>
                    </tr>
                    @endforelse

                </tbody>

            </table>
        </div>

        {{-- MODAL EDIT GURU --}}
        <div x-show="openModal"
            x-transition
            class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">

            <div @click.away="openModal = false"
                class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6">

                <h2 class="text-lg font-semibold mb-4">Edit Data Guru</h2>

                <form :action="'/guru/' + form.id" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- NAMA --}}
                    <div class="mb-3">
                        <label class="text-sm">Nama</label>
                        <input type="text" name="nama" x-model="form.nama"
                            class="w-full border rounded-lg p-2">
                    </div>

                    {{-- NIP --}}
                    <div class="mb-3">
                        <label class="text-sm">NIP</label>
                        <input type="text" name="nip" x-model="form.nip"
                            class="w-full border rounded-lg p-2">
                    </div>

                    {{-- EMAIL --}}
                    <div class="mb-3">
                        <label class="text-sm">Email</label>
                        <input type="email" name="email" x-model="form.email"
                            class="w-full border rounded-lg p-2">
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

        <script>
        function modalGuru() {
            return {
                openModal: false,

                form: {
                    id: '',
                    nama: '',
                    nip: '',
                    email: ''
                },

                setData(data) {
                    this.form.id = data.id
                    this.form.nama = data.nama
                    this.form.nip = data.nip
                    this.form.email = data.email
                }
            }
        }
        </script>

    </div>

    <script defer
    src="https://cdn.jsdelivr.net/npm/face-api.js">
    </script>

    

</x-app-layout>

</div>
<div x-data="modalEdit()">

<x-app-layout>
    
    {{-- HEADER --}}
    <x-page-header 
        title="Data Siswa" 
        subtitle="Dashboard / Siswa" 
    />

    {{-- CARD --}}
    <div class="bg-white rounded-xl shadow-sm border p-6">

        {{-- ACTION --}}
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">

            <h2 class="text-lg font-medium text-gray-700">
                Daftar Siswa
            </h2>

            <div class="flex items-center gap-3">

                {{-- SEARCH --}}
                <div class="relative">

                    <input 
                        type="text"
                        x-model="search"
                        placeholder="Cari siswa..."
                        class="w-72 border border-gray-300 rounded-xl pl-10 pr-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >

                    <i data-lucide="search"
                       class="w-4 h-4 absolute left-3 top-2.5 text-gray-400">
                    </i>

                </div>

                {{-- BUTTON TAMBAH --}}
                <a href="{{ route('siswa.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">
                    + Tambah Siswa
                </a>
                
                <form action="{{ route('siswa.naikKelas') }}" method="POST">
                    @csrf

                    <button
                        onclick="return confirm('Naikkan semua siswa?')"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm">

                        Naikkan Semua Siswa

                    </button>
                </form>
            </div>

        </div>



        {{-- ========================================= --}}
        {{-- LOOP SEMUA KELAS DINAMIS --}}
        {{-- ========================================= --}}
        @foreach($kelas as $k)

        <div class="mb-10">

            {{-- HEADER KELAS --}}
            <div class="flex items-center justify-between mb-4">

                <h3 class="text-base font-semibold text-gray-700">
                    Data Siswa {{ $k->nama_kelas }}
                </h3>

                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs">
                    {{ $k->nama_kelas }}
                </span>

            </div>

            {{-- TABLE --}}
            <div class="overflow-x-auto border rounded-xl">

                <table class="w-full text-sm text-left">

                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">

                        <tr>
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3">NIS</th>
                            <th class="px-4 py-3">Nama</th>
                            <th class="px-4 py-3">JK</th>
                            <th class="px-4 py-3">Kelas</th>
                            <th class="px-4 py-3">Orang Tua</th>
                            <th class="px-4 py-3">No HP</th>
                            <th class="px-4 py-3">Alamat</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>

                    </thead>

                    <tbody class="divide-y">

                        {{-- LOOP SISWA BERDASARKAN KELAS --}}
                        @forelse($k->siswas as $s)

                        <tr 
                            class="hover:bg-gray-50 transition"
                            x-show="
                                '{{ strtolower($s->nama) }}'.includes(search.toLowerCase()) ||
                                '{{ strtolower($s->nis) }}'.includes(search.toLowerCase())
                            "
                        >

                            {{-- NO --}}
                            <td class="px-4 py-3">
                                {{ $loop->iteration }}
                            </td>

                            {{-- NIS --}}
                            <td class="px-4 py-3 text-gray-600">
                                {{ $s->nis }}
                            </td>

                            {{-- NAMA --}}
                            <td class="px-4 py-3 font-medium text-gray-800">
                                {{ $s->nama }}
                            </td>

                            {{-- JK --}}
                            <td class="px-4 py-3">

                                @if($s->jk == 'L')
                                    <span class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded">
                                        Laki-laki
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs bg-pink-100 text-pink-700 rounded">
                                        Perempuan
                                    </span>
                                @endif

                            </td>

                            {{-- KELAS --}}
                            <td class="px-4 py-3">

                                <span class="px-2 py-1 bg-blue-100 text-blue-600 text-xs rounded">
                                    {{ $s->kelas->nama_kelas ?? '-' }}
                                </span>

                            </td>

                            {{-- ORTU --}}
                            <td class="px-4 py-3 text-gray-600">
                                {{ $s->nama_ortu }}
                            </td>

                            {{-- HP --}}
                            <td class="px-4 py-3 text-gray-600">
                                {{ $s->no_hp_ortu }}
                            </td>

                            {{-- ALAMAT --}}
                            <td class="px-4 py-3 text-gray-600 max-w-xs truncate">
                                {{ $s->alamat }}
                            </td>

                            {{-- AKSI --}}
                            <td class="px-4 py-3 flex gap-2">

                                {{-- EDIT --}}
                                <button 
                                    @click="openModal = true; setData({{ json_encode($s) }})"
                                    class="text-blue-500 hover:underline">
                                    Edit
                                </button>

                                {{-- DELETE --}}
                                <form action="{{ route('siswa.destroy', $s->id) }}" 
                                      method="POST"
                                      onsubmit="return confirm('Yakin hapus data ini?')">

                                    @csrf
                                    @method('DELETE')

                                    <button class="text-red-500 hover:underline text-sm">
                                        Hapus
                                    </button>

                                </form>

                            </td>

                        </tr>

                        {{-- JIKA TIDAK ADA SISWA --}}
                        @empty

                        <tr>
                            <td colspan="9" class="text-center py-4 text-gray-500">
                                Belum ada siswa di kelas ini
                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        @endforeach


        {{-- ========================================= --}}
        {{-- MODAL EDIT --}}
        {{-- ========================================= --}}
        <div x-show="openModal"
            x-transition
            class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">

            <div @click.away="openModal = false"
                class="bg-white rounded-xl shadow-lg w-full max-w-4xl p-6 max-h-[90vh] overflow-y-auto">

                <h2 class="text-lg font-semibold mb-4">
                    Edit Data Siswa
                </h2>

                <form x-bind:action="`{{ url('siswa') }}/${form.id}`" method="POST">

                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- KOLOM KIRI --}}
                        <div>

                            {{-- NIS --}}
                            <div class="mb-3">
                                <label class="text-sm">NIS</label>

                                <input type="text"
                                       name="nis"
                                       x-model="form.nis"
                                       class="w-full border rounded-lg p-2">
                            </div>

                            {{-- NAMA --}}
                            <div class="mb-3">
                                <label class="text-sm">Nama</label>

                                <input type="text"
                                       name="nama"
                                       x-model="form.nama"
                                       class="w-full border rounded-lg p-2">
                            </div>

                            {{-- JK --}}
                            <div class="mb-3">

                                <label class="text-sm">
                                    Jenis Kelamin
                                </label>

                                <select name="jk"
                                        x-model="form.jk"
                                        class="w-full border rounded-lg p-2">

                                    <option value="">
                                        -- Pilih --
                                    </option>

                                    <option value="L">
                                        Laki-laki
                                    </option>

                                    <option value="P">
                                        Perempuan
                                    </option>

                                </select>

                            </div>

                            {{-- KELAS --}}
                            <div class="mb-3">

                                <label class="text-sm">
                                    Kelas
                                </label>

                                <select name="kelas_id"
                                        x-model="form.kelas_id"
                                        class="w-full border rounded-lg p-2">

                                    <option value="">
                                        -- Pilih Kelas --
                                    </option>

                                    @foreach($kelas as $kls)

                                    <option value="{{ $kls->id }}">
                                        {{ $kls->nama_kelas }}
                                    </option>

                                    @endforeach

                                </select>

                            </div>

                            {{-- ALAMAT --}}
                            <div class="mb-3">

                                <label class="text-sm">
                                    Alamat
                                </label>

                                <textarea name="alamat"
                                          x-model="form.alamat"
                                          class="w-full border rounded-lg p-2"></textarea>

                            </div>

                        </div>


                        {{-- KOLOM KANAN --}}
                        <div>

                            <h3 class="text-sm font-semibold text-gray-600 mb-2">
                                Data Orang Tua / Wali
                            </h3>

                            {{-- NAMA ORTU --}}
                            <div class="mb-3">

                                <label class="text-sm">
                                    Nama Orang Tua
                                </label>

                                <input type="text"
                                       name="nama_ortu"
                                       x-model="form.nama_ortu"
                                       class="w-full border rounded-lg p-2">

                            </div>

                            {{-- HP --}}
                            <div class="mb-3">

                                <label class="text-sm">
                                    No HP
                                </label>

                                <input type="text"
                                       name="no_hp_ortu"
                                       x-model="form.no_hp_ortu"
                                       class="w-full border rounded-lg p-2">

                            </div>

                            {{-- ALAMAT ORTU --}}
                            <div class="mb-3">

                                <label class="text-sm">
                                    Alamat Orang Tua
                                </label>

                                <textarea name="alamat_ortu"
                                          x-model="form.alamat_ortu"
                                          class="w-full border rounded-lg p-2"></textarea>

                            </div>

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


        {{-- ========================================= --}}
        {{-- ALPINE JS --}}
        {{-- ========================================= --}}
        <script>
        function modalEdit() {
            return {

                openModal: false,
                search: '',

                form: {
                    id: '',
                    nis: '',
                    nama: '',
                    jk: '',
                    kelas_id: '',
                    alamat: '',
                    nama_ortu: '',
                    no_hp_ortu: '',
                    alamat_ortu: ''
                },

                setData(data) {

                    this.form.id = data.id
                    this.form.nis = data.nis
                    this.form.nama = data.nama
                    this.form.jk = data.jk
                    this.form.kelas_id = data.kelas_id
                    this.form.alamat = data.alamat
                    this.form.nama_ortu = data.nama_ortu
                    this.form.no_hp_ortu = data.no_hp_ortu
                    this.form.alamat_ortu = data.alamat_ortu

                }

            }
        }
        </script>

    </div>

</x-app-layout>

</div>
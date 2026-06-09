<x-app-layout>

    {{-- HEADER --}}
    <x-page-header 
        title="Tambah Data Siswa" 
        subtitle="Dashboard / Siswa / Tambah Data" 
    />

    {{-- CARD --}}
    <div class="bg-white rounded-xl shadow-sm border p-6 max-w-5xl">

        <form action="/siswa" method="POST" class="space-y-5">
            @csrf

            {{-- GRID RESPONSIVE --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- KOLOM KIRI --}}
                <div class="space-y-5">

                    {{-- NIS --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            NIS
                        </label>
                        <input type="text" name="nis"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Masukkan NIS">

                            @error('nis')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                    </div>

                    {{-- NAMA --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Siswa
                        </label>
                        <input type="text" name="nama"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Masukkan nama siswa">
                    </div>

                    {{-- JENIS KELAMIN --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Jenis Kelamin
                        </label>

                        <select name="jk"
                            class="w-full border-gray-300 rounded-lg shadow-sm 
                            focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                            <option value="">-- Pilih Jenis Kelamin --</option>

                            <option value="L" {{ old('jk') == 'L' ? 'selected' : '' }}>
                                Laki-laki
                            </option>

                            <option value="P" {{ old('jk') == 'P' ? 'selected' : '' }}>
                                Perempuan
                            </option>

                        </select>

                        @error('jk')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- KELAS --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Kelas
                        </label>

                        <select name="kelas_id"
                            class="w-full border-gray-300 rounded-lg shadow-sm 
                            focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                            <option value="">-- Pilih Kelas --</option>

                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}"
                                    {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kelas ?? '-' }}
                                </option>
                            @endforeach

                        </select>

                        @error('kelas_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ALAMAT --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Alamat
                        </label>
                        <textarea name="alamat"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            rows="3"
                            placeholder="Masukkan alamat siswa"></textarea>
                    </div>

                </div>

                {{-- KOLOM KANAN --}}
                <div class="space-y-5">

                    {{-- PEMISAH --}}
                    <div class="border-t lg:border-t-0 pt-4 lg:pt-0">
                        <h2 class="text-md font-semibold text-gray-700 mb-2">
                            Data Orang Tua / Wali
                        </h2>
                    </div>

                    {{-- NAMA ORTU --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Orang Tua
                        </label>
                        <input type="text" name="nama_ortu"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Masukkan nama orang tua">
                    </div>

                    {{-- NO HP --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            No HP Orang Tua
                        </label>
                        <input type="text" name="no_hp_ortu"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="08xxxxxxxxxx">
                    </div>

                    {{-- ALAMAT ORTU --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Alamat Orang Tua
                        </label>
                        <textarea name="alamat_ortu"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            rows="3"
                            placeholder="Masukkan alamat orang tua"></textarea>
                    </div>

                </div>

            </div>

            {{-- BUTTON --}}
            <div class="flex justify-end gap-2 pt-4">

                <a href="/siswa"
                   class="px-4 py-2 rounded-lg border text-gray-600 hover:bg-gray-100">
                    Batal
                </a>

                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow-sm transition">
                    Simpan
                </button>

            </div>
        </form>

    </div>

</x-app-layout>
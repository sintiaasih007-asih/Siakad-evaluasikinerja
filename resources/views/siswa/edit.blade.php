<x-app-layout>

    {{-- HEADER --}}
    <x-page-header 
        title="Edit Data Siswa" 
        subtitle="Dashboard / Siswa / Edit Data" 
    />

    {{-- CARD --}}
    <div class="bg-white rounded-xl shadow-sm border p-6 max-w-3xl">

        <form action="/siswa/{{ $siswa->id }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- NIS --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    NIS
                </label>
                <input type="text" name="nis"
                    value="{{ old('nis', $siswa->nis) }}"
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
                    value="{{ old('nama', $siswa->nama) }}"
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

                    <option value="L" 
                        {{ old('jk', $siswa->jk) == 'L' ? 'selected' : '' }}>
                        Laki-laki
                    </option>

                    <option value="P" 
                        {{ old('jk', $siswa->jk) == 'P' ? 'selected' : '' }}>
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
                            {{ old('kelas_id', $siswa->kelas_id) == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
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
                    placeholder="Masukkan alamat siswa">{{ old('alamat', $siswa->alamat) }}</textarea>
            </div>

            {{-- PEMISAH --}}
            <div class="border-t pt-4">
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
                    value="{{ old('nama_ortu', $siswa->nama_ortu) }}"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Masukkan nama orang tua">
            </div>

            {{-- NO HP --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    No HP Orang Tua
                </label>
                <input type="text" name="no_hp_ortu"
                    value="{{ old('no_hp_ortu', $siswa->no_hp_ortu) }}"
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
                    placeholder="Masukkan alamat orang tua">{{ old('alamat_ortu', $siswa->alamat_ortu) }}</textarea>
            </div>

            {{-- BUTTON --}}
            <div class="flex justify-end gap-2 pt-4">

                <a href="/siswa"
                   class="px-4 py-2 rounded-lg border text-gray-600 hover:bg-gray-100">
                    Batal
                </a>

                <button type="submit"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded-lg shadow-sm transition">
                    Update
                </button>

            </div>

        </form>

    </div>

</x-app-layout>
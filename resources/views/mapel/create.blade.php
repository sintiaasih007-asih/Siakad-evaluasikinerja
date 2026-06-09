<x-app-layout>

    {{-- HEADER --}}
    <x-page-header 
        title="Tambah Mata Pelajaran" 
        subtitle="Dashboard / Mapel / Tambah Data" 
    />

    {{-- CARD --}}
    <div class="bg-white rounded-xl shadow-sm border p-6 max-w-2xl">

        <form method="POST" action="{{ route('mapel.store') }}" class="space-y-5">
            @csrf

            {{-- KODE MAPEL --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Kode Mata Pelajaran
                </label>
                <input type="text" name="kode_mapel"
                    value="{{ old('kode_mapel') }}"
                    placeholder="Contoh: MTK01"
                    class="w-full border-gray-300 rounded-lg shadow-sm 
                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                @error('kode_mapel')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- NAMA MAPEL --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Nama Mata Pelajaran
                </label>
                <input type="text" name="nama_mapel"
                    value="{{ old('nama_mapel') }}"
                    placeholder="Contoh: Matematika"
                    class="w-full border-gray-300 rounded-lg shadow-sm 
                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                @error('nama_mapel')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- GURU --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Guru Pengampu
                </label>
                <select name="guru_id"
                    class="w-full border-gray-300 rounded-lg shadow-sm 
                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                    <option value="">-- Pilih Guru --</option>

                    @foreach($gurus as $g)
                        <option value="{{ $g->id }}"
                            {{ old('guru_id') == $g->id ? 'selected' : '' }}>
                            {{ $g->nama }}
                        </option>
                    @endforeach

                </select>

                @error('guru_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- BUTTON --}}
            <div class="flex justify-end gap-2 pt-4">

                <a href="{{ route('mapel.index') }}"
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
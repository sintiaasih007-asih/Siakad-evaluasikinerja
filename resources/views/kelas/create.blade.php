<x-app-layout>

    {{-- HEADER --}}
    <x-page-header 
        title="Tambah Data Kelas" 
        subtitle="Dashboard / Kelas / Tambah Data" 
    />

    {{-- CARD --}}
    <div class="bg-white rounded-xl shadow-sm border p-6 max-w-2xl">

        <form action="{{ route('kelas.store') }}" method="POST" class="space-y-5">
            @csrf

            {{-- NAMA KELAS --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Nama Kelas
                </label>
                <input type="text" name="nama_kelas"
                    value="{{ old('nama_kelas') }}"
                    placeholder="Contoh: VII-A"
                    class="w-full border-gray-300 rounded-lg shadow-sm 
                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            {{-- WALI KELAS --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Wali Kelas
                </label>
                <select name="guru_id"
                    class="w-full border-gray-300 rounded-lg shadow-sm 
                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                    <option value="">-- Pilih Guru --</option>

                    @foreach($guru as $g)
                        <option value="{{ $g->id }}"
                            {{ old('guru_id') == $g->id ? 'selected' : '' }}>
                            {{ $g->nama }}
                        </option>
                    @endforeach

                </select>
            </div>

            {{-- TAHUN AJARAN --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Tahun Ajaran
                </label>
                <select name="tahun_ajaran_id"
                    class="w-full border-gray-300 rounded-lg shadow-sm 
                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                    <option value="">-- Pilih Tahun Ajaran --</option>

                    @foreach($tahun as $t)
                        <option value="{{ $t->id }}"
                            {{ old('tahun_ajaran_id') == $t->id ? 'selected' : '' }}>
                            {{ $t->tahun }} - {{ $t->semester }}
                        </option>
                    @endforeach

                </select>
            </div>

            {{-- BUTTON --}}
            <div class="flex justify-end gap-2 pt-4">

                <a href="{{ route('kelas.index') }}"
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
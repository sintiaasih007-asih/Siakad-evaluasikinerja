<x-app-layout>

    {{-- HEADER --}}
    <x-page-header 
        title="Tambah Tahun Ajaran" 
        subtitle="Dashboard / Tahun Ajaran / Tambah Data" 
    />

    {{-- CARD --}}
    <div class="bg-white rounded-xl shadow-sm border p-6 max-w-2xl">

        <form action="{{ route('tahun-ajaran.store') }}" method="POST" class="space-y-5">
            @csrf

            {{-- TAHUN --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Tahun Ajaran
                </label>
                <input type="text" name="tahun"
                    value="{{ old('tahun') }}"
                    placeholder="Contoh: 2025/2026"
                    class="w-full border-gray-300 rounded-lg shadow-sm 
                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                @error('tahun')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- SEMESTER --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Semester
                </label>
                <select name="semester"
                    class="w-full border-gray-300 rounded-lg shadow-sm 
                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                    <option value="">-- Pilih Semester --</option>
                    <option value="ganjil" {{ old('semester') == 'ganjil' ? 'selected' : '' }}>
                        Ganjil
                    </option>
                    <option value="genap" {{ old('semester') == 'genap' ? 'selected' : '' }}>
                        Genap
                    </option>
                </select>

                @error('semester')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- STATUS AKTIF --}}
            <div class="flex items-center gap-3 pt-2">
                <input type="checkbox" name="is_active" value="1"
                    {{ old('is_active') ? 'checked' : '' }}
                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">

                <label class="text-sm text-gray-700">
                    Jadikan sebagai Tahun Ajaran Aktif
                </label>
            </div>

            {{-- INFO TAMBAHAN --}}
            <div class="text-xs text-gray-400">
                * Hanya satu tahun ajaran yang bisa aktif
            </div>

            {{-- BUTTON --}}
            <div class="flex justify-end gap-2 pt-4">

                <a href="{{ route('tahun-ajaran.index') }}"
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


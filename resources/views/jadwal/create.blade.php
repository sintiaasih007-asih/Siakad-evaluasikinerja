<x-app-layout>

    {{-- HEADER --}}
    <x-page-header 
        title="Tambah Jadwal Pelajaran" 
        subtitle="Dashboard / Jadwal / Tambah Data" 
    />

    {{-- CARD --}}
    <div class="bg-white rounded-xl shadow-sm border p-6 max-w-3xl">

        <form method="POST" action="{{ route('jadwal.store') }}" class="space-y-5">
            @csrf

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
                        <option value="{{ $k->id }}">
                            {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- MAPEL --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Mata Pelajaran
                </label>
                <select name="mapel_id"
                    class="w-full border-gray-300 rounded-lg shadow-sm 
                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                    <option value="">-- Pilih Mapel --</option>

                    @foreach($mapels as $m)
                        <option value="{{ $m->id }}">
                            {{ $m->nama_mapel }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- GURU --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Guru Pengajar
                </label>
                <select name="guru_id"
                    class="w-full border-gray-300 rounded-lg shadow-sm 
                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                    <option value="">-- Pilih Guru --</option>

                    @foreach($gurus as $g)
                        <option value="{{ $g->id }}">
                            {{ $g->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- PEMISAH --}}
            <div class="border-t pt-4">
                <h2 class="text-md font-semibold text-gray-700">
                    Waktu Pelajaran
                </h2>
            </div>

            {{-- HARI --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Hari
                </label>
                <select name="hari"
                    class="w-full border-gray-300 rounded-lg shadow-sm 
                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                    <option value="">-- Pilih Hari --</option>
                    <option>Senin</option>
                    <option>Selasa</option>
                    <option>Rabu</option>
                    <option>Kamis</option>
                    <option>Jumat</option>
                    <option>Sabtu</option>
                </select>
            </div>

            {{-- JAM --}}
            <div class="grid grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Jam Masuk
                    </label>
                    <input type="time" name="jam_masuk"
                        class="w-full border-gray-300 rounded-lg shadow-sm 
                        focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Jam Selesai
                    </label>
                    <input type="time" name="jam_selesai"
                        class="w-full border-gray-300 rounded-lg shadow-sm 
                        focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

            </div>

            {{-- BUTTON --}}
            <div class="flex justify-end gap-2 pt-4">

                <a href="{{ route('jadwal.index') }}"
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
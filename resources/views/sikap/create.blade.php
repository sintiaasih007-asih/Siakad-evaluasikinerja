{{-- resources/views/sikap/create.blade.php --}}
<x-app-layout>

    {{-- HEADER --}}
    <x-page-header 
        title="Input Nilai Sikap"
        subtitle="Penilaian Sikap Siswa"
    />

    <div class="bg-white rounded-xl shadow-sm border p-6">

        <form action="{{ route('sikap.store') }}" method="POST">
            @csrf

            <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">

            {{-- INFO JADWAL --}}
            <div class="mb-5 border-b pb-4">
                <h2 class="text-lg font-semibold text-gray-800">
                    {{ $jadwal->mapel->nama_mapel }} - {{ $jadwal->kelas->nama_kelas }}
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Guru: {{ $jadwal->guru->nama }}
                </p>
            </div>

            {{-- TABEL --}}
            <div class="overflow-x-auto">

                <table class="w-full border border-gray-200 rounded-lg">

                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 border text-left">No</th>
                            <th class="p-3 border text-left">Nama Siswa</th>
                            <th class="p-3 border text-left">Nilai</th>
                            <th class="p-3 border text-left">Keterangan Otomatis</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($siswas as $s)

                        <tr class="hover:bg-gray-50">

                            <td class="p-3 border">
                                {{ $loop->iteration }}
                            </td>

                            <td class="p-3 border">
                                {{ $s->nama }}

                                <input type="hidden" 
                                    name="siswa_id[]" 
                                    value="{{ $s->id }}">
                            </td>

                            {{-- INPUT NILAI --}}
                            <td class="p-3 border w-40">

                                <input type="number"
                                    min="0"
                                    max="100"
                                    name="nilai_sikap[]"
                                    class="nilai-input w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500"
                                    placeholder="0 - 100"
                                    required>

                            </td>

                            {{-- KETERANGAN AUTO --}}
                            <td class="p-3 border">

                                <input type="text"
                                    name="keterangan[]"
                                    class="ket-input w-full border-gray-300 rounded-lg bg-gray-50 text-gray-700"
                                    readonly
                                    placeholder="Otomatis muncul">

                            </td>

                        </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

            {{-- INFO RANGE --}}
            <div class="mt-5 bg-blue-50 border border-blue-200 rounded-lg p-4 text-sm text-gray-700">

                <h3 class="font-semibold text-blue-700 mb-2">
                    Kategori Penilaian Sikap
                </h3>

                <div class="space-y-1">
                    <p>81 - 100 : Sangat Baik</p>
                    <p>71 - 80 : Baik</p>
                    <p>61 - 70 : Cukup</p>
                    <p>0 - 60 : Kurang Fokus</p>
                </div>

            </div>

            {{-- BUTTON --}}
            <div class="flex justify-end mt-6 gap-2">

                <a href="{{ route('sikap.index') }}"
                    class="px-4 py-2 rounded-lg border text-gray-600 hover:bg-gray-100">
                    Batal
                </a>

                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow-sm">
                    Simpan
                </button>

            </div>

        </form>

    </div>

    {{-- SCRIPT AUTO KETERANGAN --}}
    <script>
        document.querySelectorAll('.nilai-input').forEach(function(input){

            input.addEventListener('keyup', function(){

                let nilai = parseInt(this.value);
                let row = this.closest('tr');
                let ket = row.querySelector('.ket-input');

                if (nilai >= 81) {
                    ket.value = 'Sangat Baik';
                } 
                else if (nilai >= 71) {
                    ket.value = 'Baik';
                }
                else if (nilai >= 61) {
                    ket.value = 'Cukup';
                }
                else if (nilai >= 0) {
                    ket.value = 'Kurang Fokus';
                }
                else {
                    ket.value = '';
                }

            });

        });
    </script>

</x-app-layout>
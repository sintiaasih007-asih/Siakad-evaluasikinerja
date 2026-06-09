{{-- resources/views/sikap/index.blade.php --}}
<x-app-layout>

    {{-- HEADER --}}
    <x-page-header 
        title="Input Sikap"
        subtitle="Pilih Mata Pelajaran / Kelas"
    />

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- GRID CARD --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

                @forelse($jadwals as $j)

                    <div class="bg-white rounded-2xl border border-gray-200 hover:border-indigo-300 transition shadow-sm hover:shadow-md p-5">

                        {{-- HEADER CARD --}}
                        <div class="flex items-start justify-between mb-4">

                            <div>
                                <h3 class="text-base font-semibold text-gray-800">
                                    {{ $j->mapel->nama_mapel ?? '-' }}
                                </h3>

                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $j->kelas->nama_kelas ?? '-' }}
                                </p>
                            </div>

                            <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 text-lg">
                                😊
                            </div>

                        </div>

                        {{-- INFORMASI --}}
                        <div class="text-sm text-gray-600 space-y-3 mb-5">

                            <div class="flex justify-between gap-3">
                                <span class="text-gray-400">Guru</span>

                                <span class="font-medium text-gray-800 text-right">
                                    {{ $j->guru->nama ?? '-' }}
                                </span>
                            </div>

                            <div class="flex justify-between gap-3">
                                <span class="text-gray-400">Hari</span>

                                <span class="font-medium text-gray-700">
                                    {{ $j->hari ?? '-' }}
                                </span>
                            </div>

                            <div class="flex justify-between gap-3">
                                <span class="text-gray-400">Jam</span>

                                <span class="font-medium text-gray-700">
                                    {{ substr($j->jam_masuk,0,5) }} - 
                                    {{ substr($j->jam_selesai,0,5) }}
                                </span>
                            </div>

                        </div>

                        {{-- BUTTON --}}
                        <div class="mt-4">
                            <a href="{{ route('sikap.create', $j->id) }}"
                                style="display:block;
                                    width:100%;
                                    background:#1e3a8a;
                                    color:white;
                                    text-align:center;
                                    padding:10px;
                                    border-radius:8px;
                                    font-weight:600;
                                    box-shadow:0 2px 6px rgba(0,0,0,0.1);">
                                Nilai Sikap
                            </a>
                        </div>

                    </div>

                @empty

                    {{-- EMPTY STATE --}}
                    <div class="col-span-3">

                        <div class="bg-white rounded-2xl border border-dashed border-gray-300 py-16 px-6 text-center">

                            <div class="text-4xl mb-3">
                                📚
                            </div>

                            <h3 class="text-lg font-semibold text-gray-700 mb-2">
                                Tidak Ada Jadwal Mengajar
                            </h3>

                            <p class="text-sm text-gray-400">
                                Data jadwal belum tersedia untuk penilaian sikap.
                            </p>

                        </div>

                    </div>

                @endforelse

            </div>

        </div>
    </div>

</x-app-layout>
<x-app-layout>

    <x-page-header
        title="Input Nilai Sikap"
        subtitle="{{ $jadwal->mapel->nama_mapel }} — {{ $jadwal->kelas->nama_kelas }}"
    />

    {{-- Info Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white border border-slate-200 rounded-2xl p-4 flex items-center gap-3 shadow-sm">
            <div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14-7H5a2 2 0 00-2 2v12a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wide">Kelas</p>
                <p class="text-sm font-bold text-slate-800 truncate">{{ $jadwal->kelas->nama_kelas }}</p>
            </div>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl p-4 flex items-center gap-3 shadow-sm">
            <div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wide">Mata Pelajaran</p>
                <p class="text-sm font-bold text-slate-800 truncate">{{ $jadwal->mapel->nama_mapel }}</p>
            </div>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl p-4 flex items-center gap-3 shadow-sm">
            <div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wide">Guru</p>
                <p class="text-sm font-bold text-slate-800 truncate">{{ $jadwal->guru->nama ?? '-' }}</p>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

        <div class="bg-gradient-to-r from-violet-700 to-indigo-700 px-6 py-4 flex items-center gap-3">
            <div class="bg-white/10 p-2 rounded-xl">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-white font-bold text-sm">Form Nilai Sikap</h3>
                <p class="text-violet-200 text-xs">{{ $siswas->count() }} siswa</p>
            </div>
        </div>

        <form action="{{ route('sikap.store') }}" method="POST" class="p-6">
            @csrf
            <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">

            {{-- Legenda --}}
            <div class="mb-5 bg-violet-50 border border-violet-100 rounded-xl p-4 flex flex-wrap gap-x-6 gap-y-1 text-xs text-slate-600">
                <span><strong class="text-violet-700">81–100</strong> : Sangat Baik</span>
                <span><strong class="text-blue-700">71–80</strong> : Baik</span>
                <span><strong class="text-amber-700">61–70</strong> : Cukup</span>
                <span><strong class="text-rose-700">0–60</strong> : Kurang</span>
            </div>

            <div class="overflow-x-auto rounded-xl border border-slate-200">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase w-12">No</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase">Nama Siswa</th>
                            <th class="px-5 py-3.5 text-center text-xs font-bold text-slate-500 uppercase w-36">Nilai (0–100)</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($siswas as $s)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-5 py-3 text-slate-400 text-xs">{{ $loop->iteration }}</td>
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-full bg-violet-100 text-violet-700 text-xs font-bold flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($s->nama,0,1)) }}
                                    </div>
                                    <span class="font-medium text-slate-800">{{ $s->nama }}</span>
                                </div>
                                <input type="hidden" name="siswa_id[]" value="{{ $s->id }}">
                            </td>
                            <td class="px-5 py-3 text-center">
                                <input type="number" name="nilai_sikap[]"
                                    min="0" max="100" placeholder="0"
                                    class="nilai-input w-24 text-center rounded-xl border-slate-300 text-sm font-semibold focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
                                    required>
                            </td>
                            <td class="px-5 py-3">
                                <input type="text" name="keterangan[]"
                                    class="ket-input w-full rounded-xl border-slate-200 bg-slate-50 text-sm text-slate-600"
                                    readonly placeholder="Otomatis">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex items-center justify-between mt-6 pt-5 border-t border-slate-100">
                <a href="{{ route('sikap.index') }}"
                    class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-800 border border-slate-300 hover:border-slate-400 px-4 py-2.5 rounded-xl transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-violet-700 hover:bg-violet-800 text-white text-sm font-bold px-6 py-2.5 rounded-xl transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Nilai Sikap
                </button>
            </div>

        </form>
    </div>

    <script>
    document.querySelectorAll('.nilai-input').forEach(input => {
        input.addEventListener('input', function () {
            const n = parseInt(this.value);
            const ket = this.closest('tr').querySelector('.ket-input');
            if (n >= 81)      ket.value = 'Sangat Baik';
            else if (n >= 71) ket.value = 'Baik';
            else if (n >= 61) ket.value = 'Cukup';
            else if (n >= 0)  ket.value = 'Kurang';
            else              ket.value = '';
        });
    });
    </script>

</x-app-layout>

<x-app-layout>

    <x-page-header
        title="Input Nilai Akademik"
        subtitle="{{ $jadwal->mapel->nama_mapel }} — {{ $jadwal->kelas->nama_kelas }}"
    />

    {{-- Info Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white border border-slate-200 rounded-2xl p-4 flex items-center gap-3 shadow-sm">
            <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14-7H5a2 2 0 00-2 2v12a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wide">Kelas</p>
                <p class="text-sm font-bold text-slate-800 truncate">{{ $jadwal->kelas->nama_kelas }}</p>
            </div>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl p-4 flex items-center gap-3 shadow-sm">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wide">Mapel</p>
                <p class="text-sm font-bold text-slate-800 truncate">{{ $jadwal->mapel->nama_mapel }}</p>
            </div>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl p-4 flex items-center gap-3 shadow-sm">
            <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10m-13 9h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v11a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wide">Tahun Ajaran</p>
                <p class="text-sm font-bold text-slate-800 truncate">{{ $jadwal->kelas->tahunAjaran->tahun ?? now()->year }}</p>
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

        {{-- Card Header --}}
        <div class="bg-gradient-to-r from-slate-800 to-slate-700 px-6 py-4 flex items-center gap-3">
            <div class="bg-white/10 p-2 rounded-xl">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-white font-bold text-sm">Form Input Nilai</h3>
                <p class="text-slate-300 text-xs">{{ $siswas->count() }} siswa</p>
            </div>
        </div>

        <form action="{{ route('nilai.store') }}" method="POST" class="p-6">
            @csrf
            <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">

            {{-- Jenis & Nama Penilaian --}}
            <div class="grid md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1.5">Jenis Nilai</label>
                    <select name="jenis_nilai"
                        class="w-full rounded-xl border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="Tugas">Tugas</option>
                        <option value="Quis">Kuis</option>
                        <option value="UTS">UTS</option>
                        <option value="UAS">UAS</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1.5">Nama Penilaian</label>
                    <input type="text" name="nama_penilaian"
                        placeholder="Contoh: Tugas Bab 1, UTS Semester 1..."
                        class="w-full rounded-xl border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                </div>
            </div>

            {{-- Tabel Nilai --}}
            <div class="overflow-x-auto rounded-xl border border-slate-200">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wide w-12">No</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wide">Nama Siswa</th>
                            <th class="px-5 py-3.5 text-center text-xs font-bold text-slate-500 uppercase tracking-wide w-36">Nilai (0–100)</th>
                            <th class="px-5 py-3.5 text-center text-xs font-bold text-slate-500 uppercase tracking-wide w-24">Predikat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($siswas as $s)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-5 py-3 text-slate-400 text-xs">{{ $loop->iteration }}</td>
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($s->nama,0,1)) }}
                                    </div>
                                    <span class="font-medium text-slate-800">{{ $s->nama }}</span>
                                </div>
                                <input type="hidden" name="siswa_id[]" value="{{ $s->id }}">
                            </td>
                            <td class="px-5 py-3 text-center">
                                <input type="number" name="nilai[]"
                                    min="0" max="100"
                                    placeholder="0"
                                    class="nilai-input w-24 text-center rounded-xl border-slate-300 text-sm font-semibold focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    required>
                            </td>
                            <td class="px-5 py-3 text-center">
                                <span class="predikat-badge px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500">—</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-between mt-6 pt-5 border-t border-slate-100">
                <a href="{{ route('nilai.index') }}"
                    class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-800 border border-slate-300 hover:border-slate-400 px-4 py-2.5 rounded-xl transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-indigo-700 hover:bg-indigo-800 text-white text-sm font-bold px-6 py-2.5 rounded-xl transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Nilai
                </button>
            </div>

        </form>
    </div>

    <script>
    const predikatConfig = [
        { min: 90, label: 'A', cls: 'bg-emerald-100 text-emerald-700' },
        { min: 80, label: 'B', cls: 'bg-blue-100 text-blue-700' },
        { min: 70, label: 'C', cls: 'bg-amber-100 text-amber-700' },
        { min: 60, label: 'D', cls: 'bg-orange-100 text-orange-700' },
        { min: 0,  label: 'E', cls: 'bg-rose-100 text-rose-700' },
    ];

    document.querySelectorAll('.nilai-input').forEach(input => {
        input.addEventListener('input', function () {
            const n = parseInt(this.value);
            const badge = this.closest('tr').querySelector('.predikat-badge');
            const hit = predikatConfig.find(p => n >= p.min) || predikatConfig[predikatConfig.length - 1];
            if (!isNaN(n) && n >= 0 && n <= 100) {
                badge.textContent = hit.label;
                badge.className = `predikat-badge px-2.5 py-1 rounded-full text-xs font-bold ${hit.cls}`;
            } else {
                badge.textContent = '—';
                badge.className = 'predikat-badge px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500';
            }
        });
    });
    </script>

</x-app-layout>

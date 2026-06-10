<x-app-layout>

    <x-page-header
        title="Penilaian Sikap"
        subtitle="Pilih mata pelajaran untuk menginput nilai sikap siswa"
    />

    @if(session('success'))
        <div class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Header info guru --}}
    <div class="bg-gradient-to-r from-violet-700 to-indigo-700 rounded-2xl p-6 mb-6 flex items-center justify-between shadow-lg">
        <div>
            <p class="text-violet-200 text-xs font-semibold uppercase tracking-widest mb-1">Penilaian Sikap Siswa</p>
            <h2 class="text-xl font-bold text-white">{{ auth()->user()->name }}</h2>
            <p class="text-violet-200 text-sm mt-0.5">{{ $jadwals->count() }} mata pelajaran diampu</p>
        </div>
        <div class="hidden md:flex items-center justify-center w-16 h-16 rounded-2xl bg-white/15">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
        </div>
    </div>

    {{-- Grid Kartu --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

        @forelse($jadwals as $j)

        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm hover:shadow-lg hover:border-violet-200 transition-all duration-300 overflow-hidden group">

            {{-- Accent bar --}}
            <div class="h-1 bg-gradient-to-r from-violet-500 to-indigo-500"></div>

            {{-- Card Header --}}
            <div class="px-6 pt-5 pb-4 border-b border-slate-100 flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Mata Pelajaran</p>
                    <h3 class="text-base font-bold text-slate-800 leading-snug truncate">
                        {{ $j->mapel->nama_mapel ?? '-' }}
                    </h3>
                    <span class="inline-flex items-center mt-1.5 text-xs font-semibold text-violet-700 bg-violet-100 px-2.5 py-0.5 rounded-full">
                        {{ $j->kelas->nama_kelas ?? '-' }}
                    </span>
                </div>
                <div class="w-11 h-11 rounded-xl bg-violet-100 flex items-center justify-center shrink-0 group-hover:bg-violet-200 transition">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
            </div>

            {{-- Card Body --}}
            <div class="px-6 py-4 space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-400">Hari</span>
                    <span class="font-medium text-slate-700">{{ $j->hari ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Jam</span>
                    <span class="font-medium text-slate-700 font-mono">
                        {{ substr($j->jam_masuk,0,5) }} – {{ substr($j->jam_selesai,0,5) }}
                    </span>
                </div>
            </div>

            {{-- Actions --}}
            <div class="px-6 pb-5 space-y-2.5">
                <a href="{{ route('sikap.create', $j->id) }}"
                    class="flex items-center justify-center gap-2 w-full bg-violet-700 hover:bg-violet-800 text-white text-sm font-semibold py-2.5 rounded-xl transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Input Nilai Sikap
                </a>
                <button
                    onclick="bukaRiwayatSikap({{ $j->id }}, '{{ addslashes($j->mapel->nama_mapel) }}', '{{ addslashes($j->kelas->nama_kelas) }}')"
                    class="flex items-center justify-center gap-2 w-full border border-slate-200 hover:border-violet-300 hover:bg-violet-50 text-slate-600 hover:text-violet-700 text-sm font-semibold py-2.5 rounded-xl transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Riwayat Nilai
                </button>
            </div>

        </div>

        @empty

        <div class="col-span-3 bg-white border border-dashed border-slate-300 rounded-2xl p-16 text-center">
            <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-700">Belum Ada Jadwal Mengajar</h3>
            <p class="text-sm text-slate-400 mt-1">Data jadwal belum tersedia untuk penilaian sikap.</p>
        </div>

        @endforelse

    </div>

    {{-- ── MODAL RIWAYAT SIKAP ─────────────────────────────────────────── --}}
    <div id="modalRiwayatSikap"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div class="bg-white w-full max-w-5xl rounded-3xl shadow-2xl overflow-hidden">

            {{-- Header Modal --}}
            <div class="bg-gradient-to-r from-violet-700 to-indigo-700 px-7 py-5 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-white" id="judul-modal-sikap">Riwayat Nilai Sikap</h2>
                    <p class="text-violet-200 text-sm mt-0.5" id="subjudul-modal-sikap">—</p>
                </div>
                <button onclick="tutupRiwayatSikap()"
                    class="w-9 h-9 rounded-xl bg-white/15 hover:bg-white/25 text-white flex items-center justify-center transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Body Modal --}}
            <div id="isi-modal-sikap" class="p-6 max-h-[70vh] overflow-y-auto bg-slate-50">
                <div class="flex items-center justify-center py-16 text-slate-400">
                    <svg class="w-6 h-6 animate-spin mr-2" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                    </svg>
                    Memuat data...
                </div>
            </div>

        </div>
    </div>

    <script>
    const CSRF = document.querySelector('meta[name="csrf-token"]').content;

    function bukaRiwayatSikap(jadwalId, mapel, kelas) {
        document.getElementById('judul-modal-sikap').textContent = 'Riwayat Nilai Sikap';
        document.getElementById('subjudul-modal-sikap').textContent = mapel + ' • Kelas ' + kelas;
        document.getElementById('isi-modal-sikap').innerHTML = `
            <div class="flex items-center justify-center py-16 text-slate-400">
                <svg class="w-6 h-6 animate-spin mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                </svg>Memuat data...
            </div>`;

        const modal = document.getElementById('modalRiwayatSikap');
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        fetch(`/sikap/riwayat/${jadwalId}`)
            .then(r => r.json())
            .then(data => {
                const keys = Object.keys(data);
                if (!keys.length) {
                    document.getElementById('isi-modal-sikap').innerHTML = `
                        <div class="text-center py-16 text-slate-400">
                            <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            <p class="text-sm font-medium">Belum ada data nilai sikap</p>
                        </div>`;
                    return;
                }

                let html = '';
                keys.forEach(tanggal => {
                    const items = data[tanggal];
                    html += `
                    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden mb-5">
                        <div class="bg-violet-50 border-b border-violet-100 px-5 py-3 flex items-center justify-between">
                            <div>
                                <h3 class="font-bold text-slate-800 text-sm">${formatTanggal(tanggal)}</h3>
                                <p class="text-xs text-slate-500">${items.length} siswa dinilai</p>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase">No</th>
                                    <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase">Nama Siswa</th>
                                    <th class="px-5 py-3 text-center text-xs font-bold text-slate-500 uppercase">Nilai</th>
                                    <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">`;

                    items.forEach((item, idx) => {
                        const badge = nilaiSikapBadge(item.nilai_sikap);
                        html += `
                            <tr class="hover:bg-slate-50">
                                <td class="px-5 py-3 text-slate-500">${idx + 1}</td>
                                <td class="px-5 py-3 font-medium text-slate-800">${item.siswa?.nama ?? '-'}</td>
                                <td class="px-5 py-3 text-center"><span class="px-3 py-1 rounded-full text-xs font-bold ${badge.cls}">${item.nilai_sikap}</span></td>
                                <td class="px-5 py-3 text-slate-600">${item.keterangan ?? '-'}</td>
                            </tr>`;
                    });

                    html += `</tbody></table></div></div>`;
                });

                document.getElementById('isi-modal-sikap').innerHTML = html;
            })
            .catch(() => {
                document.getElementById('isi-modal-sikap').innerHTML =
                    '<p class="text-center text-red-500 py-10">Gagal memuat data.</p>';
            });
    }

    function tutupRiwayatSikap() {
        const modal = document.getElementById('modalRiwayatSikap');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.getElementById('modalRiwayatSikap').addEventListener('click', function(e){
        if (e.target === this) tutupRiwayatSikap();
    });

    function nilaiSikapBadge(n) {
        if (n >= 81) return { cls: 'bg-emerald-100 text-emerald-700' };
        if (n >= 71) return { cls: 'bg-blue-100 text-blue-700' };
        if (n >= 61) return { cls: 'bg-amber-100 text-amber-700' };
        return { cls: 'bg-rose-100 text-rose-700' };
    }

    function formatTanggal(str) {
        const d = new Date(str);
        return d.toLocaleDateString('id-ID', { weekday:'long', day:'numeric', month:'long', year:'numeric' });
    }
    </script>

</x-app-layout>

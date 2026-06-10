<x-app-layout>

    <x-page-header
        title="Penilaian Kedisiplinan"
        subtitle="Pilih mata pelajaran untuk menginput nilai kedisiplinan siswa"
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
    <div class="bg-gradient-to-r from-amber-600 to-orange-600 rounded-2xl p-6 mb-6 flex items-center justify-between shadow-lg">
        <div>
            <p class="text-amber-100 text-xs font-semibold uppercase tracking-widest mb-1">Penilaian Kedisiplinan Siswa</p>
            <h2 class="text-xl font-bold text-white">{{ auth()->user()->name }}</h2>
            <p class="text-amber-100 text-sm mt-0.5">{{ $jadwals->count() }} mata pelajaran diampu</p>
        </div>
        <div class="hidden md:flex items-center justify-center w-16 h-16 rounded-2xl bg-white/15">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
        </div>
    </div>

    {{-- Grid Kartu --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

        @forelse($jadwals as $j)

        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm hover:shadow-lg hover:border-amber-200 transition-all duration-300 overflow-hidden group">

            {{-- Accent bar --}}
            <div class="h-1 bg-gradient-to-r from-amber-500 to-orange-500"></div>

            {{-- Card Header --}}
            <div class="px-6 pt-5 pb-4 border-b border-slate-100 flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Mata Pelajaran</p>
                    <h3 class="text-base font-bold text-slate-800 leading-snug truncate">
                        {{ $j->mapel->nama_mapel ?? '-' }}
                    </h3>
                    <span class="inline-flex items-center mt-1.5 text-xs font-semibold text-amber-700 bg-amber-100 px-2.5 py-0.5 rounded-full">
                        {{ $j->kelas->nama_kelas ?? '-' }}
                    </span>
                </div>
                <div class="w-11 h-11 rounded-xl bg-amber-100 flex items-center justify-center shrink-0 group-hover:bg-amber-200 transition">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
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
                <a href="{{ route('kedisiplinan.create', $j->id) }}"
                    class="flex items-center justify-center gap-2 w-full bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold py-2.5 rounded-xl transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Input Kedisiplinan
                </a>
                <button
                    onclick="bukaRiwayatDisiplin({{ $j->id }}, '{{ addslashes($j->mapel->nama_mapel) }}', '{{ addslashes($j->kelas->nama_kelas) }}')"
                    class="flex items-center justify-center gap-2 w-full border border-slate-200 hover:border-amber-300 hover:bg-amber-50 text-slate-600 hover:text-amber-700 text-sm font-semibold py-2.5 rounded-xl transition">
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
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-700">Belum Ada Jadwal Mengajar</h3>
            <p class="text-sm text-slate-400 mt-1">Data jadwal belum tersedia untuk penilaian kedisiplinan.</p>
        </div>

        @endforelse

    </div>

    {{-- ── MODAL RIWAYAT KEDISIPLINAN ─────────────────────────────────── --}}
    <div id="modalRiwayatDisiplin"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div class="bg-white w-full max-w-5xl rounded-3xl shadow-2xl overflow-hidden">

            <div class="bg-gradient-to-r from-amber-600 to-orange-600 px-7 py-5 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-white" id="judul-modal-disiplin">Riwayat Kedisiplinan</h2>
                    <p class="text-amber-100 text-sm mt-0.5" id="subjudul-modal-disiplin">—</p>
                </div>
                <button onclick="tutupRiwayatDisiplin()"
                    class="w-9 h-9 rounded-xl bg-white/15 hover:bg-white/25 text-white flex items-center justify-center transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div id="isi-modal-disiplin" class="p-6 max-h-[70vh] overflow-y-auto bg-slate-50">
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
    function bukaRiwayatDisiplin(jadwalId, mapel, kelas) {
        document.getElementById('judul-modal-disiplin').textContent = 'Riwayat Kedisiplinan';
        document.getElementById('subjudul-modal-disiplin').textContent = mapel + ' • Kelas ' + kelas;
        document.getElementById('isi-modal-disiplin').innerHTML = `
            <div class="flex items-center justify-center py-16 text-slate-400">
                <svg class="w-6 h-6 animate-spin mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                </svg>Memuat data...
            </div>`;

        const modal = document.getElementById('modalRiwayatDisiplin');
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        fetch(`/kedisiplinan/riwayat/${jadwalId}`)
            .then(r => r.json())
            .then(data => {
                const keys = Object.keys(data);
                if (!keys.length) {
                    document.getElementById('isi-modal-disiplin').innerHTML = `
                        <div class="text-center py-16 text-slate-400">
                            <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <p class="text-sm font-medium">Belum ada data kedisiplinan</p>
                        </div>`;
                    return;
                }

                let html = '';
                keys.forEach(tanggal => {
                    const items = data[tanggal];
                    html += `
                    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden mb-5">
                        <div class="bg-amber-50 border-b border-amber-100 px-5 py-3 flex items-center justify-between">
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
                        const badge = nilaiDisiplinBadge(item.nilai_disiplin);
                        html += `
                            <tr class="hover:bg-slate-50">
                                <td class="px-5 py-3 text-slate-500">${idx + 1}</td>
                                <td class="px-5 py-3 font-medium text-slate-800">${item.siswa?.nama ?? '-'}</td>
                                <td class="px-5 py-3 text-center"><span class="px-3 py-1 rounded-full text-xs font-bold ${badge.cls}">${item.nilai_disiplin}</span></td>
                                <td class="px-5 py-3 text-slate-600">${item.keterangan ?? '-'}</td>
                            </tr>`;
                    });

                    html += `</tbody></table></div></div>`;
                });

                document.getElementById('isi-modal-disiplin').innerHTML = html;
            })
            .catch(() => {
                document.getElementById('isi-modal-disiplin').innerHTML =
                    '<p class="text-center text-red-500 py-10">Gagal memuat data.</p>';
            });
    }

    function tutupRiwayatDisiplin() {
        const modal = document.getElementById('modalRiwayatDisiplin');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.getElementById('modalRiwayatDisiplin').addEventListener('click', function(e){
        if (e.target === this) tutupRiwayatDisiplin();
    });

    function nilaiDisiplinBadge(n) {
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

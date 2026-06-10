<x-app-layout>

    <x-page-header
        title="Absensi Siswa"
        subtitle="Jadwal mengajar hari {{ $hariIni }}"
    />

    {{-- Header strip --}}
    <div class="bg-gradient-to-r from-indigo-700 to-blue-600 rounded-2xl p-6 mb-6 flex items-center justify-between shadow-lg">
        <div>
            <p class="text-indigo-200 text-xs font-semibold uppercase tracking-widest mb-1">Absensi Siswa</p>
            <h2 class="text-xl font-bold text-white">{{ now()->translatedFormat('l, d F Y') }}</h2>
            <p class="text-indigo-100 text-sm mt-0.5">
                {{ $jadwals->count() }} jadwal tersedia hari ini
            </p>
        </div>
        <button
            onclick="openRiwayatModal()"
            class="hidden md:inline-flex items-center gap-2 bg-white/15 border border-white/25 hover:bg-white/25 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Riwayat Absensi
        </button>
    </div>

    {{-- Mobile riwayat button --}}
    <div class="flex justify-end mb-4 md:hidden">
        <button onclick="openRiwayatModal()"
            class="inline-flex items-center gap-2 bg-white border border-slate-200 hover:border-indigo-300 hover:bg-indigo-50 text-slate-700 hover:text-indigo-700 text-sm font-semibold px-4 py-2.5 rounded-xl transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Riwayat
        </button>
    </div>

    {{-- GRID JADWAL --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

        @forelse($jadwals as $j)
        @php
            $now      = \Carbon\Carbon::now();
            $mulai    = \Carbon\Carbon::today()->setTimeFromTimeString($j->jam_masuk);
            $selesai  = \Carbon\Carbon::today()->setTimeFromTimeString($j->jam_selesai);
            $isActive   = $now->between($mulai, $selesai);
            $isFinished = $now->greaterThan($selesai);
            $isUpcoming = $now->lessThan($mulai);
            $sudahAbsen = \App\Models\Absensi::where('jadwal_id', $j->id)
                ->whereDate('tanggal', now()->toDateString())
                ->exists();

            $accentClass = $sudahAbsen
                ? 'from-indigo-500 to-blue-500'
                : ($isActive ? 'from-emerald-500 to-teal-500'
                    : ($isFinished ? 'from-slate-400 to-slate-300'
                        : 'from-blue-400 to-indigo-400'));
        @endphp

        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden
            {{ $isActive && !$sudahAbsen ? 'ring-2 ring-emerald-400' : '' }}">

            {{-- Accent bar --}}
            <div class="h-1.5 bg-gradient-to-r {{ $accentClass }}"></div>

            <div class="p-5">

                {{-- Header --}}
                <div class="flex items-start justify-between mb-4 gap-2">
                    <div class="min-w-0">
                        <h3 class="text-base font-bold text-slate-800 truncate">{{ $j->mapel->nama_mapel }}</h3>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $j->kelas->nama_kelas }}</p>
                    </div>

                    @if($sudahAbsen)
                        <span class="shrink-0 text-xs font-bold bg-indigo-100 text-indigo-700 border border-indigo-200 px-2.5 py-1 rounded-full">
                            ✓ Diisi
                        </span>
                    @elseif($isActive)
                        <span class="shrink-0 text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200 px-2.5 py-1 rounded-full animate-pulse">
                            ● Berlangsung
                        </span>
                    @elseif($isFinished)
                        <span class="shrink-0 text-xs font-bold bg-slate-100 text-slate-500 border border-slate-200 px-2.5 py-1 rounded-full">
                            Selesai
                        </span>
                    @else
                        <span class="shrink-0 text-xs font-bold bg-blue-100 text-blue-600 border border-blue-200 px-2.5 py-1 rounded-full">
                            Akan Datang
                        </span>
                    @endif
                </div>

                {{-- Info --}}
                <div class="space-y-2 text-sm mb-5">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Jam
                        </span>
                        <span class="font-mono font-semibold text-slate-700 text-xs">
                            {{ substr($j->jam_masuk,0,5) }} – {{ substr($j->jam_selesai,0,5) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Guru
                        </span>
                        <span class="font-medium text-slate-700 text-xs truncate max-w-[140px]">{{ $j->guru->nama }}</span>
                    </div>
                </div>

                {{-- Button --}}
                @if($sudahAbsen)
                    <button disabled
                        class="w-full bg-indigo-50 text-indigo-400 border border-indigo-100 py-2.5 rounded-xl text-sm font-semibold cursor-not-allowed">
                        Absensi Sudah Diisi
                    </button>
                @elseif($isFinished)
                    <button disabled
                        class="w-full bg-slate-100 text-slate-400 border border-slate-200 py-2.5 rounded-xl text-sm font-semibold cursor-not-allowed">
                        Waktu Sudah Lewat
                    </button>
                @elseif($isUpcoming)
                    <button disabled
                        class="w-full bg-blue-50 text-blue-400 border border-blue-100 py-2.5 rounded-xl text-sm font-semibold cursor-not-allowed">
                        Belum Waktunya
                    </button>
                @else
                    <a href="{{ route('absensi.create', $j->id) }}"
                        class="flex items-center justify-center gap-2 w-full bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-blue-600 hover:to-indigo-600 text-white py-2.5 rounded-xl text-sm font-bold transition-all duration-300 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Isi Absensi Sekarang
                    </a>
                @endif

            </div>
        </div>

        @empty
        <div class="col-span-3 bg-white border border-dashed border-slate-300 rounded-2xl p-16 text-center">
            <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-700">Tidak Ada Jadwal Hari Ini</h3>
            <p class="text-sm text-slate-400 mt-1">Nikmati hari Anda 😊</p>
        </div>
        @endforelse

    </div>

    {{-- ── MODAL PILIH KELAS RIWAYAT ───────────────────────────────────── --}}
    <div id="riwayatModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden">

            <div class="bg-gradient-to-r from-indigo-700 to-blue-600 px-7 py-5 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-white">Riwayat Absensi</h2>
                    <p class="text-indigo-200 text-sm mt-0.5">Pilih kelas untuk melihat riwayat</p>
                </div>
                <button onclick="closeRiwayatModal()"
                    class="w-9 h-9 rounded-xl bg-white/15 hover:bg-white/25 text-white flex items-center justify-center transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="p-6 space-y-3">
                @foreach($kelasDiampu as $kelas)
                <button onclick="openDetailModal({{ $kelas->id }})"
                    class="w-full flex items-center justify-between p-4 rounded-2xl border border-slate-200 hover:border-indigo-400 hover:bg-indigo-50 transition-all duration-300">
                    <div class="text-left">
                        <h3 class="font-bold text-slate-800">{{ $kelas->nama_kelas }}</h3>
                        <p class="text-sm text-slate-500">Lihat riwayat absensi siswa</p>
                    </div>
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                @endforeach
            </div>

        </div>
    </div>

    {{-- DETAIL MODAL per kelas --}}
    @foreach($kelasDiampu as $kelas)
    <div id="detailModal{{ $kelas->id }}"
        class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div class="bg-white w-full max-w-5xl rounded-3xl shadow-2xl overflow-hidden">

            <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-7 py-5 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-white">Riwayat Absensi</h2>
                    <p class="text-indigo-100 text-sm mt-0.5">Kelas {{ $kelas->nama_kelas }}</p>
                </div>
                <button onclick="closeDetailModal({{ $kelas->id }})"
                    class="w-9 h-9 rounded-xl bg-white/20 hover:bg-white/30 text-white flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="px-6 py-4 border-b bg-slate-50">
                <div class="relative">
                    <input type="text" id="search{{ $kelas->id }}"
                        onkeyup="searchSiswa({{ $kelas->id }})"
                        placeholder="Cari nama siswa..."
                        class="w-full rounded-xl border-slate-200 pl-10 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">
                    <svg class="w-4 h-4 absolute left-3 top-3 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110 3a7.5 7.5 0 016.65 13.65z"/>
                    </svg>
                </div>
            </div>

            <div class="p-6 max-h-[65vh] overflow-y-auto bg-slate-50 space-y-5">
                @php
                    $guruId = auth()->user()->guru_id;
                    $riwayatKelas = \App\Models\AbsensiDetail::with(['siswa','absensi'])
                        ->whereHas('siswa', fn($q) => $q->where('kelas_id', $kelas->id))
                        ->whereHas('absensi.jadwal', fn($q) => $q->where('guru_id', $guruId))
                        ->orderByDesc('created_at')
                        ->get()
                        ->groupBy(fn($i) => $i->absensi->pertemuan);
                @endphp

                @forelse($riwayatKelas as $pertemuan => $items)
                <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-50 to-blue-50 px-6 py-3.5 border-b flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-slate-800 text-sm">Pertemuan {{ $pertemuan }}</h3>
                            <p class="text-xs text-slate-500">
                                {{ \Carbon\Carbon::parse($items->first()->absensi->tanggal)->translatedFormat('l, d F Y') }}
                            </p>
                        </div>
                        <span class="text-xs font-semibold bg-indigo-100 text-indigo-700 px-2.5 py-1 rounded-full">
                            {{ $items->count() }} siswa
                        </span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase">No</th>
                                    <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase">Nama Siswa</th>
                                    <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($items as $i => $detail)
                                <tr class="hover:bg-slate-50 siswa-row{{ $kelas->id }}">
                                    <td class="px-5 py-3 text-slate-500 text-xs">{{ $i + 1 }}</td>
                                    <td class="px-5 py-3 font-medium text-slate-800 siswa-name">{{ $detail->siswa->nama }}</td>
                                    <td class="px-5 py-3">
                                        <select onchange="updateStatus(this)" data-id="{{ $detail->id }}"
                                            class="rounded-lg border-slate-200 text-xs font-medium focus:ring-2 focus:ring-indigo-500 py-1.5">
                                            <option value="hadir"  {{ $detail->status=='hadir'  ? 'selected':'' }}>Hadir</option>
                                            <option value="izin"   {{ $detail->status=='izin'   ? 'selected':'' }}>Izin</option>
                                            <option value="sakit"  {{ $detail->status=='sakit'  ? 'selected':'' }}>Sakit</option>
                                            <option value="alpha"  {{ $detail->status=='alpha'  ? 'selected':'' }}>Alpha</option>
                                        </select>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @empty
                <div class="bg-white border border-slate-200 rounded-2xl p-12 text-center text-slate-400">
                    <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                    </svg>
                    <p class="text-sm font-medium">Belum ada riwayat absensi</p>
                </div>
                @endforelse
            </div>

        </div>
    </div>
    @endforeach

    <script>
    function openRiwayatModal() {
        const m = document.getElementById('riwayatModal');
        m.classList.remove('hidden'); m.classList.add('flex');
    }
    function closeRiwayatModal() {
        const m = document.getElementById('riwayatModal');
        m.classList.add('hidden'); m.classList.remove('flex');
    }
    function openDetailModal(id) {
        const m = document.getElementById('detailModal' + id);
        m.classList.remove('hidden'); m.classList.add('flex');
    }
    function closeDetailModal(id) {
        const m = document.getElementById('detailModal' + id);
        m.classList.add('hidden'); m.classList.remove('flex');
    }
    function searchSiswa(kelasId) {
        const filter = document.getElementById('search' + kelasId).value.toLowerCase();
        document.querySelectorAll('.siswa-row' + kelasId).forEach(row => {
            row.style.display = row.querySelector('.siswa-name').innerText.toLowerCase().includes(filter) ? '' : 'none';
        });
    }
    function updateStatus(select) {
        fetch("{{ route('absensi.update-detail') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ detail_id: select.dataset.id, status: select.value })
        }).then(r => r.json()).then(data => {
            if (data.success) {
                select.classList.add('ring-2','ring-emerald-500');
                setTimeout(() => select.classList.remove('ring-2','ring-emerald-500'), 1200);
            }
        });
    }
    // Close modal on backdrop click
    ['riwayatModal'].forEach(id => {
        document.getElementById(id)?.addEventListener('click', function(e){ if(e.target===this) closeRiwayatModal(); });
    });
    </script>

</x-app-layout>

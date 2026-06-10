<x-app-layout>

    <x-page-header
        title="Laporan Absensi Guru"
        subtitle="Dashboard / Laporan / Absensi Guru"
    />

    <div class="space-y-5">

        {{-- ── REKAP CARDS ─────────────────────────────────────────────── --}}
        <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">

            <div class="bg-white rounded-2xl shadow-sm border overflow-hidden flex h-20">
                <div class="w-14 bg-emerald-500 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1 px-4 flex flex-col justify-center">
                    <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-wide">Hadir</p>
                    <h2 class="text-2xl font-bold text-gray-800">{{ $hadir }}</h2>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border overflow-hidden flex h-20">
                <div class="w-14 bg-amber-400 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1 px-4 flex flex-col justify-center">
                    <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-wide">Terlambat</p>
                    <h2 class="text-2xl font-bold text-gray-800">{{ $terlambat }}</h2>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border overflow-hidden flex h-20">
                <div class="w-14 bg-blue-500 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div class="flex-1 px-4 flex flex-col justify-center">
                    <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-wide">Izin / Sakit</p>
                    <h2 class="text-2xl font-bold text-gray-800">{{ $izin }}</h2>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border overflow-hidden flex h-20">
                <div class="w-14 bg-rose-500 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1 px-4 flex flex-col justify-center">
                    <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-wide">Alpa</p>
                    <h2 class="text-2xl font-bold text-gray-800">{{ $alpa }}</h2>
                </div>
            </div>

        </div>

        {{-- ── CARD UTAMA ───────────────────────────────────────────────── --}}
        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">

            {{-- Header Card --}}
            <div class="bg-gradient-to-r from-slate-800 to-slate-700 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="bg-white/10 p-2 rounded-xl">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-sm">Data Absensi Guru</h3>
                        <p class="text-slate-300 text-xs">{{ now()->translatedFormat('l, d F Y') }}</p>
                    </div>
                </div>
                {{-- Export Buttons --}}
                <div class="flex gap-2">
                    <a href="{{ route('laporan-absensi-guru.pdf', request()->all()) }}"
                        target="_blank"
                        class="inline-flex items-center gap-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold px-3 py-2 rounded-lg transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        PDF
                    </a>
                    <a href="{{ route('laporan-absensi-guru.excel', request()->all()) }}"
                        class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-3 py-2 rounded-lg transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        Excel
                    </a>
                </div>
            </div>

            {{-- Filter --}}
            <div class="px-6 py-4 bg-slate-50 border-b">
                <form method="GET" class="grid grid-cols-2 md:grid-cols-5 gap-3">

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Tanggal Awal</label>
                        <input type="date" name="tanggal_awal"
                            value="{{ request('tanggal_awal') }}"
                            class="w-full rounded-xl border-gray-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Tanggal Akhir</label>
                        <input type="date" name="tanggal_akhir"
                            value="{{ request('tanggal_akhir') }}"
                            class="w-full rounded-xl border-gray-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Guru</label>
                        <select name="guru"
                            class="w-full rounded-xl border-gray-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Semua Guru</option>
                            @foreach($guru as $g)
                                <option value="{{ $g->id }}" {{ request('guru') == $g->id ? 'selected' : '' }}>
                                    {{ $g->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Status</label>
                        <select name="status"
                            class="w-full rounded-xl border-gray-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Semua Status</option>
                            <option value="Hadir"     {{ request('status')=='Hadir'     ? 'selected' : '' }}>Hadir</option>
                            <option value="Terlambat" {{ request('status')=='Terlambat' ? 'selected' : '' }}>Terlambat</option>
                            <option value="Izin"      {{ request('status')=='Izin'      ? 'selected' : '' }}>Izin</option>
                            <option value="Sakit"     {{ request('status')=='Sakit'     ? 'selected' : '' }}>Sakit</option>
                            <option value="Alpa"      {{ request('status')=='Alpa'      ? 'selected' : '' }}>Alpa</option>
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition">
                            Filter
                        </button>
                        <a href="{{ route('laporan-absensi-guru.index') }}"
                            class="flex-1 text-center bg-white border border-gray-300 hover:bg-gray-50 text-gray-600 text-sm font-semibold px-4 py-2.5 rounded-xl transition">
                            Reset
                        </a>
                    </div>

                </form>
            </div>

            {{-- Tabel --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-600 uppercase tracking-wide">No</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-600 uppercase tracking-wide">Tanggal</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-600 uppercase tracking-wide">Guru</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-600 uppercase tracking-wide">Masuk</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-600 uppercase tracking-wide">Pulang</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-600 uppercase tracking-wide">Lokasi</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-600 uppercase tracking-wide">Status</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-600 uppercase tracking-wide">Foto</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">

                        @forelse($absensi as $item)
                        <tr class="hover:bg-slate-50 transition">

                            <td class="px-5 py-3.5 text-slate-500 text-xs">
                                {{ ($absensi->currentPage() - 1) * $absensi->perPage() + $loop->iteration }}
                            </td>

                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <div class="font-semibold text-slate-800 text-xs">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                                </div>
                                <div class="text-xs text-slate-400">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l') }}
                                </div>
                            </td>

                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs shrink-0">
                                        {{ strtoupper(substr($item->guru->nama, 0, 1)) }}
                                    </div>
                                    <span class="font-medium text-slate-800 text-xs">{{ $item->guru->nama }}</span>
                                </div>
                            </td>

                            <td class="px-5 py-3.5 font-mono text-blue-700 text-xs font-semibold">
                                {{ $item->jam_masuk ? substr($item->jam_masuk, 0, 5) : '—' }}
                            </td>

                            <td class="px-5 py-3.5 font-mono text-orange-600 text-xs font-semibold">
                                {{ $item->jam_pulang ? substr($item->jam_pulang, 0, 5) : '—' }}
                            </td>

                            {{-- LOKASI — tampilkan alamat lengkap, bukan koordinat --}}
                            <td class="px-5 py-3.5 max-w-xs">
                                @if($item->alamat)
                                    <div class="text-xs text-slate-600 leading-relaxed line-clamp-2" title="{{ $item->alamat }}">
                                        {{ $item->alamat }}
                                    </div>
                                @else
                                    <span class="text-slate-400 text-xs">—</span>
                                @endif
                            </td>

                            <td class="px-5 py-3.5">
                                @php
                                    $badge = match($item->status) {
                                        'Hadir'     => 'bg-emerald-100 text-emerald-700 border border-emerald-200',
                                        'Terlambat' => 'bg-amber-100  text-amber-700  border border-amber-200',
                                        'Izin'      => 'bg-blue-100   text-blue-700   border border-blue-200',
                                        'Sakit'     => 'bg-indigo-100 text-indigo-700 border border-indigo-200',
                                        default     => 'bg-rose-100   text-rose-700   border border-rose-200',
                                    };
                                @endphp
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $badge }}">
                                    {{ $item->status }}
                                </span>
                            </td>

                            <td class="px-5 py-3.5">
                                @if($item->foto_absensi)
                                    <img src="{{ asset('storage/'.$item->foto_absensi) }}"
                                        class="w-12 h-12 rounded-xl object-cover border border-slate-200 cursor-pointer hover:scale-110 transition shadow-sm"
                                        onclick="lihatFoto('{{ asset('storage/'.$item->foto_absensi) }}')"
                                        title="Klik untuk perbesar">
                                @else
                                    <span class="text-slate-400 text-xs">—</span>
                                @endif
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center gap-3 text-slate-400">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-sm font-medium">Data tidak ditemukan</p>
                                    <p class="text-xs">Coba ubah filter untuk melihat data lainnya</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($absensi->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
                {{ $absensi->appends(request()->all())->links() }}
            </div>
            @endif

        </div>

    </div>

    {{-- Modal Lihat Foto --}}
    <div id="modalFoto"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 backdrop-blur-sm p-4">
        <div class="relative">
            <button onclick="tutupFoto()"
                class="absolute -top-3 -right-3 bg-white rounded-full w-8 h-8 flex items-center justify-center shadow-lg text-gray-700 hover:bg-red-50 hover:text-red-600 transition text-sm font-bold">
                ✕
            </button>
            <img id="modalFotoImg" src="" class="max-w-sm max-h-[80vh] rounded-2xl shadow-2xl object-contain">
        </div>
    </div>

    <script>
    function lihatFoto(src) {
        document.getElementById('modalFotoImg').src = src;
        const modal = document.getElementById('modalFoto');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function tutupFoto() {
        const modal = document.getElementById('modalFoto');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    document.getElementById('modalFoto').addEventListener('click', function(e) {
        if (e.target === this) tutupFoto();
    });
    </script>

</x-app-layout>

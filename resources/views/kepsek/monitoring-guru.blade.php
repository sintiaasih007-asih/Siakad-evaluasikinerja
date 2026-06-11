<x-app-layout>
    <x-page-header title="Monitoring Guru" subtitle="Pantau kehadiran dan aktivitas mengajar guru"/>

    {{-- Banner --}}
    <div class="rounded-2xl p-5 mb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 shadow-sm"
         style="background:linear-gradient(135deg,#134e4a,#0f766e)">
        <div>
            <p class="text-teal-200 text-[10px] font-bold uppercase tracking-widest mb-0.5">Kepala Sekolah · Guru</p>
            <h2 class="text-lg font-bold text-white">Monitoring Guru</h2>
            <p class="text-teal-200 text-xs mt-0.5">Bulan: <strong class="text-white">{{ $namaBulan }}</strong></p>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card p-4 mb-5">
        <form method="GET" class="flex flex-wrap items-end gap-3">
            <div>
                <label class="form-label">Bulan</label>
                <select name="bulan" onchange="this.form.submit()" class="form-input w-40">
                    @foreach(['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $k=>$v)
                    <option value="{{ $k }}" {{ $bulan==$k?'selected':'' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    {{-- Stat cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-5">
        @foreach([
            ['Total Guru',       $ringkasan['total_guru'],       'users',        'text-teal-700',    'bg-teal-50 border-teal-100'],
            ['Guru Aktif',       $ringkasan['guru_aktif'],       'check-circle', 'text-emerald-700', 'bg-emerald-50 border-emerald-100'],
            ['Rata-rata Hadir',  $ringkasan['rata_hadir'].'%',   'trending-up',  $ringkasan['rata_hadir']>=80?'text-emerald-700':'text-amber-700', 'bg-white border-slate-200'],
            ['Perlu Perhatian',  $ringkasan['perlu_perhatian'],  'alert-circle', 'text-rose-700',    'bg-rose-50 border-rose-100'],
        ] as [$label,$val,$icon,$txt,$bg])
        <div class="rounded-2xl border p-4 shadow-sm {{ $bg }}">
            <div class="flex items-start justify-between gap-2">
                <div>
                    <p class="stat-card-label">{{ $label }}</p>
                    <p class="text-2xl font-bold {{ $txt }} mt-1">{{ $val }}</p>
                </div>
                <i data-lucide="{{ $icon }}" class="w-5 h-5 {{ $txt }} opacity-60 shrink-0 mt-0.5"></i>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Tabel --}}
    <div class="card overflow-hidden">
        <div class="px-5 py-3.5 border-b bg-slate-50 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="user-check" class="w-4 h-4 text-slate-500"></i>
                <span class="text-sm font-semibold text-slate-700">Data Aktivitas Guru — {{ $namaBulan }}</span>
            </div>
            <span class="badge badge-neutral">{{ $data->count() }} guru</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-white" style="background:linear-gradient(135deg,#134e4a,#0f766e)">
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide w-10">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide">Nama Guru</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide">Hadir</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide hide-mobile">Terlambat</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide">% Hadir</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide hide-mobile">Nilai Input</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide hide-mobile">Absensi Buat</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($data as $i => $g)
                    @php
                        $sc = match($g['status_color']) {
                            'emerald' => 'bg-emerald-100 text-emerald-700',
                            'blue'    => 'bg-blue-100 text-blue-700',
                            'amber'   => 'bg-amber-100 text-amber-700',
                            'rose'    => 'bg-rose-100 text-rose-700',
                            default   => 'bg-slate-100 text-slate-600',
                        };
                        $hadirColor = $g['pct_hadir'] >= 80 ? 'text-emerald-700' : ($g['pct_hadir'] >= 60 ? 'text-amber-600' : 'text-rose-600');
                    @endphp
                    <tr class="hover:bg-slate-50/70 transition-colors {{ $g['status_color']==='rose' ? 'bg-rose-50/30' : '' }}">
                        <td class="px-4 py-3 text-slate-400 text-xs">{{ $i+1 }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-teal-800 text-white text-xs font-bold flex items-center justify-center shrink-0">
                                    {{ strtoupper(substr($g['nama'],0,1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800">{{ $g['nama'] }}</p>
                                    <p class="text-[10px] text-slate-400 font-mono">{{ $g['nip'] ?: '—' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center font-mono text-slate-700">{{ $g['hadir_guru'] }}/{{ $g['total_abs'] }}</td>
                        <td class="px-4 py-3 text-center font-mono hide-mobile {{ $g['terlambat'] > 3 ? 'text-amber-600 font-bold' : 'text-slate-500' }}">{{ $g['terlambat'] }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="font-bold font-mono {{ $hadirColor }}">{{ $g['pct_hadir'] }}%</span>
                        </td>
                        <td class="px-4 py-3 text-center font-mono text-slate-600 hide-mobile">{{ $g['nilai_input'] }}</td>
                        <td class="px-4 py-3 text-center font-mono text-slate-600 hide-mobile">{{ $g['absensi_buat'] }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="badge {{ $sc }}">{{ $g['status'] }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="px-4 py-10 text-center text-slate-400 text-sm">Belum ada data guru</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 bg-slate-50 border-t text-xs text-slate-400 flex flex-wrap gap-4">
            <span class="text-emerald-600 font-semibold">Aktif</span> = sudah input nilai/absensi bulan ini ·
            <span class="text-amber-600 font-semibold">Perhatian</span> = terlambat lebih dari 3x ·
            <span class="text-rose-600 font-semibold">Kurang Aktif</span> = belum ada aktivitas input
        </div>
    </div>

</x-app-layout>

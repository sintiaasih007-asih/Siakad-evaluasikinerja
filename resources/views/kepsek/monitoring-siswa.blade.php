<x-app-layout>
    <x-page-header title="Monitoring Siswa" subtitle="Pantau kehadiran dan perkembangan akademik siswa"/>

    {{-- Banner --}}
    <div class="rounded-2xl p-5 mb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 shadow-sm"
         style="background:linear-gradient(135deg,#3b0764,#6d28d9)">
        <div>
            <p class="text-violet-200 text-[10px] font-bold uppercase tracking-widest mb-0.5">Kepala Sekolah · Siswa</p>
            <h2 class="text-lg font-bold text-white">Monitoring Siswa</h2>
            <p class="text-violet-200 text-xs mt-0.5">Bulan: <strong class="text-white">{{ $namaBulan }}</strong></p>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card p-4 mb-5">
        <form method="GET" class="flex flex-wrap items-end gap-3">
            <div>
                <label class="form-label">Kelas</label>
                <select name="kelas_id" class="form-input w-36">
                    <option value="">Semua Kelas</option>
                    @foreach($kelasList as $k)
                    <option value="{{ $k->id }}" {{ $kelasId==$k->id?'selected':'' }}>{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-input w-40">
                    @foreach(['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $k=>$v)
                    <option value="{{ $k }}" {{ $bulan==$k?'selected':'' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-primary"><i data-lucide="search" class="w-4 h-4"></i> Tampilkan</button>
            <a href="{{ route('kepsek.monitoring-siswa') }}" class="btn-secondary">Reset</a>
        </form>
    </div>

    {{-- Stat cards --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-5">
        @foreach([
            ['Total Siswa',      $ringkasan['total_siswa'],      'users',        'text-slate-700',   'bg-white border-slate-200'],
            ['Status Baik',      $ringkasan['status_baik'],      'check-circle', 'text-emerald-700', 'bg-emerald-50 border-emerald-100'],
            ['Pantauan',         $ringkasan['pantauan'],         'eye',          'text-amber-700',   'bg-amber-50 border-amber-100'],
            ['Perlu Tindakan',   $ringkasan['perlu_tindakan'],   'alert-circle', 'text-rose-700',    'bg-rose-50 border-rose-100'],
            ['Rata Nilai',       $ringkasan['rata_nilai'],       'book-check',   $ringkasan['rata_nilai']>=75?'text-blue-700':'text-amber-700', 'bg-white border-slate-200'],
            ['Rata Hadir',       $ringkasan['rata_hadir'].'%',   'user-check',   $ringkasan['rata_hadir']>=80?'text-teal-700':'text-rose-700',  'bg-white border-slate-200'],
        ] as [$label,$val,$icon,$txt,$bg])
        <div class="rounded-2xl border p-4 shadow-sm {{ $bg }}">
            <div class="flex items-start justify-between gap-2">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">{{ $label }}</p>
                    <p class="text-xl font-bold {{ $txt }} mt-1">{{ $val }}</p>
                </div>
                <i data-lucide="{{ $icon }}" class="w-4 h-4 {{ $txt }} opacity-60 shrink-0 mt-0.5"></i>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Alert siswa bermasalah --}}
    @php $butuhTindakan = $data->where('status','Perlu Tindakan'); @endphp
    @if($butuhTindakan->count())
    <div class="flex items-start gap-3 bg-rose-50 border border-rose-200 rounded-xl px-4 py-3 mb-5 text-sm text-rose-700">
        <i data-lucide="alert-triangle" class="w-4 h-4 shrink-0 mt-0.5"></i>
        <div>
            <p class="font-bold">{{ $butuhTindakan->count() }} siswa memerlukan tindakan segera</p>
            <p class="text-xs mt-0.5 text-rose-500">{{ $butuhTindakan->pluck('nama')->take(5)->join(', ') }}{{ $butuhTindakan->count()>5 ? '...' : '' }}</p>
        </div>
    </div>
    @endif

    {{-- Search + Tabel --}}
    <div class="card overflow-hidden" x-data="{ search:'' }">
        <div class="px-5 py-3.5 border-b bg-slate-50 flex flex-col sm:flex-row sm:items-center gap-3 justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="users" class="w-4 h-4 text-slate-500"></i>
                <span class="text-sm font-semibold text-slate-700">Data Siswa — {{ $namaBulan }}</span>
            </div>
            <div class="relative">
                <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                <input type="text" x-model="search" placeholder="Cari nama siswa..." class="form-input pl-9 w-48 text-xs">
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-white" style="background:linear-gradient(135deg,#3b0764,#6d28d9)">
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide w-10">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide">Nama Siswa</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide">Kelas</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide">Nilai</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide">Hadir%</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide hide-mobile">Alpha</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($data as $i => $s)
                    @php
                        $sc = match($s['status_color']) {
                            'emerald' => 'bg-emerald-100 text-emerald-700',
                            'blue'    => 'bg-blue-100 text-blue-700',
                            'amber'   => 'bg-amber-100 text-amber-700',
                            'rose'    => 'bg-rose-100 text-rose-700',
                            default   => 'bg-slate-100 text-slate-600',
                        };
                        $nilaiColor = $s['avg_nilai']>=80?'text-emerald-700':($s['avg_nilai']>=65?'text-blue-700':($s['avg_nilai']>0?'text-rose-600':'text-slate-300'));
                        $hadirColor = $s['pct_hadir']>=85?'text-emerald-700':($s['pct_hadir']>=70?'text-amber-600':($s['pct_hadir']>0?'text-rose-600':'text-slate-300'));
                    @endphp
                    <tr class="hover:bg-slate-50/70 transition-colors siswa-row {{ $s['status_color']==='rose'?'bg-rose-50/20':'' }}"
                        x-show="!search || '{{ strtolower($s['nama']) }}'.includes(search.toLowerCase())">
                        <td class="px-4 py-3 text-slate-400 text-xs">{{ $i+1 }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-violet-100 text-violet-700 text-xs font-bold flex items-center justify-center shrink-0">
                                    {{ strtoupper(substr($s['nama'],0,1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800 text-xs">{{ $s['nama'] }}</p>
                                    <p class="text-[10px] text-slate-400 font-mono">{{ $s['nis'] }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center"><span class="badge badge-neutral text-xs">{{ $s['nama_kelas'] }}</span></td>
                        <td class="px-4 py-3 text-center font-mono font-bold {{ $nilaiColor }}">{{ $s['avg_nilai'] ?: '—' }}</td>
                        <td class="px-4 py-3 text-center font-mono font-bold {{ $hadirColor }}">{{ $s['pct_hadir'] ? $s['pct_hadir'].'%' : '—' }}</td>
                        <td class="px-4 py-3 text-center hide-mobile font-mono {{ $s['alpha']>0?'text-rose-600 font-bold':'text-slate-400' }}">{{ $s['alpha'] ?: '0' }}x</td>
                        <td class="px-4 py-3 text-center"><span class="badge text-xs {{ $sc }}">{{ $s['status'] }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-4 py-10 text-center text-slate-400 text-sm">Belum ada data siswa</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-app-layout>

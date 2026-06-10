<div x-data="modalJadwal()">
<x-app-layout>

    <x-page-header title="Jadwal Pelajaran" subtitle="Kelola jadwal mengajar per kelas"/>

    @if(session('success'))
    <div class="alert-success mb-5"><i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i>{{ session('success') }}</div>
    @endif

    {{-- Toolbar --}}
    <div class="card p-4 mb-5 flex items-center justify-between gap-3">
        <div>
            <p class="text-sm font-semibold text-slate-700">Jadwal per Kelas</p>
            <p class="text-xs text-slate-400 mt-0.5">{{ $jadwals->count() }} jadwal terdaftar</p>
        </div>
        <a href="{{ route('jadwal.create') }}" class="btn-primary">
            <i data-lucide="plus" class="w-4 h-4"></i> Tambah Jadwal
        </a>
    </div>

    {{-- Per kelas --}}
    @foreach($kelas as $k)
    @php $jadwalKelas = $jadwals->where('kelas_id', $k->id); @endphp
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-1 h-6 rounded-full bg-blue-700"></div>
            <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wide">Kelas {{ $k->nama_kelas }}</h3>
            <span class="badge badge-info ml-auto">{{ $jadwalKelas->count() }} jadwal</span>
        </div>
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-blue-900 text-white">
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide w-10">#</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide">Hari</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide">Jam</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide">Mata Pelajaran</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide hide-mobile">Guru</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($jadwalKelas as $j)
                        <tr class="hover:bg-slate-50/70 transition-colors duration-150">
                            <td class="px-4 py-3 text-slate-400 text-xs">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $hariColor = match($j->hari) {
                                        'Senin'  => 'bg-blue-100 text-blue-700',
                                        'Selasa' => 'bg-indigo-100 text-indigo-700',
                                        'Rabu'   => 'bg-teal-100 text-teal-700',
                                        'Kamis'  => 'bg-amber-100 text-amber-700',
                                        'Jumat'  => 'bg-emerald-100 text-emerald-700',
                                        'Sabtu'  => 'bg-slate-100 text-slate-700',
                                        default  => 'bg-slate-100 text-slate-600',
                                    };
                                @endphp
                                <span class="badge {{ $hariColor }}">{{ $j->hari }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="font-mono text-xs bg-slate-100 text-slate-700 px-2.5 py-1 rounded-lg">
                                    {{ substr($j->jam_masuk,0,5) }} – {{ substr($j->jam_selesai,0,5) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-semibold text-slate-800">{{ $j->mapel->nama_mapel ?? '—' }}</td>
                            <td class="px-4 py-3 text-slate-500 text-xs hide-mobile">{{ $j->guru->nama ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button @click="openModal = true; setData({{ json_encode($j) }})"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold
                                               bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 transition">
                                        <i data-lucide="pencil" class="w-3 h-3"></i> Edit
                                    </button>
                                    <form action="{{ route('jadwal.destroy', $j->id) }}" method="POST"
                                          onsubmit="return confirm('Hapus jadwal ini?')">
                                        @csrf @method('DELETE')
                                        <button class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold
                                                       bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 transition">
                                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-slate-400 text-sm">
                                Belum ada jadwal untuk kelas ini
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endforeach

    {{-- Modal Edit --}}
    <div x-show="openModal" x-transition.opacity @click.self="openModal = false"
         class="fixed inset-0 z-50 flex items-center justify-center bg-blue-950/60 backdrop-blur-sm p-4"
         style="display:none">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto"
             x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="flex items-center justify-between px-6 py-4 sticky top-0"
                 style="background:linear-gradient(135deg,#1e3a5f,#1e40af)">
                <h2 class="text-base font-bold text-white">Edit Jadwal Pelajaran</h2>
                <button @click="openModal = false"
                    class="w-8 h-8 rounded-lg bg-white/15 hover:bg-white/25 text-white flex items-center justify-center">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <form :action="'/jadwal/' + form.id" method="POST" class="p-6">
                @csrf @method('PUT')
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Hari</label>
                        <select name="hari" x-model="form.hari" class="form-input w-full">
                            @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $h)
                            <option value="{{ $h }}">{{ $h }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Kelas</label>
                        <select name="kelas_id" x-model="form.kelas_id" class="form-input w-full">
                            @foreach($kelas as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Jam Masuk</label>
                        <input type="time" name="jam_masuk" x-model="form.jam_masuk" class="form-input w-full">
                    </div>
                    <div>
                        <label class="form-label">Jam Selesai</label>
                        <input type="time" name="jam_selesai" x-model="form.jam_selesai" class="form-input w-full">
                    </div>
                    <div>
                        <label class="form-label">Mata Pelajaran</label>
                        <select name="mapel_id" x-model="form.mapel_id" class="form-input w-full">
                            @foreach($mapels as $m)
                            <option value="{{ $m->id }}">{{ $m->nama_mapel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Guru</label>
                        <select name="guru_id" x-model="form.guru_id" class="form-input w-full">
                            @foreach($gurus as $g)
                            <option value="{{ $g->id }}">{{ $g->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-5 pt-4 border-t border-slate-100">
                    <button type="button" @click="openModal = false" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-primary"><i data-lucide="save" class="w-4 h-4"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>
<script>
function modalJadwal() {
    return {
        openModal: false,
        form: { id:'', hari:'', jam_masuk:'', jam_selesai:'', kelas_id:'', mapel_id:'', guru_id:'' },
        setData(d) {
            this.form.id=d.id; this.form.hari=d.hari; this.form.jam_masuk=d.jam_masuk;
            this.form.jam_selesai=d.jam_selesai; this.form.kelas_id=d.kelas_id;
            this.form.mapel_id=d.mapel_id; this.form.guru_id=d.guru_id;
        }
    }
}
</script>
</div>

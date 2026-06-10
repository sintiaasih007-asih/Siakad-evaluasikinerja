<div x-data="modalKelas()">
<x-app-layout>

    <x-page-header title="Data Kelas" subtitle="Kelola kelas dan wali kelas"/>

    @if(session('success'))
    <div class="alert-success mb-5">
        <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i>{{ session('success') }}
    </div>
    @endif

    {{-- Toolbar --}}
    <div class="card p-4 mb-5 flex items-center justify-between gap-3">
        <div>
            <p class="text-sm font-semibold text-slate-700">Daftar Kelas</p>
            <p class="text-xs text-slate-400 mt-0.5">{{ $kelas->count() }} kelas terdaftar</p>
        </div>
        <a href="{{ route('kelas.create') }}" class="btn-primary">
            <i data-lucide="plus" class="w-4 h-4"></i> Tambah Kelas
        </a>
    </div>

    {{-- Tabel --}}
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-blue-900 text-white">
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide w-10">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide">Nama Kelas</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide">Wali Kelas</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide hide-mobile">Tahun Ajaran</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide hide-mobile">Jumlah Siswa</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($kelas as $k)
                    <tr class="hover:bg-slate-50/70 transition-colors duration-150">
                        <td class="px-4 py-3 text-slate-400 text-xs">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-blue-800 text-white text-xs font-bold flex items-center justify-center shrink-0">
                                    {{ strtoupper(substr($k->nama_kelas,0,1)) }}
                                </div>
                                <span class="font-bold text-slate-800">{{ $k->nama_kelas }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            @if($k->guru)
                            <span class="badge badge-info">{{ $k->guru->nama }}</span>
                            @else
                            <span class="text-slate-400 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-slate-600 text-xs hide-mobile">
                            @if($k->tahunAjaran)
                            {{ $k->tahunAjaran->tahun }}
                            <span class="text-slate-400">({{ ucfirst($k->tahunAjaran->semester) }})</span>
                            @else —
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center hide-mobile">
                            <span class="badge badge-neutral">{{ $k->siswas->count() ?? 0 }} siswa</span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-1.5">
                                <button @click='openModal = true; setData(@json($k))'
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold
                                           bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 transition">
                                    <i data-lucide="pencil" class="w-3 h-3"></i> Edit
                                </button>
                                <form action="{{ route('kelas.destroy', $k->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus kelas {{ $k->nama_kelas }}?')">
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
                        <td colspan="6" class="px-4 py-12 text-center text-slate-400">
                            <i data-lucide="layout-grid" class="w-8 h-8 mx-auto mb-2 opacity-30"></i>
                            <p class="text-sm">Data kelas belum tersedia</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div x-show="openModal" x-transition.opacity @click.self="openModal = false"
         class="fixed inset-0 z-50 flex items-center justify-center bg-blue-950/60 backdrop-blur-sm p-4"
         style="display:none">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md"
             x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="flex items-center justify-between px-6 py-4" style="background:linear-gradient(135deg,#1e3a5f,#1e40af)">
                <h2 class="text-base font-bold text-white">Edit Data Kelas</h2>
                <button @click="openModal = false" class="w-8 h-8 rounded-lg bg-white/15 hover:bg-white/25 text-white flex items-center justify-center">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <form :action="'/kelas/' + form.id" method="POST" class="p-6 space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="form-label">Nama Kelas</label>
                    <input type="text" name="nama_kelas" x-model="form.nama_kelas" class="form-input w-full">
                </div>
                <div>
                    <label class="form-label">Wali Kelas</label>
                    <select name="guru_id" x-model="form.guru_id" class="form-input w-full">
                        <option value="">— Pilih Guru —</option>
                        @foreach($gurus as $g)
                        <option value="{{ $g->id }}">{{ $g->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Tahun Ajaran</label>
                    <select name="tahun_ajaran_id" x-model="form.tahun_ajaran_id" class="form-input w-full">
                        <option value="">— Pilih Tahun Ajaran —</option>
                        @foreach($tahunAjarans as $t)
                        <option value="{{ $t->id }}">{{ $t->tahun }} ({{ ucfirst($t->semester) }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                    <button type="button" @click="openModal = false" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-primary"><i data-lucide="save" class="w-4 h-4"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>
<script>
function modalKelas() {
    return {
        openModal: false,
        form: { id:'', nama_kelas:'', guru_id:'', tahun_ajaran_id:'' },
        setData(d) { this.form.id=d.id; this.form.nama_kelas=d.nama_kelas; this.form.guru_id=d.guru_id; this.form.tahun_ajaran_id=d.tahun_ajaran_id; }
    }
}
</script>
</div>

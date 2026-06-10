<div x-data="modalTahunAjaran()">
<x-app-layout>

    <x-page-header title="Tahun Ajaran" subtitle="Kelola tahun ajaran aktif dan semester"/>

    @if(session('success'))
    <div class="alert-success mb-5"><i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i>{{ session('success') }}</div>
    @endif

    {{-- Toolbar --}}
    <div class="card p-4 mb-5 flex items-center justify-between gap-3">
        <div>
            <p class="text-sm font-semibold text-slate-700">Daftar Tahun Ajaran</p>
            <p class="text-xs text-slate-400 mt-0.5">{{ $data->count() }} tahun ajaran tercatat</p>
        </div>
        <a href="{{ route('tahun-ajaran.create') }}" class="btn-primary">
            <i data-lucide="plus" class="w-4 h-4"></i> Tambah Tahun Ajaran
        </a>
    </div>

    {{-- Tabel --}}
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-blue-900 text-white">
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide w-10">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide">Tahun Ajaran</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide">Semester</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($data as $d)
                    <tr class="hover:bg-slate-50/70 transition-colors duration-150 {{ $d->is_active ? 'bg-blue-50/40' : '' }}">
                        <td class="px-4 py-3 text-slate-400 text-xs">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0
                                    {{ $d->is_active ? 'bg-blue-800 text-white' : 'bg-slate-100 text-slate-500' }}">
                                    <i data-lucide="calendar" class="w-4 h-4"></i>
                                </div>
                                <span class="font-bold text-slate-800">{{ $d->tahun }}</span>
                                @if($d->is_active)
                                <span class="ml-1 text-[10px] font-bold text-blue-600 bg-blue-100 px-2 py-0.5 rounded-full">Aktif</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="badge {{ $d->semester == 'ganjil' ? 'bg-amber-100 text-amber-700' : 'bg-teal-100 text-teal-700' }}">
                                {{ ucfirst($d->semester) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @if($d->is_active)
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 bg-emerald-100 px-2.5 py-1 rounded-full">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Aktif
                            </span>
                            @else
                            <span class="badge badge-neutral">Tidak Aktif</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-1.5">
                                <button @click="openModal = true; setData({{ json_encode($d) }})"
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold
                                           bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 transition">
                                    <i data-lucide="pencil" class="w-3 h-3"></i> Edit
                                </button>
                                <form action="{{ route('tahun-ajaran.destroy', $d->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus tahun ajaran {{ $d->tahun }}?')">
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
                        <td colspan="5" class="px-4 py-12 text-center text-slate-400">
                            <i data-lucide="calendar" class="w-8 h-8 mx-auto mb-2 opacity-30"></i>
                            <p class="text-sm">Data tahun ajaran belum tersedia</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Info aktif --}}
    @php $aktif = $data->firstWhere('is_active', true); @endphp
    @if($aktif)
    <div class="mt-4 flex items-center gap-3 bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 text-sm text-blue-700">
        <i data-lucide="info" class="w-4 h-4 shrink-0"></i>
        Tahun ajaran aktif saat ini: <strong>{{ $aktif->tahun }}</strong> — Semester <strong>{{ ucfirst($aktif->semester) }}</strong>
    </div>
    @endif

    {{-- Modal Edit --}}
    <div x-show="openModal" x-transition.opacity @click.self="openModal = false"
         class="fixed inset-0 z-50 flex items-center justify-center bg-blue-950/60 backdrop-blur-sm p-4"
         style="display:none">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md"
             x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="flex items-center justify-between px-6 py-4"
                 style="background:linear-gradient(135deg,#1e3a5f,#1e40af)">
                <h2 class="text-base font-bold text-white">Edit Tahun Ajaran</h2>
                <button @click="openModal = false"
                    class="w-8 h-8 rounded-lg bg-white/15 hover:bg-white/25 text-white flex items-center justify-center">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <form :action="'/tahun-ajaran/' + form.id" method="POST" class="p-6 space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="form-label">Tahun Ajaran <span class="text-slate-400 font-normal normal-case">contoh: 2025/2026</span></label>
                    <input type="text" name="tahun" x-model="form.tahun" placeholder="2025/2026" class="form-input w-full">
                </div>
                <div>
                    <label class="form-label">Semester</label>
                    <select name="semester" x-model="form.semester" class="form-input w-full">
                        <option value="ganjil">Ganjil (Juli – Desember)</option>
                        <option value="genap">Genap (Januari – Juni)</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Status</label>
                    <select name="is_active" x-model="form.is_active" class="form-input w-full">
                        <option value="1">✅ Aktif</option>
                        <option value="0">Tidak Aktif</option>
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
function modalTahunAjaran() {
    return {
        openModal: false,
        form: { id:'', tahun:'', semester:'', is_active:'' },
        setData(d) { this.form.id=d.id; this.form.tahun=d.tahun; this.form.semester=d.semester; this.form.is_active=d.is_active?'1':'0'; }
    }
}
</script>
</div>

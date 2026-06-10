<div x-data="modalGuru()">
<x-app-layout>

    <x-page-header title="Data Guru" subtitle="Kelola data guru dan tenaga pengajar"/>

    @if(session('success'))
    <div class="alert-success mb-5">
        <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i>{{ session('success') }}
    </div>
    @endif

    {{-- Toolbar --}}
    <div class="card p-4 mb-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <p class="text-sm font-semibold text-slate-700">Daftar Guru</p>
            <p class="text-xs text-slate-400 mt-0.5">{{ $gurus->count() }} guru terdaftar</p>
        </div>
        <a href="{{ route('guru.create') }}" class="btn-primary self-start sm:self-auto">
            <i data-lucide="user-plus" class="w-4 h-4"></i> Tambah Guru
        </a>
    </div>

    {{-- Tabel --}}
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-blue-900 text-white">
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide w-10">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide">Nama Guru</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide">NIP</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide hide-mobile">Email</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($gurus as $g)
                    <tr class="hover:bg-slate-50/70 transition-colors duration-150">
                        <td class="px-4 py-3 text-slate-400 text-xs">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 text-xs font-bold flex items-center justify-center shrink-0">
                                    {{ strtoupper(substr($g->nama,0,1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800">{{ $g->nama }}</p>
                                    <p class="text-[10px] text-slate-400 font-mono hide-mobile">{{ $g->nip ?: '—' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-slate-500 font-mono text-xs">{{ $g->nip ?: '—' }}</td>
                        <td class="px-4 py-3 hide-mobile">
                            <span class="text-blue-700 text-xs">{{ $g->email ?: '—' }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-1.5">
                                <button @click="openModal = true; setData({{ json_encode($g) }})"
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold
                                           bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 transition">
                                    <i data-lucide="pencil" class="w-3 h-3"></i> Edit
                                </button>
                                <form action="{{ route('guru.destroy', $g->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus data guru {{ $g->nama }}?')">
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
                            <i data-lucide="user-x" class="w-8 h-8 mx-auto mb-2 opacity-30"></i>
                            <p class="text-sm">Data guru belum tersedia</p>
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
             x-transition:enter="transition duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            <div class="flex items-center justify-between px-6 py-4"
                 style="background:linear-gradient(135deg,#1e3a5f,#1e40af)">
                <h2 class="text-base font-bold text-white">Edit Data Guru</h2>
                <button @click="openModal = false"
                    class="w-8 h-8 rounded-lg bg-white/15 hover:bg-white/25 text-white flex items-center justify-center">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <form :action="'/guru/' + form.id" method="POST" class="p-6 space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" x-model="form.nama" class="form-input w-full">
                </div>
                <div>
                    <label class="form-label">NIP</label>
                    <input type="text" name="nip" x-model="form.nip" class="form-input w-full">
                </div>
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" x-model="form.email" class="form-input w-full">
                </div>
                <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                    <button type="button" @click="openModal = false" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-primary">
                        <i data-lucide="save" class="w-4 h-4"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function modalGuru() {
        return {
            openModal: false,
            form: { id:'', nama:'', nip:'', email:'' },
            setData(d) { this.form.id=d.id; this.form.nama=d.nama; this.form.nip=d.nip; this.form.email=d.email; }
        }
    }
    </script>

</x-app-layout>
</div>

<div x-data="modalEdit()">
<x-app-layout>

    <x-page-header title="Data Siswa" subtitle="Kelola data siswa aktif per kelas"/>

    {{-- Flash --}}
    @if(session('success'))
    <div class="alert-success mb-5">
        <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i>
        {{ session('success') }}
    </div>
    @endif

    {{-- Toolbar --}}
    <div class="card p-4 mb-5 flex flex-col sm:flex-row sm:items-center gap-3">
        <div class="relative flex-1 max-w-xs">
            <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input type="text" x-model="search" placeholder="Cari nama atau NIS..."
                class="form-input pl-9 w-full">
        </div>
        <div class="flex gap-2 ml-auto">
            <a href="{{ route('siswa.create') }}" class="btn-primary">
                <i data-lucide="user-plus" class="w-4 h-4"></i> Tambah Siswa
            </a>
            <form action="{{ route('siswa.naikKelas') }}" method="POST">
                @csrf
                <button onclick="return confirm('Naikkan semua siswa ke kelas berikutnya?')"
                    class="inline-flex items-center gap-2 bg-teal-700 hover:bg-teal-800 text-white
                           text-sm font-semibold px-4 py-2.5 rounded-xl transition shadow-sm">
                    <i data-lucide="arrow-up-circle" class="w-4 h-4"></i>
                    Naikkan Semua
                </button>
            </form>
        </div>
    </div>

    {{-- Tabel per kelas --}}
    @foreach($kelas as $k)
    <div class="mb-6">

        {{-- Header kelas --}}
        <div class="flex items-center gap-3 mb-3">
            <div class="w-1 h-6 rounded-full bg-blue-700"></div>
            <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wide">
                Kelas {{ $k->nama_kelas }}
            </h3>
            <span class="badge badge-info ml-auto">{{ $k->siswas->count() }} siswa</span>
        </div>

        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-blue-900 text-white">
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide w-10">#</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide">NIS</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide">Nama Siswa</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide">JK</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide">Orang Tua</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide hide-mobile">No HP</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide hide-mobile">Alamat</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($k->siswas as $s)
                        <tr class="hover:bg-slate-50/70 transition-colors duration-150"
                            x-show="'{{ strtolower($s->nama) }}'.includes(search.toLowerCase()) ||
                                    '{{ strtolower($s->nis) }}'.includes(search.toLowerCase())">
                            <td class="px-4 py-3 text-slate-400 text-xs">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 font-mono text-xs text-slate-600">{{ $s->nis }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center text-[10px] font-bold shrink-0
                                        {{ $s->jk == 'L' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
                                        {{ strtoupper(substr($s->nama,0,1)) }}
                                    </div>
                                    <span class="font-semibold text-slate-800">{{ $s->nama }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="badge {{ $s->jk=='L' ? 'badge-info' : 'bg-pink-100 text-pink-700' }}">
                                    {{ $s->jk=='L' ? 'L' : 'P' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-slate-600 text-xs">{{ $s->nama_ortu ?: '—' }}</td>
                            <td class="px-4 py-3 text-slate-500 text-xs font-mono hide-mobile">{{ $s->no_hp_ortu ?: '—' }}</td>
                            <td class="px-4 py-3 text-slate-500 text-xs hide-mobile max-w-[160px] truncate">{{ $s->alamat ?: '—' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button @click="openModal = true; setData({{ json_encode($s) }})"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold
                                               bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 transition">
                                        <i data-lucide="pencil" class="w-3 h-3"></i> Edit
                                    </button>
                                    <form action="{{ route('siswa.destroy', $s->id) }}" method="POST"
                                          onsubmit="return confirm('Hapus siswa {{ $s->nama }}?')">
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
                            <td colspan="8" class="px-4 py-10 text-center text-slate-400 text-sm">
                                <i data-lucide="users" class="w-8 h-8 mx-auto mb-2 opacity-30"></i>
                                Belum ada siswa di kelas ini
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endforeach

    {{-- ── MODAL EDIT ──────────────────────────────────────────────────── --}}
    <div x-show="openModal"
         x-transition:enter="transition duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         @click.self="openModal = false"
         class="fixed inset-0 z-50 flex items-center justify-center bg-blue-950/60 backdrop-blur-sm p-4"
         style="display:none">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto"
             x-transition:enter="transition duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">

            <div class="flex items-center justify-between px-6 py-5"
                 style="background:linear-gradient(135deg,#1e3a5f,#1e40af)">
                <div>
                    <p class="text-blue-200 text-[10px] font-bold uppercase tracking-widest">Data Siswa</p>
                    <h2 class="text-lg font-bold text-white mt-0.5">Edit Data Siswa</h2>
                </div>
                <button @click="openModal = false"
                    class="w-8 h-8 rounded-lg bg-white/15 hover:bg-white/25 text-white flex items-center justify-center transition">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <form x-bind:action="`{{ url('siswa') }}/${form.id}`" method="POST" class="p-6">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div class="space-y-4">
                        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wide">Data Siswa</h3>
                        <div>
                            <label class="form-label">NIS</label>
                            <input type="text" name="nis" x-model="form.nis" class="form-input w-full">
                        </div>
                        <div>
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" x-model="form.nama" class="form-input w-full">
                        </div>
                        <div>
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="jk" x-model="form.jk" class="form-input w-full">
                                <option value="">-- Pilih --</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Kelas</label>
                            <select name="kelas_id" x-model="form.kelas_id" class="form-input w-full">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($kelas as $kls)
                                <option value="{{ $kls->id }}">{{ $kls->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" x-model="form.alamat" rows="3" class="form-input w-full"></textarea>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wide">Data Orang Tua / Wali</h3>
                        <div>
                            <label class="form-label">Nama Orang Tua</label>
                            <input type="text" name="nama_ortu" x-model="form.nama_ortu" class="form-input w-full">
                        </div>
                        <div>
                            <label class="form-label">No. HP</label>
                            <input type="text" name="no_hp_ortu" x-model="form.no_hp_ortu" class="form-input w-full">
                        </div>
                        <div>
                            <label class="form-label">Alamat Orang Tua</label>
                            <textarea name="alamat_ortu" x-model="form.alamat_ortu" rows="3" class="form-input w-full"></textarea>
                        </div>
                    </div>

                </div>
                <div class="flex justify-end gap-2 mt-6 pt-5 border-t border-slate-100">
                    <button type="button" @click="openModal = false" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-primary">
                        <i data-lucide="save" class="w-4 h-4"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function modalEdit() {
        return {
            openModal: false, search: '',
            form: { id:'',nis:'',nama:'',jk:'',kelas_id:'',alamat:'',nama_ortu:'',no_hp_ortu:'',alamat_ortu:'' },
            setData(d) {
                this.form.id=d.id; this.form.nis=d.nis; this.form.nama=d.nama;
                this.form.jk=d.jk; this.form.kelas_id=d.kelas_id; this.form.alamat=d.alamat;
                this.form.nama_ortu=d.nama_ortu; this.form.no_hp_ortu=d.no_hp_ortu; this.form.alamat_ortu=d.alamat_ortu;
            }
        }
    }
    </script>

</x-app-layout>
</div>

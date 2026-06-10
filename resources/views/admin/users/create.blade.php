<x-app-layout>
    <x-page-header title="Tambah User" subtitle="Buat akun pengguna sistem baru"/>

    <div class="max-w-2xl">
        <div class="card overflow-hidden">
            <div class="px-6 py-4" style="background:linear-gradient(135deg,#1e3a5f,#1e40af)">
                <p class="text-blue-200 text-[10px] font-bold uppercase tracking-widest">Formulir</p>
                <h2 class="text-base font-bold text-white mt-0.5">Akun Pengguna Baru</h2>
            </div>
            <form method="POST" action="{{ route('users.store') }}" class="p-6 space-y-5">
                @csrf
                <input type="hidden" name="name" id="name_auto">

                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="email@gmail.com" class="form-input w-full">
                    @error('email')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Password</label>
                    <input type="password" name="password" placeholder="Minimal 8 karakter" class="form-input w-full">
                    @error('password')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Role</label>
                    <select name="role" id="roleSelect" class="form-input w-full">
                        <option value="">-- Pilih Role --</option>
                        <option value="admin">Admin</option>
                        <option value="guru">Guru</option>
                        <option value="kepala_sekolah">Kepala Sekolah</option>
                        <option value="guru&wali_kelas">Guru & Wali Kelas</option>
                        <option value="orang_tua">Orang Tua</option>
                    </select>
                    @error('role')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Guru picker --}}
                <div id="guruBox" class="hidden">
                    <label class="form-label">Pilih Guru</label>
                    <div class="flex gap-2">
                        <input type="hidden" name="guru_id" id="guru_id">
                        <input type="text" id="guru_nama" readonly placeholder="Belum dipilih" class="form-input w-full bg-slate-50 cursor-default">
                        <button type="button" id="btnGuru" onclick="openModal(this.dataset.modal)" data-modal="modalAllGuru" class="btn-primary whitespace-nowrap">
                            <i data-lucide="search" class="w-4 h-4"></i> Pilih
                        </button>
                    </div>
                </div>

                {{-- Siswa picker --}}
                <div id="siswaBox" class="hidden">
                    <label class="form-label">Pilih Siswa</label>
                    <div class="flex gap-2">
                        <input type="hidden" name="siswa_id" id="siswa_id">
                        <input type="text" id="siswa_nama" readonly placeholder="Belum dipilih" class="form-input w-full bg-slate-50 cursor-default">
                        <button type="button" onclick="openModal('siswaModal')" class="btn-primary whitespace-nowrap">
                            <i data-lucide="search" class="w-4 h-4"></i> Pilih
                        </button>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_active" value="1" id="is_active" checked
                        class="w-4 h-4 text-blue-700 border-slate-300 rounded focus:ring-blue-500">
                    <label for="is_active" class="text-sm text-slate-700 font-medium cursor-pointer">Aktif</label>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                    <a href="{{ route('users.index') }}" class="btn-secondary flex items-center gap-2">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
                    </a>
                    <button type="submit" class="btn-primary">
                        <i data-lucide="save" class="w-4 h-4"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>

{{-- Modal Guru --}}
<div id="modalAllGuru" class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-2xl overflow-hidden shadow-xl">
        <div class="px-5 py-4 flex items-center justify-between" style="background:linear-gradient(135deg,#1e3a5f,#1e40af)">
            <h3 class="text-sm font-bold text-white">Pilih Guru</h3>
            <button onclick="closeModal('modalAllGuru')" class="text-blue-200 hover:text-white"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <div class="p-5">
            <input type="text" id="searchGuru" placeholder="Cari nama guru..." class="form-input w-full mb-4">
            <div class="max-h-80 overflow-y-auto space-y-2">
                @foreach($allGuru as $g)
                <button type="button" onclick="selectGuru('{{ $g->id }}','{{ $g->nama }}')"
                    class="guru-item w-full text-left px-4 py-3 rounded-xl border border-slate-200 hover:border-blue-400 hover:bg-blue-50 text-sm text-slate-700 transition">
                    {{ $g->nama }}
                </button>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Modal Wali Kelas --}}
<div id="modalWaliKelas" class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-2xl overflow-hidden shadow-xl">
        <div class="px-5 py-4 flex items-center justify-between" style="background:linear-gradient(135deg,#1e3a5f,#1e40af)">
            <h3 class="text-sm font-bold text-white">Pilih Guru Wali Kelas</h3>
            <button onclick="closeModal('modalWaliKelas')" class="text-blue-200 hover:text-white"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <div class="p-5">
            <div class="max-h-80 overflow-y-auto space-y-2">
                @foreach($guruWaliKelas as $g)
                @if($g)
                <button type="button" onclick="selectGuru('{{ $g->id }}','{{ $g->nama }}')"
                    class="w-full text-left px-4 py-3 rounded-xl border border-slate-200 hover:border-blue-400 hover:bg-blue-50 text-sm text-slate-700 transition">
                    {{ $g->nama }}
                </button>
                @endif
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Modal Siswa --}}
<div id="siswaModal" class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-2xl overflow-hidden shadow-xl">
        <div class="px-5 py-4 flex items-center justify-between" style="background:linear-gradient(135deg,#1e3a5f,#1e40af)">
            <h3 class="text-sm font-bold text-white">Pilih Siswa</h3>
            <button onclick="closeModal('siswaModal')" class="text-blue-200 hover:text-white"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <div class="p-5">
            <input type="text" id="searchSiswa" placeholder="Cari nama siswa..." class="form-input w-full mb-4">
            <div class="max-h-80 overflow-y-auto space-y-2">
                @foreach($siswas ?? [] as $s)
                <button type="button" onclick="selectSiswa('{{ $s->id }}','{{ $s->nama }}')"
                    class="siswa-item w-full text-left px-4 py-3 rounded-xl border border-slate-200 hover:border-blue-400 hover:bg-blue-50 text-sm text-slate-700 transition">
                    {{ $s->nama }}
                </button>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
const roleSelect = document.getElementById('roleSelect');
const guruBox    = document.getElementById('guruBox');
const siswaBox   = document.getElementById('siswaBox');
const btnGuru    = document.getElementById('btnGuru');

roleSelect.addEventListener('change', function() {
    guruBox.classList.add('hidden');
    siswaBox.classList.add('hidden');
    if (['admin','guru','kepala_sekolah'].includes(this.value)) {
        guruBox.classList.remove('hidden');
        btnGuru.dataset.modal = 'modalAllGuru';
    } else if (this.value === 'guru&wali_kelas') {
        guruBox.classList.remove('hidden');
        btnGuru.dataset.modal = 'modalWaliKelas';
    } else if (this.value === 'orang_tua') {
        siswaBox.classList.remove('hidden');
    }
});

function openModal(id) {
    const el = document.getElementById(id);
    if (el) { el.classList.remove('hidden'); el.classList.add('flex'); }
}
function closeModal(id) {
    const el = document.getElementById(id);
    if (el) { el.classList.add('hidden'); el.classList.remove('flex'); }
}
function selectGuru(id, nama) {
    document.getElementById('guru_id').value   = id;
    document.getElementById('guru_nama').value = nama;
    document.getElementById('name_auto').value = nama;
    closeModal('modalAllGuru');
    closeModal('modalWaliKelas');
}
function selectSiswa(id, nama) {
    document.getElementById('siswa_id').value   = id;
    document.getElementById('siswa_nama').value = nama;
    document.getElementById('name_auto').value  = nama;
    closeModal('siswaModal');
}
document.getElementById('searchGuru').addEventListener('input', function() {
    const v = this.value.toLowerCase();
    document.querySelectorAll('.guru-item').forEach(i => {
        i.style.display = i.innerText.toLowerCase().includes(v) ? '' : 'none';
    });
});
document.getElementById('searchSiswa').addEventListener('input', function() {
    const v = this.value.toLowerCase();
    document.querySelectorAll('.siswa-item').forEach(i => {
        i.style.display = i.innerText.toLowerCase().includes(v) ? '' : 'none';
    });
});
</script>

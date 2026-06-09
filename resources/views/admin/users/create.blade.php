<x-app-layout>

    <x-page-header
        title="Tambah User"
        subtitle="Dashboard / Users / Create"
    />

    <div class="py-6">
        <div class="max-w-4xl mx-auto">

            <div class="bg-white rounded-2xl shadow-sm border p-6">

                <form method="POST" action="{{ route('users.store') }}" class="space-y-6">
                    @csrf

                    {{-- AUTO NAME --}}
                    <input type="hidden" name="name" id="name_auto">

                    {{-- EMAIL --}}
                    <div>
                        <label class="text-sm font-semibold">Email</label>
                        <input type="email"
                            name="email"
                            class="w-full mt-2 rounded-xl border-gray-300"
                            placeholder="email@gmail.com">
                    </div>

                    {{-- PASSWORD --}}
                    <div>
                        <label class="text-sm font-semibold">Password</label>
                        <input type="password"
                            name="password"
                            class="w-full mt-2 rounded-xl border-gray-300"
                            placeholder="******">
                    </div>

                    {{-- ROLE --}}
                    <div>
                        <label class="text-sm font-semibold">Role</label>

                        <select name="role" id="roleSelect"
                            class="w-full mt-2 rounded-xl border-gray-300">

                            <option value="">-- Pilih Role --</option>
                            <option value="admin">Admin</option>
                            <option value="guru">Guru</option>
                            <option value="kepala_sekolah">Kepala Sekolah</option>
                            <option value="guru&wali_kelas">Guru & Wali Kelas</option>
                            <option value="orang_tua">Orang Tua</option>

                        </select>
                    </div>

                    {{-- GURU --}}
                    <div id="guruBox" class="hidden">
                        <label class="text-sm font-semibold">Pilih Guru</label>

                        <div class="flex gap-2 mt-2">
                            <input type="hidden" name="guru_id" id="guru_id">

                            <input type="text"
                                id="guru_nama"
                                readonly
                                class="w-full bg-gray-50 rounded-xl border-gray-300"
                                placeholder="Belum dipilih">

                            <button type="button"
                                id="btnGuru"
                                onclick="openModal(this.dataset.modal)"
                                data-modal="modalAllGuru"
                                class="px-4 bg-indigo-600 text-white rounded-xl cursor-pointer">
                                Pilih
                            </button>
                        </div>
                    </div>

                    {{-- SISWA --}}
                    <div id="siswaBox" class="hidden">
                        <label class="text-sm font-semibold">Pilih Siswa</label>

                        <div class="flex gap-2 mt-2">
                            <input type="hidden" name="siswa_id" id="siswa_id">

                            <input type="text"
                                id="siswa_nama"
                                readonly
                                class="w-full bg-gray-50 rounded-xl border-gray-300"
                                placeholder="Belum dipilih">

                            <button type="button"
                                onclick="openModal('siswaModal')"
                                class="px-4 bg-indigo-600 text-white rounded-xl cursor-pointer">
                                Pilih
                            </button>
                        </div>
                    </div>

                    {{-- STATUS --}}
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" checked>
                        <span>Aktif</span>
                    </label>

                    {{-- BUTTON --}}
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('users.index') }}" class="px-5 py-2 border rounded-xl">
                            Batal
                        </a>

                        <button class="px-5 py-2 bg-indigo-600 text-white rounded-xl">
                            Simpan
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>

{{-- ================= MODAL GURU ================= --}}
<div id="modalAllGuru" class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center p-4">
    <div class="bg-white w-full max-w-2xl rounded-2xl p-6 relative z-50 pointer-events-auto">

        <div class="flex justify-between mb-3">
            <h2 class="font-bold">Pilih Guru</h2>
            <button onclick="closeModal('modalAllGuru')">✕</button>
        </div>

        <input type="text" id="searchGuru"
            class="w-full mb-3 border rounded-xl p-2"
            placeholder="Cari guru">

        <div class="max-h-96 overflow-y-auto space-y-2">
            @foreach($allGuru as $g)
                <button type="button"
                    onclick="selectGuru('{{ $g->id }}','{{ $g->nama }}')"
                    class="guru-item w-full text-left p-3 border rounded-xl hover:bg-indigo-50 cursor-pointer">
                    {{ $g->nama }}
                </button>
            @endforeach
        </div>

    </div>
</div>

{{-- ================= MODAL WALI KELAS ================= --}}
<div id="modalWaliKelas" class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center p-4">
    <div class="bg-white w-full max-w-2xl rounded-2xl p-6 relative z-50 pointer-events-auto">

        <div class="flex justify-between mb-3">
            <h2 class="font-bold">Pilih Guru Wali Kelas</h2>
            <button onclick="closeModal('modalWaliKelas')">✕</button>
        </div>

        <div class="max-h-96 overflow-y-auto space-y-2">
            @foreach($guruWaliKelas as $g)
                @if($g)
                <button type="button"
                    onclick="selectGuru('{{ $g->id }}','{{ $g->nama }}')"
                    class="w-full text-left p-3 border rounded-xl hover:bg-indigo-50 cursor-pointer">
                    {{ $g->nama }}
                </button>
                @endif
            @endforeach
        </div>

    </div>
</div>

{{-- ================= MODAL SISWA ================= --}}
<div id="siswaModal" class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center p-4">
    <div class="bg-white w-full max-w-2xl rounded-2xl p-6 relative z-50 pointer-events-auto">

        <div class="flex justify-between mb-3">
            <h2 class="font-bold">Pilih Siswa</h2>
            <button onclick="closeModal('siswaModal')">✕</button>
        </div>

        <input type="text" id="searchSiswa"
            class="w-full mb-3 border rounded-xl p-2"
            placeholder="Cari siswa">

        <div class="max-h-96 overflow-y-auto space-y-2">
            @foreach($siswas ?? [] as $s)
                <button type="button"
                    onclick="selectSiswa('{{ $s->id }}','{{ $s->nama }}')"
                    class="siswa-item w-full text-left p-3 border rounded-xl hover:bg-indigo-50 cursor-pointer">
                    {{ $s->nama }}
                </button>
            @endforeach
        </div>

    </div>
</div>

{{-- ================= SCRIPT ================= --}}
<script>
const role = document.getElementById('roleSelect');
const guruBox = document.getElementById('guruBox');
const siswaBox = document.getElementById('siswaBox');
const btnGuru = document.getElementById('btnGuru');

role.addEventListener('change', function () {

    guruBox.classList.add('hidden');
    siswaBox.classList.add('hidden');

    if (this.value === 'admin' || this.value === 'guru' || this.value === 'kepala_sekolah') {
        guruBox.classList.remove('hidden');
        btnGuru.dataset.modal = 'modalAllGuru';
    }

    if (this.value === 'guru&wali_kelas') {
        guruBox.classList.remove('hidden');
        btnGuru.dataset.modal = 'modalWaliKelas';
    }

    if (this.value === 'orang_tua') {
        siswaBox.classList.remove('hidden');
    }
});

function openModal(id){
    const el = document.getElementById(id);
    if(el){
        el.classList.remove('hidden');
        el.classList.add('flex');
    }
}

function closeModal(id){
    const el = document.getElementById(id);
    if(el){
        el.classList.add('hidden');
        el.classList.remove('flex');
    }
}

// SELECT GURU
function selectGuru(id,nama){
    document.getElementById('guru_id').value = id;
    document.getElementById('guru_nama').value = nama;
    document.getElementById('name_auto').value = nama;

    closeModal('modalAllGuru');
    closeModal('modalWaliKelas');
}

// SELECT SISWA
function selectSiswa(id,nama){
    document.getElementById('siswa_id').value = id;
    document.getElementById('siswa_nama').value = nama;
    document.getElementById('name_auto').value = nama;

    closeModal('siswaModal');
}

// SEARCH GURU
document.getElementById('searchGuru').addEventListener('input', function(){
    let v = this.value.toLowerCase();
    document.querySelectorAll('.guru-item').forEach(i=>{
        i.style.display = i.innerText.toLowerCase().includes(v) ? '' : 'none';
    });
});

// SEARCH SISWA
document.getElementById('searchSiswa').addEventListener('input', function(){
    let v = this.value.toLowerCase();
    document.querySelectorAll('.siswa-item').forEach(i=>{
        i.style.display = i.innerText.toLowerCase().includes(v) ? '' : 'none';
    });
});
</script>
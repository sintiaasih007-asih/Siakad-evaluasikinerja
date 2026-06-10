<x-app-layout>
    <x-page-header title="Tambah Mata Pelajaran" subtitle="Formulir penambahan mapel baru"/>
    <div class="max-w-lg">
        <div class="card overflow-hidden">
            <div class="px-6 py-4" style="background:linear-gradient(135deg,#1e3a5f,#1e40af)">
                <p class="text-blue-200 text-[10px] font-bold uppercase tracking-widest">Formulir</p>
                <h2 class="text-base font-bold text-white mt-0.5">Mata Pelajaran Baru</h2>
            </div>
            <form method="POST" action="{{ route('mapel.store') }}" class="p-6 space-y-5">
                @csrf
                <div>
                    <label class="form-label">Kode Mata Pelajaran</label>
                    <input type="text" name="kode_mapel" value="{{ old('kode_mapel') }}" placeholder="Contoh: MTK01" class="form-input w-full">
                    @error('kode_mapel')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Nama Mata Pelajaran</label>
                    <input type="text" name="nama_mapel" value="{{ old('nama_mapel') }}" placeholder="Contoh: Matematika" class="form-input w-full">
                    @error('nama_mapel')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Guru Pengampu</label>
                    <select name="guru_id" class="form-input w-full">
                        <option value="">-- Pilih Guru --</option>
                        @foreach($gurus as $g)
                        <option value="{{ $g->id }}" {{ old('guru_id')==$g->id?'selected':'' }}>{{ $g->nama }}</option>
                        @endforeach
                    </select>
                    @error('guru_id')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                    <a href="{{ route('mapel.index') }}" class="btn-secondary flex items-center gap-2">
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

<x-app-layout>
    <x-page-header title="Tambah Kelas" subtitle="Formulir penambahan kelas baru"/>
    <div class="max-w-lg">
        <div class="card overflow-hidden">
            <div class="px-6 py-4" style="background:linear-gradient(135deg,#1e3a5f,#1e40af)">
                <p class="text-blue-200 text-[10px] font-bold uppercase tracking-widest">Formulir</p>
                <h2 class="text-base font-bold text-white mt-0.5">Data Kelas Baru</h2>
            </div>
            <form action="{{ route('kelas.store') }}" method="POST" class="p-6 space-y-5">
                @csrf
                <div>
                    <label class="form-label">Nama Kelas</label>
                    <input type="text" name="nama_kelas" value="{{ old('nama_kelas') }}" placeholder="Contoh: VII-A" class="form-input w-full">
                    @error('nama_kelas')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Wali Kelas</label>
                    <select name="guru_id" class="form-input w-full">
                        <option value="">-- Pilih Guru --</option>
                        @foreach($guru as $g)
                        <option value="{{ $g->id }}" {{ old('guru_id')==$g->id?'selected':'' }}>{{ $g->nama }}</option>
                        @endforeach
                    </select>
                    @error('guru_id')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Tahun Ajaran</label>
                    <select name="tahun_ajaran_id" class="form-input w-full">
                        <option value="">-- Pilih Tahun Ajaran --</option>
                        @foreach($tahun as $t)
                        <option value="{{ $t->id }}" {{ old('tahun_ajaran_id')==$t->id?'selected':'' }}>{{ $t->tahun }} ({{ ucfirst($t->semester) }})</option>
                        @endforeach
                    </select>
                    @error('tahun_ajaran_id')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                    <a href="{{ route('kelas.index') }}" class="btn-secondary flex items-center gap-2">
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

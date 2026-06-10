<x-app-layout>
    <x-page-header title="Tambah Data Siswa" subtitle="Formulir pendaftaran siswa baru"/>
    <div class="max-w-3xl">
        <div class="card overflow-hidden">
            <div class="px-6 py-4" style="background:linear-gradient(135deg,#1e3a5f,#1e40af)">
                <p class="text-blue-200 text-[10px] font-bold uppercase tracking-widest">Formulir</p>
                <h2 class="text-base font-bold text-white mt-0.5">Data Siswa Baru</h2>
            </div>
            <form action="/siswa" method="POST" class="p-6 space-y-5">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">Data Siswa</p>
                        <div>
                            <label class="form-label">NIS</label>
                            <input type="text" name="nis" value="{{ old('nis') }}" placeholder="Masukkan NIS" class="form-input w-full">
                            @error('nis')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="form-label">Nama Siswa</label>
                            <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Nama lengkap siswa" class="form-input w-full">
                            @error('nama')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="jk" class="form-input w-full">
                                <option value="">-- Pilih --</option>
                                <option value="L" {{ old('jk')=='L'?'selected':'' }}>Laki-laki</option>
                                <option value="P" {{ old('jk')=='P'?'selected':'' }}>Perempuan</option>
                            </select>
                            @error('jk')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="form-label">Kelas</label>
                            <select name="kelas_id" class="form-input w-full">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($kelas as $k)
                                <option value="{{ $k->id }}" {{ old('kelas_id')==$k->id?'selected':'' }}>{{ $k->nama_kelas }}</option>
                                @endforeach
                            </select>
                            @error('kelas_id')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" rows="3" placeholder="Alamat siswa" class="form-input w-full">{{ old('alamat') }}</textarea>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">Data Orang Tua / Wali</p>
                        <div>
                            <label class="form-label">Nama Orang Tua</label>
                            <input type="text" name="nama_ortu" value="{{ old('nama_ortu') }}" placeholder="Nama orang tua/wali" class="form-input w-full">
                        </div>
                        <div>
                            <label class="form-label">No HP Orang Tua</label>
                            <input type="text" name="no_hp_ortu" value="{{ old('no_hp_ortu') }}" placeholder="08xxxxxxxxxx" class="form-input w-full">
                        </div>
                        <div>
                            <label class="form-label">Alamat Orang Tua</label>
                            <textarea name="alamat_ortu" rows="3" placeholder="Alamat orang tua" class="form-input w-full">{{ old('alamat_ortu') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                    <a href="{{ route('siswa.index') }}" class="btn-secondary flex items-center gap-2">
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

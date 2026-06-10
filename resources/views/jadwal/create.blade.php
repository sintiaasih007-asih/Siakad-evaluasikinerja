<x-app-layout>
    <x-page-header title="Tambah Jadwal Pelajaran" subtitle="Formulir penambahan jadwal baru"/>
    <div class="max-w-2xl">
        <div class="card overflow-hidden">
            <div class="px-6 py-4" style="background:linear-gradient(135deg,#1e3a5f,#1e40af)">
                <p class="text-blue-200 text-[10px] font-bold uppercase tracking-widest">Formulir</p>
                <h2 class="text-base font-bold text-white mt-0.5">Jadwal Pelajaran Baru</h2>
            </div>
            <form method="POST" action="{{ route('jadwal.store') }}" class="p-6 space-y-6">
                @csrf

                {{-- Kelas, Mapel, Guru --}}
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2 mb-4">Kelas & Pengajar</p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="form-label">Kelas</label>
                            <select name="kelas_id" class="form-input w-full">
                                <option value="">-- Pilih --</option>
                                @foreach($kelas as $k)
                                <option value="{{ $k->id }}" {{ old('kelas_id')==$k->id?'selected':'' }}>{{ $k->nama_kelas }}</option>
                                @endforeach
                            </select>
                            @error('kelas_id')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="form-label">Mata Pelajaran</label>
                            <select name="mapel_id" class="form-input w-full">
                                <option value="">-- Pilih --</option>
                                @foreach($mapels as $m)
                                <option value="{{ $m->id }}" {{ old('mapel_id')==$m->id?'selected':'' }}>{{ $m->nama_mapel }}</option>
                                @endforeach
                            </select>
                            @error('mapel_id')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="form-label">Guru Pengajar</label>
                            <select name="guru_id" class="form-input w-full">
                                <option value="">-- Pilih --</option>
                                @foreach($gurus as $g)
                                <option value="{{ $g->id }}" {{ old('guru_id')==$g->id?'selected':'' }}>{{ $g->nama }}</option>
                                @endforeach
                            </select>
                            @error('guru_id')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- Hari & Jam --}}
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2 mb-4">Waktu Pelajaran</p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="form-label">Hari</label>
                            <select name="hari" class="form-input w-full">
                                <option value="">-- Pilih Hari --</option>
                                @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $h)
                                <option value="{{ $h }}" {{ old('hari')==$h?'selected':'' }}>{{ $h }}</option>
                                @endforeach
                            </select>
                            @error('hari')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="form-label">Jam Masuk</label>
                            <input type="time" name="jam_masuk" value="{{ old('jam_masuk') }}" class="form-input w-full">
                            @error('jam_masuk')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="form-label">Jam Selesai</label>
                            <input type="time" name="jam_selesai" value="{{ old('jam_selesai') }}" class="form-input w-full">
                            @error('jam_selesai')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                    <a href="{{ route('jadwal.index') }}" class="btn-secondary flex items-center gap-2">
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

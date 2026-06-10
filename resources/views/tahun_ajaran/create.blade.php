<x-app-layout>
    <x-page-header title="Tambah Tahun Ajaran" subtitle="Formulir penambahan tahun ajaran baru"/>
    <div class="max-w-lg">
        <div class="card overflow-hidden">
            <div class="px-6 py-4" style="background:linear-gradient(135deg,#1e3a5f,#1e40af)">
                <p class="text-blue-200 text-[10px] font-bold uppercase tracking-widest">Formulir</p>
                <h2 class="text-base font-bold text-white mt-0.5">Tahun Ajaran Baru</h2>
            </div>
            <form action="{{ route('tahun-ajaran.store') }}" method="POST" class="p-6 space-y-5">
                @csrf
                <div>
                    <label class="form-label">Tahun Ajaran</label>
                    <input type="text" name="tahun" value="{{ old('tahun') }}" placeholder="2025/2026" class="form-input w-full">
                    @error('tahun')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Semester</label>
                    <select name="semester" class="form-input w-full">
                        <option value="">-- Pilih Semester --</option>
                        <option value="ganjil" {{ old('semester')=='ganjil'?'selected':'' }}>Ganjil (Juli – Desember)</option>
                        <option value="genap"  {{ old('semester')=='genap' ?'selected':'' }}>Genap (Januari – Juni)</option>
                    </select>
                    @error('semester')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_active" value="1" id="is_active"
                        {{ old('is_active')?'checked':'' }}
                        class="w-4 h-4 text-blue-700 border-slate-300 rounded focus:ring-blue-500">
                    <label for="is_active" class="text-sm text-slate-700 font-medium cursor-pointer">
                        Jadikan sebagai Tahun Ajaran Aktif
                    </label>
                </div>
                <p class="text-xs text-slate-400">* Hanya satu tahun ajaran yang bisa aktif pada satu waktu.</p>
                <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                    <a href="{{ route('tahun-ajaran.index') }}" class="btn-secondary flex items-center gap-2">
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

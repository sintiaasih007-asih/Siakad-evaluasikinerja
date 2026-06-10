<x-app-layout>

    <x-page-header title="Profil Sekolah" subtitle="Kelola identitas dan informasi sekolah"/>

    @if(session('success'))
    <div class="alert-success mb-5"><i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i>{{ session('success') }}</div>
    @endif

    <form action="{{ route('profil-sekolah.update') }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div x-data="{ editMode: false }">

        {{-- Banner hero --}}
        <div class="rounded-2xl p-6 mb-6 text-white shadow-sm"
             style="background:linear-gradient(135deg,#1e3a5f 0%,#1e40af 50%,#0891b2 100%)">
            <div class="flex flex-col md:flex-row items-center gap-5">
                {{-- Logo --}}
                <div class="shrink-0">
                    @if($profil->logo_sekolah)
                    <div class="w-24 h-24 bg-white rounded-2xl overflow-hidden shadow-lg flex items-center justify-center border-4 border-white/20">
                        <img src="{{ asset('storage/'.$profil->logo_sekolah) }}" class="w-full h-full object-contain p-2">
                    </div>
                    @else
                    <div class="w-24 h-24 bg-white/15 border-2 border-white/20 rounded-2xl flex items-center justify-center">
                        <i data-lucide="school" class="w-10 h-10 text-white/60"></i>
                    </div>
                    @endif
                </div>
                {{-- Info --}}
                <div class="flex-1 text-center md:text-left">
                    <h2 class="text-2xl font-bold">{{ $profil->nama_sekolah ?: 'Nama Sekolah' }}</h2>
                    <div class="flex flex-wrap justify-center md:justify-start gap-x-5 gap-y-1 mt-2 text-blue-100 text-sm">
                        @if($profil->npsn)<span>NPSN: {{ $profil->npsn }}</span>@endif
                        @if($profil->jenjang)<span>{{ $profil->jenjang }}</span>@endif
                        @if($profil->akreditasi)<span>Akreditasi {{ $profil->akreditasi }}</span>@endif
                        @if($profil->kurikulum)<span>{{ $profil->kurikulum }}</span>@endif
                    </div>
                    @if($profil->kepala_sekolah)
                    <p class="text-blue-200 text-sm mt-1">Kepala Sekolah: <strong class="text-white">{{ $profil->kepala_sekolah }}</strong></p>
                    @endif
                </div>
                <button type="button" @click="editMode = !editMode"
                    class="shrink-0 bg-white/20 hover:bg-white/30 border border-white/25 text-white text-sm font-semibold px-4 py-2 rounded-xl transition flex items-center gap-2">
                    <i data-lucide="pencil" class="w-4 h-4"></i>
                    <span x-text="editMode ? 'Batal Edit' : 'Edit Profil'"></span>
                </button>
            </div>
        </div>

        {{-- Form sections --}}
        <div :class="editMode ? 'opacity-100' : 'opacity-80 pointer-events-none'" class="space-y-5 transition-opacity duration-200">

            {{-- Identitas Sekolah --}}
            <div class="card overflow-hidden">
                <div class="px-5 py-3.5 border-b bg-slate-50 flex items-center gap-2">
                    <i data-lucide="building-2" class="w-4 h-4 text-slate-500"></i>
                    <span class="text-sm font-semibold text-slate-700">Identitas Sekolah</span>
                </div>
                <div class="p-5 grid md:grid-cols-2 gap-4">
                    @foreach([
                        ['Nama Sekolah','nama_sekolah','text',$profil->nama_sekolah],
                        ['NPSN','npsn','text',$profil->npsn],
                        ['NSS','nss','text',$profil->nss],
                        ['Jenjang','jenjang','text',$profil->jenjang],
                        ['Akreditasi','akreditasi','text',$profil->akreditasi],
                        ['Izin Operasional','izin_operasional','text',$profil->izin_operasional],
                    ] as [$label,$name,$type,$val])
                    <div>
                        <label class="form-label">{{ $label }}</label>
                        <input type="{{ $type }}" name="{{ $name }}" value="{{ old($name,$val) }}"
                            class="form-input w-full" placeholder="{{ $label }}">
                    </div>
                    @endforeach
                    <div>
                        <label class="form-label">Kurikulum</label>
                        <select name="kurikulum" class="form-input w-full">
                            <option value="">-- Pilih Kurikulum --</option>
                            @foreach(['Kurikulum 2013 (K-13)','Kurikulum Merdeka','Kurikulum 2013 Revisi','Kurikulum 2006 (KTSP)','Kurikulum Nasional','Kurikulum Darurat (Kondisi Khusus)'] as $kur)
                            <option value="{{ $kur }}" {{ old('kurikulum',$profil->kurikulum)==$kur?'selected':'' }}>{{ $kur }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Nama Yayasan</label>
                        <input type="text" name="nama_yayasan" value="{{ old('nama_yayasan',$profil->nama_yayasan) }}"
                            class="form-input w-full" placeholder="Nama Yayasan">
                    </div>
                </div>
            </div>

            {{-- Kepala Sekolah --}}
            <div class="card overflow-hidden">
                <div class="px-5 py-3.5 border-b bg-slate-50 flex items-center gap-2">
                    <i data-lucide="user-check" class="w-4 h-4 text-slate-500"></i>
                    <span class="text-sm font-semibold text-slate-700">Kepala Sekolah</span>
                </div>
                <div class="p-5 grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Nama Kepala Sekolah</label>
                        <input type="text" name="kepala_sekolah" value="{{ old('kepala_sekolah',$profil->kepala_sekolah) }}"
                            class="form-input w-full" placeholder="Nama lengkap">
                    </div>
                    <div>
                        <label class="form-label">NIP Kepala Sekolah</label>
                        <input type="text" name="nip_kepala_sekolah" value="{{ old('nip_kepala_sekolah',$profil->nip_kepala_sekolah) }}"
                            class="form-input w-full" placeholder="NIP">
                    </div>
                </div>
            </div>

            {{-- Logo & Foto --}}
            <div class="card overflow-hidden">
                <div class="px-5 py-3.5 border-b bg-slate-50 flex items-center gap-2">
                    <i data-lucide="image" class="w-4 h-4 text-slate-500"></i>
                    <span class="text-sm font-semibold text-slate-700">Logo & Foto</span>
                </div>
                <div class="p-5 grid md:grid-cols-3 gap-5">
                    @foreach([
                        ['Logo Sekolah','logo_sekolah',$profil->logo_sekolah],
                        ['Logo Yayasan','logo_yayasan',$profil->logo_yayasan],
                        ['Foto Kepala Sekolah','foto_kepala_sekolah',$profil->foto_kepala_sekolah],
                    ] as [$label,$name,$existing])
                    <div>
                        <label class="form-label">{{ $label }}</label>
                        <input type="file" name="{{ $name }}" class="text-sm text-slate-600 w-full">
                        @if($existing)
                        <img src="{{ asset('storage/'.$existing) }}"
                            class="mt-3 h-20 w-20 object-contain border border-slate-200 rounded-xl bg-slate-50 p-1">
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Kontak & Alamat --}}
            <div class="card overflow-hidden">
                <div class="px-5 py-3.5 border-b bg-slate-50 flex items-center gap-2">
                    <i data-lucide="map-pin" class="w-4 h-4 text-slate-500"></i>
                    <span class="text-sm font-semibold text-slate-700">Kontak & Alamat</span>
                </div>
                <div class="p-5 space-y-4">
                    <div class="grid md:grid-cols-2 gap-4">
                        @foreach([
                            ['Telepon','telepon','text',$profil->telepon],
                            ['WhatsApp','whatsapp','text',$profil->whatsapp],
                            ['Email','email','email',$profil->email],
                            ['Website','website','text',$profil->website],
                        ] as [$label,$name,$type,$val])
                        <div>
                            <label class="form-label">{{ $label }}</label>
                            <input type="{{ $type }}" name="{{ $name }}" value="{{ old($name,$val) }}"
                                class="form-input w-full" placeholder="{{ $label }}">
                        </div>
                        @endforeach
                    </div>
                    <div>
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea name="alamat" rows="3" class="form-input w-full" placeholder="Alamat lengkap sekolah">{{ old('alamat',$profil->alamat) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Lokasi --}}
            <div class="card overflow-hidden">
                <div class="px-5 py-3.5 border-b bg-slate-50 flex items-center gap-2">
                    <i data-lucide="globe" class="w-4 h-4 text-slate-500"></i>
                    <span class="text-sm font-semibold text-slate-700">Detail Lokasi & Absensi GPS</span>
                </div>
                <div class="p-5 space-y-4">
                    <div class="grid md:grid-cols-2 gap-4">
                        @foreach([
                            ['Provinsi','provinsi'],['Kabupaten / Kota','kabupaten'],
                            ['Kecamatan','kecamatan'],['Desa / Kelurahan','desa'],
                        ] as [$label,$id])
                        <div>
                            <label class="form-label">{{ $label }}</label>
                            <select id="{{ $id }}" name="{{ $id }}" class="form-input w-full">
                                <option value="">Pilih {{ $label }}</option>
                            </select>
                        </div>
                        @endforeach
                    </div>
                    <div class="grid md:grid-cols-3 gap-4">
                        <div>
                            <label class="form-label">Latitude</label>
                            <input type="text" name="latitude" value="{{ old('latitude',$profil->latitude) }}" class="form-input w-full" placeholder="-6.123456">
                        </div>
                        <div>
                            <label class="form-label">Longitude</label>
                            <input type="text" name="longitude" value="{{ old('longitude',$profil->longitude) }}" class="form-input w-full" placeholder="106.123456">
                        </div>
                        <div>
                            <label class="form-label">Radius Absensi (meter)</label>
                            <input type="number" name="radius_absensi" value="{{ old('radius_absensi',$profil->radius_absensi ?? 100) }}" min="10" max="5000" class="form-input w-full" placeholder="100">
                        </div>
                    </div>
                    <button type="button" onclick="getLocation()"
                        class="inline-flex items-center gap-2 bg-teal-700 hover:bg-teal-800 text-white text-xs font-semibold px-4 py-2 rounded-xl transition">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5"></i> Ambil Koordinat GPS Otomatis
                    </button>
                    <p class="text-xs text-slate-400">Guru hanya dapat absen jika dalam radius ini dari koordinat sekolah.</p>
                </div>
            </div>

            {{-- Visi & Misi --}}
            <div class="card overflow-hidden">
                <div class="px-5 py-3.5 border-b bg-slate-50 flex items-center gap-2">
                    <i data-lucide="target" class="w-4 h-4 text-slate-500"></i>
                    <span class="text-sm font-semibold text-slate-700">Visi & Misi</span>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <label class="form-label">Visi</label>
                        <textarea name="visi" rows="3" class="form-input w-full">{{ old('visi',$profil->visi) }}</textarea>
                    </div>
                    <div>
                        <label class="form-label">Misi</label>
                        <textarea name="misi" rows="5" class="form-input w-full">{{ old('misi',$profil->misi) }}</textarea>
                    </div>
                </div>
            </div>

        </div>

        {{-- Sticky action bar --}}
        <div x-show="editMode" x-transition
             class="sticky bottom-4 z-20 mt-5 flex justify-end gap-3 bg-white/90 backdrop-blur border border-slate-200 rounded-2xl px-5 py-3 shadow-lg">
            <button type="button" @click="editMode = false" class="btn-secondary">
                <i data-lucide="x" class="w-4 h-4"></i> Batal
            </button>
            <button type="submit" class="btn-primary">
                <i data-lucide="save" class="w-4 h-4"></i> Simpan Perubahan
            </button>
        </div>

    </div>
    </form>

    <script>
    const selectedProvinsi  = "{{ $profil->provinsi }}";
    const selectedKabupaten = "{{ $profil->kabupaten }}";
    const selectedKecamatan = "{{ $profil->kecamatan }}";
    const selectedDesa      = "{{ $profil->desa }}";

    document.addEventListener('DOMContentLoaded', function() {
        const prov = document.getElementById('provinsi');
        const kab  = document.getElementById('kabupaten');
        const kec  = document.getElementById('kecamatan');
        const des  = document.getElementById('desa');

        const API = 'https://www.emsifa.com/api-wilayah-indonesia/api';

        fetch(`${API}/provinces.json`).then(r=>r.json()).then(data=>{
            data.forEach(i=>{
                prov.innerHTML += `<option value="${i.name}" data-id="${i.id}" ${i.name==selectedProvinsi?'selected':''}>${i.name}</option>`;
            });
            if (selectedProvinsi) prov.dispatchEvent(new Event('change'));
        });

        prov.addEventListener('change', function(){
            const id = this.options[this.selectedIndex]?.dataset.id;
            if (!id) return;
            kab.innerHTML='<option value="">Pilih Kab/Kota</option>'; kec.innerHTML='<option value="">Pilih Kecamatan</option>'; des.innerHTML='<option value="">Pilih Desa</option>';
            fetch(`${API}/regencies/${id}.json`).then(r=>r.json()).then(data=>{
                data.forEach(i=>{ kab.innerHTML += `<option value="${i.name}" data-id="${i.id}" ${i.name==selectedKabupaten?'selected':''}>${i.name}</option>`; });
                if (selectedKabupaten) kab.dispatchEvent(new Event('change'));
            });
        });

        kab.addEventListener('change', function(){
            const id = this.options[this.selectedIndex]?.dataset.id;
            if (!id) return;
            kec.innerHTML='<option value="">Pilih Kecamatan</option>'; des.innerHTML='<option value="">Pilih Desa</option>';
            fetch(`${API}/districts/${id}.json`).then(r=>r.json()).then(data=>{
                data.forEach(i=>{ kec.innerHTML += `<option value="${i.name}" data-id="${i.id}" ${i.name==selectedKecamatan?'selected':''}>${i.name}</option>`; });
                if (selectedKecamatan) kec.dispatchEvent(new Event('change'));
            });
        });

        kec.addEventListener('change', function(){
            const id = this.options[this.selectedIndex]?.dataset.id;
            if (!id) return;
            des.innerHTML='<option value="">Pilih Desa</option>';
            fetch(`${API}/villages/${id}.json`).then(r=>r.json()).then(data=>{
                data.forEach(i=>{ des.innerHTML += `<option value="${i.name}" ${i.name==selectedDesa?'selected':''}>${i.name}</option>`; });
            });
        });
    });

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(pos) {
                document.querySelector('[name="latitude"]').value  = pos.coords.latitude;
                document.querySelector('[name="longitude"]').value = pos.coords.longitude;
            });
        }
    }
    </script>

</x-app-layout>

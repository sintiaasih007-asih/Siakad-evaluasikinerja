<x-app-layout>

<x-page-header
    title="Profil Sekolah"
    subtitle="Master Data / Profil Sekolah"
/>

<form
    action="{{ route('profil-sekolah.update') }}"
    method="POST"
    enctype="multipart/form-data"
>

@csrf
@method('PUT')
<div x-data="{ edit:false }">
<div class="bg-gradient-to-r from-indigo-600 via-blue-600 to-cyan-600 rounded-2xl shadow-lg p-6 text-white">

    <div class="flex flex-col md:flex-row items-center gap-6">

        {{-- Logo Sekolah --}}
        <div class="flex-shrink-0">

            @if($profil->logo_sekolah)

                <div class="w-28 h-28 bg-white rounded-2xl overflow-hidden shadow-lg flex items-center justify-center">

                    <img
                        src="{{ asset('storage/'.$profil->logo_sekolah) }}"
                        class="w-full h-full object-contain p-2"
                    >

                </div>

            @else

                <div class="w-28 h-28 bg-white/20 rounded-2xl"></div>

            @endif

        </div>

        {{-- Informasi --}}
        <div class="flex-1">

            <h2 class="text-3xl font-bold">
                {{ $profil->nama_sekolah ?: 'Profil Sekolah' }}
            </h2>

            <div class="grid md:grid-cols-2 gap-2 mt-3 text-sm">

                <p>NPSN : {{ $profil->npsn ?: '-' }}</p>

                <p>NSS : {{ $profil->nss ?: '-' }}</p>

                <p>Jenjang : {{ $profil->jenjang ?: '-' }}</p>

                <p>Akreditasi : {{ $profil->akreditasi ?: '-' }}</p>

                <p>Kurikulum : {{ $profil->kurikulum ?: '-' }}</p>

                <p>Status : {{ $profil->status_sekolah ?: '-' }}</p>

                <p>Kepala Sekolah : {{ $profil->kepala_sekolah ?: '-' }}</p>

            </div>

        </div>

    </div>

</div>

<div class="max-w-7xl mx-auto space-y-6">

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    {{-- IDENTITAS SEKOLAH --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">

        <h3 class="font-bold text-lg mb-4">
            Identitas Sekolah
        </h3>

        <div class="grid md:grid-cols-2 gap-4">

            <div>
                <label>Nama Sekolah</label>
                <input
                    type="text"
                    name="nama_sekolah"
                    value="{{ old('nama_sekolah',$profil->nama_sekolah) }}"
                    class="w-full rounded-lg border-gray-300"
                >
            </div>

            <div>
                <label>NPSN</label>
                <input
                    type="text"
                    name="npsn"
                    value="{{ old('npsn',$profil->npsn) }}"
                    class="w-full rounded-lg border-gray-300"
                >
            </div>

            <div>
                <label>NSS</label>
                <input
                    type="text"
                    name="nss"
                    value="{{ old('nss',$profil->nss) }}"
                    class="w-full rounded-lg border-gray-300"
                >
            </div>

            <div>
                <label>Jenjang</label>
                <input
                    type="text"
                    name="jenjang"
                    value="{{ old('jenjang',$profil->jenjang) }}"
                    class="w-full rounded-lg border-gray-300"
                >
            </div>

            <div>
                <label>Akreditasi</label>
                <input
                    type="text"
                    name="akreditasi"
                    value="{{ old('akreditasi',$profil->akreditasi) }}"
                    class="w-full rounded-lg border-gray-300"
                >
            </div>

            <div>
                <label>Kurikulum</label>
                <select
                    name="kurikulum"
                    class="w-full rounded-lg border-gray-300"
                >
                    <option value="">-- Pilih Kurikulum --</option>
                    @foreach(['Kurikulum 2013 (K-13)', 'Kurikulum Merdeka', 'Kurikulum 2013 Revisi', 'Kurikulum 2006 (KTSP)', 'Kurikulum Nasional', 'Kurikulum Darurat (Kondisi Khusus)'] as $kur)
                        <option value="{{ $kur }}" {{ old('kurikulum', $profil->kurikulum) == $kur ? 'selected' : '' }}>
                            {{ $kur }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label>Izin Operasional</label>
                <input
                    type="text"
                    name="izin_operasional"
                    value="{{ old('izin_operasional',$profil->izin_operasional) }}"
                    class="w-full rounded-lg border-gray-300"
                >
            </div>

        </div>

    </div>

    {{-- LOGO --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">

        <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-6">
            Logo & Dokumentasi
        </h3>

        <div class="grid md:grid-cols-3 gap-8">

            {{-- Logo Sekolah --}}
            <div>

                <label class="font-medium block mb-2">
                    Logo Sekolah
                </label>

                <input
                    type="file"
                    name="logo_sekolah"
                    class="w-full"
                >

                @if($profil->logo_sekolah)
                    <img
                        src="{{ asset('storage/'.$profil->logo_sekolah) }}"
                        class="h-32 w-32 object-contain border rounded-xl mt-4 p-2 bg-gray-50"
                    >
                @endif

            </div>

            {{-- Logo Yayasan --}}
            <div>

                <label class="font-medium block mb-2">
                    Logo Yayasan
                </label>

                <input
                    type="file"
                    name="logo_yayasan"
                    class="w-full"
                >

                @if($profil->logo_yayasan)
                    <img
                        src="{{ asset('storage/'.$profil->logo_yayasan) }}"
                        class="h-32 w-32 object-contain border rounded-xl mt-4 p-2 bg-gray-50"
                    >
                @endif

            </div>

            {{-- Kepala Sekolah --}}
            <div>

                <label class="font-medium block mb-2">
                    Foto Kepala Sekolah
                </label>

                <input
                    type="file"
                    name="foto_kepala_sekolah"
                    class="w-full"
                >

                @if($profil->foto_kepala_sekolah)
                    <img
                        src="{{ asset('storage/'.$profil->foto_kepala_sekolah) }}"
                        class="h-32 w-32 object-cover border rounded-xl mt-4"
                    >
                @endif

            </div>

        </div>

    </div>

    {{-- YAYASAN --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">

        <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-6">
            Data Yayasan
        </h3>

        <label class="block mb-2 font-medium">
            Nama Yayasan
        </label>

        <input
            type="text"
            name="nama_yayasan"
            value="{{ old('nama_yayasan',$profil->nama_yayasan) }}"
            class="w-full rounded-xl border-gray-300"
        >

    </div>

    {{-- KEPALA SEKOLAH --}}
    <div class="bg-white rounded-xl shadow border p-6">

        <h3 class="font-bold text-lg mb-4">
            Kepala Sekolah
        </h3>

        <div class="grid md:grid-cols-2 gap-4">

            <input
                type="text"
                name="kepala_sekolah"
                placeholder="Nama Kepala Sekolah"
                value="{{ $profil->kepala_sekolah }}"
                class="rounded-lg border-gray-300"
            >

            <input
                type="text"
                name="nip_kepala_sekolah"
                placeholder="NIP Kepala Sekolah"
                value="{{ $profil->nip_kepala_sekolah }}"
                class="rounded-lg border-gray-300"
            >

        </div>

    </div>

    {{-- KONTAK & ALAMAT --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">

        <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-6">
            Kontak & Alamat Sekolah
        </h3>

        <div class="grid md:grid-cols-2 gap-4">

            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">
                    Telepon
                </label>
                <input
                    type="text"
                    name="telepon"
                    value="{{ $profil->telepon }}"
                    class="w-full rounded-xl border-gray-300"
                    placeholder="Nomor Telepon"
                >
            </div>

            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">
                    WhatsApp
                </label>
                <input
                    type="text"
                    name="whatsapp"
                    value="{{ $profil->whatsapp }}"
                    class="w-full rounded-xl border-gray-300"
                    placeholder="Nomor WhatsApp"
                >
            </div>

            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">
                    Email
                </label>
                <input
                    type="email"
                    name="email"
                    value="{{ $profil->email }}"
                    class="w-full rounded-xl border-gray-300"
                    placeholder="Email Sekolah"
                >
            </div>

            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">
                    Website
                </label>
                <input
                    type="text"
                    name="website"
                    value="{{ $profil->website }}"
                    class="w-full rounded-xl border-gray-300"
                    placeholder="Website Sekolah"
                >
            </div>

        </div>

        <div class="mt-5">
            <label class="block mb-1 text-sm font-medium text-gray-700">
                Alamat Lengkap
            </label>

            <textarea
                name="alamat"
                rows="4"
                class="w-full rounded-xl border-gray-300"
                placeholder="Alamat lengkap sekolah"
            >{{ $profil->alamat }}</textarea>
        </div>

    </div>

    {{-- DETAIL LOKASI --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">

        <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-6">
            Detail Lokasi Sekolah
        </h3>

        <div class="grid md:grid-cols-2 gap-4">

            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">
                    Provinsi
                </label>

                <select
                    id="provinsi"
                    name="provinsi"
                    class="w-full rounded-xl border-gray-300"
                >
                    <option value="">
                        Pilih Provinsi
                    </option>
                </select>
            </div>

            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">
                    Kabupaten / Kota
                </label>

                <select
                    id="kabupaten"
                    name="kabupaten"
                    class="w-full rounded-xl border-gray-300"
                >
                    <option value="">
                        Pilih Kabupaten / Kota
                    </option>
                </select>
            </div>

            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">
                    Kecamatan
                </label>

                <select
                    id="kecamatan"
                    name="kecamatan"
                    class="w-full rounded-xl border-gray-300"
                >
                    <option value="">
                        Pilih Kecamatan
                    </option>
                </select>
            </div>

            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">
                    Desa / Kelurahan
                </label>

                <select
                    id="desa"
                    name="desa"
                    class="w-full rounded-xl border-gray-300"
                >
                    <option value="">
                        Pilih Desa / Kelurahan
                    </option>
                </select>
            </div>

        </div>

        <div class="grid md:grid-cols-2 gap-4 mt-5">

            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">
                    Latitude
                </label>

                <input
                    type="text"
                    name="latitude"
                    value="{{ $profil->latitude }}"
                    class="w-full rounded-xl border-gray-300"
                    placeholder="-6.123456"
                >
            </div>

            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">
                    Longitude
                </label>

                <input
                    type="text"
                    name="longitude"
                    value="{{ $profil->longitude }}"
                    class="w-full rounded-xl border-gray-300"
                    placeholder="106.123456"
                >
            </div>

        </div>

        <div class="mt-4">
            <button
                type="button"
                onclick="getLocation()"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl"
            >
                Ambil Koordinat Otomatis
            </button>
        </div>

        <div class="mt-4">
            <label class="block mb-1 text-sm font-medium text-gray-700">
                Radius Absensi (meter)
            </label>
            <div class="flex items-center gap-3">
                <input
                    type="number"
                    name="radius_absensi"
                    value="{{ old('radius_absensi', $profil->radius_absensi ?? 100) }}"
                    min="10"
                    max="5000"
                    class="w-40 rounded-xl border-gray-300"
                    placeholder="100"
                >
                <span class="text-sm text-gray-500">meter dari lokasi sekolah</span>
            </div>
            <p class="text-xs text-gray-400 mt-1">
                Guru hanya bisa absen jika berada dalam radius ini dari koordinat sekolah.
            </p>
        </div>

    </div>


    {{-- VISI MISI --}}
    <div class="bg-white rounded-xl shadow border p-6">

        <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-6">
            Visi dan Misi
        </h3>

        <textarea
            name="visi"
            rows="4"
            class="w-full rounded-lg border-gray-300 mb-3"
        >{{ $profil->visi }}</textarea>

        <textarea
            name="misi"
            rows="6"
            class="w-full rounded-lg border-gray-300"
        >{{ $profil->misi }}</textarea>

    </div>

    <div class="sticky bottom-4 z-20 flex gap-3">

        <button
            type="button"
            @click="edit = !edit"
            class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-xl shadow"
        >
            Edit Profil
        </button>

        <button
            type="submit"
            x-show="edit"
            class="bg-blue-600 hover:bg-blue-700 shadow-lg text-white px-8 py-3 rounded-xl"
        >
            Simpan Perubahan
        </button>

    </div>

</div>

</div>

</form>

<script>

const selectedProvinsi = "{{ $profil->provinsi }}";
const selectedKabupaten = "{{ $profil->kabupaten }}";
const selectedKecamatan = "{{ $profil->kecamatan }}";
const selectedDesa = "{{ $profil->desa }}";

</script>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const selectedProvinsi = "{{ $profil->provinsi }}";
    const selectedKabupaten = "{{ $profil->kabupaten }}";
    const selectedKecamatan = "{{ $profil->kecamatan }}";
    const selectedDesa = "{{ $profil->desa }}";

    let provinsi = document.getElementById('provinsi');
    let kabupaten = document.getElementById('kabupaten');
    let kecamatan = document.getElementById('kecamatan');
    let desa = document.getElementById('desa');

    // Load Provinsi
    fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json')
        .then(res => res.json())
        .then(data => {

            data.forEach(item => {

                let selected =
                    item.name == selectedProvinsi
                    ? 'selected'
                    : '';

                provinsi.innerHTML += `
                    <option
                        value="${item.name}"
                        data-id="${item.id}"
                        ${selected}
                    >
                        ${item.name}
                    </option>
                `;

            });

            if(selectedProvinsi){
                provinsi.dispatchEvent(new Event('change'));
            }

        });

    // Kabupaten
    provinsi.addEventListener('change', function(){

        const id = this.options[this.selectedIndex].dataset.id;

        kabupaten.innerHTML =
            '<option value="">Pilih Kabupaten</option>';

        kecamatan.innerHTML =
            '<option value="">Pilih Kecamatan</option>';

        desa.innerHTML =
            '<option value="">Pilih Desa</option>';

        fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${id}.json`)
        .then(res => res.json())
        .then(data => {

            data.forEach(item => {

                let selected =
                    item.name == selectedKabupaten
                    ? 'selected'
                    : '';

                kabupaten.innerHTML += `
                    <option
                        value="${item.name}"
                        data-id="${item.id}"
                        ${selected}
                    >
                        ${item.name}
                    </option>
                `;

            });

            if(selectedKabupaten){
                kabupaten.dispatchEvent(new Event('change'));
            }

        });

    });

    // Kecamatan
    kabupaten.addEventListener('change', function(){

        const id = this.options[this.selectedIndex].dataset.id;

        kecamatan.innerHTML =
            '<option value="">Pilih Kecamatan</option>';

        desa.innerHTML =
            '<option value="">Pilih Desa</option>';

        fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${id}.json`)
        .then(res => res.json())
        .then(data => {

            data.forEach(item => {

                let selected =
                    item.name == selectedKecamatan
                    ? 'selected'
                    : '';

                kecamatan.innerHTML += `
                    <option
                        value="${item.name}"
                        data-id="${item.id}"
                        ${selected}
                    >
                        ${item.name}
                    </option>
                `;

            });

            if(selectedKecamatan){
                kecamatan.dispatchEvent(new Event('change'));
            }

        });

    });

    // Desa
    kecamatan.addEventListener('change', function(){

        const id = this.options[this.selectedIndex].dataset.id;

        desa.innerHTML =
            '<option value="">Pilih Desa</option>';

        fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${id}.json`)
        .then(res => res.json())
        .then(data => {

            data.forEach(item => {

                let selected =
                    item.name == selectedDesa
                    ? 'selected'
                    : '';

                desa.innerHTML += `
                    <option
                        value="${item.name}"
                        ${selected}
                    >
                        ${item.name}
                    </option>
                `;

            });
            if(selectedDesa){
                desa.dispatchEvent(new Event('change'));
            }

        });

    });

});

</script>

<script>
function getLocation()
{
    if (navigator.geolocation)
    {
        navigator.geolocation.getCurrentPosition(function(position){

            document.querySelector('[name="latitude"]').value =
                position.coords.latitude;

            document.querySelector('[name="longitude"]').value =
                position.coords.longitude;

        });
    }
}
</script>

</x-app-layout>
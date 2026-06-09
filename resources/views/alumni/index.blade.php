<x-app-layout>

    {{-- HEADER --}}
    <x-page-header
        title="Data Alumni"
        subtitle="Dashboard / Akademik / Alumni"
    />

    <div
        x-data="{
            search: '',
            genderFilter: '',
            kelasFilter: ''
        }"
        class="space-y-6"
    >

        {{-- ========================================= --}}
        {{-- STATISTIK --}}
        {{-- ========================================= --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

            {{-- TOTAL ALUMNI --}}
            <div class="bg-white rounded-2xl shadow-sm border p-5">

                <div class="flex items-center justify-between">

                    <div>
                        <p class="text-sm text-gray-500">
                            Total Alumni
                        </p>

                        <h2 class="text-3xl font-bold text-slate-800 mt-1">
                            {{ $alumni->count() }}
                        </h2>
                    </div>

                    <div class="w-14 h-14 rounded-2xl bg-purple-100 flex items-center justify-center">

                        <i data-lucide="graduation-cap"
                           class="w-7 h-7 text-purple-600">
                        </i>

                    </div>

                </div>

            </div>

            {{-- LAKI-LAKI --}}
            <div class="bg-white rounded-2xl shadow-sm border p-5">

                <div class="flex items-center justify-between">

                    <div>
                        <p class="text-sm text-gray-500">
                            Alumni Laki-laki
                        </p>

                        <h2 class="text-3xl font-bold text-blue-700 mt-1">
                            {{ $alumni->where('jk', 'L')->count() }}
                        </h2>
                    </div>

                    <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center">

                        <i data-lucide="users"
                           class="w-7 h-7 text-blue-600">
                        </i>

                    </div>

                </div>

            </div>

            {{-- PEREMPUAN --}}
            <div class="bg-white rounded-2xl shadow-sm border p-5">

                <div class="flex items-center justify-between">

                    <div>
                        <p class="text-sm text-gray-500">
                            Alumni Perempuan
                        </p>

                        <h2 class="text-3xl font-bold text-pink-700 mt-1">
                            {{ $alumni->where('jk', 'P')->count() }}
                        </h2>
                    </div>

                    <div class="w-14 h-14 rounded-2xl bg-pink-100 flex items-center justify-center">

                        <i data-lucide="user-round"
                           class="w-7 h-7 text-pink-600">
                        </i>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================= --}}
        {{-- FILTER --}}
        {{-- ========================================= --}}
        <div class="bg-white rounded-2xl shadow-sm border p-6">

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                <div>

                    <h2 class="text-lg font-semibold text-gray-800">
                        Database Alumni Sekolah
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Menampilkan seluruh data alumni yang telah lulus dari sekolah
                    </p>

                </div>

                <div class="flex flex-col md:flex-row gap-3">

                    {{-- SEARCH --}}
                    <div class="relative">

                        <input
                            type="text"
                            x-model="search"
                            placeholder="Cari nama alumni / NIS..."
                            class="w-full md:w-72 border border-gray-300 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                        >

                        <i data-lucide="search"
                           class="w-4 h-4 absolute left-3 top-3 text-gray-400">
                        </i>

                    </div>

                    {{-- FILTER JK --}}
                    <select
                        x-model="genderFilter"
                        class="border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                    >
                        <option value="">Semua Gender</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>

                    {{-- FILTER KELAS --}}
                    <select
                        x-model="kelasFilter"
                        class="border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                    >
                        <option value="">Semua Kelas</option>

                        @foreach(
                            $alumni
                                ->filter(fn($item) => $item->kelas)
                                ->pluck('kelas.nama_kelas')
                                ->unique()
                                ->sort()
                            as $kelas
                        )

                            <option value="{{ $kelas }}">
                                {{ $kelas }}
                            </option>

                        @endforeach

                    </select>

                </div>

            </div>

        </div>


        {{-- ========================================= --}}
        {{-- TABLE --}}
        {{-- ========================================= --}}
        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full text-sm text-left">

                    <thead class="bg-slate-50 border-b text-slate-600 uppercase text-xs">

                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Alumni</th>
                            <th class="px-6 py-4">NIS</th>
                            <th class="px-6 py-4">Gender</th>
                            <th class="px-6 py-4">Kelas Terakhir</th>
                            <th class="px-6 py-4">Orang Tua</th>
                            <th class="px-6 py-4">No HP</th>
                            <th class="px-6 py-4">Status</th>
                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse($alumni as $a)

                        <tr
                            class="hover:bg-slate-50 transition"

                            x-show="
                                (
                                    '{{ strtolower($a->nama ?? '') }}'
                                    .includes(search.toLowerCase())

                                    ||

                                    '{{ strtolower($a->nis ?? '') }}'
                                    .includes(search.toLowerCase())
                                )
                                &&
                                (
                                    genderFilter == '' ||
                                    genderFilter == '{{ $a->jk }}'
                                )
                                &&
                                (
                                    kelasFilter == '' ||
                                    kelasFilter == '{{ $a->kelas->nama_kelas ?? '' }}'
                                )
                            "
                        >

                            {{-- NO --}}
                            <td class="px-6 py-4 text-gray-500">
                                {{ $loop->iteration }}
                            </td>

                            {{-- ALUMNI --}}
                            <td class="px-6 py-4">

                                <div class="flex items-center gap-3">

                                    <div class="w-11 h-11 rounded-full bg-purple-100 flex items-center justify-center text-purple-700 font-semibold">
                                        {{ strtoupper(substr($a->nama ?? 'A', 0, 1)) }}
                                    </div>

                                    <div>

                                        <h3 class="font-semibold text-gray-800">
                                            {{ $a->nama }}
                                        </h3>

                                        <p class="text-xs text-gray-500">
                                            Alumni SMP
                                        </p>

                                    </div>

                                </div>

                            </td>

                            {{-- NIS --}}
                            <td class="px-6 py-4 text-gray-600">
                                {{ $a->nis }}
                            </td>

                            {{-- JK --}}
                            <td class="px-6 py-4">

                                @if($a->jk == 'L')

                                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                                        Laki-laki
                                    </span>

                                @else

                                    <span class="px-3 py-1 bg-pink-100 text-pink-700 rounded-full text-xs font-semibold">
                                        Perempuan
                                    </span>

                                @endif

                            </td>

                            {{-- KELAS --}}
                            <td class="px-6 py-4">

                                <span class="px-3 py-1 rounded-full bg-purple-100 text-purple-700 text-xs font-medium">
                                    {{ $a->kelas->nama_kelas ?? '-' }}
                                </span>

                            </td>

                            {{-- ORANG TUA --}}
                            <td class="px-6 py-4 text-gray-700">
                                {{ $a->nama_ortu }}
                            </td>

                            {{-- NO HP --}}
                            <td class="px-6 py-4 text-gray-600">
                                {{ $a->no_hp_ortu }}
                            </td>

                            {{-- STATUS --}}
                            <td class="px-6 py-4">

                                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                    Alumni
                                </span>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="8"
                                class="text-center py-16">

                                <div class="flex flex-col items-center justify-center">

                                    <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center mb-4">

                                        <i data-lucide="graduation-cap"
                                           class="w-10 h-10 text-slate-400">
                                        </i>

                                    </div>

                                    <h3 class="text-lg font-semibold text-gray-700">
                                        Belum Ada Data Alumni
                                    </h3>

                                    <p class="text-sm text-gray-500 mt-1">
                                        Data alumni yang telah lulus akan tampil di halaman ini
                                    </p>

                                </div>

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</x-app-layout>
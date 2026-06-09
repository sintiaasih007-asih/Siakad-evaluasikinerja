{{-- resources/views/users/index.blade.php --}}

<x-app-layout>

    {{-- HEADER --}}
    <x-page-header
        title="Manajemen Users"
        subtitle="Dashboard / Users"
    />

    <div class="py-6">
        <div class="max-w-7xl mx-auto">

            {{-- ALERT --}}
            @if(session('success'))
                <div class="mb-4 bg-green-100 text-green-700 px-4 py-3 rounded-xl border border-green-200">
                    {{ session('success') }}
                </div>
            @endif

            {{-- TOP BAR --}}
            <div class="bg-white rounded-2xl shadow-sm border p-5 mb-5">

                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                    {{-- SEARCH --}}
                    <form method="GET" action="{{ route('users.index') }}" class="w-full md:w-96">
                        <input type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari nama / email..."
                            class="w-full rounded-xl border-gray-300 focus:ring-2 focus:ring-indigo-500">
                    </form>

                    {{-- BUTTON --}}
                    <a href="{{ route('users.create') }}"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-semibold text-center">
                        + Tambah User
                    </a>

                </div>

            </div>

            {{-- ================================================= --}}
            {{-- TABLE PER ROLE --}}
            {{-- ================================================= --}}

            @php
                $roles = [
                    'admin' => 'Admin',
                    'guru' => 'Guru',
                    'guru&wali_kelas' => 'Guru & Wali Kelas',
                    'kepala_sekolah' => 'Kepala Sekolah',
                    'orang_tua' => 'Orang Tua'
                ];
            @endphp

            @foreach($roles as $key => $label)

            @php
                $filteredUsers = $users->where('role', $key);
            @endphp

            <div class="bg-white rounded-2xl shadow-sm border overflow-hidden mb-6">

                {{-- HEADER ROLE --}}
                <div class="px-6 py-4 border-b bg-gray-50 flex items-center justify-between">

                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">
                            {{ $label }}
                        </h2>

                        <p class="text-sm text-gray-500">
                            Total {{ $filteredUsers->count() }} user
                        </p>
                    </div>

                    <span class="px-3 py-1 text-xs rounded-full bg-indigo-100 text-indigo-700">
                        {{ $label }}
                    </span>

                </div>

                {{-- TABLE --}}
                <div class="overflow-x-auto">

                    <table class="w-full text-sm">

                        <thead class="bg-gray-50 text-gray-600">
                            <tr>
                                <th class="p-4 text-left">No</th>
                                <th class="p-4 text-left">Nama</th>
                                <th class="p-4 text-left">Email</th>
                                <th class="p-4 text-left">Role</th>
                                <th class="p-4 text-left">Status</th>
                                <th class="p-4 text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($filteredUsers as $u)

                                <tr class="border-t hover:bg-gray-50">

                                    <td class="p-4">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="p-4 font-medium text-gray-800">
                                        {{ $u->name }}
                                    </td>

                                    <td class="p-4 text-gray-600">
                                        {{ $u->email }}
                                    </td>

                                    <td class="p-4">

                                        @if($u->role == 'admin')
                                            <span class="px-3 py-1 text-xs rounded-full bg-indigo-100 text-indigo-700">
                                                Admin
                                            </span>
                                        @elseif($u->role == 'guru')
                                            <span class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-700">
                                                Guru
                                            </span>
                                        @elseif($u->role == 'kepala_sekolah')
                                            <span class="px-3 py-1 text-xs rounded-full bg-cyan-100 text-cyan-700">
                                                Kepala Sekolah
                                            </span>
                                        @elseif($u->role == 'guru&wali_kelas')
                                            <span class="px-3 py-1 text-xs rounded-full bg-sky-100 text-sky-700">
                                                Guru & Wali Kelas
                                            </span>
                                        @else
                                            <span class="px-3 py-1 text-xs rounded-full bg-purple-100 text-purple-700">
                                                Orang Tua
                                            </span>
                                        @endif

                                    </td>

                                    <td class="p-4">

                                        @if($u->is_active)
                                            <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-700">
                                                Active
                                            </span>
                                        @else
                                            <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-700">
                                                Inactive
                                            </span>
                                        @endif

                                    </td>

                                    <td class="p-4">

                                        <div class="flex justify-center gap-3">

                                            {{-- Toggle --}}
                                            <a href="{{ route('users.toggle', $u->id) }}"
                                                class="text-yellow-600 hover:text-yellow-700 font-medium">
                                                Toggle
                                            </a>

                                            {{-- Reset --}}
                                            <a href="{{ route('users.resend', $u->id) }}"
                                                class="text-blue-600 hover:text-blue-700 font-medium">
                                                Reset
                                            </a>

                                            {{-- Delete --}}
                                            <form method="POST"
                                                action="{{ route('users.destroy', $u->id) }}"
                                                onsubmit="return confirm('Hapus user ini?')">

                                                @csrf
                                                @method('DELETE')

                                                <button class="text-red-600 hover:text-red-700 font-medium">
                                                    Hapus
                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="7" class="text-center py-10 text-gray-400">
                                        Data {{ strtolower($label) }} belum tersedia
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

            @endforeach

            {{-- PAGINATION --}}
            <div class="bg-white rounded-2xl shadow-sm border p-4">
                {{ $users->links() }}
            </div>

        </div>
    </div>

</x-app-layout>
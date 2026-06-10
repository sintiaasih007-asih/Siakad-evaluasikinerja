<x-app-layout>

    <x-page-header title="Manajemen Users" subtitle="Kelola akun pengguna sistem"/>

    @if(session('success'))
    <div class="alert-success mb-5"><i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i>{{ session('success') }}</div>
    @endif

    {{-- Toolbar --}}
    <div class="card p-4 mb-5 flex flex-col sm:flex-row sm:items-center gap-3">
        <form method="GET" action="{{ route('users.index') }}" class="flex-1 max-w-sm">
            <div class="relative">
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nama atau email..."
                    class="form-input pl-9 w-full">
            </div>
        </form>
        <a href="{{ route('users.create') }}" class="btn-primary self-start sm:self-auto ml-auto">
            <i data-lucide="user-plus" class="w-4 h-4"></i> Tambah User
        </a>
    </div>

    {{-- Stat ringkasan --}}
    @php
        $roleCfg = [
            'admin'           => ['Admin',             'bg-violet-100 text-violet-700',  'bg-violet-800'],
            'guru'            => ['Guru',               'bg-blue-100 text-blue-700',      'bg-blue-800'],
            'guru&wali_kelas' => ['Guru & Wali Kelas', 'bg-sky-100 text-sky-700',        'bg-sky-700'],
            'kepala_sekolah'  => ['Kepala Sekolah',    'bg-teal-100 text-teal-700',      'bg-teal-700'],
            'orang_tua'       => ['Orang Tua',          'bg-amber-100 text-amber-700',    'bg-amber-600'],
        ];
    @endphp

    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-6">
        @foreach($roleCfg as $key => [$label, $badge, $headerBg])
        @php $cnt = $users->where('role', $key)->count(); @endphp
        <div class="card p-4 text-center">
            <p class="stat-card-label">{{ $label }}</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $cnt }}</p>
        </div>
        @endforeach
    </div>

    {{-- Tabel per role --}}
    @foreach($roleCfg as $role => [$label, $badge, $headerBg])
    @php $filteredUsers = $users->where('role', $role); @endphp

    <div class="mb-6">
        {{-- Section label --}}
        <div class="flex items-center gap-3 mb-3">
            <div class="w-1 h-6 rounded-full {{ $headerBg }}"></div>
            <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wide">{{ $label }}</h3>
            <span class="badge {{ $badge }} ml-auto">{{ $filteredUsers->count() }} user</span>
        </div>

        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="{{ $headerBg }} text-white">
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide w-10">#</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide">Nama</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide hide-mobile">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide">Role</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide">Status</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($filteredUsers as $u)
                        <tr class="hover:bg-slate-50/70 transition-colors duration-150">
                            <td class="px-4 py-3 text-slate-400 text-xs">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg {{ $headerBg }} text-white text-xs font-bold flex items-center justify-center shrink-0 opacity-90">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-slate-800 truncate">{{ $u->name }}</p>
                                        <p class="text-[10px] text-slate-400 truncate hide-mobile">{{ $u->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-slate-500 text-xs hide-mobile">{{ $u->email }}</td>
                            <td class="px-4 py-3">
                                <span class="badge {{ $badge }}">{{ $label }}</span>
                            </td>
                            <td class="px-4 py-3">
                                @if($u->is_active)
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 bg-emerald-100 px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-rose-700 bg-rose-100 px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Nonaktif
                                </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                    {{-- Toggle aktif/nonaktif --}}
                                    <a href="{{ route('users.toggle', $u->id) }}"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition
                                               {{ $u->is_active ? 'bg-amber-50 text-amber-700 hover:bg-amber-100 border border-amber-200' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200' }}">
                                        <i data-lucide="{{ $u->is_active ? 'toggle-right' : 'toggle-left' }}" class="w-3 h-3"></i>
                                        {{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </a>
                                    {{-- Reset password --}}
                                    <a href="{{ route('users.resend', $u->id) }}"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold
                                               bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 transition">
                                        <i data-lucide="key" class="w-3 h-3"></i> Reset
                                    </a>
                                    {{-- Hapus --}}
                                    <form method="POST" action="{{ route('users.destroy', $u->id) }}"
                                          onsubmit="return confirm('Hapus user {{ addslashes($u->name) }}? Tindakan ini tidak bisa dibatalkan.')">
                                        @csrf @method('DELETE')
                                        <button class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold
                                                       bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 transition">
                                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-slate-400 text-sm">
                                Belum ada user dengan role {{ strtolower($label) }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endforeach

    {{-- Pagination --}}
    @if($users->hasPages())
    <div class="card p-4 mt-2">
        {{ $users->links() }}
    </div>
    @endif

</x-app-layout>

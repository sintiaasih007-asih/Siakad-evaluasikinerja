<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Edit Kelas</h2>
    </x-slot>

    <div class="p-6">
        <form action="{{ route('kelas.update', $kelas->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Nama Kelas</label>
                <input type="text" name="nama_kelas" value="{{ $kelas->nama_kelas }}" class="w-full border p-2">
            </div>

            <div class="mb-3">
                <label>Wali Kelas</label>
                <select name="guru_id" class="w-full border p-2">
                    @foreach($guru as $g)
                        <option value="{{ $g->id }}" {{ $kelas->guru_id == $g->id ? 'selected' : '' }}>
                            {{ $g->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Tahun Ajaran</label>
                <select name="tahun_ajaran_id" class="w-full border p-2">
                    @foreach($tahun as $t)
                        <option value="{{ $t->id }}" {{ $kelas->tahun_ajaran_id == $t->id ? 'selected' : '' }}>
                            {{ $t->tahun }} - {{ $t->semester }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button class="bg-blue-500 text-white px-4 py-2 rounded">
                Update
            </button>
        </form>
    </div>
</x-app-layout>
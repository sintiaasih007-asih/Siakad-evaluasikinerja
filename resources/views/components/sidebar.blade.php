<div class="w-64 bg-blue-900 text-white min-h-screen p-4">

    <h2 class="text-2xl font-bold mb-6">SI Akademik</h2>

    <ul>
        <li class="mb-3">
            <a href="{{ route('dashboard') }}" class="block hover:bg-blue-700 p-2 rounded">
                Dashboard
            </a>
        </li>

        <li class="mb-3">
            <a href="{{ route('siswa.index') }}" class="block hover:bg-blue-700 p-2 rounded">
                Data Siswa
            </a>
        </li>

        <li class="mb-3">
            <a href="{{ route('guru.index') }}" class="block hover:bg-blue-700 p-2 rounded">
                Data Guru
            </a>
        </li>

        <li class="mb-3">
            <a href="{{ route('kelas.index') }}" class="block hover:bg-blue-700 p-2 rounded">
                Kelas
            </a>
        </li>

        <li class="mb-3">
            <a href="{{ route('mapel.index') }}" class="block hover:bg-blue-700 p-2 rounded">
                Mata Pelajaran
            </a>
        </li>

        <li class="mb-3">
            <a href="{{ route('jadwal.index') }}" class="block hover:bg-blue-700 p-2 rounded">
                Jadwal
            </a>
        </li>
    </ul>

</div>
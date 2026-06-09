<table>

    {{-- KOP SEKOLAH --}}
    <tr>
        <td colspan="7" align="center">
            {{ strtoupper($profil->nama_yayasan ?? '-') }}
        </td>
    </tr>

    <tr>
        <td colspan="7" align="center">
            {{ strtoupper($profil->nama_sekolah ?? '-') }}
        </td>
    </tr>

    <tr>
        <td colspan="7" align="center">
            {{ $profil->alamat ?? '-' }}
        </td>
    </tr>

    <tr>
        <td colspan="7" align="center">
            Telp :
            {{ $profil->telepon ?? '-' }}
            |
            Email :
            {{ $profil->email ?? '-' }}
        </td>
    </tr>

    <tr></tr>

    {{-- JUDUL --}}
    <tr>
        <td colspan="7" align="center">
            LAPORAN ABSENSI SISWA
        </td>
    </tr>

    <tr></tr>

    {{-- INFORMASI --}}

    <tr>
        <td>Periode</td>
        <td colspan="6">
            {{ $periode }}
        </td>
    </tr>

    <tr>
        <td>Total Data</td>
        <td colspan="6">
            {{ $absensi->count() }} Absensi
        </td>
    </tr>

    <tr></tr>

    {{-- RINGKASAN --}}
    <tr>
        <td>Hadir</td>
        <td>{{ $hadir }}</td>

        <td>Izin</td>
        <td>{{ $izin }}</td>

        <td>Sakit</td>
        <td>{{ $sakit }}</td>

        <td>Alpa</td>
        <td>{{ $alpa }}</td>
    </tr>

    <tr></tr>

    @php
        $grouped = $absensi->groupBy(function ($item) {

            return
                ($item->absensi->jadwal->mapel->nama_mapel ?? '-') .
                '|' .
                ($item->absensi->guru->nama ?? '-');

        });
    @endphp

    @foreach($grouped as $key => $items)

        @php
            [$mapel, $guru] = explode('|', $key);
        @endphp

        {{-- INFORMASI MAPEL DAN GURU --}}
        <tr>
            <td colspan="7">
                <strong>Mata Pelajaran :</strong> {{ $mapel }}
            </td>
        </tr>

        <tr>
            <td colspan="7">
                <strong>Guru :</strong> {{ $guru }}
            </td>
        </tr>

        <tr></tr>

        {{-- HEADER TABEL --}}
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Jam</th>
            <th>Nama Siswa</th>
            <th>Kelas</th>
            <th>Status</th>
        </tr>

        @foreach($items as $item)

        <tr>

            <td>
                {{ $loop->iteration }}
            </td>

            <td>
                {{ \Carbon\Carbon::parse($item->absensi->tanggal)->format('d-m-Y') }}
            </td>

            <td>
                {{ $item->absensi->jadwal->jam_masuk ?? '-' }}
                -
                {{ $item->absensi->jadwal->jam_selesai ?? '-' }}
            </td>

            <td>
                {{ $item->siswa->nama ?? '-' }}
            </td>

            <td>
                {{ $item->siswa->kelas->nama_kelas ?? '-' }}
            </td>

            <td>
                {{ ucfirst($item->status) }}
            </td>

        </tr>

        @endforeach

        <tr></tr>
        <tr></tr>

    @endforeach

</table>
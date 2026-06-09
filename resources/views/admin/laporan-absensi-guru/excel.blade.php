<table>

    {{-- KOP SEKOLAH --}}
    <tr>
        <td colspan="7" align="center">
            {{ strtoupper($profil->nama_yayasan) }}
        </td>
    </tr>

    <tr>
        <td colspan="7" align="center">
            {{ strtoupper($profil->nama_sekolah) }}
        </td>
    </tr>

    <tr>
        <td colspan="7" align="center">
            {{ $profil->alamat }}
        </td>
    </tr>

    <tr>
        <td colspan="7" align="center">
            Telp :
            {{ $profil->telepon }}
            |
            Email :
            {{ $profil->email }}
        </td>
    </tr>

    <tr></tr>

    {{-- JUDUL --}}
    <tr>
        <td colspan="7" align="center">
            LAPORAN ABSENSI GURU
        </td>
    </tr>

    <tr></tr>

    {{-- RINGKASAN --}}
    <tr>
        <td>Hadir</td>
        <td>{{ $hadir }}</td>

        <td>Terlambat</td>
        <td>{{ $terlambat }}</td>

        <td>Izin</td>
        <td>{{ $izin }}</td>
    </tr>

    <tr>
        <td>Sakit</td>
        <td>{{ $sakit }}</td>

        <td>Alpa</td>
        <td>{{ $alpa }}</td>
    </tr>

    <tr></tr>

    {{-- HEADER TABEL --}}
    <tr>

        <th>No</th>
        <th>Tanggal</th>
        <th>Nama Guru</th>
        <th>Jam Masuk</th>
        <th>Jam Pulang</th>
        <th>Status</th>
        <th>Lokasi</th>

    </tr>

    @foreach($absensi as $item)

    <tr>

        <td>{{ $loop->iteration }}</td>

        <td>
            {{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}
        </td>

        <td>
            {{ $item->guru->nama ?? '-' }}
        </td>

        <td>{{ $item->jam_masuk }}</td>

        <td>{{ $item->jam_pulang }}</td>

        <td>{{ $item->status }}</td>

        <td>
            {{ $item->latitude }},
            {{ $item->longitude }}
        </td>

    </tr>

    @endforeach

</table>
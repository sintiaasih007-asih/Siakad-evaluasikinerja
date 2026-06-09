<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Absensi Guru</title>

    <style>
        body{
            font-family: 'Times New Roman', Times, serif;
            font-size: 12px;
            color: #333;
        }

        .header{
            width:100%;
            border-bottom:3px solid #000;
            padding-bottom:10px;
            margin-bottom:20px;
        }

        .header{
            width:100%;
            margin-bottom:15px;
        }

        .kop-table{
            width:100%;
            border-collapse:collapse;
        }

        .kop-table td{
            vertical-align:middle;
        }

        .logo-kiri{
            width:90px;
            height:120px;
            object-fit:contain;
        }

        .logo-kanan{
            width:100px;
            height:100px;
            object-fit:contain;
        }

        .school{
            text-align:center;
            padding:0 10px;
        }

        .school .yayasan{
            font-size:16px;
            font-weight:bold;
            margin-bottom:4px;
        }

        .school .nama-sekolah{
            font-size:26px;
            font-weight:bold;
            margin-bottom:5px;
        }

        .school .info{
            font-size:12px;
            line-height:1.5;
            margin:2px 0;
        }

        .garis1{
            border-bottom:3px solid #000;
            margin-top:8px;
        }

        .garis2{
            border-bottom:1px solid #000;
            margin-top:2px;
        }

        .logo{
            width:90px;
            float:left;
        }

        .school{
            text-align:center;
        }

        .school h3{
            margin:0;
            font-size:22px;
        }

        .school h2{
            margin:5px 0;
            font-size:16px;
        }

        .school p{
            margin:0;
            font-size:12px;
        }

        .clearfix{
            clear:both;
        }

        .title{
            text-align:center;
            margin-top:20px;
            margin-bottom:20px;
        }

        .title h3{
            margin:0;
            font-size:18px;
        }

        .info{
            margin-bottom:20px;
        }

        .info table{
            width:100%;
        }

        .info td{
            padding:4px;
        }

        .summary{
            margin-bottom:20px;
        }

        .summary table{
            width:100%;
            border-collapse:collapse;
        }

        .summary th{
            background:#f1f5f9;
            padding:8px;
            border:1px solid #ddd;
        }

        .summary td{
            text-align:center;
            padding:8px;
            border:1px solid #ddd;
        }

        .data-table{
            width:100%;
            border-collapse:collapse;
        }

        .data-table th{
            background:#1e40af;
            color:white;
            padding:8px;
            border:1px solid #ddd;
        }

        .data-table td{
            padding:7px;
            border:1px solid #ddd;
        }

        .data-table tr:nth-child(even){
            background:#f9fafb;
        }

        .footer{
            margin-top:50px;
            width:100%;
        }

        .signature{
            width:250px;
            float:right;
            text-align:center;
        }
    </style>

</head>
<body>


    {{-- HEADER --}}
<div class="header">

    <table class="kop-table">

        <tr>

            {{-- LOGO SEKOLAH --}}
            <td width="15%" align="center">

                @if($profil && $profil->logo_sekolah)
                    <img
                        src="{{ public_path('storage/'.$profil->logo_sekolah) }}"
                        class="logo-kiri"
                    >
                @endif

            </td>

            {{-- IDENTITAS SEKOLAH --}}
            <td width="70%" class="school">

                <div class="yayasan">
                    {{ strtoupper($profil->nama_yayasan ?? '-') }}
                </div>

                <div class="nama-sekolah">
                    {{ strtoupper($profil->nama_sekolah ?? '-') }}
                </div>

                <div class="info">

                    NPSN :
                    {{ $profil->npsn ?? '-' }}

                    &nbsp; | &nbsp;

                    Akreditasi :
                    {{ $profil->akreditasi ?? '-' }}

                    &nbsp; | &nbsp;

                    Izin Operasional :
                    {{ $profil->izin_operasional ?? '-' }}

                </div>

                <div class="info">

                    {{ $profil->alamat ?? '-' }}

                    <!-- @if($profil->desa)
                        , {{ $profil->desa }}
                    @endif

                    @if($profil->kecamatan)
                        , Kecamatan {{ $profil->kecamatan }}
                    @endif

                    @if($profil->kabupaten)
                        , Kabupaten {{ $profil->kabupaten }}
                    @endif

                    @if($profil->provinsi)
                        , {{ $profil->provinsi }}
                    @endif -->

                </div>

                <div class="info">

                    Telp :
                    {{ $profil->telepon ?? '-' }}

                    &nbsp; | &nbsp;

                    Email :
                    {{ $profil->email ?? '-' }}

                    @if($profil->website)
                        &nbsp; | &nbsp;
                        Website :
                        {{ $profil->website }}
                    @endif

                </div>

            </td>

            {{-- LOGO YAYASAN --}}
            <td width="15%" align="center">

                @if($profil && $profil->logo_yayasan)
                    <img
                        src="{{ public_path('storage/'.$profil->logo_yayasan) }}"
                        class="logo-kanan"
                    >
                @endif

            </td>

        </tr>

    </table>

    <div class="garis1"></div>
    <div class="garis2"></div>

</div>

    {{-- JUDUL --}}
    <div class="title">
        <h3>LAPORAN ABSENSI GURU</h3>
        <p>Tahun Ajaran {{ $tahunAjaran ?? '2025/2026' }}</p>
    </div>

    {{-- INFORMASI --}}
    <div class="info">

        <table>
            <tr>
                <td width="150">Periode Laporan</td>
                <td>: {{ $periode }}</td>
            </tr>

            <tr>
                <td>Tanggal Cetak</td>
                <td>: {{ now()->format('d F Y H:i') }}</td>
            </tr>

            <tr>
                <td>Total Data</td>
                <td>: {{ $absensi->count() }} Absensi</td>
            </tr>
        </table>

    </div>

    {{-- RINGKASAN --}}
    <div class="summary">

        <table>

            <tr>
                <th>Hadir</th>
                <th>Terlambat</th>
                <th>Izin</th>
                <th>Sakit</th>
                <th>Alpa</th>
            </tr>

            <tr>
                <td>{{ $hadir ?? 0 }}</td>
                <td>{{ $terlambat ?? 0 }}</td>
                <td>{{ $izin ?? 0 }}</td>
                <td>{{ $sakit ?? 0 }}</td>
                <td>{{ $alpa ?? 0 }}</td>
            </tr>

        </table>

    </div>

    {{-- DATA ABSENSI --}}
    <table class="data-table">

        <thead>

            <tr>
                <th width="5%">No</th>
                <th width="12%">Tanggal</th>
                <th width="25%">Nama Guru</th>
                <th width="12%">Jam Masuk</th>
                <th width="12%">Jam Pulang</th>
                <th width="15%">Status</th>
                <th width="19%">Lokasi</th>
            </tr>

        </thead>

        <tbody>

        @forelse($absensi as $item)

            <tr>

                <td align="center">
                    {{ $loop->iteration }}
                </td>

                <td>
                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}
                </td>

                <td>
                    {{ $item->guru->nama ?? '-' }}
                </td>

                <td align="center">
                    {{ $item->jam_masuk }}
                </td>

                <td align="center">
                    {{ $item->jam_pulang }}
                </td>

                <td align="center">
                    {{ $item->status }}
                </td>

                <td>
                    {{ $item->latitude }},
                    {{ $item->longitude }}
                </td>

            </tr>

        @empty

            <tr>
                <td colspan="7" align="center">
                    Tidak ada data absensi
                </td>
            </tr>

        @endforelse

        </tbody>

    </table>

    {{-- TANDA TANGAN --}}
    <div class="footer">

        <div class="signature">

            <p>
                {{ $kota ?? 'Garut' }},
                {{ now()->format('d F Y') }}
            </p>

            <p>Kepala Sekolah</p>

            <br><br><br><br>

            <strong>
                {{ $profil->kepala_sekolah ?? '-' }}
            </strong>

            <br>

            NIP.
            {{ $profil->nip_kepala_sekolah ?? '-' }}

        </div>

    </div>

</body>
</html>
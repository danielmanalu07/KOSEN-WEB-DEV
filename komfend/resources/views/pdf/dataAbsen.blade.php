<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Surat Dinas - Data Absen</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }

        .text-center {
            text-align: center;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table,
        .table th,
        .table td {
            border: 1px solid black;
            padding: 8px;
        }

        .table th {
            background-color: #f2f2f2;
        }

        .logo {
            width: 100px;
        }

        /* Status styles */
        .status-hadir {
            color: green;
            font-weight: bold;
        }

        .status-terlambat {
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="text-center">
            <img src="{{ public_path('assets/images/logo-Diskominfo-.jpg') }}" alt="Diskominfo" width="250px"
                class="img-fluid">
            <h2>Dinas Komunikasi dan Informatika</h2>
            <h5>Tobasa/Balige</h5>
        </div>

        <div class="text-center">
            <h3>Data Absensi Pegawai</h3>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pegawai</th>
                    <th>Email</th>
                    <th>Judul Absen</th>
                    <th>Tanggal Absen</th>
                    <th>Status Absen</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $key => $absen)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $absen->karyawan->nama }}</td>
                        <td>{{ $absen->karyawan->email }}</td>
                        <td>{{ $absen->absensi->judul }}</td>
                        <td>{{ $absen->tanggal }}</td>
                        <td>
                            @if ($absen->status === 'Hadir')
                                <span class="status-hadir">{{ $absen->status }}</span>
                            @elseif($absen->status === 'Terlambat')
                                <span class="status-terlambat">{{ $absen->status }}</span>
                            @else
                                {{ $absen->status }}
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak Ada Data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>

</html>

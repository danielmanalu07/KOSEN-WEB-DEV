<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Surat Resmi - Data Absensi Dinas Komunikasi dan Informatika Balige</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            margin: 0 auto;
            padding: 20px;
        }

        .text-center {
            text-align: center;
        }

        .kop-surat {
            border-bottom: 3px solid black;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .kop-surat img {
            float: left;
            width: 60px;
            max-width: 100%;
            margin-right: 10px;
            margin-left: 10px;
        }

        .kop-surat h2,
        .kop-surat h3,
        .kop-surat p {
            margin: 0;
            text-align: center;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .table th,
        .table td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }

        .table th {
            background-color: #f2f2f2;
        }

        .status-hadir {
            color: green;
            font-weight: bold;
        }

        .status-terlambat {
            color: red;
            font-weight: bold;
        }

        .signature {
            margin-top: 50px;
            text-align: right;
        }

        .signature p {
            margin: 5px 0;
        }

        .content {
            margin-top: 10px;
            text-align: justify;
            line-height: 1.6;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Kop Surat -->
        <div class="kop-surat">
            <img src="{{ public_path('assets/images/logodiskominfo.png') }}" alt="Logo Dinas Kominfo">
            <h2>PEMERINTAH KABUPATEN TOBASA</h2>
            <h3>DINAS KOMUNIKASI DAN INFORMATIKA</h3>
            <p>Jl. Tarutung Km 2 Soposurung , Balige, Indonesia, Sumatera Utara</p>
            <p>Email: tobakominfo@gmail.com | Telepon: 0811-6100-112</p>
        </div>

        <!-- Judul Surat -->
        <div class="text-center">
            <h3><u>DATA ABSENSI PEGAWAI</u></h3>
        </div>

        <!-- Paragraf Pengantar -->
        <div class="content">
            <p>
                Berdasarkan hasil rekapitulasi data absensi pegawai di lingkungan Dinas Komunikasi dan Informatika
                Kabupaten
                Tobasa, berikut ini kami sampaikan laporan kehadiran pegawai pada periode yang telah ditentukan. Data
                ini
                digunakan sebagai acuan untuk evaluasi kinerja pegawai.
            </p>
        </div>

        <!-- Tabel Data Absensi -->
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pegawai</th>
                    <th>Email</th>
                    <th>Jabatan</th>
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
                    <td>
                        @if (!empty($absen->karyawan->email))
                            {{ $absen->karyawan->email }}
                        @else
                            <span style="color: black; font-weight: bold;">Email tidak terdaftar</span>
                        @endif
                    </td>
                    <td>{{ $absen->karyawan->jabatan }}</td>
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
                    <td colspan="7" class="text-center">Tidak Ada Data</td>
                </tr>
                @endforelse
            </tbody>
            
        </table>

        <!-- Penutup dan Tanda Tangan -->
        <div class="signature">
            <p>Balige, {{ date('d F Y') }}</p>
            <p>Kepala Dinas Komunikasi dan Informatika</p>
            <br><br>
            <p><strong><u>Sesmon Butar Butar</u></strong></p>
            <p>NIP: 197310232007011004</p>
        </div>
    </div>
</body>

</html>

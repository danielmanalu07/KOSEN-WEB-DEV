<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Karyawan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center; /* Memusatkan secara horizontal */
            align-items: center; /* Memusatkan secara vertikal */
            height: 100vh; /* Mengatur tinggi body agar memenuhi layar */
            margin: 0; /* Menghilangkan margin default */
        }

        .kartu {
            width: 300px; /* Lebar kartu */
            border: 2px solid black;
            border-style: double;
            padding: 20px;
            margin: 0 auto; /* Memastikan kartu terpusat dalam PDF */
            text-align: center; /* Memusatkan teks dalam kartu */
        }

        .name {
            font-weight: bold;
            font-size: 16px;
        }

        .notelp {
            font-weight: bold;
            margin: 5px 0;
        }

        .tgl-lahir {
            margin: 5px 0;
        }

        .photo {
            width: 100px;
            height: 100px;
        }

        .qr-code {
            width: 150px;
            height: 150px;
        }
    </style>
</head>

<body>
    <div class="kartu">
        <div class="photo">
            <img src="data:image/jpeg;base64,{!! base64_encode(file_get_contents(public_path('assets/images/logo.jpeg'))) !!}" alt="Logo" class="photo">
        </div>

        <div class="name">{{ $data->nama }}</div>
        <div class="notelp">No Telepon: {{ $data->phone }}</div>
        <div class="tgl-lahir">Tanggal Lahir: {{ \Carbon\Carbon::parse($data->tanggal_lahir)->format('d M Y') }}</div>
        <div class="qr-code">
            <img src="data:image/png;base64,{!! base64_encode(file_get_contents(public_path($data->qrcode))) !!}" alt="QR Code" class="qr-code">
        </div>
    </div>
</body>
</html>

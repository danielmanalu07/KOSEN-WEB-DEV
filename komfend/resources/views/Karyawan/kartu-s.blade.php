<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Karyawan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }

        .kartu {
            width: 300px;
            border: 4px double #000;
            padding: 20px;
            display: flex;
            margin-left: 25%;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .logo {
            width: 80px;
            height: 80px;
            margin-bottom: 15px;
        }

        .name {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 10px;
        }

        .info {
            font-size: 12px;
            margin-bottom: 5px;
            color: #555;
        }

        .qr-code {
            width: 150px;
            height: 150px;
            margin-top: 15px;
        }

        img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>

<body>
    <div class="kartu">
        <img src="{{ public_path('assets/images/logo.jpeg') }}" alt="Logo" class="logo">
        <div class="name">{{ $data->nama }}</div>
        <div class="info">No Telepon: {{ $data->phone }}</div>
        <div class="info">Tanggal Lahir: {{ \Carbon\Carbon::parse($data->tanggal_lahir)->format('d M Y') }}</div>
        <img src="{{ public_path($data->qrcode) }}" alt="QR Code" class="qr-code">
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</body>

</html>

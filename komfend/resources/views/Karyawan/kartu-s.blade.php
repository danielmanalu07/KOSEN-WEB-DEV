<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Karyawan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .kartu {
            width: 300px;
            border: 2px solid black;
            border-style: double;
            padding: 20px;
            margin: auto;
            text-align: center;
        }
        .name {
            font-weight: bold;
            font-size: 16px;
        }
        .status {
            font-weight: bold;
        }
        .photo {
            width: 80px;
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
        <img src="{{ asset($data->photo) }}" alt="Foto Karyawan" class="photo">
        <div class="name">{{ $data->nama }}</div>
        <div class="status">Status: {{ $data->status }}</div>
        <div>ID: {{ $data->id }}</div>
        <div>
            <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(150)->generate($qr)) !!} " alt="QR Code" class="qr-code">
        </div>
    </div>
</body>
</html>
    
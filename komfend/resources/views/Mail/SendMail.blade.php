<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Informasi Akun</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .user-info {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .qr-code-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .qr-code {
            background-color: #ffffff;
            padding: 20px;
            display: inline-block;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .qr-code img {
            max-width: 100%;
            height: auto;
        }

        .download-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: skyblue;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .download-button:hover {
            background-color: #5cacee;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>Informasi Akun</h2>
        </div>
        <div class="user-info">
            <p><strong>Nama:</strong> {{ $user->nama }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Nomor Telepon:</strong> {{ $user->phone }}</p>
            <p><strong>Tanggal Lahir:</strong> {{ $user->tanggal_lahir }}</p>
        </div>
        <div class="qr-code-container">
            <a href="{{ asset($user->qrcode) }}" class="download-button w-75"
                download="qrcode_{{ $user->nama }}">Lihat QR
                Code</a>
        </div>
    </div>
</body>

</html>

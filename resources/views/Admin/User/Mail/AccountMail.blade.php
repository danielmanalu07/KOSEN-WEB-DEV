<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Informasi Akun</title>
</head>

<body>
    <h1>Selamat {{ $user->nama }}, Akun Anda Berhasil Dibuat</h1>
    <ul>
        <li>Nama : {{ $user->nama }}</li>
        <li>Email : {{ $user->email }}</li>
        <li>Username : {{ $user->username }}</li>
        <li>Umur : {{ $user->umur }}</li>
        <li>Tanggal Lahir : {{ $user->tanggal_lahir }}</li>
        <li>No Telephone : {{ $user->phone }}</li>
        <li>Status Akun : {{ $user->status }}</li>
        <li>Posisi : {{ $user->role }}</li>
    </ul>

    <h1>Informasi Akun</h1>
    <b>Email : {{ $user->email }}</b> <br>
    <b>Password : {{ $user->password }}</b>
</body>

</html>

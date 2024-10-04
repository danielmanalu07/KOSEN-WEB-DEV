@extends('components.layout')
@section('content')
<main class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1>Detail Pegawai</h1>

            <p><strong>Nama:</strong> {{ $user->nama }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Umur:</strong> {{ $user->umur }}</p>
            <p><strong>Tanggal Lahir:</strong> {{ $user->tanggal_lahir }}</p>
            <p><strong>Phone:</strong> {{ $user->phone }}</p>
            <p><strong>Status:</strong> {{ $user->status }}</p>
            <p><strong>Role:</strong> {{ $user->role }}</p>

            <p><strong>QR Code:</strong></p>
            <img src="{{ asset($user->qrcode) }}" alt="QR Code" width="200px">
            <br>

            <a href="{{ route('users.index') }}" class="btn btn-primary">Kembali</a>
            <a href="{{ route('users.downloadQrCodePDF', $user->id) }}" class="btn btn-danger">Download PDF</a>
        </div>
    </div>
</main>
@endsection

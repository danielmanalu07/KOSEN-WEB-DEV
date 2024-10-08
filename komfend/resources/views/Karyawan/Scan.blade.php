@extends('components.layout')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                Detail Karyawan
            </div>
            <div class="card-body">
                <p><strong>Nama:</strong> {{ $karyawan->nama }}</p>
                <p><strong>Email:</strong> {{ $karyawan->email }}</p>
                <p><strong>Handphone:</strong> {{ $karyawan->phone }}</p>
                <p><strong>Tanggal Lahir:</strong> {{ $karyawan->tanggal_lahir }}</p>
                <p><strong>Status:</strong> {{ $karyawan->status }}</p>
                <img class="img-fluid" width="200px" src="{{ asset($karyawan->photo) }}" alt="">
            </div>
        </div>
    </div>
@endsection

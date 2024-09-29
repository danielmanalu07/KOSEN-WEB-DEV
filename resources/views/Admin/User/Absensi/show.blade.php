@extends('components.layout')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" crossorigin="anonymous">

<div class="container py-5">
    <div class="row">
        <div class="col-md-6 mb-3 mb-md-0">
            <h1 class="fs-2">{{ $attendance->title }}</h1>
            <p class="text-muted">{{ $attendance->description }}</p>

            <div class="mb-4">
                <span class="badge text-bg-light border shadow-sm">
                    Masuk: {{ substr($attendance->start_time, 0, -3) }} - {{ substr($attendance->batas_start_time ?? '', 0, -3) }}
                </span>
                <span class="badge text-bg-light border shadow-sm">
                    Pulang: {{ substr($attendance->end_time, 0 , -3) }} - {{ substr($attendance->batas_end_time ?? '', 0, -3) }}
                </span>
            </div>

            {{-- Tombol untuk menghasilkan barcode --}}
            <a href="{{ route('presences.qrcode', $attendance->id) }}" class="badge text-bg-success">QRCode</a>
        </div>

        <div class="col-md-6">
            <h4>QR Code</h4>
            @if($attendance->code)
                <img src="{{ asset($attendance->code) }}" alt="QR Code untuk {{ $attendance->title }}" class="img-fluid">
            @else
                <p>QR Code belum tersedia.</p>
            @endif
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection

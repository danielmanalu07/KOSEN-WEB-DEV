@extends('components.layout')
@section('content')
<main>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <div class="container mt-4">
        <h1>Presensi untuk: {{ $attendance->title }}</h1>
        
        <div class="row">
            <div class="col-md-6">
                <h4>Detail Absensi</h4>
                <p>{{ $attendance->description }}</p>
                <p><strong>Waktu Mulai:</strong> {{ $attendance->start_time }}</p>
                <p><strong>Waktu Akhir:</strong> {{ $attendance->end_time }}</p>
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

        <div class="row mt-4">
            <div class="col-md-12">
                <h4>Formulir Presensi</h4>
                <form action="{{ route('presences.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="attendance_id" value="{{ $attendance->id }}">
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                    <div class="form-group">
                        <label for="presence_date">Tanggal:</label>
                        <input type="date" name="presence_date" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="presence_enter_time">Waktu Masuk:</label>
                        <input type="time" name="presence_enter_time" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
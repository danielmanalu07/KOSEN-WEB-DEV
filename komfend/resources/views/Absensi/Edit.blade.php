@extends('components.layout')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                @if (Session::has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>{{ Session::get('error') }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <form action="{{ url('/admin/absensis', ['id' => $absensi->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group mb-3">
                        <label for="judul" class="form-label">Judul</label>
                        <input type="text" class="form-control" id="judul" name="judul"
                            value="{{ $absensi->judul }}">
                        @error('judul')
                            <div class="alert alert-danger m-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control" cols="20" rows="10">{{ $absensi->deskripsi }}</textarea>
                        @error('deskripsi')
                            <div class="alert alert-danger m-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="start_time" class="form-label">Waktu Mulai</label>
                        <input type="datetime-local" class="form-control" id="start_time" name="start_time"
                            value="{{ $absensi->start_time }}">
                        @error('start_time')
                            <div class="alert alert-danger m-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="end_time" class="form-label">Waktu Selesai</label>
                        <input type="datetime-local" class="form-control" id="end_time" name="end_time"
                            value="{{ $absensi->end_time }}">
                        @error('end_time')
                            <div class="alert alert-danger m-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="checkOut_time" class="form-label">Waktu Check Out</label>
                        <input type="datetime-local" class="form-control" id="checkOut_time" name="checkOut_time"
                            value="{{ $absensi->checkOut_time }}">
                        @error('checkOut_time')
                            <div class="alert alert-danger m-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary">Ubah Absensi</button>
                    <a href="{{ url('/admin/absensis') }}" class="btn btn-sm btn-danger">Kembali</a>
                </form>
            </div>
        </div>
    </div>
@endsection

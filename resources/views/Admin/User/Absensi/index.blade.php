@extends('components.layout')
@section('content')
<main>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" crossorigin="anonymous">

    <div class="container mt-4">
        <h1>Daftar Absensi</h1>

        <div class="mb-3">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAttendanceModal">
                Tambah Absensi
            </button>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <table class="table">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Deskripsi</th>
                    <th>Waktu Mulai</th>
                    <th>Waktu Akhir</th>
                    <th>Batas Waktu Akhir</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($attendances as $attendance)
                    <tr>
                        <td>{{ $attendance->title }}</td>
                        <td>{{ $attendance->description }}</td>
                        <td>{{ $attendance->start_time }}</td>
                        <td>{{ $attendance->end_time }}</td>
                        <td>{{ $attendance->batas_end_time }}</td>
                        <td>
                            <a href="{{ route('attendances.show', $attendance->id) }}" class="btn btn-info">Detail</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal untuk menambahkan absensi -->
    <div class="modal fade" id="addAttendanceModal" tabindex="-1" aria-labelledby="addAttendanceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAttendanceModalLabel">Tambah Absensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('attendances.store') }}" method="POST" id="addAttendanceForm">
                        @csrf
                        <div class="mb-3">
                            <label for="attendance_title" class="form-label">Judul Absensi<sup class="text-danger">*</sup></label>
                            <input type="text" class="form-control" id="attendance_title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="attendance_description" class="form-label">Deskripsi<sup class="text-danger">*</sup></label>
                            <textarea class="form-control" id="attendance_description" name="description" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="start_time" class="form-label">Waktu Mulai<sup class="text-danger">*</sup></label>
                            <input type="time" class="form-control" id="start_time" name="start_time" required>
                        </div>
                        <div class="mb-3">
                            <label for="end_time" class="form-label">Waktu Akhir<sup class="text-danger">*</sup></label>
                            <input type="time" class="form-control" id="end_time" name="end_time" required>
                        </div>
                        <div class="mb-3">
                            <label for="batas_end_time" class="form-label">Batas Waktu Akhir<sup class="text-danger">*</sup></label>
                            <input type="time" class="form-control" id="batas_end_time" name="batas_end_time" required>
                        </div>
                        <button type="submit" class="btn btn-info w-100">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</main>
@endsection
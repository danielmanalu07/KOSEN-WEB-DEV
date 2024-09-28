@extends('components.layout')
@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        crossorigin="anonymous">

    <div class="row">
        <div class="col-md-7">
            <ul class="list-group">
                @foreach ($attendances as $attendance)
                <a href="{{ route('presences.show', $attendance->id) }}"
                    class="list-group-item d-flex justify-content-between align-items-start py-3">
                    <div class="ms-2 me-auto">
                        <div class="fw-bold">{{ $attendance->title }}</div>
                        <p class="mb-0">{{ $attendance->description }}</p>
                    </div>
                </a>
                @endforeach
            </ul>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection
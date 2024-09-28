@extends('components.layout')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
    crossorigin="anonymous">

<div class="container py-5">
    <div class="row">
        <div class="col-md-6 mb-3 mb-md-0">
            <h1 class="fs-2">{{ $attendance->title }}</h1>
            <p class="text-muted">{{ $attendance->description }}</p>

            <div class="mb-4">
                <span class="badge text-bg-light border shadow-sm">
                    Masuk: {{ substr($attendance->start_time, 0, -3) }}
                </span>
                <span class="badge text-bg-light border shadow-sm">
                    Pulang: {{ substr($attendance->end_time, 0, -3) }}
                </span>
            </div>

            {{-- Tampilkan QR Code jika tersedia --}}
            @if ($attendance->code)
            <div class="mb-4">
                <h5>QR Code</h5>
                <img src="{{ asset($attendance->code) }}" alt="QR Code" class="img-fluid" style="max-width: 200px;">
                <div>
                    <a href="{{ asset($attendance->code) }}" class="btn btn-primary mt-2" download>Download QR Code</a>
                </div>
            </div>
            @else
            <div class="mb-4">
                <div class="badge text-bg-danger">QR Code belum tersedia.</div>
            </div>
            @endif

            {{-- Form untuk menghasilkan QR Code --}}
            <form action="{{ route('presences.generate', ['id' => $attendance->id]) }}" method="POST">
                @csrf
                <button type="submit">Generate QR Code</button>
            </form>

        </div>


        <div class="col-md-6">
            <h5 class="mb-3">Histori 30 Hari Terakhir</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Jam Masuk</th>
                            <th scope="col">Jam Pulang</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($priodDate as $date)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            @php
                            $histo = $history->where('presence_date', $date)->first();
                            @endphp

                            {{-- Tidak Hadir --}}
                            @if (!$histo)
                            <td>{{ $date }}</td>
                            <td colspan="3">
                                @if($date == now()->toDateString())
                                <div class="badge text-bg-info">Belum Hadir</div>
                                @else
                                <div class="badge text-bg-danger">Tidak Hadir</div>
                                @endif
                            </td>
                            @else
                            <td>{{ $histo->presence_date }}</td>
                            <td>{{ $histo->presence_enter_time }}</td>
                            <td>
                                @if($histo->presence_out_time)
                                {{ $histo->presence_out_time }}
                                @else
                                <span class="badge text-bg-danger">Belum Absensi Pulang</span>
                                @endif
                            </td>
                            <td>
                                @if ($histo->is_permission)
                                <div class="badge text-bg-warning">Izin</div>
                                @else
                                <div class="badge text-bg-success">Hadir</div>
                                @endif
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

@endsection
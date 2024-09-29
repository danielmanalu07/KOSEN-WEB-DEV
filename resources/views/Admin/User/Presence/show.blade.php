@extends('components.layout')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
    crossorigin="anonymous">

<div class="container py-5">
    <div class="row">
        <div class="col-md-12 mb-3 mb-md-0">
            <!-- Attendance Title -->
            <h1 class="fs-1 fw-bold mb-3">{{ $attendance->title }}</h1> <!-- Increased size and boldness -->
            <p class="text-muted mb-4">{{ $attendance->description }}</p>

            <!-- Attendance Time Badges -->
            <div class="mb-4">
                <span class="badge text-bg-light border shadow-sm me-2">
                    Masuk: {{ substr($attendance->start_time, 0, -3) }} - {{ substr($attendance->batas_start_time ?? '',
                    0, -3) }}
                </span>
                <span class="badge text-bg-light border shadow-sm">
                    Pulang: {{ substr($attendance->end_time, 0, -3) }} - {{ substr($attendance->batas_end_time ?? '', 0,
                    -3) }}
                </span>
            </div>

            <!-- Button to trigger QR Code modal -->
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#qrcodeModal">
                Tampilkan QR Code
            </button>

            <!-- QR Code Modal -->
            <div class="modal fade" id="qrcodeModal" tabindex="-1" aria-labelledby="qrcodeModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="qrcodeModalLabel">QR Code</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center">
                            <!-- Center the QR code in modal -->
                            <h4 class="mb-3">QR Code</h4>
                            @if($attendance->code)
                            <img src="{{ asset($attendance->code) }}" alt="QR Code untuk {{ $attendance->title }}"
                                class="img-fluid">
                            @else
                            <p>QR Code belum tersedia.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    


    {{-- History table section moved here --}}
    <div class="col-md-12 py-5">
        <!-- This should also be col-md-12 to span the entire width -->
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
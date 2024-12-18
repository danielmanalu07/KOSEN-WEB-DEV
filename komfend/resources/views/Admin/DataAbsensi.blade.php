@extends('components.layout')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <!-- Filter Buttons -->
                <form action="{{ route('data.absen') }}" method="GET" class="d-inline-block">
                    <select name="filter_by" onchange="this.form.submit()" class="form-select form-select-sm">
                        <option value="">-- Filter Berdasarkan --</option>
                        <option value="week" {{ request('filter_by') == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="month" {{ request('filter_by') == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="year" {{ request('filter_by') == 'year' ? 'selected' : '' }}>Tahun Ini</option>
                    </select>
                </form>

                <!-- Download PDF Button -->
                <form action="{{ route('data.absen') }}" method="GET" class="d-inline-block">
                    <input type="hidden" name="export" value="pdf">
                    <input type="hidden" name="filter_by" value="{{ request('filter_by') }}">
                    <button type="submit" class="btn btn-sm btn-info">Download PDF</button>
                </form>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <td>No</td>
                                <td>Nama Pegawai</td>
                                <td>Email</td>
                                <td>Jabatan</td> <!-- Add Jabatan Column -->
                                <td>Judul Absen</td>
                                <td>Tanggal Absen</td>
                                <td>Status Absen</td>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($absens as $key => $absen)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $absen->karyawan->nama }}</td>
                                    <td>{{ $absen->karyawan->email ? $absen->karyawan->email : 'Email tidak terdaftar' }}</td>
                                    <td>{{ $absen->karyawan->jabatan }}</td>
                                    <td>{{ $absen->absensi->judul }}</td>
                                    <td>{{ $absen->tanggal }}</td>
                                    <td>{{ $absen->status }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak Ada Data</td>
                                </tr>
                            @endforelse
                        </tbody>
                        
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

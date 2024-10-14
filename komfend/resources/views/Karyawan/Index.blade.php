@extends('components.layout')
@section('content')
    <div class="container">
        <div class="justify-content-center">
            <div class="card">
                <div class="card-header">
                    <a href="{{ url('/admin/users/create') }}" class="btn btn-info btn-sm">Tambah Data</a>
                </div>
                <div class="card-body">
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Photo</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Handphone</th>
                                <th>Tanggal Lahir</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($karyawans as $key => $karyawan)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <img class="img-fluid" width="200px" src="{{ asset($karyawan->photo) }}"
                                            alt="">
                                    </td>
                                    <td>{{ $karyawan->nama }}</td>
                                    <td>{{ $karyawan->email }}</td>
                                    <td>{{ $karyawan->phone }}</td>
                                    <td>{{ $karyawan->tanggal_lahir }}</td>
                                    <td>{{ $karyawan->status }}</td>
                                    <td>
                                        <a href="{{ route('users.show', $karyawan->id) }}" class="btn btn-sm btn-primary">
                                            <iconify-icon icon="mdi:eye"></iconify-icon>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">Tidak Ada Data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

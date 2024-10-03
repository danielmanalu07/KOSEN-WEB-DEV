@extends('components.layout')
@section('content')
<main>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <div class="row">
        <div class="p-3 text-center">
            <h1>Data Pegawai</h1>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <a href="{{ route('users.create') }}" class="btn btn-md btn-primary">Tambah Data</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Photo</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Email</th>
                            <th scope="col">Umur</th>
                            <th scope="col">Tanggal Lahir</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Status</th>
                            <th scope="col">Role</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $key => $user)
                        <tr>
                            <th scope="row">{{ $key + 1 }}</th>
                            <td>
                                <img src="{{ asset('images/user/' . $user->photo) }}" alt="Photo User"
                                    class="img-fluid rounded-circle" width="100px">
                            </td>
                            <td>{{ $user->nama }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->umur }}</td>
                            <td>{{ $user->tanggal_lahir }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->status }}</td>
                            <td>{{ $user->role }}</td>
                            <td class="d-flex">
                                <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-success mr-2">
                                    Detail
                                </a>
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-info mr-2">
                                    Edit
                                </a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center">Tidak Ada Data Pegawai</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
@endsection

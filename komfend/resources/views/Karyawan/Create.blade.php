@extends('components.layout')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="{{ url('/admin/users') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Nama</label>
                        <input class="form-control" type="text" name="nama" id="" placeholder="Nama Lengkap">
                        @error('nama')
                            <div class="alert alert-danger m-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Email</label>
                        <input class="form-control" type="email" name="email" id="" placeholder="Email">
                        @error('email')
                            <div class="alert alert-danger m-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Tanggal Lahir</label>
                        <input class="form-control" type="date" name="tanggal_lahir" id="">
                        @error('tanggal_lahir')
                            <div class="alert alert-danger m-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Phone</label>
                        <input class="form-control" type="number" name="phone" id="" placeholder="No. Handphone">
                        @error('phone')
                            <div class="alert alert-danger m-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="" class="form-label">Jabatan</label>
                        <input class="form-control" type="text" name="jabatan" id="" placeholder="Jabatan">
                        @error('jabatan')
                            <div class="alert alert-danger m-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="" class="form-label">Photo</label>
                        <input class="form-control" type="file" name="photo" id="">
                        @error('photo')
                            <div class="alert alert-danger m-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-info btn-sm w-100 mt-3">Simpan Data</button>

                </form>
            </div>
        </div>
    </div>
@endsection

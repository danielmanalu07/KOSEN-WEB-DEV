@extends('components.layout')

<!-- DataTables -->
<link rel="stylesheet" href="/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

<!-- Bootstrap and jQuery -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

@section('content')
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                @if (session()->has('fail'))
                Gagal!
                @endif
                <!-- Profile Image -->
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile w-100">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle" src="{{ asset($karyawans->photo) }}" alt="User profile picture">
                        </div>
                        <h3 class="profile-username text-center">{{ $karyawans->nama }}</h3>
                        <p class="text-center">{{ $karyawans->email }}</p>
                
                        <!-- Menampilkan data di bawah label -->
                        <div class="profile-info">
                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item text-center">
                                    <b>Telepon</b>
                                    <p>{{ $karyawans->phone }}</p>
                                </li>
                                <li class="list-group-item text-center">
                                    <b>Tanggal Lahir</b>
                                    <p>{{ \Carbon\Carbon::parse($karyawans->tanggal_lahir)->format('d M Y') }}</p>
                                </li>
                                <li class="list-group-item text-center">
                                    <b>Status</b>
                                    <p>{{ $karyawans->status }}</p>
                                </li>
                                <li class="list-group-item text-center">
                                    <b>Di Buat</b>
                                    <p>{{ date('d-m-Y', strtotime($karyawans->created_at)) }}</p>
                                </li>
                                <li class="list-group-item text-center">
                                    <b>Di Perbarui</b>
                                    <p>{{ date('d-m-Y', strtotime($karyawans->updated_at)) }}</p>
                                </li>
                            </ul>
                        </div>
                
                        <a href="#" class="btn btn-warning btn-block" data-toggle="modal" data-target="#modalQr"><b>Lihat Kartu</b></a>
                    </div>
                    <!-- /.card-body -->
                </div>
                
                <!-- /.card -->

                {{-- <!-- About Me Box -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Profile</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <strong><i class="fas fa-map-marker-alt mr-1"></i> Alamat</strong>
                        <p class="text-muted">{{ $karyawans->alamat }}</p>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card --> --}}
            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" href="#activity"
                                    data-toggle="tab">Aktivitas</a></li>
                            <li class="nav-item"><a class="nav-link" href="#profile" data-toggle="tab">Profil</a></li>
                            <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Pengaturan</a>
                            </li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Activity Tab -->
                            <div class="active tab-pane" id="activity">
                                <!-- Konten Aktivitas -->
                                <p>Aktivitas karyawan akan ditampilkan di sini.</p>
                            </div>

                            <!-- Profile Tab -->
                            <div class="tab-pane" id="profile">
                                <!-- Konten Profil -->
                                <form action="{{ route('update.karyawan', $karyawans->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <!-- Nama -->
                                    <div class="form-group">
                                        <label for="nama">Nama</label>
                                        <input type="text" class="form-control" id="nama" name="nama"
                                            value="{{ $karyawans->nama }}" required>
                                    </div>

                                    <!-- Email -->
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="{{ $karyawans->email }}" required>
                                    </div>

                                    <!-- Telepon -->
                                    <div class="form-group">
                                        <label for="phone">Telepon</label>
                                        <input type="text" class="form-control" id="phone" name="phone"
                                            value="{{ $karyawans->phone }}" required>
                                    </div>

                                    <!-- Tanggal Lahir -->
                                    <div class="form-group">
                                        <label for="tanggal_lahir">Tanggal Lahir</label>
                                        <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir"
                                            value="{{ $karyawans->tanggal_lahir }}" required>
                                    </div>

                                    <!-- Status -->
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="form-control" id="status" name="status">
                                            <option value="Aktif" {{ $karyawans->status == 'Aktif' ? 'selected' : ''
                                                }}>Aktif</option>
                                            <option value="Nonaktif" {{ $karyawans->status == 'Nonaktif' ? 'selected' :
                                                '' }}>Nonaktif</option>
                                        </select>
                                    </div>


                                    <!-- Tombol Submit untuk Update Data -->
                                    <button type="submit" class="btn btn-primary">Update Data</button>
                                </form>
                            </div>


                            <!-- Settings Tab -->
                            <div class="tab-pane" id="settings">
                                <!-- Konten Pengaturan -->
                                <form action="{{ url('/admin/users/'.$karyawans->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Hapus Karyawan</button>
                                </form>
                            </div>
                        </div>
                        <!-- /.tab-content -->
                    </div><!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<!-- Modal Lihat Kartu -->
<div class="modal fade" id="modalQr" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content col-md-9">
            <style>
                .kartu {
                    width: 300px;
                    height: 100%;
                    border: 2px solid black;
                    border-style: double;
                    box-shadow: 1px 1px 3px #ccc;
                    padding: 20px;
                    margin: 20px auto;
                    text-align: center;
                    font-family: inherit;
                    font-size: 13px;
                }

                .name {
                    font-weight: bold;
                    font-size: 16px;
                    margin-bottom: -5px;
                }

                .notelp {
                    font-weight: bold;
                    font-size: 13px;
                    margin-bottom: -5px;
                }

                .photo {
                    width: 80px;
                    height: 100px;
                    margin: 0 auto;
                }

                .tgl-lahir {
                    margin-bottom: 10px;
                }

                .qr-code {
                    width: 100%;
                    height: 100%;
                    margin: 30px auto;
                    margin-bottom: 25px;
                }
            </style>

            <div class="kartu">
                <div class="photo">
                    <img src="{{ asset('assets/images/logo.jpeg') }}" class="img-fluid" alt="Foto Karyawan">
                </div>

                <div class="name">
                    {{ $karyawans->nama }}
                </div>
                <div class="notelp">
                    No Telepon: {{ $karyawans->phone }}
                </div>
                <div class="tgl-lahir">
                    Tanggal Lahir: {{ \Carbon\Carbon::parse($karyawans->tanggal_lahir)->format('d M Y') }}
                </div>
                <div class="qr-code">
                    <img src="{{ asset($karyawans->qrcode) }}" class="img-fluid" width="200px" alt="QR Code">
                </div>
            </div>
            <div class="button-container d-flex justify-content-center mb-3">
                <a href="{{ route('download.kartu', $karyawans->id) }}" class="btn btn-success">Download Kartu</a>
            </div>
        </div>
    </div>
</div>
@endsection
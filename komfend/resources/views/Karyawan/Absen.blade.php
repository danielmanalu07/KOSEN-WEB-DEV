<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scanner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <style>
        .scanner-container {
            position: relative;
            width: 100%;
            overflow: hidden;
            border-radius: 10px;
        }

        .video-preview {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .scanner-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 300px;
            height: 300px;
            transform: translate(-50%, -50%);
            border-radius: 10px;
            z-index: 1;
        }

        .scanner-line {
            position: absolute;
            width: 100%;
            height: 2px;
            background-color: rgba(0, 217, 255, 0.8);
            animation: scan-move 2s infinite;
        }

        @keyframes scan-move {
            0% {
                top: 0;
            }

            50% {
                top: 90%;
            }

            100% {
                top: 0;
            }
        }

        .scanner-border::before,
        .scanner-border::after {
            content: "";
            position: absolute;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(0, 217, 255, 0.8);
        }

        .scanner-border .top-right {
            position: absolute;
            top: 0;
            right: 0;
            width: 20px;
            height: 20px;
            border-top: 3px solid rgba(0, 217, 255, 0.8);
            border-right: 3px solid rgba(0, 217, 255, 0.8);
        }

        .scanner-border .bottom-left {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 20px;
            height: 20px;
            border-bottom: 3px solid rgba(0, 217, 255, 0.8);
            border-left: 3px solid rgba(0, 217, 255, 0.8);
        }

        .scanner-border::before {
            top: -3px;
            left: -3px;
            border-right: none;
            border-bottom: none;
        }

        .scanner-border::after {
            bottom: -3px;
            right: -3px;
            border-left: none;
            border-top: none;
        }

        .scanner-text {
            position: absolute;
            bottom: -60px;
            left: 50%;
            transform: translateX(-50%);
            background-color: skyBlue;
            color: white;
            padding: 5px 15px;
            border-radius: 5px;
            font-weight: bold;
            font-size: 18px;
            text-align: center;
            width: max-content;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container col-lg-8 py-5">
        <div class="card bg-white shadow rounded-3 border-0">
            <div class="card-body">
                <h3 class="card-title text-center text-primary mb-4">Scanner Absensi</h3>

                <!-- Alerts Section -->
                @if (Session::has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>{{ Session()->get('error') }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (Session::has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>{{ Session()->get('success') }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (Session::has('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>{{ Session()->get('warning') }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @php
                    $publishedAbsensis = $absensis->where('status', 'published');
                @endphp

                <div class="mb-3">
                    <label for="absensiSelect" class="form-label">Pilih Absensi</label>
                    <select class="form-select" id="absensiSelect" name="id_absensi" aria-label="Pilih Absensi">
                        <option value="">Pilih Absensi</option>
                        @foreach ($publishedAbsensis as $absensi)
                            <option value="{{ $absensi->id }}"
                                {{ session('selected_absensi_id') == $absensi->id ? 'selected' : '' }}>
                                {{ $absensi->judul }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Video Scanner -->
                <div class="scanner-container">
                    <video id="preview" class="video-preview"></video>
                    <div class="scanner-overlay">
                        <div class="scanner-border">
                            <div class="top-right"></div>
                            <div class="bottom-left"></div>
                        </div>
                        <div class="scanner-line"></div>
                        <div class="scanner-text">Scan Disini!</div>
                    </div>
                </div>



                <form action="{{ route('absen.store') }}" method="post" id="form">
                    @csrf
                    <input type="hidden" name="id_karyawan" id="id_karyawan">
                    <input type="hidden" name="id_absensi" id="id_absensi">
                </form>
            </div>
        </div>

        <!-- Table Section -->
        <div class="table-responsive mt-5">
            <table class="table table-striped table-bordered shadow-sm">
                <thead class="table-primary text-center">
                    <tr>
                        <th>No</th>
                        <th>Profile</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Jabatan</th>
                        <th>Check-In</th>
                        <th>Check-Out</th>
                        <th>Absensi</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($absensiKaryawans as $key => $absensiKaryawan)
                        <tr data-absensi-id="{{ $absensiKaryawan->id_absensi }}">
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td class="text-center">
                                @if (empty($absensiKaryawan->karyawan->photo))
                                    <span style="color: black; font-weight: bold;">Photo tidak tersedia</span>
                                @else
                                    <img src="{{ asset($absensiKaryawan->karyawan->photo) }}" alt="Profile Picture"
                                        class="img-fluid rounded-circle" width="60">
                                @endif
                            </td>
                            <td>{{ $absensiKaryawan->karyawan->nama }}</td>
                            <td>
                                @if (!empty($absensiKaryawan->karyawan->email))
                                    {{ $absensiKaryawan->karyawan->email }}
                                @else
                                    <span style="color: black; font-weight: bold;">Email tidak tersedia</span>
                                @endif
                            </td>
                            <td>{{ $absensiKaryawan->karyawan->jabatan }}</td>
                            <td>{{ \Carbon\Carbon::parse($absensiKaryawan->checkIn)->format('Y-m-d H:i') }}</td>
                            <td>
                                @if (!empty($absensiKaryawan->checkOut))
                                    {{ \Carbon\Carbon::parse($absensiKaryawan->checkOut)->format('Y-m-d H:i') }}
                                @else
                                    <span style="color: black; font-weight: bold;">Belum Melakukan CheckOut</span>
                                @endif
                            </td>
                            <td>{{ $absensiKaryawan->absensi->judul }}</td>
                            <td class="text-center">
                                <span
                                    class="badge {{ $absensiKaryawan->status == 'Hadir' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $absensiKaryawan->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr id="noDataRow">
                            <td colspan="8" class="text-center">Tidak Ada Data Absen</td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>

    <script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            const absensiSelect = document.getElementById('absensiSelect');
            const idKaryawanInput = document.getElementById('id_karyawan');
            const idAbsensiInput = document.getElementById('id_absensi');
            const form = document.getElementById('form');

            absensiSelect.value = localStorage.getItem('selectedAbsensiId') || "";
            absensiSelect.addEventListener('change', () => {
                localStorage.setItem('selectedAbsensiId', absensiSelect.value);
            });

            let scanner = new Instascan.Scanner({
                video: document.getElementById('preview'),
                scanPeriod: 5,
                mirror: false
            });

            Instascan.Camera.getCameras().then(function(cameras) {
                if (cameras.length > 0) {
                    scanner.start(cameras[0]);
                } else {
                    console.error('No cameras found.');
                }
            }).catch(console.error);

            scanner.addListener('scan', function(content) {
                idKaryawanInput.value = content;
                if (absensiSelect.value) {
                    idAbsensiInput.value = absensiSelect.value;
                    form.submit();
                } else {
                    alert('Silakan pilih absensi terlebih dahulu.');
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>
</body>

</html>

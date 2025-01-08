<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scanner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('assets/css/absen.css') }}">
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
                <!-- Tambahkan di dalam card-body -->
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
                <canvas id="canvas" style="display:none;"></canvas>
                <img id="photo" alt="Captured Photo" style="display:none;">



                <form action="{{ route('absen.store') }}" method="post" id="form">
                    @csrf
                    <input type="hidden" name="id_karyawan" id="id_karyawan">
                    <input type="hidden" name="id_absensi" id="id_absensi">
                </form>
            </div>
        </div>

        <!-- Modal untuk menampilkan gambar -->
        <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="imageModalLabel">Bukti Foto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img id="modalImage" src="" alt="Bukti Foto" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="table-responsive mt-5">
            <table class="table table-striped table-bordered shadow-sm text-center">
                <thead class="table-primary text-center">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Jabatan</th>
                        <th>Check-In</th>
                        <th>Check-Out</th>
                        <th>Absensi</th>
                        <th>Status</th>
                        <th>Bukti Foto</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($absensiKaryawans as $key => $absensiKaryawan)
                        <tr data-absensi-id="{{ $absensiKaryawan->id_absensi }}">
                            <td class="text-center">{{ $key + 1 }}</td>
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
                                    <span style="color: black; font-weight: bold;">Belum Check-Out</span>
                                @endif
                            </td>
                            <td>{{ $absensiKaryawan->absensi->judul }}</td>
                            <td class="text-center">
                                <span
                                    class="badge {{ $absensiKaryawan->status == 'Hadir' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $absensiKaryawan->status }}
                                </span>
                            </td>
                            <td>
                                @if ($absensiKaryawan->capture)
                                    <img src="{{ asset('photos/' . $absensiKaryawan->capture->photo) }}"
                                        alt="Foto Absen" width="50" style="cursor: pointer;"
                                        onclick="openImageModal('{{ asset('photos/' . $absensiKaryawan->capture->photo) }}')">
                                @else
                                    <span style="color: black; font-weight: bold;">Foto tidak tersedia</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr id="noDataRow">
                            <td colspan="9" class="text-center">Tidak Ada Data Absen</td>
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
            const video = document.getElementById('preview');
            const canvas = document.getElementById('canvas');
            const photo = document.getElementById('photo');

            absensiSelect.value = localStorage.getItem('selectedAbsensiId') || "";
            absensiSelect.addEventListener('change', () => {
                localStorage.setItem('selectedAbsensiId', absensiSelect.value);
            });

            let scanner = new Instascan.Scanner({
                video: video,
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

                    // Capture photo from video stream
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
                    photo.src = canvas.toDataURL('image/png');

                    // Create a hidden input to send the photo data
                    const photoInput = document.createElement('input');
                    photoInput.type = 'hidden';
                    photoInput.name = 'photo';
                    photoInput.value = photo.src;
                    form.appendChild(photoInput);

                    form.submit();
                } else {
                    alert('Silakan pilih absensi terlebih dahulu.');
                }
            });
        });

        function openImageModal(imageUrl) {
            const modalImage = document.getElementById('modalImage');
            modalImage.src = imageUrl; // Set sumber gambar
            const imageModal = new bootstrap.Modal(document.getElementById('imageModal')); // Inisialisasi modal
            imageModal.show(); // Tampilkan modal
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>
</body>

</html>

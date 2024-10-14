<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scanner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>

<body>
    <div class="container col-lg-6 py-5">
        <div class="card bg-white shadow rounded-3 p-3 border-0">
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

            <div class="camera-container mb-4">
                <video id="preview" class="video-preview"
                    style="width: 100%; height: auto; border-radius: 8px;"></video>
            </div>

            <form action="{{ route('absen.store') }}" method="post" id="form">
                @csrf
                <input type="hidden" name="id_karyawan" id="id_karyawan">
                <input type="hidden" name="id_absensi" id="id_absensi">
            </form>
        </div>

        <div class="table-responsive mt-5">
            <table class="table table-bordered table-hover" id="absensiTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Profile</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Tanggal</th>
                        <th>Absensi</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($absensiKaryawans as $key => $absensiKaryawan)
                        <tr data-absensi-id="{{ $absensiKaryawan->id_absensi }}">
                            <td>{{ $key + 1 }}</td>
                            <td>
                                <img src="{{ asset($absensiKaryawan->karyawan->photo) }}" alt=""
                                    class="img-fluid" width="200px">
                            </td>
                            <td>{{ $absensiKaryawan->karyawan->nama }}</td>
                            <td>{{ $absensiKaryawan->karyawan->email }}</td>
                            <td>{{ \Carbon\Carbon::parse($absensiKaryawan->tanggal)->format('Y-m-d H:i') }}</td>
                            <td>{{ $absensiKaryawan->absensi->judul }}</td>
                            <td>{{ $absensiKaryawan->status }}</td>
                        </tr>
                    @empty
                        <tr id="noDataRow">
                            <td colspan="7" class="text-center">Tidak Ada Data Absen</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <script type="text/javascript">
        let scanner = new Instascan.Scanner({
            video: document.getElementById('preview')
        });

        Instascan.Camera.getCameras().then(function(cameras) {
            if (cameras.length > 0) {
                scanner.start(cameras[0]);
            } else {
                console.error('No cameras found.');
            }
        }).catch(function(e) {
            console.error(e);
        });

        scanner.addListener('scan', function(content) {
            document.getElementById('id_karyawan').value = content;

            const selectedAbsensiId = document.getElementById('absensiSelect').value;
            if (selectedAbsensiId) {
                document.getElementById('id_absensi').value = selectedAbsensiId;
                localStorage.setItem('selectedAbsensiId', selectedAbsensiId); // Simpan pilihan
                document.getElementById('form').submit();
            } else {
                alert('Silakan pilih absensi terlebih dahulu.');
            }
        });

        function updateTableVisibility() {
            const selectedAbsensiId = document.getElementById('absensiSelect').value;
            const tableRows = document.querySelectorAll('#absensiTable tbody tr:not(#noDataRow)');
            let visibleRowCount = 0;

            tableRows.forEach((row, index) => {
                if (selectedAbsensiId === "" || row.getAttribute('data-absensi-id') === selectedAbsensiId) {
                    row.style.display = '';
                    visibleRowCount++;
                    row.cells[0].textContent = visibleRowCount;
                } else {
                    row.style.display = 'none';
                }
            });

            const noDataRow = document.getElementById('noDataRow');
            if (visibleRowCount === 0) {
                noDataRow.style.display = '';
            } else {
                noDataRow.style.display = 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const savedAbsensiId = localStorage.getItem('selectedAbsensiId');
            if (savedAbsensiId) {
                document.getElementById('absensiSelect').value = savedAbsensiId;
            }
            updateTableVisibility();
        });

        document.getElementById('absensiSelect').addEventListener('change', function() {
            updateTableVisibility();

            const selectedAbsensiId = this.value;
            const tableRows = document.querySelectorAll('#absensiTable tbody tr:not(#noDataRow)');
            let visibleRowCount = 0;

            tableRows.forEach((row, index) => {
                if (selectedAbsensiId === "" || row.getAttribute('data-absensi-id') === selectedAbsensiId) {
                    row.style.display = '';
                    visibleRowCount++;
                    row.cells[0].textContent = visibleRowCount; // Update nomor urut
                } else {
                    row.style.display = 'none';
                }
            });

            const noDataRow = document.getElementById('noDataRow');
            if (visibleRowCount === 0) {
                noDataRow.style.display = '';
            } else {
                noDataRow.style.display = 'none';
            }
            localStorage.setItem('selectedAbsensiId', this.value);
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>
</body>

</html>

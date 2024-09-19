<x-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .row {
            font-size: 20px;
        }
    </style>

    <div class="container-fluid d-flex justify-content-between mb-3">
        <h1> Selamat datang {{ Auth::user()->nama }}</h1>
        <h3 id="current-date"></h3>
    </div>

    <div class="row">

        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card shadow-sm bg-info text-bg-dark">
                <div class="card-header">
                    Absensi Pegawai
                </div>
                <div class="card-body">
                    <b>100</b>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card shadow-sm bg-danger text-bg-dark">
                <div class="card-header">
                    Data Pegawai
                </div>
                <div class="card-body">
                    <b>100</b>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card shadow-sm bg-primary text-bg-dark">
                <div class="card-header">
                    Riwayat Absensi
                </div>
                <div class="card-body">
                    <b>100</b>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-6 col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-bg-dark">
                    Grafik Data Absensi
                </div>
                <div class="card-body">
                    <canvas id="grafik1"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-bg-dark">
                    Grafik Data Pegawai
                </div>
                <div class="card-body">
                    <canvas id="grafik2"></canvas>
                </div>
            </div>
        </div>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const currentDateElement = document.getElementById('current-date');
        const today = new Date();
        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        currentDateElement.textContent = today.toLocaleDateString('id-ID', options)


        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif
    </script>
</x-layout>

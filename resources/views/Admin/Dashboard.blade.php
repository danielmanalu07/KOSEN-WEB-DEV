<x-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <div class="container-fluid d-flex justify-content-between mb-3">
        <h1> Selamat datang {{ Auth::user()->nama }}</h1>
        <h3 id="current-date"></h3>
    </div>

    <div class="row">

        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card shadow-sm bg-info text-bg-dark">
                <div class="card-header">
                    Absensi Pegawai
                </div>
                <div class="card-body">
                    100
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card shadow-sm bg-secondary text-bg-dark">
                <div class="card-header">
                    Data Pegawai
                </div>
                <div class="card-body">
                    100
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card shadow-sm bg-primary text-bg-dark">
                <div class="card-header">
                    Riwayat Absensi
                </div>
                <div class="card-body">
                    100
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Grafik 1 -->
        <div class="col-lg-6 col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-bg-dark">
                    Grafik 1
                </div>
                <div class="card-body">
                    <canvas id="grafik1"></canvas> <!-- Ini untuk grafik pertama -->
                </div>
            </div>
        </div>

        <!-- Grafik 2 -->
        <div class="col-lg-6 col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-bg-dark">
                    Grafik 2
                </div>
                <div class="card-body">
                    <canvas id="grafik2"></canvas> <!-- Ini untuk grafik kedua -->
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

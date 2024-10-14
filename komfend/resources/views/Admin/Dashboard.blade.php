@extends('components.layout')
@section('content')
    <main>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <style>
            .row {
                font-size: 20px;
            }
        </style>

        <div class="container-fluid d-flex justify-content-between mb-3">
            <h1> Selamat datang {{ Auth::guard('admin')->user()->username }}</h1>
            <h3 id="current-date"></h3>
        </div>

        <div class="row">

            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card shadow-sm bg-info text-bg-dark">
                    <div class="card-header">
                        Absensi Pegawai
                    </div>
                    <div class="card-body">
                        <b>{{ $absensi }}</b>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card shadow-sm bg-danger text-bg-dark">
                    <div class="card-header">
                        Data Pegawai
                    </div>
                    <div class="card-body">
                        <b>{{ $karyawan }}</b>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card shadow-sm bg-primary text-bg-dark">
                    <div class="card-header">
                        Riwayat Absensi
                    </div>
                    <div class="card-body">
                        <b>{{ $absen }}</b>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-bg-dark">
                    Grafik Data Pegawai
                </div>
                <div class="card-body">
                    <div id="grafik_karyawan">
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-danger text-bg-dark">
                    Grafik Data Absensi
                </div>
                <div class="card-body">
                    <div id="grafik_absensi"></div>
                </div>
            </div>
        </div>




        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script type="text/javascript">
            var karyawan = <?php echo json_encode($total_karyawan); ?>;
            var bulan_karyawan = <?php echo json_encode($bulan_karyawan); ?>;
            Highcharts.chart('grafik_karyawan', {
                title: {
                    text: 'Grafik Total Pegawai'
                },
                xAxis: {
                    categories: bulan_karyawan
                },
                yAxis: {
                    title: {
                        text: 'Jumlah Pegawai'
                    },
                    min: 0,
                    allowDecimals: false
                },
                series: [{
                    name: 'Jumlah Pegawai',
                    data: karyawan
                }],
                tooltip: {
                    formatter: function() {
                        return '<b>' + this.x + '</b><br/>' +
                            this.series.name + ': ' + this.y;
                    }
                }
            });

            var absen = <?php echo json_encode($total_absen); ?>;
            var bulan_absensi = <?php echo json_encode($bulan_absensi); ?>;
            Highcharts.chart('grafik_absensi', {
                title: {
                    text: 'Grafik Total Absensi'
                },
                xAxis: {
                    categories: bulan_absensi
                },
                yAxis: {
                    title: {
                        text: 'Jumlah Absensi'
                    },
                    min: 0,
                    allowDecimals: false
                },
                series: [{
                    name: 'Jumlah Absensi',
                    data: absen
                }],
                tooltip: {
                    formatter: function() {
                        return '<b>' + this.x + '</b><br/>' +
                            this.series.name + ': ' + this.y;
                    }
                }
            });
        </script>
        <script>
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
    </main>
@endsection

<x-layout>
    <div class="row">
      <div class="col">
        <div class="card">
          <div class="card-body">
            <div class="mb-3 mb-sm-0">
              <h5 class="card-title fw-semibold mt-3">Kelas A1</h5>
              <div class="row">
                <div class="col">
                  <button class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#exampleModal2">Download
                    Rekap</button>
                </div>
                <div class="col text-end">
                  <button class="btn btn-info text-white" data-bs-toggle="modal"
                    data-bs-target="#exampleModal">Filter</button>
                </div>
                <div class="table-responsive">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th class="col-1 text-black" scope="col">#</th>
                        <th class="col-1 text-black" scope="col">NIS</th>
                        <th class="text-black" scope="col">Nama</th>
                        <th class="text-center text-black" scope="col">Kelas</th>
                        <th class="col-1 text-center text-black" scope="col">Hadir</th>
                        <th class="col-1 text-center text-black" scope="col">Sakit</th>
                        <th class="col-1 text-center text-black" scope="col">Izin</th>
                        <th class="col-1 text-center text-black" scope="col">Alpha</th>
                      </tr>
                    </thead>
                    <tbody>
                      @php
                        // Data dummy untuk kehadiran siswa
                        $kehadirans = [
                            ['nis' => '123456', 'nama' => 'Budi', 'nama_kelas' => 'A1', 'hadir' => 20, 'sakit' => 1, 'izin' => 0, 'alpha' => 1],
                            ['nis' => '654321', 'nama' => 'Siti', 'nama_kelas' => 'A1', 'hadir' => 18, 'sakit' => 2, 'izin' => 1, 'alpha' => 2],
                            ['nis' => '789012', 'nama' => 'Joko', 'nama_kelas' => 'A1', 'hadir' => 22, 'sakit' => 0, 'izin' => 2, 'alpha' => 0],
                        ];
                      @endphp
                      @foreach($kehadirans as $kehadiran)
                      <tr>
                        <th class="text-black" scope="row">{{ $loop->iteration }}</th>
                        <td class="text-black">{{ $kehadiran['nis'] }}</td>
                        <td class="text-black">{{ $kehadiran['nama'] }}</td>
                        <td class="text-center text-black">{{ $kehadiran['nama_kelas'] }}</td>
                        <td class="text-center text-black">{{ $kehadiran['hadir'] }}</td>
                        @php
                        $sakitColor = ($kehadiran['sakit'] > 3) ? 'text-danger fw-bold' : 'text-black';
                        $izinColor = ($kehadiran['izin'] > 3) ? 'text-danger fw-bold' : 'text-black';
                        $alphaColor = ($kehadiran['alpha'] > 3) ? 'text-danger fw-bold' : 'text-black';
                        @endphp
                        <td class="text-center {{ $sakitColor }}">{{ $kehadiran['sakit'] }}</td>
                        <td class="text-center {{ $izinColor }}">{{ $kehadiran['izin'] }}</td>
                        <td class="text-center {{ $alphaColor }}">{{ $kehadiran['alpha'] }}</td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal untuk filter dan download -->
    <!-- Modal content sama seperti sebelumnya -->
  </x-layout>
  {{-- <x-layout>
    <div class="row">
      <div class="col">
        <div class="card">
          <div class="card-body">
            <div class="mb-3 mb-sm-0">
              <h5 class="card-title fw-semibold mt-3">Kelas A1</h5>
              <div class="row">
                <div class="col">
                  <button class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#exampleModal2">Download
                    Rekap</button>
                </div>
                <div class="col text-end">
                  <button class="btn btn-info text-white" data-bs-toggle="modal"
                    data-bs-target="#exampleModal">Filter</button>
                </div>
                <div class="table-responsive">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th class="col-1 text-black" scope="col">#</th>
                        <th class="col-1 text-black" scope="col">NIS</th>
                        <th class="text-black" scope="col">Nama</th>
                        <th class="text-center text-black" scope="col">Kelas</th>
                        <th class="col-1 text-center text-black" scope="col">Hadir</th>
                        <th class="col-1 text-center text-black" scope="col">Sakit</th>
                        <th class="col-1 text-center text-black" scope="col">Izin</th>
                        <th class="col-1 text-center text-black" scope="col">Alpha</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($kehadirans as $kehadiran)
                      <tr>
                        <th class="text-black" scope="row">{{ $loop->iteration }}</th>
                        <td class="text-black">{{ $kehadiran->nis }}</td>
                        <td class="text-black">{{ $kehadiran->nama }}</td>
                        <td class="text-center text-black">{{ $kehadiran->nama_kelas }}</td>
                        <td class="text-center text-black">{{ $kehadiran->hadir }}</td>
                        @php
                        $sakitColor = ($kehadiran->sakit > 3) ? 'text-danger fw-bold' : 'text-black';
                        $izinColor = ($kehadiran->izin > 3) ? 'text-danger fw-bold' : 'text-black';
                        $alphaColor = ($kehadiran->alpha > 3) ? 'text-danger fw-bold' : 'text-black';
                        @endphp
                        <td class="text-center {{ $sakitColor }}">{{ $kehadiran->sakit }}</td>
                        <td class="text-center {{ $izinColor }}">{{ $kehadiran->izin }}</td>
                        <td class="text-center {{ $alphaColor }}">{{ $kehadiran->alpha }}</td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Siswa</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="{{ route('rekap.filter') }}" method="POST">
              @csrf
              <div class="row gx-3">
                <div class="col-12 p-0 mb-3">
                  <div class="input-group">
                    <label class="input-group-text rounded-0 rounded-start" for="kelasSelect">Kelas</label>
                    <select class="form-select rounded-0" id="kelasSelect" name="kelas">
                      @foreach ($kelases as $kelas)
                      <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-12 p-0 mb-3">
                  <div class="input-group">
                    <label class="input-group-text rounded-0" for="pelajaranSelect">Pelajaran</label>
                    <select class="form-select rounded-0" id="pelajaranSelect" name="mata_pelajaran">
                      @foreach ($pelajarans as $pelajaran)
                      <option value="{{ $pelajaran->id }}">{{ $pelajaran->nama_pelajaran }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-12 col-md p-0 mb-3">
                  <div class="input-group">
                    <label class="input-group-text rounded-0" for="bulanSelect">Bulan</label>
                    <select class="form-select rounded-0 rounded-end" id="bulanSelect" name="bulan">
                      <option value="1" selected>Januari</option>
                      <option value="2">Februari</option>
                      <option value="3">Maret</option>
                      <option value="4">April</option>
                      <option value="5">Mei</option>
                      <option value="6">Juni</option>
                      <option value="7">Juli</option>
                      <option value="8">Agustus</option>
                      <option value="9">September</option>
                      <option value="10">Oktober</option>
                      <option value="11">November</option>
                      <option value="12">Desember</option>
                    </select>
                  </div>
                </div>
              </div>
              <button type="submit" class="btn btn-primary">Ubah</button>
              <a href="/rekap" class="btn btn-danger">Reset</a>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Download Rekap</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="{{ route('rekap.download') }}" method="POST">
              @csrf
              <div class="row gx-3">
                <div class="col-12 p-0 mb-3">
                  <div class="input-group">
                    <label class="input-group-text rounded-0 rounded-start" for="kelasSelect">Kelas</label>
                    <select class="form-select rounded-0" id="kelasSelect" name="kelas">
                      <option value="" selected>Semua</option>
                      @foreach ($kelases as $kelas)
                      <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-12 p-0 mb-3">
                  <div class="input-group">
                    <label class="input-group-text rounded-0" for="pelajaranSelect">Pelajaran</label>
                    <select class="form-select rounded-0" id="pelajaranSelect" name="pelajaran">
                      <option value="" selected>Semua</option>
                      @foreach ($pelajarans as $pelajaran)
                      <option value="{{ $pelajaran->id }}">{{ $pelajaran->nama_pelajaran }}</option>
                      @endforeach
                    </select>
                    </select>
                  </div>
                </div>
                <div class="col-12 col-md p-0 mb-3">
                  <div class="input-group">
                    <label class="input-group-text rounded-0" for="bulanSelect">Bulan</label>
                    <select class="form-select rounded-0 rounded-end" id="bulanSelect" name="bulan">
                      <option value="1" selected>Januari</option>
                      <option value="2">Februari</option>
                      <option value="3">Maret</option>
                      <option value="4">April</option>
                      <option value="5">Mei</option>
                      <option value="6">Juni</option>
                      <option value="7">Juli</option>
                      <option value="8">Agustus</option>
                      <option value="9">September</option>
                      <option value="10">Oktober</option>
                      <option value="11">November</option>
                      <option value="12">Desember</option>
                    </select>
                  </div>
                </div>
              </div>
              <button type="submit" class="btn btn-primary">Download Rekap</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </x-layout> --}}
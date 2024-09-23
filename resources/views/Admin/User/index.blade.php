<x-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">


    <div class="row">
        <div class="p-3 text-center">
            <h1>Data Pegawai</h1>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <a href="#" class="btn btn-md btn-primary" data-bs-toggle="modal"
                data-bs-target="#addEmployeeModal">Tambah Data</a>
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
                            <th scope="col">Password</th>
                            <th scope="col">Umur</th>
                            <th scope="col">Tanggal Lahir</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Status</th>
                            <th scope="col">Role</th>
                            <th scope="col">Action</th>
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
                                <td>{{ Str::limit($user->password, 10) }}</td>
                                <td>{{ $user->umur }}</td>
                                <td>{{ $user->tanggal_lahir }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ $user->status }}</td>
                                <td>{{ $user->role }}</td>
                                <td class="d-flex">
                                    <a href="#" class="btn btn-sm btn-info mr-2 editEmployeeBtn"
                                        data-id="{{ $user->id }}" data-bs-toggle="modal"
                                        data-bs-target="#editEmployeeModal"><iconify-icon
                                            icon="mdi:edit"></iconify-icon></a>
                                    <a href="#" class="btn btn-sm btn-danger deleteEmployeeBtn"
                                        data-id="{{ $user->id }}" data-bs-toggle="modal"
                                        data-bs-target="#deleteEmployeeModal"><iconify-icon
                                            icon="mdi:trash"></iconify-icon></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center">Tidak Ada Data Pegawai</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pop-up modal untuk menambahkan data --}}
    <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEmployeeModalLabel">Tambah Data Pegawai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addEmployeeForm">
                        @csrf
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama<sup class="text-danger">*</sup></label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email<sup class="text-danger">*</sup></label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        {{-- <div class="mb-3">
                            <label for="password" class="form-label">Password<sup class="text-danger">*</sup></label>
                            <input type="password" class="form-control" id="password" name="password" >
                        </div> --}}
                        <div class="mb-3">
                            <label for="umur" class="form-label">Umur<sup class="text-danger">*</sup></label>
                            <input type="number" class="form-control" id="umur" name="umur" required>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir<sup
                                    class="text-danger">*</sup></label>
                            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone<sup class="text-danger">*</sup></label>
                            <input type="number" class="form-control" id="phone" name="phone" required>
                        </div>
                        {{-- <button type="submit" id="submitButton" class="btn btn-primary">Simpan</button> --}}
                        <button type="submit" class="btn w-100 btn-info" id="submitButton">
                            <span class="spinner-border spinner-border-sm d-none" role="status"
                                aria-hidden="true"></span>
                            <span class="button-text">Simpan </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Pop-up modal untuk edit data -->
    <div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEmployeeModalLabel">Edit Data Pegawai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editEmployeeForm">
                        @csrf
                        @method('PUT') <!-- Metode untuk update -->
                        <input type="hidden" id="editUserId" name="id">
                        <div class="mb-3">
                            <label for="editNama" class="form-label">Nama<sup class="text-danger">*</sup></label>
                            <input type="text" class="form-control" id="editNama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">Email<sup class="text-danger">*</sup></label>
                            <input type="email" class="form-control" id="editEmail" name="email" required>
                        </div>
                        {{-- <div class="mb-3">
                            <label for="editPassword" class="form-label">Password (kosongkan jika tidak ingin
                                mengubah)</label>
                            <input type="password" class="form-control" id="editPassword" name="password">
                        </div> --}}
                        <div class="mb-3">
                            <label for="editUmur" class="form-label">Umur<sup class="text-danger">*</sup></label>
                            <input type="number" class="form-control" id="editUmur" name="umur" required>
                        </div>
                        <div class="mb-3">
                            <label for="editTanggalLahir" class="form-label">Tanggal Lahir<sup
                                    class="text-danger">*</sup></label>
                            <input type="date" class="form-control" id="editTanggalLahir" name="tanggal_lahir"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="editPhone" class="form-label">Phone<sup class="text-danger">*</sup></label>
                            <input type="number" class="form-control" id="editPhone" name="phone" required>
                        </div>
                        <button type="submit" class="btn w-100 btn-info" id="submitButton">
                            <span class="spinner-border spinner-border-sm d-none" role="status"
                                aria-hidden="true"></span>
                            <span class="button-text">Simpan Perubahan</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Pop-up modal untuk konfirmasi delete data -->
    <div class="modal fade" id="deleteEmployeeModal" tabindex="-1" aria-labelledby="deleteEmployeeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteEmployeeModalLabel">Hapus Data Pegawai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus data pegawai ini?</p>
                    <form id="deleteEmployeeForm">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" id="deleteUserId" name="id">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('addEmployeeForm').addEventListener('submit', function(event) {
                var submitButton = document.getElementById('submitButton');
                var spinner = submitButton.querySelector('.spinner-border');
                var buttonText = submitButton.querySelector('.button-text');

                spinner.classList.remove('d-none');
                buttonText.textContent = 'Loading...';

                submitButton.disabled = true;
            });
        });
        $(document).ready(function() {
            $('#addEmployeeForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    type: 'POST',
                    url: '{{ route('users.store') }}',
                    data: $('#addEmployeeForm').serialize(),
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses',
                            text: 'Data berhasil ditambahkan',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function() {
                            window.location.reload();
                        });
                    },
                    error: function(error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan: ' + error.responseJSON.error,
                            confirmButtonText: 'Tutup'
                        });
                    }
                });
            });

            $('.editEmployeeBtn').on('click', function() {
                var userId = $(this).data('id');

                $.ajax({
                    url: '/admin/users/' + userId + '/edit',
                    method: 'GET',
                    success: function(response) {
                        $('#editUserId').val(response.id);
                        $('#editNama').val(response.nama);
                        $('#editEmail').val(response.email);
                        $('#editUmur').val(response.umur);
                        $('#editTanggalLahir').val(response.tanggal_lahir);
                        $('#editPhone').val(response.phone);
                    }
                });
            });

            // Submit form edit untuk memperbarui data
            $('#editEmployeeForm').on('submit', function(e) {
                e.preventDefault();
                var userId = $('#editUserId').val();

                $.ajax({
                    type: 'PUT',
                    url: '/admin/users/' + userId,
                    data: $('#editEmployeeForm').serialize(),
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses',
                            text: 'Data berhasil diperbarui',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function() {
                            window.location.reload();
                        });
                    },
                    error: function(error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan: ' + error.responseJSON.error,
                            confirmButtonText: 'Tutup'
                        });
                    }
                });
            });

            $('.deleteEmployeeBtn').on('click', function() {
                var userId = $(this).data('id');
                $('#deleteUserId').val(userId); // Set id pengguna untuk penghapusan
            });

            // Submit form delete untuk menghapus data
            $('#deleteEmployeeForm').on('submit', function(e) {
                e.preventDefault();
                var userId = $('#deleteUserId').val();

                $.ajax({
                    type: 'DELETE',
                    url: '/admin/users/' + userId,
                    data: $('#deleteEmployeeForm').serialize(),
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses',
                            text: 'Data berhasil dihapus',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function() {
                            window.location.reload();
                        });
                    },
                    error: function(error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan: ' + error.responseJSON.error,
                            confirmButtonText: 'Tutup'
                        });
                    }
                });
            });
        });
    </script>

</x-layout>

<x-layout>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                        Tambah Data +
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Start Time</th>
                                <th>Batas Start Time</th>
                                <th>End Time</th>
                                <th>Batas End Time</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendances as $attendance)
                            <tr>
                                <td>{{ $attendance->id }}</td>
                                <td>{{ $attendance->title }}</td>
                                <td>{{ $attendance->description }}</td>
                                <td>{{ $attendance->start_time }}</td>
                                <td>{{ $attendance->batas_start_time }}</td>
                                <td>{{ $attendance->end_time }}</td>
                                <td>{{ $attendance->batas_end_time }}</td>
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#editModal" data-id="{{ $attendance->id }}"
                                        data-title="{{ $attendance->title }}"
                                        data-description="{{ $attendance->description }}"
                                        data-start_time="{{ $attendance->start_time }}"
                                        data-batas_start_time="{{ $attendance->batas_start_time }}"
                                        data-end_time="{{ $attendance->end_time }}"
                                        data-batas_end_time="{{ $attendance->batas_end_time }}"
                                        data-use_qrcode="{{ $attendance->code }}">
                                        Edit
                                    </button>
                                    <form action="{{ route('attendance.destroy', $attendance->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for creating a new attendance -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="createModalLabel">Tambah Kehadiran</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('attendance.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"
                                required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="start_time" class="form-label">Start Time</label>
                            <input type="time" class="form-control" id="start_time" name="start_time" required>
                        </div>
                        <div class="mb-3">
                            <label for="batas_start_time" class="form-label">Batas Start Time</label>
                            <input type="time" class="form-control" id="batas_start_time" name="batas_start_time"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="end_time" class="form-label">End Time</label>
                            <input type="time" class="form-control" id="end_time" name="end_time" required>
                        </div>
                        <div class="mb-3">
                            <label for="batas_end_time" class="form-label">Batas End Time</label>
                            <input type="time" class="form-control" id="batas_end_time" name="batas_end_time" required>
                        </div>
                        <div class="mb-3">
                            <label for="code" class="form-label">QRCode</label>
                            <input type="checkbox" id="use_qrcode" name="use_qrcode">
                            <label for="use_qrcode">Ingin Menggunakan QRCode</label>
                        </div>
                        <div class="mb-3">
                            <label for="position" class="form-label">Posisi Karyawan</label><br>
                            @foreach($positions as $position)
                            <input type="checkbox" id="create_position_{{ $position->id }}" name="position[]"
                                value="{{ $position->name }}">
                            <label for="create_position_{{ $position->id }}">{{ $position->name }}</label><br>
                            @endforeach
                        </div>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal for editing attendance -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editModalLabel">Edit Kehadiran</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" action="" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="edit-title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="edit-title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit-description" name="description" rows="3"
                                required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit-start_time" class="form-label">Start Time</label>
                            <input type="time" class="form-control" id="edit-start_time" name="start_time" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-batas_start_time" class="form-label">Batas Start Time</label>
                            <input type="time" class="form-control" id="edit-batas_start_time" name="batas_start_time"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-end_time" class="form-label">End Time</label>
                            <input type="time" class="form-control" id="edit-end_time" name="end_time" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-batas_end_time" class="form-label">Batas End Time</label>
                            <input type="time" class="form-control" id="edit-batas_end_time" name="batas_end_time"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-use_qrcode" class="form-label">QRCode</label>
                            <input type="checkbox" id="edit-use_qrcode" name="use_qrcode">
                            <label for="edit-use_qrcode">Ingin Menggunakan QRCode</label>
                        </div>  
                        <div class="mb-3">
                            <label for="edit-position" class="form-label">Posisi Karyawan</label><br>
                            @foreach($positions as $position)
                            <input type="checkbox" id="edit_position_{{ $position->id }}" name="position[]"
                                value="{{ $position->name }}">
                            <label for="edit_position_{{ $position->id }}">{{ $position->name }}</label><br>
                            @endforeach
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <form id="hapusEdit" class="position-absolute" style="top: 77%; left: 36%;" action=""
                            method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-layout>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var editModal = document.getElementById('editModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var title = button.getAttribute('data-title');
            var description = button.getAttribute('data-description');
            var start_time = button.getAttribute('data-start_time');
            var batas_start_time = button.getAttribute('data-batas_start_time');
            var end_time = button.getAttribute('data-end_time');
            var batas_end_time = button.getAttribute('data-batas_end_time');
            var use_qrcode = button.getAttribute('data-use_qrcode');

            var form = document.getElementById('editForm');
            form.action = '/attendance/' + id;
            form.querySelector('#edit-title').value = title;
            form.querySelector('#edit-description').value = description;
            form.querySelector('#edit-start_time').value = start_time;
            form.querySelector('#edit-batas_start_time').value = batas_start_time;
            form.querySelector('#edit-end_time').value = end_time;
            form.querySelector('#edit-batas_end_time').value = batas_end_time;
            form.querySelector('#edit-use_qrcode').checked = use_qrcode !== null && use_qrcode !== '';

            // Update positions checkboxes
            var positionCheckboxes = document.querySelectorAll('#editModal input[name="position[]"]');
            positionCheckboxes.forEach(function (checkbox) {
                checkbox.checked = button.getAttribute('data-positions').split(',').includes(checkbox.value);
            });

            // Handle delete button
            var deleteForm = document.getElementById('hapusEdit');
            deleteForm.action = '/attendance/' + id;
        });
    });
</script>
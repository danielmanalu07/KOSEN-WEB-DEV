@extends('components.layout')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <a href="{{ url('/admin/absensis/create') }}" class="btn btn-sm btn-info">Buat Absensi</a>
                <div class="form-check form-switch">
                    <meta name="csrf-token" content="{{ csrf_token() }}">
                    <input class="form-check-input" type="checkbox" id="accessToggle"
                        {{ session('absensi_access') ? 'checked' : '' }}>
                    <label class="form-check-label" for="accessToggle">Akses Absensi</label>
                </div>
            </div>

            <div class="card-body">
                @if (Session::has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>{{ Session::get('success') }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <table class="table table-responsive">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>Deskripsi</th>
                            <th>Waktu Mulai</th>
                            <th>Waktu Selesai</th>
                            <th>Waktu Check Out</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($absensis as $key => $absensi)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $absensi->judul }}</td>
                                <td>{{ Str::limit($absensi->deskripsi, 15) }}</td>
                                <td>{{ $absensi->start_time }}</td>
                                <td>{{ $absensi->end_time }}</td>
                                <td>{{ $absensi->checkOut_time }}</td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input status-toggle" type="checkbox" role="switch"
                                            id="statusSwitch{{ $absensi->id }}"
                                            {{ $absensi->status === 'published' ? 'checked' : '' }}
                                            data-id="{{ $absensi->id }}"
                                            data-url="{{ route('toggle.status', $absensi->id) }}">
                                        <label class="form-check-label" for="statusSwitch{{ $absensi->id }}">
                                            {{ $absensi->status === 'published' ? 'Published' : 'Unpublished' }}
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <form id="deleteForm{{ $absensi->id }}"
                                        action="{{ url('/admin/absensis/' . $absensi->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#absensiModal{{ $absensi->id }}">
                                            <iconify-icon icon="mdi:eye"></iconify-icon>
                                        </button>
                                        <a href="{{ url('/admin/absensis/' . $absensi->id . '/edit') }}"
                                            class="btn btn-sm btn-warning">
                                            <iconify-icon icon="mdi:edit"></iconify-icon>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger delete-btn"
                                            data-id="{{ $absensi->id }}">
                                            <iconify-icon icon="mdi:trash"></iconify-icon>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <div class="modal fade" id="absensiModal{{ $absensi->id }}" tabindex="-1"
                                aria-labelledby="absensiModalLabel{{ $absensi->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="absensiModalLabel{{ $absensi->id }}">Detail
                                                Absensi</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Judul:</strong> {{ $absensi->judul }}</p>
                                            <p><strong>Deskripsi:</strong> {{ $absensi->deskripsi }}</p>
                                            <p><strong>Waktu Mulai:</strong> {{ $absensi->start_time }}</p>
                                            <p><strong>Waktu Selesai:</strong> {{ $absensi->end_time }}</p>
                                            <p><strong>Status:</strong> {{ $absensi->status }}</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak Ada Data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle status toggle
            const toggles = document.querySelectorAll('.status-toggle');
            toggles.forEach(toggle => {
                toggle.addEventListener('change', function() {
                    const id = this.dataset.id;
                    const url = this.dataset.url;
                    const status = this.checked ? 'published' : 'unpublished';

                    fetch(url, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                status: status
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                this.nextElementSibling.textContent = status.charAt(0)
                                    .toUpperCase() + status.slice(1);

                                Swal.fire({
                                    title: 'Status Updated!',
                                    text: 'Status Absensi berhasil diubah menjadi ' +
                                        status.charAt(0).toUpperCase() + status.slice(
                                        1),
                                    icon: 'success',
                                    confirmButtonText: 'Ok'
                                });
                            } else {
                                console.error('Failed to update status');
                                this.checked = !this.checked;
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            this.checked = !this.checked;
                        });
                });
            });

            // Handle delete buttons
            const deleteButtons = document.querySelectorAll('.delete-btn');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const absensiId = this.dataset.id;
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Anda tidak akan bisa mengembalikan data yang dihapus!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('deleteForm' + absensiId).submit();
                        }
                    });
                });
            });

            // Handle access toggle
            const accessToggle = document.getElementById('accessToggle');
            if (accessToggle) {
                accessToggle.addEventListener('change', function() {
                    const access = this.checked ? 'on' : 'off';

                    fetch("{{ route('toggle.access') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                access: access
                            })
                        })
                        .then(response => {
                            if (response.ok) {
                                Swal.fire({
                                    title: 'Status Akses Diubah!',
                                    text: 'Akses Absensi berhasil diubah.',
                                    icon: 'success',
                                    confirmButtonText: 'Ok'
                                });
                            }
                        });
                });
            }
        });
    </script>
@endpush

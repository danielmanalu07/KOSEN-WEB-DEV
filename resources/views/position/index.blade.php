<x-layout>
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-body">
          <div class="mb-3 mb-sm-0">
            <div class="row">
              <div class="col">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                  Tambah Data +
                </button>
              </div>
            </div>
            <div class="row">
              <div class="table-responsive">
                <table class="table table-striped table-hover">
                  <thead>
                    <tr>
                      <th class="col-1 text-black" scope="col">#</th>
                      <th class="col text-black" scope="col">Position Name</th>
                      <th class="col-2 text-black" scope="col">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($positions as $position)
                    <tr>
                      <th class="text-black" scope="row">{{ $loop->iteration }}</th>
                      <td class="text-black">{{ $position->name }}</td>
                      <td>
                        <button class="btn btn-sm btn-warning btn-edit" data-id="{{ $position->id }}"
                          data-name="{{ $position->name }}">
                          Edit
                        </button>
                      </td>
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

  <!-- Modal for adding a new position -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Posisi</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('position.index') }}" method="POST">
            @csrf
            <div class="mb-3">
              <label for="name" class="form-label">Position Name</label>
              <input type="text" class="form-control" id="name" name="name">
            </div>
            <button type="submit" class="btn btn-primary">Tambah</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal for editing position -->
  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="editModalLabel">Edit Posisi</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="editForm" action="" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
              <label for="edit-name" class="form-label">Position Name</label>
              <input type="text" class="form-control" id="edit-name" name="name">
            </div>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
          </form>
          <form id="hapusEdit" action="" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Hapus</button>
          </form>

        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var editButtons = document.querySelectorAll('.btn-edit');

      editButtons.forEach(function(button) {
        button.addEventListener('click', function() {
          var id = this.getAttribute('data-id');
          var name = this.getAttribute('data-name');

          var form = document.getElementById('editForm');
          var actionEdit = "{{ route('position.index', 'id_placeholder') }}".replace('id_placeholder', id);
          form.action = actionEdit;
          document.getElementById('edit-name').value = name;

          var hapusEdit = "{{ route('position.index', 'id_placeholder') }}".replace('id_placeholder', id);
          document.getElementById('hapusEdit').action = hapusEdit;

          var editModal = new bootstrap.Modal(document.getElementById('editModal'));
          editModal.show();
        });
      });
    });
  </script>
</x-layout>
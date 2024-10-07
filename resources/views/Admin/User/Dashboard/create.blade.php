@extends('components.layout')

@section('content')
<main class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1>Tambah Data Pegawai</h1>

            <form id="userForm">
                @csrf
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="nama" name="nama" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <div class="mb-3">
                    <label for="umur" class="form-label">Umur</label>
                    <input type="number" class="form-control" id="umur" name="umur" required>
                </div>

                <div class="mb-3">
                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                    <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" required>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>

            <div id="resultContainer" style="display:none;">
                <h2>User Created Successfully!</h2>
                <p>Password: <span id="generatedPassword"></span></p>

                <h3>User QR Code:</h3>
                <canvas id="qrCodeCanvas"></canvas>
            </div>
        </div>
    </div>
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
<script>
    $(document).ready(function() {
        $('#userForm').on('submit', function(e) {
            e.preventDefault();

            const formData = {
                nama: $('#nama').val(),
                email: $('#email').val(),
                umur: $('#umur').val(),
                tanggal_lahir: $('#tanggal_lahir').val(),
                phone: $('#phone').val(),
                _token: $('input[name="_token"]').val()
            };

            $.ajax({
                url: '{{ route("users.store") }}',
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        // Show the result container and display the password
                        $('#resultContainer').show();
                        $('#generatedPassword').text(response.password);

                        // Generate QR code based on the user_id
                        const qrCodeUrl = '{{ route("attendances.show", ":id") }}'.replace(':id', response.user_id);
                        generateQRCode(qrCodeUrl);
                    } else {
                        alert('Error creating user.');
                    }
                },
                error: function(xhr) {
                    alert('An error occurred: ' + xhr.responseText);
                }
            });
        });

        function generateQRCode(data) {
            // Use qrious.js to generate the QR code
            var qr = new QRious({
                element: document.getElementById('qrCodeCanvas'),
                value: data,
                size: 200
            });
        }
    });
</script>
@endsection

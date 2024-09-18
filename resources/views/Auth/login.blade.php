<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>LOGIN - KOMIFEN</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/logo.jpeg') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body>
    <main>
        <div class="box">
            <div class="togaIcon">
                <div class="circle">
                    <img src="{{ asset('assets/images/toga.png') }}" />
                </div>
            </div>
            <div class="formLogin">
                <form id="loginForm" action="" method="POST">
                    @csrf
                    <table>
                        <tr>
                            <td>
                                <div class="container">
                                    <div class="did-floating-label-content">
                                        <input class="did-floating-input" type="email" placeholder=""
                                            name="email" />
                                        <label class="did-floating-label">Email</label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="container">
                                    <div class="did-floating-label-content">
                                        <input class="did-floating-input" type="password" placeholder=""
                                            name="password" />
                                        <label class="did-floating-label">Password</label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><button type="submit" class="btn w-100 btn-info" id="submitButton">
                                    <span class="spinner-border spinner-border-sm d-none" role="status"
                                        aria-hidden="true"></span>
                                    <span class="button-text">Masuk</span>
                                </button>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Login',
                    text: '{{ $errors->first() }}',
                    confirmButtonText: 'OK'
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Akses Ditolak',
                    text: '{{ session('error') }}',
                    confirmButtonText: 'OK'
                });
            @endif

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif
        });

        document.getElementById('loginForm').addEventListener('submit', function(event) {
            var submitButton = document.getElementById('submitButton');
            var spinner = submitButton.querySelector('.spinner-border');
            var buttonText = submitButton.querySelector('.button-text');

            spinner.classList.remove('d-none');
            buttonText.textContent = 'Loading...';

            submitButton.disabled = true;
        });
    </script>

</body>

</html>

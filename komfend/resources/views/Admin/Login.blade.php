<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Form Login</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Custom Style -->
    <style>
        body {
            background: linear-gradient(to right, #74ebd5, #ACB6E5);
            font-family: 'Arial', sans-serif;
        }

        .card {
            border: none;
            border-radius: 15px;
        }

        .form-control {
            border-radius: 25px;
            padding-left: 40px;
        }

        .input-group-text {
            border: none;
            background-color: transparent;
            color: #6c757d;
            position: absolute;
            left: 10px;
        }

        .btn-custom {
            background-color: #6a11cb;
            color: white;
            border-radius: 25px;
            transition: all 0.3s ease-in-out;
        }

        .btn-custom:hover {
            background-color: #2575fc;
            color: white;
        }

        .scanner-btn {
            color: #6a11cb;
            transition: all 0.3s ease-in-out;
        }

        .scanner-btn:hover {
            color: #2575fc;
        }
    </style>
</head>

<body>

    <!-- Section: Design Block -->
    <section class="text-center text-lg-start">
        <div class="container py-5">
            <div class="row g-0 align-items-center justify-content-center">
                <div class="col-lg-6">
                    <div class="card shadow-lg p-3 mb-5 bg-body-tertiary">
                        <div class="card-body p-5">
                            <h2 class="fw-bold mb-4 text-center text-primary">Silahkan Masuk</h2>

                            <!-- Notifications -->
                            @if (Session::has('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong><i class="fas fa-exclamation-circle"></i> {{ Session()->get('error') }}</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                            @endif
                            @if (Session::has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong><i class="fas fa-check-circle"></i> {{ Session()->get('success') }}</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                            @endif

                            <!-- Login Form -->
                            <form action="{{ route('Admin.Login') }}" method="POST">
                                @csrf

                                <!-- Username Input -->
                                <div class="mb-4 position-relative">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" id="username" name="username" class="form-control"
                                        placeholder="Username">
                                    @error('username')
                                    <div class="alert alert-danger m-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Password Input -->
                                <div class="mb-4 position-relative">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" id="password" name="password" class="form-control"
                                        placeholder="Password">
                                    @error('password')
                                    <div class="alert alert-danger m-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-custom btn-block w-100">
                                    <i class="fas fa-sign-in-alt"></i> Masuk
                                </button>
                            </form>

                            <!-- Toggle Button for Scanner -->
                            <div class="text-center mt-4">
                                <a href="{{ route('Absensi') }}" class="scanner-btn">
                                    <i class="fas fa-qrcode fa-2x"></i>
                                    <p>Pergi ke Scanner</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side Image -->
                <div class="col-lg-6 d-none d-lg-block">
                    <img src="https://mdbootstrap.com/img/new/ecommerce/vertical/004.jpg"
                        class="w-100 rounded-4 shadow-4" alt="Login Image" />
                </div>
            </div>
        </div>
    </section>
    <!-- Section: Design Block -->

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>

</body>

</html>
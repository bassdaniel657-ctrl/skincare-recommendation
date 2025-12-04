<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SkinRecommender | Login</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #6c63ff;
        }

        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            background-image: url('https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?auto=format&fit=crop&w=1500&q=60');
            background-size: cover;
            background-position: center;
        }

        .login-container {
            max-width: 420px;
            width: 100%;
            margin: auto;
            background-color: rgba(255, 255, 255, .95);
            border-radius: 12px;
            box-shadow: 0 10px 35px rgba(0, 0, 0, .15);
            overflow: hidden;
        }

        .login-header {
            background: var(--primary-color);
            color: white;
            text-align: center;
            padding: 25px 20px;
        }

        .login-header i {
            font-size: 2.5rem;
        }

        .login-body {
            padding: 30px;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #5a52d6;
            border-color: #5a52d6;
        }

        .input-group-text {
            background: #f1f1f1;
        }

        .alert-fixed {
            position: fixed;
            top: 20px;
            right: 20px;
            max-width: 350px;
            z-index: 1000;
        }

        .show-pass-icon {
            cursor: pointer;
        }
    </style>
</head>

<body>

    @if (session('error'))
        <div class="alert alert-warning alert-dismissible fade show alert-fixed" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="login-container">
        <div class="login-header">
            <i class="fas fa-spa mb-2"></i>
            <h2 class="mt-2">SkinRecommender</h2>
        </div>

        <div class="login-body">
            <p class="text-center mb-4">Masuk untuk mengakses rekomendasi skincare personal Anda</p>

            <form action="{{ route('login.process') }}" method="POST">
                @csrf

                {{-- Email --}}
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="Email Anda"
                            value="{{ old('email') }}"
                            required
                        >
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Password --}}
                <div class="mb-4">
                    <label for="password" class="form-label fw-semibold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Password"
                            required
                        >
                        <span class="input-group-text bg-white show-pass-icon" onclick="togglePassword()">
                            <i id="toggleIcon" class="fas fa-eye"></i>
                        </span>
                    </div>

                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <button class="btn btn-primary btn-lg w-100" type="submit">
                    <i class="fas fa-sign-in-alt me-2"></i> Masuk
                </button>

                <div class="text-center mt-3">
                    <small>Belum punya akun?</small>
                    <a href="{{ route('register') }}" class="text-decoration-none fw-semibold">Daftar sekarang</a>
                </div>
            </form>
        </div>
    </div>

    <!-- JS -->
    <script>
        function togglePassword() {
            const passwordField = document.getElementById("password");
            const toggleIcon = document.getElementById("toggleIcon");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleIcon.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                passwordField.type = "password";
                toggleIcon.classList.replace("fa-eye-slash", "fa-eye");
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

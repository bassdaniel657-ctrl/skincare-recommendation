<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SkinRecommender | Register</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome Icons -->
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

        .register-container {
            width: 100%;
            max-width: 420px;
            margin: auto;
            background-color: rgba(255, 255, 255, .95);
            border-radius: 12px;
            box-shadow: 0 10px 35px rgba(0, 0, 0, .15);
            overflow: hidden;
        }

        .register-header {
            background: var(--primary-color);
            padding: 25px;
            color: white;
            text-align: center;
        }

        .register-header i {
            font-size: 2.5rem;
        }

        .register-body {
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

        .show-pass-icon {
            cursor: pointer;
        }

        .alert-fixed {
            position: fixed;
            top: 20px;
            right: 20px;
            max-width: 350px;
            z-index: 1000;
        }
    </style>
</head>

<body>

    {{-- Alert Global --}}
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show alert-fixed">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show alert-fixed">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="register-container">
        <div class="register-header">
            <i class="fas fa-spa mb-2"></i>
            <h2>Daftar Akun Baru</h2>
        </div>

        <div class="register-body">

            <form action="{{ route('register.process') }}" method="POST">
                @csrf

                {{-- NAME --}}
                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}"
                            placeholder="Nama lengkap Anda"
                            required>
                    </div>
                    @error('name')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- EMAIL --}}
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}"
                            placeholder="Email Anda"
                            required>
                    </div>
                    @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- PASSWORD --}}
                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            required>

                        <span class="input-group-text bg-white show-pass-icon" onclick="togglePassword('password','toggleIcon1')">
                            <i id="toggleIcon1" class="fas fa-eye"></i>
                        </span>
                    </div>
                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- CONFIRM PASSWORD --}}
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>

                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="form-control"
                            required>

                        <span class="input-group-text bg-white show-pass-icon" onclick="togglePassword('password_confirmation','toggleIcon2')">
                            <i id="toggleIcon2" class="fas fa-eye"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                    <i class="fas fa-user-plus me-2"></i> Daftar
                </button>

                <div class="text-center">
                    <small class="text-muted">Sudah punya akun?</small>
                    <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">Masuk disini</a>
                </div>

            </form>
        </div>
    </div>

    <!-- JS: Password Toggler -->
    <script>
        function togglePassword(fieldId, iconId) {
            const input = document.getElementById(fieldId);
            const icon = document.getElementById(iconId);

            if (input.type === "password") {
                input.type = "text";
                icon.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.replace("fa-eye-slash", "fa-eye");
            }
        }
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

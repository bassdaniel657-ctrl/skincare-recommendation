<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkinRecommender - Rekomendasi Skincare Berdasarkan Bahan</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
                url('https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?auto=format&fit=crop&w=1500&q=60');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 120px 0;
            text-align: center;
        }

        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #6c63ff;
        }

        .benefit-card {
            border-radius: 10px;
            transition: transform .3s;
        }

        .benefit-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 8px 18px rgba(0,0,0,0.1);
        }

        footer {
            background-color: #343a40;
            color: white;
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">

            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-spa me-2"></i>SkinRecommender
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#features">Fitur</a></li>
                    <li class="nav-item"><a class="nav-link" href="#how-it-works">Cara Kerja</a></li>
                    <li class="nav-item"><a class="nav-link" href="#benefits">Manfaat</a></li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light ms-lg-3" href="{{ route('login') }}">Login</a>
                    </li>
                </ul>
            </div>

        </div>
    </nav>

    <!-- HERO -->
    <section class="hero-section">
        <div class="container">
            <h1 class="display-5 fw-bold mb-4">Temukan Skincare Terbaik Sesuai Kebutuhan Kulit Anda</h1>
            <p class="lead mb-5">Sistem AI kami menganalisis ingredients untuk memberikan rekomendasi produk yang tepat</p>

            <!-- FORM SEARCH -->
            <div class="search-box mx-auto" style="max-width: 600px;">
                <form action="{{ route('user.recommend') }}" method="POST">
                    @csrf
                    <div class="input-group input-group-lg">
                        <input type="text" name="query" class="form-control" placeholder="Contoh: niacinamide|allantoin|aha" required>
                        <button class="btn btn-primary" type="submit"><i class="fas fa-search me-2"></i>Cari</button>
                    </div>
                </form>
                <p class="text-white-50 mt-2">Silakan login untuk melihat hasil rekomendasi</p>
            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <section id="features" class="py-5 bg-light">
        <div class="container text-center mb-5">
            <h2 class="fw-bold">Fitur Unggulan</h2>
            <p class="lead text-muted">Menggunakan teknologi untuk memudahkan perawatan kulit Anda</p>
        </div>

        <div class="container">
            <div class="row g-4">

                <div class="col-md-4 text-center">
                    <i class="fas fa-flask feature-icon"></i>
                    <h4>Analisis Ingredients</h4>
                    <p class="text-muted">Memahami komposisi produk lebih dalam dengan bantuan sistem cerdas.</p>
                </div>

                <div class="col-md-4 text-center">
                    <i class="fas fa-robot feature-icon"></i>
                    <h4>Rekomendasi AI</h4>
                    <p class="text-muted">Sistem terus belajar untuk memberikan hasil rekomendasi paling akurat.</p>
                </div>

                <div class="col-md-4 text-center">
                    <i class="fas fa-check-circle feature-icon"></i>
                    <h4>Data Valid</h4>
                    <p class="text-muted">Rekomendasi berdasarkan database produk skincare tepercaya.</p>
                </div>

            </div>
        </div>
    </section>

    <!-- HOW IT WORKS -->
    <section id="how-it-works" class="py-5">
        <div class="container text-center">
            <h2 class="fw-bold mb-4">Cara Kerja</h2>
            <p class="lead text-muted mb-5">3 langkah sederhana untuk mulai mendapatkan rekomendasi</p>

            <div class="row align-items-center">

                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="https://images.unsplash.com/photo-1556228578-8c89e6adf883?auto=format&fit=crop&w=1500&q=60"
                         class="img-fluid rounded shadow" alt="Skincare Image">
                </div>

                <div class="col-lg-6">
                    <div class="mb-4 d-flex">
                        <span class="bg-primary text-white fw-bold rounded-circle d-flex align-items-center justify-content-center"
                              style="width:50px; height:50px;">1</span>
                        <div class="ms-3">
                            <h5>Input Ingredients</h5>
                            <p class="text-muted mb-0">Masukkan bahan aktif yang ingin Anda cari.</p>
                        </div>
                    </div>

                    <div class="mb-4 d-flex">
                        <span class="bg-primary text-white fw-bold rounded-circle d-flex align-items-center justify-content-center"
                              style="width:50px; height:50px;">2</span>
                        <div class="ms-3">
                            <h5>Analisis Sistem</h5>
                            <p class="text-muted mb-0">Sistem kami mengolah data menggunakan algoritma AI & similarity.</p>
                        </div>
                    </div>

                    <div class="d-flex">
                        <span class="bg-primary text-white fw-bold rounded-circle d-flex align-items-center justify-content-center"
                              style="width:50px; height:50px;">3</span>
                        <div class="ms-3">
                            <h5>Rekomendasi Produk</h5>
                            <p class="text-muted mb-0">Anda akan mendapatkan daftar produk yang sesuai.</p>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <!-- BENEFITS -->
    <section id="benefits" class="py-5 bg-light">
        <div class="container text-center mb-4">
            <h2 class="fw-bold">Manfaat</h2>
            <p class="lead text-muted">Mengapa SkinRecommender layak dicoba?</p>
        </div>

        <div class="container">
            <div class="row g-4">

                <div class="col-md-4">
                    <div class="card benefit-card p-4">
                        <div class="card-body text-center">
                            <i class="fas fa-hand-holding-heart text-success mb-3" style="font-size:2rem;"></i>
                            <h4>Minim Risiko</h4>
                            <p>Hindari bahan yang berpotensi menyebabkan iritasi atau breakout.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card benefit-card p-4">
                        <div class="card-body text-center">
                            <i class="fas fa-clock text-warning mb-3" style="font-size:2rem;"></i>
                            <h4>Hemat Waktu</h4>
                            <p>Tak perlu lagi mencoba banyak produk secara acak.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card benefit-card p-4">
                        <div class="card-body text-center">
                            <i class="fas fa-chart-line text-primary mb-3" style="font-size:2rem;"></i>
                            <h4>Perkembangan Kulit</h4>
                            <p>Rekomendasi semakin akurat seiring Anda memberi feedback.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-5 bg-primary text-white text-center">
        <div class="container">
            <h2 class="fw-bold mb-4">Siap Meningkatkan Kualitas Skincare Anda?</h2>
            <p class="lead mb-4">Daftar dan dapatkan rekomendasi terbaik dari AI kami</p>
            <a href="{{ route('login') }}" class="btn btn-light btn-lg px-5">
                Mulai Sekarang <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="py-4">
        <div class="container">

            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-spa me-2"></i>SkinRecommender</h5>
                    <p class="small">Platform rekomendasi skincare berbasis AI pertama di Indonesia.</p>
                </div>

                <div class="col-md-3">
                    <h5>Tautan</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ url('/') }}" class="text-white text-decoration-none">Beranda</a></li>
                        <li><a href="#features" class="text-white text-decoration-none">Fitur</a></li>
                        <li><a href="#how-it-works" class="text-white text-decoration-none">Cara Kerja</a></li>
                        <li><a href="#benefits" class="text-white text-decoration-none">Manfaat</a></li>
                    </ul>
                </div>

                <div class="col-md-3">
                    <h5>Kontak</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-envelope me-2"></i>info@skinrecommender.com</li>
                        <li><i class="fas fa-phone me-2"></i>+62 812 3456 7890</li>
                    </ul>
                </div>
            </div>

            <hr class="my-4 bg-light">

            <div class="row">
                <div class="col-md-6 text-center text-md-start small">
                    © {{ date('Y') }} SkinRecommender. All rights reserved.
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>

        </div>
    </footer>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>

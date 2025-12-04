@extends('layouts.user')

@section('content')
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12 text-center">
          <h1 class="m-0 text-primary">
            <i class="fas fa-spa mr-2"></i>
            Sistem Rekomendasi Produk Skincare
          </h1>
          <p class="text-muted mt-2">Temukan produk skincare yang tepat berdasarkan kandungan bahan aktif</p>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      <!-- Welcome Card -->
      <div class="row justify-content-center mb-4">
        <div class="col-12 col-lg-10">
          <div class="card bg-gradient-primary text-white">
            <div class="card-body text-center py-4">
              <h3 class="card-title mb-3">
                <i class="fas fa-search mr-2"></i>
                Selamat Datang di SkinRecommender
              </h3>
              <p class="card-text lead mb-0">
                Dapatkan rekomendasi produk skincare sesuai kebutuhan kulit Anda menggunakan teknologi
                <strong>Cosine Similarity</strong> & <strong>Euclidean Distance</strong>
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Search Form -->
      <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
          <div class="card shadow-lg">
            <div class="card-header bg-white border-0 pb-0">
              <div class="text-center">
                <h4 class="card-title text-dark mb-2">
                  <i class="fas fa-flask mr-2 text-info"></i>
                  Cari Produk Berdasarkan Kandungan
                </h4>
                <p class="text-muted">Masukkan bahan aktif untuk mendapatkan rekomendasi terbaik</p>
              </div>
            </div>

            <div class="card-body pt-2">
              <form action="{{ route('user.recommend') }}" method="POST">
                @csrf

                <div class="mb-4">
                  <label for="query" class="form-label h6 text-dark">
                    <i class="fas fa-leaf mr-1 text-success"></i>
                    Kandungan Bahan Aktif
                  </label>

                  <div class="input-group input-group-lg">
                    <span class="input-group-text bg-light">
                      <i class="fas fa-search text-muted"></i>
                    </span>

                    <input type="text"
                           class="form-control border-left-0"
                           id="query"
                           name="query"
                           placeholder="Contoh: niacinamide|vitamin c|hyaluronic acid"
                           required>
                  </div>

                  <div class="form-text mt-2">
                    <i class="fas fa-info-circle mr-1 text-info"></i>
                    Pisahkan tiap bahan dengan tanda <code>|</code>.
                    <br>Contoh lengkap: <em>niacinamide|vitamin c|hyaluronic acid|aloe barbadensis leaf extract</em>
                  </div>
                </div>

                <!-- Popular Ingredients -->
                <div class="mb-4">
                  <label class="form-label h6 text-dark mb-3">
                    <i class="fas fa-star mr-1 text-warning"></i>
                    Bahan Populer
                  </label>

                  <div class="row">
                    @php
                      $popular = [
                        'niacinamide',
                        'hyaluronic acid',
                        'vitamin c',
                        'retinol',
                        'ceramide',
                        'salicylic acid'
                      ];
                    @endphp

                    @foreach ($popular as $item)
                      <div class="col-md-4 mb-2">
                        <button type="button"
                                class="btn btn-outline-primary btn-sm w-100 ingredient-btn"
                                data-ingredient="{{ $item }}">
                          {{ ucfirst($item) }}
                        </button>
                      </div>
                    @endforeach
                  </div>
                </div>

                <div class="text-center">
                  <button type="submit" class="btn btn-primary btn-lg px-5 py-3">
                    <i class="fas fa-magic mr-2"></i>
                    Dapatkan Rekomendasi
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Info Cards -->
      <div class="row justify-content-center mt-5">
        <div class="col-12 col-lg-10">
          <div class="row">
            <div class="col-md-6 mb-3">
              <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                  <div class="text-primary mb-3">
                    <i class="fas fa-calculator fa-2x"></i>
                  </div>
                  <h5 class="card-title">Algoritma Cosine Similarity</h5>
                  <p class="card-text text-muted">Mengukur kesamaan berdasarkan sudut antar vektor</p>
                </div>
              </div>
            </div>

            <div class="col-md-6 mb-3">
              <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                  <div class="text-info mb-3">
                    <i class="fas fa-ruler fa-2x"></i>
                  </div>
                  <h5 class="card-title">Algoritma Euclidean Distance</h5>
                  <p class="card-text text-muted">Mengukur jarak antar fitur kandungan produk</p>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>

    </div>
  </section>

  <style>
    .ingredient-btn:hover {
      transform: translateY(-2px);
      transition: all 0.3s ease;
    }
    .card {
      border-radius: 15px;
    }
    .btn-lg {
      border-radius: 25px;
      font-weight: 600;
    }
    .input-group-lg .form-control {
      border-radius: 0 25px 25px 0;
    }
    .input-group-lg .input-group-text {
      border-radius: 25px 0 0 25px;
    }
  </style>

  <script>
    // Auto lowercase input to match preprocessing pipeline
    document.getElementById('query').addEventListener('input', function () {
      this.value = this.value.toLowerCase();
    });

    // Ingredient button behavior
    document.querySelectorAll('.ingredient-btn').forEach(btn => {
      btn.addEventListener('click', function() {

        const ingredient = this.dataset.ingredient;
        const queryInput = document.getElementById('query');
        const currentValue = queryInput.value.trim();

        // Insert ingredient to input
        queryInput.value = currentValue === ''
          ? ingredient
          : currentValue + '|' + ingredient;

        // Auto lowercase for consistency
        queryInput.value = queryInput.value.toLowerCase();

        // Detect initial state so color returns correctly
        const isPrimary = this.classList.contains('btn-outline-primary');
        const isSecondary = this.classList.contains('btn-outline-secondary');

        // Visual feedback
        this.classList.add('btn-success');
        this.classList.remove('btn-outline-primary', 'btn-outline-secondary');

        setTimeout(() => {
          this.classList.remove('btn-success');
          if (isPrimary) this.classList.add('btn-outline-primary');
          if (isSecondary) this.classList.add('btn-outline-secondary');
        }, 700);
      });
    });
  </script>
@endsection

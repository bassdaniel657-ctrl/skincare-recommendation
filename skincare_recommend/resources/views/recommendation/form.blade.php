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
        <p class="text-muted mt-2">Masukkan bahan aktif untuk mendapatkan rekomendasi terbaik</p>
      </div>
    </div>
  </div>
</div>

<section class="content">
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-8">

        <div class="card shadow-lg">
          <div class="card-header bg-white text-center">
            <h4 class="text-dark mb-1">
              <i class="fas fa-flask mr-2 text-info"></i>
              Cari Produk Skincare
            </h4>
            <p class="text-muted">Masukkan ingredients lalu tekan "Dapatkan Rekomendasi"</p>
          </div>

          <div class="card-body">
            <form action="{{ route('user.recommend') }}" method="POST">
              @csrf

              <label class="form-label h6">Ingredients (gunakan tanda | untuk memisahkan)</label>

              <div class="input-group mb-3 input-group-lg">
                <span class="input-group-text bg-light">
                  <i class="fas fa-search"></i>
                </span>
                <input type="text"
                       name="query"
                       id="query"
                       class="form-control"
                       placeholder="Contoh: niacinamide|vitamin c|hyaluronic acid"
                       required>
              </div>

              <div class="form-text mb-3">
                <i class="fas fa-info-circle text-info"></i>
                Contoh format: <code>niacinamide|retinol|ceramide</code>
              </div>

              <label class="form-label h6">Bahan Populer:</label>
              <div class="row">
                @foreach (['niacinamide','hyaluronic acid','vitamin c','retinol','ceramide','salicylic acid'] as $item)
                  <div class="col-md-4 mb-2">
                    <button type="button"
                            class="btn btn-outline-primary btn-sm w-100 ingredient-btn"
                            data-ingredient="{{ $item }}">
                      {{ ucfirst($item) }}
                    </button>
                  </div>
                @endforeach
              </div>

              <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary btn-lg px-4 py-2">
                  <i class="fas fa-magic mr-2"></i>
                  Dapatkan Rekomendasi
                </button>
              </div>

            </form>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

<style>
  .ingredient-btn:hover {
    transform: translateY(-2px);
    transition: 0.3s;
  }
  .btn-lg {
    border-radius: 25px;
  }
</style>

<script>
  document.querySelectorAll(".ingredient-btn").forEach(btn => {
    btn.addEventListener("click", function() {
      const ing = this.dataset.ingredient;
      const input = document.getElementById("query");
      let value = input.value.trim();

      if (value === "") input.value = ing;
      else input.value = value + "|" + ing;

      this.classList.add("btn-success");
      setTimeout(() => {
        this.classList.remove("btn-success");
      }, 600);
    });
  });
</script>
@endsection

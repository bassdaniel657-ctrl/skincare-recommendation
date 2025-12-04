@extends('layouts.user')

@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-3">
      <div class="col-sm-12 text-center">
        <h1 class="m-0 text-primary">
          <i class="fas fa-magic mr-2"></i>
          Hasil Rekomendasi Produk Skincare
        </h1>
        <p class="text-muted mt-2">
          Berikut hasil rekomendasi berdasarkan bahan aktif yang Anda masukkan
        </p>
      </div>
    </div>
  </div>
</div>

<section class="content">
  <script>
    console.log({{$cosineResults}});
  </script>
  <div class="container-fluid">

    <!-- Query ingredients -->
    <div class="row justify-content-center mb-4">
      <div class="col-lg-8">
        <div class="card bg-gradient-info text-white shadow">
          <div class="card-body text-center">
            <h5><i class="fas fa-search mr-2"></i> Bahan yang Anda cari:</h5>
            <div class="mt-3">
              @php
                $queryArray = explode('|', $query);
              @endphp
              @foreach ($queryArray as $item)
                <span class="badge badge-light text-info px-3 py-2 mr-2 mb-2">
                  <i class="fas fa-leaf mr-1"></i>{{ trim($item) }}
                </span>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- COSINE -->
    <div class="card shadow mb-5">
      <div class="card-header bg-primary text-white">
        <h4 class="mb-0">
          <i class="fas fa-calculator mr-2"></i>
          Hasil Rekomendasi – Cosine Similarity
        </h4>
      </div>
      <div class="card-body">

        @if (count($cosineResults) > 0)
        <div class="table-responsive">
          <table class="table table-hover">
            <thead class="thead-dark">
              <tr>
                <th>Produk</th>
                <th>Brand</th>
                <th>Skor Similarity</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($cosineResults as $productId => $score)
                @php
                  $product = \App\Models\Product::find($productId);
                @endphp
                <tr>
                  <td><strong>{{ $product->product_name }}</strong></td>
                  <td>{{ $product->brand_name }}</td>
                  <td>{{ number_format($score, 4) }}</td>
                  <td>
                    <!-- Feedback -->
                    <button class="btn btn-success btn-sm feedbackButton"
                            data-method="cosine"
                            data-id="{{ $product->id }}"
                            data-query="{{ $userQuery->id }}">
                      <i class="fas fa-thumbs-up"></i>
                    </button>

                    <button class="btn btn-danger btn-sm feedbackButton"
                            data-method="cosine"
                            data-id="{{ $product->id }}"
                            data-query="{{ $userQuery->id }}">
                      <i class="fas fa-thumbs-down"></i>
                    </button>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @else
          <p class="text-muted">Tidak ada hasil rekomendasi.</p>
        @endif

      </div>
    </div>

    <!-- EUCLIDEAN -->
    <div class="card shadow mb-5">
      <div class="card-header bg-info text-white">
        <h4 class="mb-0">
          <i class="fas fa-ruler mr-2"></i>
          Hasil Rekomendasi – Euclidean Distance
        </h4>
      </div>
      <div class="card-body">

        @if (count($euclideanResults) > 0)
        <div class="table-responsive">
          <table class="table table-hover">
            <thead class="thead-dark">
              <tr>
                <th>Produk</th>
                <th>Brand</th>
                <th>Skor Similarity</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($euclideanResults as $productId => $score)
                @php
                  $product = \App\Models\Product::find($productId);
                @endphp
                <tr>
                  <td><strong>{{ $product->product_name }}</strong></td>
                  <td>{{ $product->brand_name }}</td>
                  <td>{{ number_format($score, 4) }}</td>
                  <td>
                    <!-- Feedback -->
                    <button class="btn btn-success btn-sm feedbackButton"
                            data-method="euclidean"
                            data-id="{{ $product->id }}"
                            data-query="{{ $userQuery->id }}">
                      <i class="fas fa-thumbs-up"></i>
                    </button>

                    <button class="btn btn-danger btn-sm feedbackButton"
                            data-method="euclidean"
                            data-id="{{ $product->id }}"
                            data-query="{{ $userQuery->id }}">
                      <i class="fas fa-thumbs-down"></i>
                    </button>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @else
          <p class="text-muted">Tidak ada hasil rekomendasi.</p>
        @endif

      </div>
    </div>

    <!-- Buttons -->
    <div class="text-center mb-5">
      <a href="{{ route('user.dashboard') }}" class="btn btn-primary btn-lg px-4 py-2">
        <i class="fas fa-search mr-2"></i>
        Coba Pencarian Baru
      </a>
    </div>

  </div>
</section>

@endsection

@section('scripts')
<script>
  // $(".giveFeedback").click(function() {
  //   let productId = $(this).data("id");
  //   let userQueryId = $(this).data("query");
  //   let method = $(this).data("method");
  //   let feedback = $(this).hasClass("btn-success") ? 1 : 0;

  //   $.post("{{ route('user.feedback') }}", {
  //     product_id: productId,
  //     user_query_id: userQueryId,
  //     similarity_type: method,
  //     feedback: feedback,
  //     _token: "{{ csrf_token() }}"
  //   }, function(res) {
  //     alert("Feedback telah disimpan!");
  //   });
  // });

  $(".feedbackButton").click(function() {
    let productId = $(this).data("id");
    let userQueryId = $(this).data("query");
    let method = $(this).data("method");
    let feedback = $(this).data("feedback");

    $.patch("{{ route('user.feedback') }}", {
      product_id: productId,
      user_query_id: userQuery,
      similarity_type: method,
      feedback: feedback,
    })
  });
</script>
@endsection

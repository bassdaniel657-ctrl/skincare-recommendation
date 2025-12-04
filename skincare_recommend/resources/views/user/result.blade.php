@extends('layouts.user')

@section('content')
<div class="content-header">
  <div class="container-fluid text-center">
    <h1 class="m-0 text-primary">
      <i class="fas fa-star mr-2"></i> Hasil Rekomendasi Produk Skincare
    </h1>
    <p class="text-muted">Rekomendasi terbaik berdasarkan kandungan yang Anda cari</p>
  </div>
</div>

<section class="content">
  <div class="container-fluid">
    <!-- Query Info -->
    <div class="row justify-content-center mb-4">
      <div class="col-10">
        <div class="card bg-light shadow-sm border-0">
          <div class="card-body text-center">
            <h5><i class="fas fa-search text-info mr-2"></i>Kandungan yang Dicari:</h5>
            @php
              $ingredients = explode('|', $query);
            @endphp
            @foreach ($ingredients as $ingredient)
              <span class="badge badge-primary px-3 py-2 mr-2 mb-2">
                <i class="fas fa-leaf mr-1"></i>{{ trim($ingredient) }}
              </span>
            @endforeach
          </div>
        </div>
      </div>
    </div>

    <!-- COSINE -->
    <div class="card shadow border-0 mb-5">
      <div class="card-header bg-primary text-white">
        <h3><i class="fas fa-calculator mr-2"></i> Cosine Similarity</h3>
      </div>
      <div class="card-body table-responsive">
        <table class="table table-hover">
          <thead class="thead-dark">
            <tr>
              <th>Nama Produk</th>
              <th>Brand</th>
              <th>Kandungan</th>
              <th>Harga</th>
              <th>Kategori</th>
              <th>Penilaian Anda</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($cosineResults as $rec)
              <tr>
                <td><strong>{{ $rec->product_name }}</strong></td>
                <td>{{ $rec->product->brand_name ?? '-' }}</td>
                <td><small>{{ Str::limit($rec->product->ingredients, 100) }}</small></td>
                <td>Rp{{ number_format($rec->product->price, 0, ',', '.') }}</td>
                <td>{{ $rec->product->category ?? '-' }}</td>
                <td>
                  <div class="btn-group">
                    <button class="btn btn-sm btn-outline-success feedback-btn"
                            data-user-query-id="{{ $userQuery->id }}"
                            data-product-id="{{ $rec->product_id }}"
                            data-feedback="1"
                            data-type="cosine">
                      👍
                    </button>
                    <button class="btn btn-sm btn-outline-danger feedback-btn"
                            data-user-query-id="{{ $userQuery->id }}"
                            data-product-id="{{ $rec->product_id }}"
                            data-feedback="0"
                            data-type="cosine">
                      👎
                    </button>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <!-- EUCLIDEAN -->
    <div class="card shadow border-0 mb-5">
      <div class="card-header bg-info text-white">
        <h3><i class="fas fa-ruler mr-2"></i> Euclidean Distance</h3>
      </div>
      <div class="card-body table-responsive">
        <table class="table table-hover">
          <thead class="thead-dark">
            <tr>
              <th>Nama Produk</th>
              <th>Brand</th>
              <th>Kandungan</th>
              <th>Harga</th>
              <th>Kategori</th>
              <th>Penilaian Anda</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($euclideanResults as $rec)
              <tr>
                <td><strong>{{ $rec->product_name }}</strong></td>
                <td>{{ $rec->product->brand_name ?? '-' }}</td>
                <td><small>{{ Str::limit($rec->product->ingredients, 100) }}</small></td>
                <td>Rp{{ number_format($rec->product->price, 0, ',', '.') }}</td>
                <td>{{ $rec->product->category ?? '-' }}</td>
                <td>
                  <div class="btn-group">
                    <button class="btn btn-sm btn-outline-success feedback-btn"
                            data-user-query-id="{{ $userQuery->id }}"
                            data-product-id="{{ $rec->product_id }}"
                            data-feedback="1"
                            data-type="euclidean">
                      👍
                    </button>
                    <button class="btn btn-sm btn-outline-danger feedback-btn"
                            data-user-query-id="{{ $userQuery->id }}"
                            data-product-id="{{ $rec->product_id }}"
                            data-feedback="0"
                            data-type="euclidean">
                      👎
                    </button>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <!-- Navigation -->
    <div class="text-center mb-4">
      <a href="{{ route('user.dashboard') }}" class="btn btn-primary btn-lg px-5">Cari Lagi</a>
      <a href="{{ route('user.history') }}" class="btn btn-outline-secondary btn-lg px-5">Lihat Riwayat</a>
    </div>
  </div>
</section>
@endsection

@section('scripts')
<script>
  $(document).ready(function() {
    $('.feedback-btn').click(function() {
      console.log('Feedback button clicked');
      const userQueryId = $(this).data('user-query-id');
      const productId = $(this).data('product-id');
      const feedback = $(this).data('feedback');
      const type = $(this).data('type');

      $.ajax({
        url: '{{ route("user.feedback") }}',
        method: 'PATCH',
        data: {
          user_query_id: userQueryId,
          product_id: productId,
          feedback: feedback,
          similarity_type: type,
          _token: '{{ csrf_token() }}'
        },
        success: function(res) {
          if (res.success) {
            alert('Feedback berhasil disimpan!');
          }
        },
        error: function(xhr) {
          console.error(xhr.responseText);
          alert('Terjadi kesalahan saat menyimpan feedback.');
        }
      });
    });
  });
</script>
@endsection

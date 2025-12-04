@extends('layouts.user')

@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-12 text-center">
        <h1 class="m-0 text-primary">
          <i class="fas fa-boxes mr-2"></i>
          Daftar Produk Skincare
        </h1>
        <p class="text-muted mt-2">Berikut adalah semua produk skincare yang tersedia di sistem</p>
      </div>
    </div>
  </div>
</div>

<section class="content">
  <div class="container-fluid">

    <div class="card shadow-lg border-0">
      <div class="card-header bg-white">
        <h4 class="card-title text-dark">
          <i class="fas fa-list mr-2 text-primary"></i>
          Tabel Produk
        </h4>
      </div>

      <div class="card-body">

        <div class="table-responsive">
          <table class="table table-hover table-striped" id="productTable">
            <thead class="thead-dark">
              <tr>
                <th>Nama Produk</th>
                <th>Brand</th>
                <th>Details</th>
                <th>Ingredients</th>
                <th>Harga</th>
                <th>Kategori</th>
              </tr>
            </thead>

            <tbody>
              @forelse ($products as $product)
              <tr>
                <td><strong>{{ $product->product_name }}</strong></td>

                <td>{{ $product->brand_name }}</td>

                <td>
                  <small class="text-muted">
                    {{ Str::limit($product->details, 70) }}
                  </small>
                </td>

                <td>
                  <small class="text-muted">
                    {{ Str::limit($product->ingredients, 80) }}
                  </small>
                </td>

                <td class="text-success font-weight-bold">
                  Rp{{ number_format($product->price, 0, ',', '.') }}
                </td>

                <td>
                  <span class="badge badge-primary px-3 py-2">{{ $product->category }}</span>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="6" class="text-center text-muted py-4">
                  <i class="fas fa-info-circle mr-1"></i> 
                  Tidak ada data produk.
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>

      </div>
    </div>

  </div>
</section>

@endsection

@section('scripts')
<script>
  $(document).ready(function() {
    $('#productTable').DataTable({
      pageLength: 10,
      language: {
        search: "Cari:",
        lengthMenu: "Tampilkan _MENU_ produk",
        info: "Menampilkan _START_ - _END_ dari _TOTAL_ produk",
        emptyTable: "Belum ada produk tersedia"
      }
    });
  });
</script>
@endsection

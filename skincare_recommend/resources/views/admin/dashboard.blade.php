@extends('layouts.admin')

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">

        <div class="col-sm-6">
          <h1 class="m-0">Admin Dashboard</h1>
        </div>

        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
          </ol>
        </div>

      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      <!-- Dashboard Summary Widgets -->
      <div class="row">

        {{-- Total Produk --}}
        <div class="col-lg-3 col-6">
          <div class="small-box bg-primary">
            <div class="inner">
              <h3>{{ $totalProducts ?? 0 }}</h3>
              <p>Total Produk</p>
            </div>
            <div class="icon">
              <i class="fas fa-box"></i>
            </div>
            <a href="{{ route('product.index') }}" class="small-box-footer">
              Lihat Produk <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        {{-- Total Riwayat Pencarian --}}
        <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
            <div class="inner">
              <h3>{{ $totalHistory ?? 0 }}</h3>
              <p>Riwayat Pencarian</p>
            </div>
            <div class="icon">
              <i class="fas fa-history"></i>
            </div>
            <a href="{{ route('admin.history') }}" class="small-box-footer">
              Lihat Riwayat <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        {{-- Total User --}}
        <div class="col-lg-3 col-6">
          <div class="small-box bg-warning">
            <div class="inner">
              <h3>{{ $totalUsers ?? 0 }}</h3>
              <p>Registered Users</p>
            </div>
            <div class="icon">
              <i class="fas fa-users"></i>
            </div>
            <a href="#" class="small-box-footer">
              Lihat User <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        {{-- Total Feedback --}}
        <div class="col-lg-3 col-6">
          <div class="small-box bg-danger">
            <div class="inner">
              <h3>{{ $totalFeedback ?? 0 }}</h3>
              <p>Total Feedback</p>
            </div>
            <div class="icon">
              <i class="fas fa-comment-dots"></i>
            </div>
            <a href="{{ route('admin.history') }}" class="small-box-footer">
              Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

      </div>

      <!-- Area untuk grafik / konten selanjutnya -->
      <div class="row">
        <div class="col-12">
          <div class="card shadow-sm">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-chart-bar mr-2"></i>
                Statistik Sistem Rekomendasi
              </h3>
            </div>
            <div class="card-body">
              <div class="mt-4">
              <div class="mb-3">
                <label for="pilihanSaya" class="form-label">Rentang waktu:</label>
                <select class="form-select" id="timeFilter" aria-label="Pilihan Dropdown Standar">
                    <option value="1" selected>7 hari terakhir</option>
                    <option value="2">1 bulan terakhir</option>
                </select>
              </div>
              </div>
              <div class="container my-5">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <h2 class="mb-4">Grafik Rekomendasi dan Feedback</h2>
                        <canvas id="grafikGarisSaya" class="p-3 border rounded shadow-sm"></canvas>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12">
          <div class="card shadow-sm">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-calculator mr-2"></i>
                update vektor TF-IDF
              </h3>
            </div>
            <div class="card-body">
              <button class="btn btn-info btn-sm update-vector-btn">
                Update Vektor
              </button>
            </div>
          </div>
        </div>
      </div>

    </div>
    <div id="page-loader" class="d-none justify-content-center align-items-center" 
      style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(255, 255, 255, 0.9); z-index: 9999;">
      
      <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
          <span class="sr-only">Loading...</span>
      </div>
      <p class="ml-3 text-primary">Meupdate vector...</p>
    </div>
  </section>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
    // Dapatkan konteks elemen canvas
    const ctx = document.getElementById('grafikGarisSaya');

    // const data = fetch('{{ route("admin.chart") }}')
    //   .then(response => response.json())
    //   .then(data => {
    //     console.log(data);
    //     return data.data;
    // });

    // Buat objek Chart baru
    const chart = new Chart(ctx, {
        // Tentukan jenis grafik sebagai 'line'
        type: 'line',
        data: {
            // Label sumbu X
            labels: {!!$chartLabels!!},
            datasets: [
              {
                label: 'jumlah query',
                data: {{$chartData}},
                // Styling garis
                borderColor: 'rgba(0, 123, 255, 1)', // Warna biru Bootstrap
                backgroundColor: 'rgba(0, 123, 255, 0.2)', // Isi area di bawah garis
                borderWidth: 2,
                tension: 0.4, // Membuat garis lebih melengkung/halus
                fill: true // Isi area di bawah garis
              },{
                label: 'jumlah feedback',
                data: {{$chartDataFeedback}},
                // Styling garis
                borderColor: 'rgba(123, 0, 255, 1)', // Warna biru Bootstrap
                backgroundColor: 'rgba(123, 0, 255, 0.2)', // Isi area di bawah garis
                borderWidth: 2,
                tension: 0.4, // Membuat garis lebih melengkung/halus
                fill: true // Isi area di bawah garis
              }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    const filter = document.getElementById('timeFilter');
    filter.addEventListener('change', function() {
      if(this.value == 1){
        chart.data.labels = {!!$chartLabels!!};
        chart.data.datasets[0].data = {{$chartData}};
        chart.update();
      } else
      if(this.value == 2){
        chart.data.labels = {!!$chartLabelsMonth!!};
        chart.data.datasets[0].data = {{$chartDataMonth}};
        chart.update();
      }
    });
</script>
<script>
  $(document).ready(function() {
    $('.update-vector-btn').click(function() {
      console.log('Feedback button clicked');
      const userQueryId = $(this).data('user-query-id');
      const productId = $(this).data('product-id');
      const feedback = $(this).data('feedback');
      const type = $(this).data('type');
      // console.log("update vector clicked");
      $('#page-loader').addClass('d-flex').removeClass('d-none');

      $.ajax({
        url: '{{ route("admin.update.vector") }}',
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
            alert('response: ' + res.message);
          }
          $('#page-loader').addClass('d-none').removeClass('d-flex');
        },
        error: function(xhr) {
          console.error(xhr.responseText);
          alert('Terjadi kesalahan saat meupdate vector.');
          $('#page-loader').addClass('d-none').removeClass('d-flex');
        }
      });
    });
  });
</script>
@endsection

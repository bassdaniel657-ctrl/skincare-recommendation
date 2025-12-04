@extends('layouts.admin')

@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-12 text-center">
        <h1 class="m-0 text-primary">
          <i class="fas fa-chart-line mr-2"></i>
          Analisis Detail Riwayat Pengguna
        </h1>
        <p class="text-muted mt-2">
          Dashboard komprehensif untuk evaluasi sistem rekomendasi
        </p>
      </div>
    </div>
  </div>
</div>

<section class="content">
<div class="container-fluid">

  {{-- USER INFO --}}
  <div class="row justify-content-center mb-4">
    <div class="col-12 col-lg-10">
      <div class="card bg-gradient-info text-white shadow-lg">
        <div class="card-body py-4">
          <div class="row align-items-center">

            <div class="col-md-6 text-center text-md-left">
              <h4 class="mb-2">
                <i class="fas fa-user mr-2"></i>
                Pengguna: {{ $history->user->name }}
              </h4>
              <p class="mb-0 opacity-75">
                <i class="fas fa-envelope mr-1"></i>
                {{ $history->user->email }}
              </p>
            </div>

            <div class="col-md-6 text-center text-md-right mt-3 mt-md-0">
              <h5 class="mb-2">
                <i class="fas fa-search mr-2"></i>
                Pencarian:
              </h5>

              @foreach(explode('|', $history->query) as $ingredient)
                <span class="badge badge-light text-info px-3 py-2 badge-pill mr-2 mb-2">
                  <i class="fas fa-leaf mr-1"></i>{{ trim($ingredient) }}
                </span>
              @endforeach

            </div>

          </div>
        </div>
      </div>
    </div>
  </div>


  {{-- TABLE COSINE --}}
  <div class="row justify-content-center mb-5">
    <div class="col-12">
      <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white">
          <h3 class="card-title mb-0">
            <i class="fas fa-calculator mr-2"></i>
            Rekomendasi – Cosine Similarity
          </h3>
        </div>

        <div class="card-body">
          <div class="table-responsive">

            <table class="table table-hover">
              <thead class="thead-dark">
                <tr>
                  <th>Produk</th>
                  <th>Kandungan</th>
                  <th>Skor</th>
                  <th>Kecocokan</th>
                  <th>Relevansi</th>
                  <th>Feedback</th>
                </tr>
              </thead>

              <tbody>
                @foreach ($history->recommendations->where('similarity_type', 'cosine') as $rec)
                <tr class="{{ ($rec->is_relevant) ? 'table-success' : 'table-warning' }}">

                  <td><strong>{{ $rec->product->product_name ?? '-' }}</strong></td>

                  <td><small>{{ Str::limit($rec->product->ingredients ?? '-', 80) }}</small></td>

                  <td>
                    <span class="badge badge-primary px-3 py-2">
                      {{ number_format($rec->similarity_score, 4) }}
                    </span>
                  </td>

                  <td>
                    <span class="badge badge-success mb-1">{{ $rec->common_ingredients_count }} bahan</span>
                    <div>
                      @foreach($rec->common_ingredients as $item)
                        <span class="badge badge-info badge-sm mr-1 mb-1">{{ $item }}</span>
                      @endforeach
                    </div>
                  </td>

                  <td>
                    @if($rec->is_relevant)
                      <span class="badge badge-success px-3 py-2">Relevan</span>
                    @else
                      <span class="badge badge-warning px-3 py-2">Tidak Relevan</span>
                    @endif
                  </td>

                  <td>
                    @if($rec->user_feedback === 1)
                      <span class="badge badge-success px-3 py-2">Disukai</span>
                    @elseif($rec->user_feedback === 0)
                      <span class="badge badge-danger px-3 py-2">Tidak Disukai</span>
                    @else
                      <span class="badge badge-secondary px-3 py-2">Belum Dinilai</span>
                    @endif
                  </td>

                </tr>
                @endforeach
              </tbody>

            </table>

          </div>
        </div>

      </div>
    </div>
  </div>


  {{-- TABLE EUCLIDEAN --}}
  <div class="row justify-content-center mb-5">
    <div class="col-12">
      <div class="card shadow-lg border-0">
        <div class="card-header bg-info text-white">
          <h3 class="card-title mb-0">
            <i class="fas fa-ruler mr-2"></i>
            Rekomendasi – Euclidean Distance
          </h3>
        </div>

        <div class="card-body">
          <div class="table-responsive">

            <table class="table table-hover">
              <thead class="thead-dark">
                <tr>
                  <th>Produk</th>
                  <th>Kandungan</th>
                  <th>Skor</th>
                  <th>Kecocokan</th>
                  <th>Relevansi</th>
                  <th>Feedback</th>
                </tr>
              </thead>

              <tbody>
                @foreach ($history->recommendations->where('similarity_type', 'euclidean') as $rec)
                <tr class="{{ ($rec->is_relevant) ? 'table-success' : 'table-warning' }}">

                  <td><strong>{{ $rec->product->product_name ?? '-' }}</strong></td>

                  <td><small>{{ Str::limit($rec->product->ingredients ?? '-', 80) }}</small></td>

                  <td>
                    <span class="badge badge-info px-3 py-2">
                      {{ number_format($rec->similarity_score, 4) }}
                    </span>
                  </td>

                  <td>
                    <span class="badge badge-success mb-1">
                      {{ $rec->common_ingredients_count }} bahan
                    </span>
                    <div>
                      @foreach($rec->common_ingredients as $item)
                        <span class="badge badge-info badge-sm mr-1 mb-1">{{ $item }}</span>
                      @endforeach
                    </div>
                  </td>

                  <td>
                    @if($rec->is_relevant)
                      <span class="badge badge-success px-3 py-2">Relevan</span>
                    @else
                      <span class="badge badge-warning px-3 py-2">Tidak Relevan</span>
                    @endif
                  </td>

                  <td>
                    @if($rec->user_feedback === 1)
                      <span class="badge badge-success px-3 py-2">Disukai</span>
                    @elseif($rec->user_feedback === 0)
                      <span class="badge badge-danger px-3 py-2">Tidak Disukai</span>
                    @else
                      <span class="badge badge-secondary px-3 py-2">Belum Dinilai</span>
                    @endif
                  </td>

                </tr>
                @endforeach
              </tbody>

            </table>

          </div>
        </div>

      </div>
    </div>
  </div>


  {{-- METRICS SHOW --}}
  @if($metrics)
  <div class="row justify-content-center mb-5">
    <div class="col-12 col-lg-10">
      <div class="card shadow-lg border-0">

        <div class="card-header bg-gradient-primary text-white">
          <h3 class="mb-0">
            <i class="fas fa-chart-bar mr-2"></i>
            Evaluasi Sistem
          </h3>
        </div>

        <div class="card-body">

          <div class="row text-center">

            <div class="col-md-4 mb-3">
              <div class="metric bg-success text-white p-3 rounded">
                <h4>{{ $metrics->precision }}</h4>
                <small>Precision</small>
              </div>
            </div>

            <div class="col-md-4 mb-3">
              <div class="metric bg-warning text-white p-3 rounded">
                <h4>{{ $metrics->mrr ?? 'N/A' }}</h4>
                <small>MRR</small>
              </div>
            </div>

            <div class="col-md-4 mb-3">
              <div class="metric bg-info text-white p-3 rounded">
                <h4>{{ $metrics->hit_rate ? 'Ya' : 'Tidak' }}</h4>
                <small>Hit Rate</small>
              </div>
            </div>

          </div>

        </div>

      </div>
    </div>
  </div>
  @endif


  {{-- BACK BUTTON --}}
  <div class="row justify-content-center">
    <div class="col-12 text-center">
      <a href="{{ route('admin.history') }}" class="btn btn-secondary btn-lg px-5">
        <i class="fas fa-arrow-left mr-2"></i>
        Kembali
      </a>
    </div>
  </div>

</div>
</section>

@endsection

@extends('layouts.user')

@section('content')
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">History Query User</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">History Query User</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">

          <div class="card shadow">
            <div class="card-body">

              <table class="table table-bordered table-striped" id="historyTable">
                <thead class="thead-dark">
                  <tr>
                    <th>No</th>
                    <th>Query</th>
                    <th>Total Rekomendasi</th>
                    <th>Waktu Dibuat</th>
                    <th>Aksi</th>
                  </tr>
                </thead>

                <tbody>
                  @foreach ($history as $index => $item)
                    <tr>
                      <td>{{ $index + 1 }}</td>
                      <td>{{ $item->query }}</td>

                      <!-- Perbaikan: ambil jumlah rekomendasi dari relasi -->
                      <td>{{ $item->recommendations->count() }}</td>

                      <td>{{ $item->created_at->format('d-m-Y H:i:s') }}</td>

                      <td>
                        <a href="{{ route('user.history.show', $item->id) }}" 
                           class="btn btn-info btn-sm">
                          Lihat Detail
                        </a>
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
  </section>
@endsection

@section('scripts')
  <script>
    $(document).ready(function() {
        $('#historyTable').DataTable();
    });
  </script>
@endsection

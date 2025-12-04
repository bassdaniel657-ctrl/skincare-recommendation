@extends('layouts.admin')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Daftar Metrik Evaluasi</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Daftar Metrik Evaluasi</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-body">
                            <div class="mb-5">
                                <button class="btn btn-info btn-sm evaluate">
                                    Kalkulasi Evaluasi
                                </button>
                            </div>
                            <table class="table table-bordered table-striped" id="menuTable">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Method</th>
                                        <th>Total</th>
                                        <th>Feedback</th>
                                        <th>hit_rate</th>
                                        <th>precision</th>
                                        <th>MRR</th>
                                        <th>MAP</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($data as $index => $item)
                                        <tr>
                                            <td>{{$index}}</td>
                                            <td>{{$item['total']}}</td>
                                            <td>{{$item['feedback']}}</td>
                                            <td>{{$item['hitrate']}}</td>
                                            <td>{{$item['precision']}}</td>
                                            <td>{{$item['mrr']}}</td>
                                            <td>{{$item['map']}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            </br>
                            <select class="form-select-lg" id="method-filter" aria-label="Pilihan Dropdown Standar">
                                <option value="cosine" selected>cosine</option>
                                <option value="euclidean">euclidean</option>
                            </select>
                            </br>
                            <div class="mt-4">
                                <table class="table table-bordered table-striped" id="tableCosine">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>No</th>
                                            <th>Query</th>
                                            <th>Precision</th>
                                            <th>RR</th>
                                            <th>AP</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($data['cosine']['data'] as $index => $item)
                                            <tr>
                                                <td>{{$index}}</td>
                                                <td>{{$item->query}}</td>
                                                <td>{{$item->precision}}</td>
                                                <td>{{$item->mrr}}</td>
                                                <td>{{$item->map}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4">
                                <table class="table table-bordered table-striped" id="tableEuclidean">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>No</th>
                                            <th>Query</th>
                                            <th>Precision</th>
                                            <th>RR</th>
                                            <th>AP</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($data['euclidean']['data'] as $index => $item)
                                            <tr>
                                                <td>{{$index}}</td>
                                                <td>{{$item->query}}</td>
                                                <td>{{$item->precision}}</td>
                                                <td>{{$item->mrr}}</td>
                                                <td>{{$item->map}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
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
            <p class="ml-3 text-primary">Me-Evaluasi...</p>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#tableCosine').DataTable();
        $('#tableEuclidean').DataTable();
    });

    const cosineTableBody = document.getElementById('tableCosine').parentNode;
    const euclideanTableBody = document.getElementById('tableEuclidean').parentNode;
    euclideanTableBody.style.display = 'none';
    const methodFilter = document.getElementById('method-filter');
    methodFilter.addEventListener('change', function() {
        if (this.value === 'cosine') {
            cosineTableBody.style.display = '';
            euclideanTableBody.style.display = 'none';
        } else if (this.value === 'euclidean') {
            cosineTableBody.style.display = 'none';
            euclideanTableBody.style.display = '';
        }
    });

    $(document).ready(function() {
    $('.evaluate').click(function() {
      $('#page-loader').addClass('d-flex').removeClass('d-none');

      $.ajax({
        url: '{{ route("admin.evaluate.update") }}',
        method: 'PATCH',
        data: {
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

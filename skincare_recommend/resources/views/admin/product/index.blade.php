@extends('layouts.admin')

@section('content')
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Products</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Products</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      <div class="card">

        <div class="card-header">
          <h3 class="card-title">List of Products</h3>

          <div class="card-tools">
            <a href="{{ route('product.create') }}" class="btn btn-primary btn-sm">
              <i class="fas fa-plus"></i> Add New Product
            </a>
          </div>
        </div>

        <div class="card-body">

          {{-- Success Message --}}
          @if (session('success'))
            <div class="alert alert-success">
              {{ session('success') }}
            </div>
          @endif

          <table id="menuTable" class="table table-bordered table-striped">
            <thead class="text-center">
              <tr>
                <th style="width: 16%">Product Name</th>
                <th style="width: 15%">Brand</th>
                <th style="width: 28%">Ingredients</th>
                <th style="width: 10%">Price</th>
                <th style="width: 12%">Category</th>
                <th style="width: 12%">Actions</th>
              </tr>
            </thead>

            <tbody>
              @foreach ($products as $item)
                <tr>
                  <td>{{ $item->product_name }}</td>

                  <td>{{ $item->brand_name }}</td>

                  <td>
                    <button class="btn btn-info btn-sm toggle-ingredients">
                      Show Ingredients
                    </button>

                    <ul class="ingredients-list mt-2" style="display: none; padding-left: 18px;">
                      @foreach (explode('|', $item->ingredients) as $ingredient)
                        <li>{{ trim($ingredient) }}</li>
                      @endforeach
                    </ul>
                  </td>

                  <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>

                  <td>{{ $item->category }}</td>

                  <td class="text-center">

                    {{-- Edit --}}
                    <a href="{{ route('product.edit', $item->id) }}" class="btn btn-sm btn-warning">
                      <i class="fas fa-edit"></i>
                    </a>

                    {{-- Delete --}}
                    <form action="{{ route('product.destroy', $item->id) }}" method="POST"
                      style="display: inline-block;">
                      @csrf
                      @method('DELETE')
                      <button type="submit"
                        onclick="return confirm('Are you sure you want to delete this product?')"
                        class="btn btn-sm btn-danger">
                        <i class="fas fa-trash"></i>
                      </button>
                    </form>

                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>

        </div>

      </div>

    </div>
  </section>
@endsection

@section('scripts')
  <script>
    $(document).ready(function() {

      // Datatable initialization
      var table = $('#menuTable').DataTable({
        responsive: true,
        autoWidth: false
      });

      // Toggle ingredients visibility
      function bindToggleIngredients() {
        $('.toggle-ingredients').off('click').on('click', function() {
          let list = $(this).next('.ingredients-list');

          if (list.is(':visible')) {
            list.slideUp();
            $(this).text('Show Ingredients');
          } else {
            list.slideDown();
            $(this).text('Hide Ingredients');
          }
        });
      }

      // Bind first time
      bindToggleIngredients();

      // Rebind after pagination / search / redraw
      table.on('draw', function() {
        bindToggleIngredients();
      });

    });
  </script>
@endsection

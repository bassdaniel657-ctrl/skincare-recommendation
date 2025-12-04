@extends('layouts.admin')

@section('content')
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Create Product</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('product.index') }}">Products</a></li>
            <li class="breadcrumb-item active">Create</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      <div class="card">
        <div class="card-body">

          {{-- ERROR ALERT --}}
          @if ($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form action="{{ route('product.store') }}" method="POST" autocomplete="off">
            @csrf

            {{-- PRODUCT NAME --}}
            <div class="form-group">
              <label for="product_name">Product Name</label>
              <input type="text"
                class="form-control @error('product_name') is-invalid @enderror"
                id="product_name"
                name="product_name"
                placeholder="e.g., Hydrating Facial Toner"
                value="{{ old('product_name') }}"
                required>

              @error('product_name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- BRAND NAME --}}
            <div class="form-group">
              <label for="brand_name">Brand Name</label>
              <input type="text"
                class="form-control @error('brand_name') is-invalid @enderror"
                id="brand_name"
                name="brand_name"
                placeholder="e.g., The Ordinary, Skintific"
                value="{{ old('brand_name') }}"
                required>

              @error('brand_name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- PRICE --}}
            <div class="form-group">
              <label for="price">Price (Rp)</label>
              <input type="number"
                class="form-control @error('price') is-invalid @enderror"
                id="price"
                name="price"
                min="0"
                step="1000"
                placeholder="e.g., 75000"
                value="{{ old('price') }}"
                required>

              @error('price')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- CATEGORY --}}
            <div class="form-group">
              <label for="category">Category</label>
              <input type="text"
                class="form-control @error('category') is-invalid @enderror"
                id="category"
                name="category"
                placeholder="e.g., Toner, Moisturizer, Serum"
                value="{{ old('category') }}"
                required>

              @error('category')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- INGREDIENTS --}}
            <div class="form-group">
              <label for="ingredients">Ingredients (pisahkan dengan tanda | )</label>
              <textarea
                class="form-control @error('ingredients') is-invalid @enderror"
                id="ingredients"
                name="ingredients"
                rows="5"
                placeholder="Contoh: niacinamide|allantoin|hyaluronic acid"
                required>{{ old('ingredients') }}</textarea>

              @error('ingredients')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- BUTTONS --}}
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save mr-1"></i> Submit
            </button>

            <a href="{{ route('product.index') }}" class="btn btn-secondary ml-2">
              <i class="fas fa-times mr-1"></i> Cancel
            </a>

          </form>

        </div>
      </div>

    </div>
  </section>
@endsection

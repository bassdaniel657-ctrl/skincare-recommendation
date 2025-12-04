@extends('layouts.admin')

@section('content')
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Edit Product</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('product.index') }}">Products</a></li>
            <li class="breadcrumb-item active">Edit</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      <div class="card">
        <div class="card-body">

          {{-- Error Handling --}}
          @if ($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form action="{{ route('product.update', $product->id) }}" method="POST" autocomplete="off">
            @csrf
            @method('PUT')

            {{-- Product Name --}}
            <div class="form-group">
              <label for="product_name">Product Name</label>
              <input type="text"
                class="form-control @error('product_name') is-invalid @enderror"
                id="product_name"
                name="product_name"
                placeholder="e.g., Hydrating Facial Toner"
                value="{{ old('product_name', $product->product_name) }}"
                required>

              @error('product_name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Brand Name --}}
            <div class="form-group">
              <label for="brand_name">Brand Name</label>
              <input type="text"
                class="form-control @error('brand_name') is-invalid @enderror"
                id="brand_name"
                name="brand_name"
                placeholder="e.g., Skintific / The Ordinary"
                value="{{ old('brand_name', $product->brand_name) }}"
                required>

              @error('brand_name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Price --}}
            <div class="form-group">
              <label for="price">Price (Rp)</label>
              <input type="number"
                class="form-control @error('price') is-invalid @enderror"
                id="price"
                name="price"
                min="0"
                step="1000"
                placeholder="e.g., 75000"
                value="{{ old('price', $product->price) }}"
                required>

              @error('price')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Category --}}
            <div class="form-group">
              <label for="category">Category</label>
              <input type="text"
                class="form-control @error('category') is-invalid @enderror"
                id="category"
                name="category"
                placeholder="e.g., Toner / Serum / Moisturizer"
                value="{{ old('category', $product->category) }}"
                required>

              @error('category')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Ingredients --}}
            <div class="form-group">
              <label for="ingredients">Ingredients (pisahkan dengan tanda | )</label>
              <textarea
                class="form-control @error('ingredients') is-invalid @enderror"
                id="ingredients"
                name="ingredients"
                rows="5"
                placeholder="Contoh: niacinamide|hyaluronic acid|allantoin"
                required>{{ old('ingredients', $product->ingredients) }}</textarea>

              @error('ingredients')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Buttons --}}
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save mr-1"></i> Update
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

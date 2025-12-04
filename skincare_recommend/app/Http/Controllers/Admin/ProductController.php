<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\PreprocessingService;

class ProductController extends Controller
{
    /**
     * Tampilkan semua produk skincare.
     */
    public function index()
    {
        $products = Product::latest()->get();
        return view('admin.product.index', compact('products'));
    }

    /**
     * Tampilkan form tambah produk baru.
     */
    public function create()
    {
        return view('admin.product.create');
    }

    /**
     * Simpan produk baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'brand_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            'ingredients' => 'required|string',
        ]);

        // Preprocessing ingredients (agar data tersimpan bersih)
        $validated['ingredients'] = PreprocessingService::preprocess($validated['ingredients']);

        Product::create($validated);

        return redirect()->route('product.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Tampilkan form edit produk.
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.product.edit', compact('product'));
    }

    /**
     * Update produk.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'brand_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            'ingredients' => 'required|string',
        ]);

        $product = Product::findOrFail($id);
        $validated['ingredients'] = PreprocessingService::preprocess($validated['ingredients']);
        $product->update($validated);

        return redirect()->route('product.index')->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Hapus produk.
     */
    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return redirect()->route('product.index')->with('success', 'Produk berhasil dihapus!');
    }
}

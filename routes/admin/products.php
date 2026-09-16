<?php

use App\Helpers\RouteHelpers;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin – Master Produk (Suplemen & Vitamin)
|--------------------------------------------------------------------------
*/

// ── Index ─────────────────────────────────────────────────────────────────────
Route::get('/products', function () {
    if ($redirect = RouteHelpers::ensureAdmin()) {
        return $redirect;
    }

    $products = Product::query()
        ->select('products.*')
        ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
        ->orderByDesc('products.is_active')
        ->orderBy('categories.name')
        ->orderBy('products.name')
        ->with('category')
        ->get();

    $categories = \App\Models\Category::orderBy('name')->get();

    return view('admin.products', array_merge(RouteHelpers::pageMeta('products'), [
        'products' => $products,
        'categories' => $categories
    ]));
})->name('products');

// ── Store ─────────────────────────────────────────────────────────────────────
Route::post('/products', function (Request $request) {
    if ($redirect = RouteHelpers::ensureAdmin()) {
        return $redirect;
    }

    $validated = $request->validate([
        'name'        => ['required', 'string', 'max:255'],
        'category_id' => ['nullable', 'exists:categories,id'],
        'category'    => ['nullable', 'string', 'max:255'],
        'brand'       => ['nullable', 'string', 'max:255'],
        'sku'         => ['nullable', 'string', 'max:80', 'unique:products,sku'],
        'price'       => ['required', 'integer', 'min:1'],
        'stock'       => ['required', 'integer', 'min:0'],
        'unit'        => ['required', 'string', 'max:40'],
        'description' => ['nullable', 'string'],
        'is_active'   => ['nullable', 'boolean'],
    ]);

    if (empty($validated['category_id'])) {
        if (empty($validated['category'])) {
            return back()->withErrors(['category_id' => 'Kategori wajib dipilih.'])->withInput();
        }
        $cat = \App\Models\Category::firstOrCreate([
            'name' => ucfirst($validated['category'])
        ]);
        $validated['category_id'] = $cat->id;
    }

    unset($validated['category']);

    $validated['is_active'] = $request->boolean('is_active', true);

    $product = Product::create($validated);

    if ($product->stock > 0) {
        \App\Models\ProductStockLog::create([
            'product_id' => $product->id,
            'type' => 'in',
            'quantity' => $product->stock,
            'description' => 'Stok awal produk baru',
            'user_id' => auth()->id(),
        ]);
    }

    return redirect()->route('admin.products')->with('status', 'Produk berhasil ditambahkan.');
})->name('products.store');

// ── Update ────────────────────────────────────────────────────────────────────
Route::put('/products/{product}', function (Request $request, Product $product) {
    if ($redirect = RouteHelpers::ensureAdmin()) {
        return $redirect;
    }

    $validated = $request->validate([
        'name'        => ['required', 'string', 'max:255'],
        'category_id' => ['nullable', 'exists:categories,id'],
        'category'    => ['nullable', 'string', 'max:255'],
        'brand'       => ['nullable', 'string', 'max:255'],
        'sku'         => ['nullable', 'string', 'max:80', 'unique:products,sku,' . $product->id],
        'price'       => ['required', 'integer', 'min:1'],
        'stock'       => ['required', 'integer', 'min:0'],
        'unit'        => ['required', 'string', 'max:40'],
        'description' => ['nullable', 'string'],
        'is_active'   => ['nullable', 'boolean'],
    ]);

    if (empty($validated['category_id'])) {
        if (empty($validated['category'])) {
            return back()->withErrors(['category_id' => 'Kategori wajib dipilih.'])->withInput();
        }
        $cat = \App\Models\Category::firstOrCreate([
            'name' => ucfirst($validated['category'])
        ]);
        $validated['category_id'] = $cat->id;
    }

    unset($validated['category']);

    $validated['is_active'] = $request->boolean('is_active');

    $oldStock = $product->stock;
    $product->update($validated);
    $newStock = $product->stock;

    if ($newStock !== $oldStock) {
        $diff = $newStock - $oldStock;
        \App\Models\ProductStockLog::create([
            'product_id' => $product->id,
            'type' => $diff > 0 ? 'in' : 'out',
            'quantity' => abs($diff),
            'description' => 'Penyesuaian stok oleh admin',
            'user_id' => auth()->id(),
        ]);
    }

    return redirect()->route('admin.products')->with('status', 'Produk berhasil diperbarui.');
})->name('products.update');

// ── Destroy ───────────────────────────────────────────────────────────────────
Route::delete('/products/{product}', function (Product $product) {
    if ($redirect = RouteHelpers::ensureAdmin()) {
        return $redirect;
    }

    $product->delete();

    return redirect()->route('admin.products')->with('status', 'Produk berhasil dihapus.');
})->name('products.destroy');

// ── Category CRUD ─────────────────────────────────────────────────────────────

Route::post('/categories', function (Request $request) {
    if ($redirect = RouteHelpers::ensureAdmin()) {
        return $redirect;
    }

    $validated = $request->validate([
        'name'        => ['required', 'string', 'max:255', 'unique:categories,name'],
        'description' => ['nullable', 'string'],
    ]);

    \App\Models\Category::create($validated);

    return redirect()->route('admin.products')->with('status', 'Kategori berhasil ditambahkan.');
})->name('categories.store');

Route::put('/categories/{category}', function (Request $request, \App\Models\Category $category) {
    if ($redirect = RouteHelpers::ensureAdmin()) {
        return $redirect;
    }

    $validated = $request->validate([
        'name'        => ['required', 'string', 'max:255', 'unique:categories,name,' . $category->id],
        'description' => ['nullable', 'string'],
    ]);

    $category->update($validated);

    // Removed string category update

    return redirect()->route('admin.products')->with('status', 'Kategori berhasil diperbarui.');
})->name('categories.update');

Route::delete('/categories/{category}', function (\App\Models\Category $category) {
    if ($redirect = RouteHelpers::ensureAdmin()) {
        return $redirect;
    }

    $category->delete();

    return redirect()->route('admin.products')->with('status', 'Kategori berhasil dihapus.');
})->name('categories.destroy');

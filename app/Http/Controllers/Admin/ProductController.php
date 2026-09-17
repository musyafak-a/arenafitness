<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\RouteHelpers;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductStockLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of products and categories.
     */
    public function index(): View|RedirectResponse
    {
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

        $categories = Category::orderBy('name')->get();

        return view('admin.products', array_merge(RouteHelpers::pageMeta('products'), [
            'products'   => $products,
            'categories' => $categories,
        ]));
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request): RedirectResponse
    {
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
            $cat = Category::firstOrCreate([
                'name' => ucfirst($validated['category'])
            ]);
            $validated['category_id'] = $cat->id;
        }

        unset($validated['category']);

        $validated['is_active'] = $request->boolean('is_active', true);

        $product = Product::create($validated);

        if ($product->stock > 0) {
            ProductStockLog::create([
                'product_id'  => $product->id,
                'type'        => 'in',
                'quantity'    => $product->stock,
                'description' => 'Stok awal produk baru',
                'user_id'     => auth()->id(),
            ]);
        }

        return redirect()->route('admin.products')->with('status', 'Produk berhasil ditambahkan.');
    }

    /**
     * Update an existing product.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
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
            $cat = Category::firstOrCreate([
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
            ProductStockLog::create([
                'product_id'  => $product->id,
                'type'        => $diff > 0 ? 'in' : 'out',
                'quantity'    => abs($diff),
                'description' => 'Penyesuaian stok oleh admin',
                'user_id'     => auth()->id(),
            ]);
        }

        return redirect()->route('admin.products')->with('status', 'Produk berhasil diperbarui.');
    }

    /**
     * Delete a product.
     */
    public function destroy(Product $product): RedirectResponse
    {
        if ($redirect = RouteHelpers::ensureAdmin()) {
            return $redirect;
        }

        $product->delete();

        return redirect()->route('admin.products')->with('status', 'Produk berhasil dihapus.');
    }

    /**
     * Store a new category.
     */
    public function storeCategory(Request $request): RedirectResponse
    {
        if ($redirect = RouteHelpers::ensureAdmin()) {
            return $redirect;
        }

        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255', 'unique:categories,name'],
            'description' => ['nullable', 'string'],
        ]);

        Category::create($validated);

        return redirect()->route('admin.products')->with('status', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Update an existing category.
     */
    public function updateCategory(Request $request, Category $category): RedirectResponse
    {
        if ($redirect = RouteHelpers::ensureAdmin()) {
            return $redirect;
        }

        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255', 'unique:categories,name,' . $category->id],
            'description' => ['nullable', 'string'],
        ]);

        $category->update($validated);

        return redirect()->route('admin.products')->with('status', 'Kategori berhasil diperbarui.');
    }

    /**
     * Delete a category.
     */
    public function destroyCategory(Category $category): RedirectResponse
    {
        if ($redirect = RouteHelpers::ensureAdmin()) {
            return $redirect;
        }

        $category->delete();

        return redirect()->route('admin.products')->with('status', 'Kategori berhasil dihapus.');
    }
}

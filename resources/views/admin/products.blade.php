@extends('admin.layout')

@section('content')


@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-products.css') }}">
@endpush


<div class="dashboard-page">

    {{-- HEADER --}}
    <div class="dashboard-heading d-flex justify-content-between align-items-end flex-wrap gap-3">
        <div>
            <div class="dashboard-subtitle">
                ARENA GYM · PRODUCT MANAGEMENT
            </div>

            <h1 class="dashboard-title">
                Master Produk Suplemen & Vitamin
            </h1>
        </div>

        <div class="d-flex gap-2 align-items-center flex-wrap">
            <button class="btn btn-outline-light rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#manageCategoriesModal">
                <i class="fas fa-tags me-2"></i>
                Kelola Kategori
            </button>

            <button class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm">
                <i class="fas fa-box me-2"></i>
                Total {{ $products->count() }} Produk
            </button>
        </div>
    </div>

    {{-- STATS --}}
    <div class="stats-grid">
        <div class="stats-card">
            <div class="stats-label">Total Produk</div>
            <div class="stats-value">
                {{ $products->count() }}
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-label">Produk Aktif</div>
            <div class="stats-value text-success">
                {{ $products->where('is_active', 1)->count() }}
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-label">Vitamin</div>
            <div class="stats-value text-warning">
                {{ $products->where('category', 'vitamin')->count() }}
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-label">Stok Menipis</div>
            <div class="stats-value text-danger">
                {{ $products->where('stock', '<', 3)->count() }}
            </div>
        </div>
    </div>

    {{-- FORM --}}
    <div class="form-card p-4 mb-4">

        <div class="mb-4">
            <div class="section-title">Tambah Produk Baru</div>
            <div class="section-subtitle">
                Tambahkan produk suplemen atau vitamin baru ke sistem kasir Arena Gym.
            </div>
        </div>

        <form method="POST" action="{{ route('admin.products.store') }}">
            @csrf

            <div class="row g-3">

                <div class="col-lg-4">
                    <label class="form-label">Nama Produk</label>
                    <input type="text"
                           name="name"
                           class="form-control"
                           placeholder="Contoh: Whey Protein"
                           required>
                </div>

                <div class="col-lg-2">
                    <label class="form-label">Kategori</label>
                    <select name="category_id" class="form-select" required>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2">
                    <label class="form-label">Status</label>
                    <select name="is_active" class="form-select">
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>

                <div class="col-lg-4">
                    <label class="form-label">Brand</label>
                    <input type="text"
                           name="brand"
                           class="form-control"
                           placeholder="Contoh: Optimum Nutrition">
                </div>

                <div class="col-lg-3">
                    <label class="form-label">SKU</label>
                    <input type="text"
                           name="sku"
                           class="form-control"
                           placeholder="SKU Produk">
                </div>

                <div class="col-lg-3">
                    <label class="form-label">Harga</label>
                    <input type="number"
                           name="price"
                           class="form-control"
                           placeholder="350000"
                           required>
                </div>

                <div class="col-lg-2">
                    <label class="form-label">Stok</label>
                    <input type="number"
                           name="stock"
                           class="form-control"
                           placeholder="0"
                           required>
                </div>

                <div class="col-lg-2">
                    <label class="form-label">Satuan</label>
                    <input type="text"
                           name="unit"
                           class="form-control"
                           value="pcs"
                           required>
                </div>

                <div class="col-lg-2">
                    <label class="form-label">Deskripsi</label>
                    <input type="text"
                           name="description"
                           class="form-control"
                           placeholder="Opsional">
                </div>

                <div class="col-12 text-end">
                    <button type="submit"
                            class="btn btn-danger rounded-pill px-4 fw-bold">
                        <i class="fas fa-plus me-2"></i>
                        Simpan Produk
                    </button>
                </div>

            </div>
        </form>
    </div>

    {{-- TABLE --}}
    <div class="table-card p-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <div class="section-title">Daftar Produk</div>
            </div>
        </div>

        <div class="product-table-wrap">

            <table class="product-table">

                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Brand</th>
                        <th>SKU</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Satuan</th>
                        <th>Deskripsi</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($products as $product)
                        <tr>

                            <td>
                                <div class="product-name">
                                    {{ $product->name }}
                                </div>

                                <div class="product-small">
                                    Produk Arena Gym
                                </div>
                            </td>

                            <td>
                                @php
                                    $catLower = strtolower($product->categoryRelation?->name ?? '');
                                    $badgeClass = 'badge-other-cat';
                                    if ($catLower === 'suplemen') $badgeClass = 'badge-suplemen';
                                    elseif ($catLower === 'vitamin') $badgeClass = 'badge-vitamin';
                                @endphp
                                <span class="badge-soft {{ $badgeClass }}">
                                    {{ $product->categoryRelation?->name ?? 'Tanpa Kategori' }}
                                </span>
                            </td>

                            <td>
                                <span class="badge-soft {{ $product->is_active ? 'badge-active' : 'badge-inactive' }}">
                                    {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>

                            <td>
                                {{ $product->brand ?: '-' }}
                            </td>

                            <td>
                                {{ $product->sku ?: '-' }}
                            </td>

                            <td class="table-price">
                                Rp{{ number_format($product->price, 0, ',', '.') }}
                            </td>

                            <td>
                                {{ number_format($product->stock, 0, ',', '.') }}
                            </td>

                            <td>
                                {{ $product->unit }}
                            </td>

                            <td style="max-width:220px;">
                                <span class="product-small">
                                    {{ $product->description ?: '-' }}
                                </span>
                            </td>

                            <td>
                                <div class="action-group">

                                    <button
                                        class="btn-action btn-edit"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editProductModal{{ $product->id }}">
                                        Edit
                                    </button>

                                    <form method="POST"
                                          action="{{ route('admin.products.destroy', $product) }}"
                                          onsubmit="return confirm('Hapus produk ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn-action btn-delete">
                                            Hapus
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="10"
                                class="text-center py-5 text-secondary">
                                Belum ada produk tersedia.
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>
    </div>



    {{-- MODAL EDIT PRODUCT --}}
    @foreach ($products as $product)
        @include('admin.partials.products.modal-edit-product')
    @endforeach

    {{-- MODAL KELOLA KATEGORI --}}
    @include('admin.partials.products.modal-manage-categories')

@endsection

@push('scripts')
    <script src="{{ asset('js/admin-products.js') }}"></script>
@endpush

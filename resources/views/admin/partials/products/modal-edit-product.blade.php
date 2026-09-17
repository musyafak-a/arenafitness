<div class="modal fade"
     id="editProductModal{{ $product->id }}"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content bg-dark text-white border-0 shadow-lg"
             style="border-radius: 1.3rem;">

            <form method="POST"
                  action="{{ route('admin.products.update', $product) }}">

                @csrf
                @method('PUT')

                <div class="modal-header border-bottom border-secondary border-opacity-25">
                    <h5 class="modal-title fw-bold">
                        Edit Produk
                    </h5>

                    <button type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Nama Produk</label>

                            <input type="text"
                                   name="name"
                                   class="form-control"
                                   value="{{ $product->name }}"
                                   required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Kategori</label>

                            <select name="category_id"
                                    class="form-select"
                                    required>

                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        @selected($product->category_id === $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Status</label>

                            <select name="is_active"
                                    class="form-select"
                                    required>

                                <option value="1"
                                    @selected($product->is_active)>
                                    Aktif
                                </option>

                                <option value="0"
                                    @selected(!$product->is_active)>
                                    Nonaktif
                                </option>

                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Brand</label>

                            <input type="text"
                                   name="brand"
                                   class="form-control"
                                   value="{{ $product->brand }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">SKU</label>

                            <input type="text"
                                   name="sku"
                                   class="form-control"
                                   value="{{ $product->sku }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Satuan</label>

                            <input type="text"
                                   name="unit"
                                   class="form-control"
                                   value="{{ $product->unit }}"
                                   required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Harga</label>

                            <input type="number"
                                   name="price"
                                   class="form-control"
                                   value="{{ $product->price }}"
                                   required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Stok</label>

                            <input type="number"
                                   name="stock"
                                   class="form-control"
                                   value="{{ $product->stock }}"
                                   required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Deskripsi</label>

                            <textarea name="description"
                                      class="form-control"
                                      rows="4">{{ $product->description }}</textarea>
                        </div>

                    </div>

                </div>

                <div class="modal-footer border-top border-secondary border-opacity-25">

                    <button type="button"
                            class="btn btn-outline-light rounded-pill px-4"
                            data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit"
                            class="btn btn-danger rounded-pill px-4 fw-bold">
                        Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

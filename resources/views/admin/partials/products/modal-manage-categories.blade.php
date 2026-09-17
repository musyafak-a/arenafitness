<div class="modal fade"
     id="manageCategoriesModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content bg-dark text-white border-0 shadow-lg"
             style="border-radius: 1.3rem;">

            <div class="modal-header border-bottom border-secondary border-opacity-25">
                <h5 class="modal-title fw-bold">
                    Kelola Kategori Produk
                </h5>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                
                {{-- Form Tambah/Edit Kategori --}}
                <div class="p-3 mb-4 rounded-3" style="background: rgba(255,255,255,.02); border: 1px solid rgba(255,255,255,.05);">
                    <div class="fw-bold mb-2" id="categoryFormTitle">Tambah Kategori Baru</div>
                    <form id="categoryForm" method="POST" action="{{ route('admin.categories.store') }}">
                        @csrf
                        <input type="hidden" name="_method" id="categoryFormMethod" value="POST">
                        
                        <div class="row g-3 align-items-end">
                            <div class="col-md-5">
                                <label class="form-label small text-secondary">Nama Kategori</label>
                                <input type="text"
                                       name="name"
                                       id="categoryNameInput"
                                       class="form-control"
                                       placeholder="Contoh: Aksesoris"
                                       required>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label small text-secondary">Deskripsi</label>
                                <input type="text"
                                       name="description"
                                       id="categoryDescriptionInput"
                                       class="form-control"
                                       placeholder="Opsional">
                            </div>
                            <div class="col-md-2">
                                <button type="submit"
                                        id="categoryFormSubmitBtn"
                                        class="btn btn-danger w-100 fw-bold rounded-pill"
                                        style="padding: 0.8rem 1rem;">
                                    Tambah
                                </button>
                            </div>
                        </div>
                        <div class="mt-2 text-end" id="cancelCategoryEditContainer" style="display: none;">
                            <button type="button"
                                    class="btn btn-link btn-sm text-secondary text-decoration-none p-0"
                                    onclick="cancelCategoryEdit()">
                                <i class="fas fa-times me-1"></i> Batal Edit
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Tabel Daftar Kategori --}}
                <div class="fw-bold mb-2">Daftar Kategori</div>
                <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                    <table class="table table-dark table-hover table-borderless align-middle mb-0">
                        <thead>
                            <tr style="border-bottom: 1px solid rgba(255,255,255,.05);">
                                <th class="text-secondary small fw-bold">Nama Kategori</th>
                                <th class="text-secondary small fw-bold">Deskripsi</th>
                                <th class="text-secondary small fw-bold text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($categories as $category)
                                <tr style="border-bottom: 1px solid rgba(255,255,255,.02);">
                                    <td class="fw-semibold text-white">{{ $category->name }}</td>
                                    <td class="text-secondary small">{{ $category->description ?: '-' }}</td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-link text-warning text-decoration-none p-0 me-3 fw-bold"
                                                onclick="editCategory({{ $category->id }}, '{{ addslashes($category->name) }}', '{{ addslashes($category->description ?? '') }}')">
                                            Edit
                                        </button>
                                        <form method="POST"
                                              action="{{ route('admin.categories.destroy', $category) }}"
                                              class="d-inline"
                                              onsubmit="return confirm('Hapus kategori ini? Produk dengan kategori ini akan diset tanpa kategori.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-link text-danger text-decoration-none p-0 fw-bold">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-secondary py-4 small">
                                        Belum ada kategori.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="modal-footer border-top border-secondary border-opacity-25">
                <button type="button"
                        class="btn btn-outline-light rounded-pill px-4"
                        data-bs-dismiss="modal">
                    Tutup
                </button>
            </div>

        </div>

    </div>

</div>

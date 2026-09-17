        <div class="modal fade" id="editMemberModal{{ $member->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content bg-dark text-white border-0 shadow-lg" style="border-radius: 1.5rem;">
                    <div class="modal-header border-bottom border-white border-opacity-10 p-4">
                        <h5 class="modal-title fw-bold text-warning">Edit Member</h5><button type="button"
                            class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('admin.members.update', $member) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="modal-body p-4">
                            <div class="mb-3"><label class="form-label small text-uppercase fw-bold opacity-50">Nama
                                    Lengkap</label><input type="text" name="full_name"
                                    class="form-control bg-white bg-opacity-10 border-0 text-white p-3"
                                    style="border-radius: 0.8rem;" value="{{ $member->full_name }}" required></div>
                            <div class="mb-3"><label
                                    class="form-label small text-uppercase fw-bold opacity-50">Email</label><input
                                    type="email" name="email"
                                    class="form-control bg-white bg-opacity-10 border-0 text-white p-3"
                                    style="border-radius: 0.8rem;" value="{{ $member->email }}"></div>
                            <div class="row">
                                <div class="col-6 mb-3"><label
                                        class="form-label small text-uppercase fw-bold opacity-50">Telepon</label><input
                                        type="text" name="phone"
                                        class="form-control bg-white bg-opacity-10 border-0 text-white p-3"
                                        style="border-radius: 0.8rem;" value="{{ $member->phone }}"></div>
                                <div class="col-6 mb-3"><label
                                        class="form-label small text-uppercase fw-bold opacity-50">Metode Bayar</label>
                                    <select name="payment_method"
                                        class="form-select bg-white bg-opacity-10 border-0 text-white p-3"
                                        style="border-radius: 0.8rem;">
                                        @foreach ($paymentMethods as $pm)
                                            <option value="{{ $pm }}" class="text-dark"
                                                {{ $member->payment_method == $pm ? 'selected' : '' }}>{{ $pm }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mb-0"><label class="form-label small text-uppercase fw-bold opacity-50">Ganti
                                    Foto Profil</label><input type="file" name="profile_photo"
                                    class="form-control bg-white bg-opacity-10 border-0 text-white p-2"
                                    style="border-radius: 0.8rem;"></div>
                        </div>
                        <div class="modal-footer border-0 p-4 pt-0"><button type="submit"
                                class="btn btn-warning w-100 rounded-pill fw-bold py-3 text-dark">Update Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    <div class="transaction-modal" data-transaction-modal aria-hidden="true">
        <div class="transaction-dialog" role="dialog" aria-modal="true" aria-labelledby="transactionModalTitle">
            <div class="transaction-dialog-head">
                <div class="transaction-dialog-title">
                    <span class="transaction-dialog-icon"><i class="fas fa-receipt"></i></span>
                    <div>
                        <div class="section-label">Detail Transaksi</div>
                        <h2 class="h5 fw-bold mt-2 mb-0" id="transactionModalTitle" data-detail-title>Invoice</h2>
                    </div>
                </div>
                <button class="transaction-close" type="button" data-transaction-close aria-label="Tutup detail transaksi">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="transaction-summary">
                <div>
                    <span class="transaction-summary-label">Total Transaksi</span>
                    <strong class="transaction-summary-amount" data-detail-amount>-</strong>
                </div>
                <div class="transaction-summary-status">
                    <span class="status-pill is-pending" data-detail-payment-status>-</span>
                    <span class="status-pill is-pending" data-detail-receipt-status>-</span>
                </div>
            </div>
            <div class="transaction-detail-grid">
                <div class="transaction-detail-item">
                    <span class="transaction-detail-icon"><i class="fas fa-user"></i></span>
                    <div class="transaction-detail-copy"><span>Pelanggan</span><strong data-detail-customer>-</strong></div>
                </div>
                <div class="transaction-detail-item">
                    <span class="transaction-detail-icon"><i class="fas fa-layer-group"></i></span>
                    <div class="transaction-detail-copy"><span>Tipe</span><strong data-detail-type>-</strong></div>
                </div>
                <div class="transaction-detail-item">
                    <span class="transaction-detail-icon"><i class="fas fa-wallet"></i></span>
                    <div class="transaction-detail-copy"><span>Metode Bayar</span><strong data-detail-payment-method>-</strong></div>
                </div>
                <div class="transaction-detail-item">
                    <span class="transaction-detail-icon"><i class="fas fa-money-bill-wave"></i></span>
                    <div class="transaction-detail-copy"><span>Uang Diterima</span><strong data-detail-paid-amount>-</strong></div>
                </div>
                <div class="transaction-detail-item">
                    <span class="transaction-detail-icon"><i class="fas fa-coins"></i></span>
                    <div class="transaction-detail-copy"><span>Kembalian</span><strong data-detail-change-amount>-</strong></div>
                </div>
                <div class="transaction-detail-item">
                    <span class="transaction-detail-icon"><i class="fas fa-clock"></i></span>
                    <div class="transaction-detail-copy"><span>Waktu</span><strong data-detail-time>-</strong></div>
                </div>
                <div class="transaction-detail-item">
                    <span class="transaction-detail-icon"><i class="fas fa-hashtag"></i></span>
                    <div class="transaction-detail-copy"><span>Jumlah</span><strong data-detail-quantity>-</strong></div>
                </div>
                <div class="transaction-detail-item wide">
                    <span class="transaction-detail-icon"><i class="fas fa-note-sticky"></i></span>
                    <div class="transaction-detail-copy"><span>Catatan</span><strong data-detail-notes>-</strong></div>
                </div>
            </div>
            <div class="transaction-actions" data-detail-actions>
                <a class="btn btn-success px-4 d-none" href="#" target="_blank" data-detail-print>
                    <i class="fas fa-print me-2"></i>Cetak
                </a>
                <form method="POST" action="#" class="d-none" data-detail-finish-form onsubmit="return confirm('Selesaikan dan verifikasi pembayaran ini?')">
                    @csrf
                    <input type="hidden" name="return_to" value="dashboard">
                    <button class="btn btn-dark px-4" type="submit">
                        <i class="fas fa-check me-2"></i>Selesai
                    </button>
                </form>
            </div>
        </div>
    </div>

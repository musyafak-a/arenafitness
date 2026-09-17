        function updateRenewPreview(select, memberId, currentExpiry) {
            const monthsToAdd = parseInt(select.value);

            // Gunakan tanggal expiry dari database, jika sudah lewat gunakan hari ini
            let baseDate = new Date(currentExpiry);
            let today = new Date();

            if (baseDate < today) {
                baseDate = today;
            }

            // Tambah bulan
            baseDate.setMonth(baseDate.getMonth() + monthsToAdd);

            // Format tampilan (Contoh: 10 Aug 2026)
            const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            const day = String(baseDate.getDate()).padStart(2, '0');
            const month = monthNames[baseDate.getMonth()];
            const year = baseDate.getFullYear();

            document.getElementById('prevNew' + memberId).innerText = `${day} ${month} ${year}`;
            document.getElementById('prevMonths' + memberId).innerText = monthsToAdd;
        }

        // ── Member Search ──────────────────────────────────────────
        document.getElementById('memberSearchInput').addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            const rows = document.querySelectorAll('#memberTableBody .member-row');
            const noResult = document.getElementById('memberNoResult');
            let visible = 0;

            rows.forEach(function(row) {
                const name = row.dataset.name || '';
                const phone = row.dataset.phone || '';
                const match = name.includes(query) || phone.includes(query);
                row.style.display = match ? '' : 'none';
                if (match) visible++;
            });

            noResult.style.display = visible === 0 ? '' : 'none';
        });
    </script>

        // Generate QR saat modal dibuka
        document.querySelectorAll('[id^="detailMemberModal"]').forEach(function(modal) {
            modal.addEventListener('shown.bs.modal', function() {
                const qrId = this.querySelector('[id^="qr-"]').id;
                const memberId = qrId.replace('qr-', '');
                const codeEl = this.querySelector('.small.opacity-50.mt-2');
                const code = codeEl ? codeEl.innerText.trim() : memberId;

                const container = document.getElementById(qrId);

                // Jangan generate ulang kalau sudah ada
                if (container.querySelector('canvas') || container.querySelector('img')) return;

                new QRCode(container, {
                    text: code,
                    width: 140,
                    height: 140,
                    colorDark: '#000000',
                    colorLight: '#ffffff',
                    correctLevel: QRCode.CorrectLevel.H
                });
            });
        });

        // Cetak kartu member
        function printMemberCard(name, code, expires, phone, qrContainerId) {
            const qrContainer = document.getElementById(qrContainerId);
            const qrImg = qrContainer.querySelector('img') || qrContainer.querySelector('canvas');

            let qrSrc = '';
            if (qrImg) {
                qrSrc = qrImg.tagName === 'CANVAS' ? qrImg.toDataURL() : qrImg.src;
            }

            // 4 digit terakhir HP
            const last4 = phone ? phone.slice(-4) : '****';

            const win = window.open('', '_blank');
            win.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Kartu Member - ${name}</title>
            <style>
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body { font-family: Arial, sans-serif; background: #f0f0f0; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
                .card {
                    width: 85.6mm; height: 54mm;
                    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
                    border-radius: 8mm;
                    padding: 5mm;
                    display: flex;
                    align-items: center;
                    gap: 4mm;
                    color: #fff;
                    position: relative;
                    overflow: hidden;
                    box-shadow: 0 4px 20px rgba(0,0,0,0.4);
                }
                .card::before {
                    content: '';
                    position: absolute;
                    top: -10mm; right: -10mm;
                    width: 35mm; height: 35mm;
                    background: rgba(220,53,69,0.2);
                    border-radius: 50%;
                }
                .card::after {
                    content: '';
                    position: absolute;
                    bottom: -8mm; left: 20mm;
                    width: 25mm; height: 25mm;
                    background: rgba(220,53,69,0.1);
                    border-radius: 50%;
                }
                .qr-box {
                    flex-shrink: 0;
                    background: #fff;
                    padding: 2mm;
                    border-radius: 3mm;
                    width: 28mm; height: 28mm;
                    display: flex; align-items: center; justify-content: center;
                }
                .qr-box img { width: 100%; height: 100%; }
                .info { flex: 1; z-index: 1; }
                .gym-name { font-size: 7pt; color: #dc3545; font-weight: bold; letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 1mm; }
                .member-name { font-size: 10pt; font-weight: bold; line-height: 1.2; margin-bottom: 2mm; }
                .member-code { font-size: 7pt; color: rgba(255,255,255,0.5); margin-bottom: 3mm; font-family: monospace; }
                .divider { height: 0.3mm; background: rgba(255,255,255,0.15); margin-bottom: 3mm; }
                .expires-label { font-size: 6pt; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 1px; }
                .expires-val { font-size: 8pt; font-weight: bold; color: #dc3545; }
                .last4 { position: absolute; bottom: 3mm; right: 4mm; font-size: 6pt; color: rgba(255,255,255,0.3); letter-spacing: 2px; }
                @media print {
                    body { background: none; }
                    .card { box-shadow: none; }
                }
            </style>
        </head>
        <body>
            <div class="card">
                <div class="qr-box">
                    <img src="${qrSrc}" alt="QR">
                </div>
                <div class="info">
                    <div class="gym-name">Arena Gym</div>
                    <div class="member-name">${name}</div>
                    <div class="member-code">${code}</div>
                    <div class="divider"></div>
                    <div class="expires-label">Aktif hingga</div>
                    <div class="expires-val">${expires}</div>
                </div>
                <div class="last4">••${last4}</div>
            </div>
            <script>window.onload = function() { window.print(); }<\/script>
        </body>
        </html>
    `);
            win.document.close();
        }

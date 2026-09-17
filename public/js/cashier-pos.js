        (() => {
            const shiftCard = document.querySelector('[data-shift-start][data-shift-end]');

            if (shiftCard) {
                const startValue = shiftCard.dataset.shiftStart;
                const endValue = shiftCard.dataset.shiftEnd;
                const countdownEl = shiftCard.querySelector('.js-shift-countdown');

                const toDate = (timeValue) => {
                    const [hours, minutes] = timeValue.split(':').map(Number);
                    const now = new Date();
                    return new Date(now.getFullYear(), now.getMonth(), now.getDate(), hours, minutes, 0, 0);
                };

                const formatRemaining = (milliseconds) => {
                    const totalSeconds = Math.max(Math.floor(milliseconds / 1000), 0);
                    const totalMinutes = Math.floor(totalSeconds / 60);
                    const hours = Math.floor(totalMinutes / 60);
                    const minutes = totalMinutes % 60;
                    const seconds = totalSeconds % 60;
                    const secondLabel = `${String(seconds).padStart(2, '0')} detik`;

                    return hours <= 0
                        ? `${minutes} menit ${secondLabel}`
                        : `${hours} jam ${minutes} menit ${secondLabel}`;
                };

                const updateShiftCountdown = () => {
                    const now = new Date();
                    const startAt = toDate(startValue);
                    const endAt = toDate(endValue);

                    if (now < startAt) {
                        countdownEl.textContent = `Shift dimulai dalam ${formatRemaining(startAt - now)}`;
                        return;
                    }

                    if (now >= endAt) {
                        countdownEl.textContent = 'Shift hari ini sudah selesai';
                        return;
                    }

                    countdownEl.textContent = `Sisa waktu shift ${formatRemaining(endAt - now)}`;
                };

                updateShiftCountdown();
                window.setInterval(updateShiftCountdown, 1000);
            }

            const heroCard = document.querySelector('[data-hero-spotlight]');

            if (heroCard && window.matchMedia('(pointer: fine)').matches) {
                heroCard.addEventListener('pointermove', (event) => {
                    const rect = heroCard.getBoundingClientRect();
                    const x = ((event.clientX - rect.left) / rect.width) * 100;
                    const y = ((event.clientY - rect.top) / rect.height) * 100;
                    heroCard.style.setProperty('--hero-x', `${x}%`);
                    heroCard.style.setProperty('--hero-y', `${y}%`);
                });
            }

            const searchInput = document.querySelector('[data-transaction-search]');
            const rows = [...document.querySelectorAll('[data-transaction-row]')];
            const emptyState = document.querySelector('[data-transaction-empty]');
            const tableScrollMain = document.querySelector('[data-table-scroll-main]');
            const toast = document.querySelector('[data-dashboard-toast]');
            let toastTimeout;
            let isPointerDown = false;
            let dragStartX = 0;
            let dragStartScrollLeft = 0;

            tableScrollMain?.addEventListener('pointerdown', (event) => {
                if (event.pointerType === 'mouse' && event.button !== 0) {
                    return;
                }
                isPointerDown = true;
                dragStartX = event.clientX;
                dragStartScrollLeft = tableScrollMain.scrollLeft;
                tableScrollMain.classList.add('is-dragging');
                tableScrollMain.setPointerCapture?.(event.pointerId);
            });

            tableScrollMain?.addEventListener('pointermove', (event) => {
                if (!isPointerDown) {
                    return;
                }
                const deltaX = event.clientX - dragStartX;
                tableScrollMain.scrollLeft = dragStartScrollLeft - deltaX;
            });

            const stopDragScroll = (event) => {
                isPointerDown = false;
                tableScrollMain?.classList.remove('is-dragging');
                if (event && tableScrollMain && event.pointerId !== undefined) {
                    tableScrollMain.releasePointerCapture?.(event.pointerId);
                }
            };

            tableScrollMain?.addEventListener('pointerup', stopDragScroll);
            tableScrollMain?.addEventListener('pointercancel', stopDragScroll);
            tableScrollMain?.addEventListener('lostpointercapture', () => {
                isPointerDown = false;
                tableScrollMain?.classList.remove('is-dragging');
            });

            const showToast = (message) => {
                if (!toast) {
                    return;
                }

                toast.textContent = message;
                toast.classList.add('show');
                window.clearTimeout(toastTimeout);
                toastTimeout = window.setTimeout(() => toast.classList.remove('show'), 1800);
            };

            searchInput?.addEventListener('input', () => {
                const keyword = searchInput.value.trim().toLowerCase();
                let visibleRows = 0;

                rows.forEach((row) => {
                    const isMatch = !keyword || row.dataset.search.includes(keyword);
                    row.classList.toggle('d-none', !isMatch);
                    visibleRows += isMatch ? 1 : 0;
                });

                emptyState?.classList.toggle('d-none', visibleRows > 0);

                if (keyword) {
                    showToast(`${visibleRows} transaksi cocok dengan pencarian.`);
                }
            });

            const modal = document.querySelector('[data-transaction-modal]');
            const closeModalButton = document.querySelector('[data-transaction-close]');
            const detailFields = {
                title: document.querySelector('[data-detail-title]'),
                customer: document.querySelector('[data-detail-customer]'),
                type: document.querySelector('[data-detail-type]'),
                amount: document.querySelector('[data-detail-amount]'),
                paidAmount: document.querySelector('[data-detail-paid-amount]'),
                changeAmount: document.querySelector('[data-detail-change-amount]'),
                paymentMethod: document.querySelector('[data-detail-payment-method]'),
                paymentStatus: document.querySelector('[data-detail-payment-status]'),
                receiptStatus: document.querySelector('[data-detail-receipt-status]'),
                time: document.querySelector('[data-detail-time]'),
                quantity: document.querySelector('[data-detail-quantity]'),
                notes: document.querySelector('[data-detail-notes]'),
            };
            const detailPrint = document.querySelector('[data-detail-print]');
            const detailFinishForm = document.querySelector('[data-detail-finish-form]');

            const closeModal = () => {
                modal?.classList.remove('show');
                modal?.setAttribute('aria-hidden', 'true');
            };

            const openModal = (detail) => {
                if (!modal) {
                    return;
                }

                detailFields.title.textContent = detail.invoice || 'Detail Transaksi';
                detailFields.customer.textContent = detail.customer || '-';
                detailFields.type.textContent = detail.type || '-';
                detailFields.amount.textContent = detail.amount || '-';
                detailFields.paidAmount.textContent = detail.paidAmount || '-';
                detailFields.changeAmount.textContent = detail.changeAmount || '-';
                detailFields.paymentMethod.textContent = detail.paymentMethod || '-';
                detailFields.paymentStatus.textContent = detail.paymentStatus || '-';
                detailFields.receiptStatus.textContent = detail.receiptStatus || '-';
                detailFields.paymentStatus.className = `status-pill ${detail.paymentStatus === 'Lunas' ? 'is-paid' : 'is-pending'}`;
                detailFields.receiptStatus.className = `status-pill ${detail.receiptStatus === 'Sudah Dicetak' || detail.receiptStatus === 'Siap Cetak' ? 'is-paid' : 'is-pending'}`;
                detailFields.time.textContent = detail.time || '-';
                detailFields.quantity.textContent = detail.quantity || '-';
                detailFields.notes.textContent = detail.notes || '-';

                detailPrint?.classList.toggle('d-none', !detail.canPrint || !detail.printUrl);
                if (detailPrint && detail.printUrl) {
                    detailPrint.href = detail.printUrl;
                }

                detailFinishForm?.classList.toggle('d-none', !detail.canFinish || !detail.verifyUrl);
                if (detailFinishForm && detail.verifyUrl) {
                    detailFinishForm.action = detail.verifyUrl;
                }

                modal.classList.add('show');
                modal.setAttribute('aria-hidden', 'false');
                closeModalButton?.focus();
            };

            rows.forEach((row) => {
                const showDetail = () => {
                    try {
                        openModal(JSON.parse(row.dataset.detail || '{}'));
                    } catch (error) {
                        showToast('Detail transaksi belum bisa dibuka.');
                    }
                };

                row.addEventListener('click', showDetail);
                row.addEventListener('keydown', (event) => {
                    if (event.key === 'Enter' || event.key === ' ') {
                        event.preventDefault();
                        showDetail();
                    }
                });
            });

            document.querySelectorAll('[data-receipt-detail]').forEach((button) => {
                button.addEventListener('click', () => {
                    try {
                        openModal(JSON.parse(button.dataset.receiptDetail || '{}'));
                    } catch (error) {
                        showToast('Detail bukti pembayaran belum bisa dibuka.');
                    }
                });
            });

            closeModalButton?.addEventListener('click', closeModal);
            modal?.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeModal();
                }
            });

            const historySidebar = document.querySelector('[data-history-sidebar]');
            const historyOverlay = document.querySelector('[data-history-overlay]');
            const historyOpenButton = document.querySelector('[data-history-open]');
            const historyCloseButton = document.querySelector('[data-history-close]');
            const historyTabs = [...document.querySelectorAll('[data-history-tab]')];
            const historySections = [...document.querySelectorAll('[data-history-section]')];

            const closeHistory = () => {
                historySidebar?.classList.remove('show');
                historyOverlay?.classList.remove('show');
                historySidebar?.setAttribute('aria-hidden', 'true');
            };

            const openHistory = () => {
                historySidebar?.classList.add('show');
                historyOverlay?.classList.add('show');
                historySidebar?.setAttribute('aria-hidden', 'false');
                historyCloseButton?.focus();
            };

            historyOpenButton?.addEventListener('click', openHistory);
            historyCloseButton?.addEventListener('click', closeHistory);
            historyOverlay?.addEventListener('click', closeHistory);

            historyTabs.forEach((tab) => {
                tab.addEventListener('click', () => {
                    const target = tab.dataset.historyTab;
                    historyTabs.forEach((item) => item.classList.toggle('active', item === tab));
                    historySections.forEach((section) => {
                        section.classList.toggle('active', section.dataset.historySection === target);
                    });
                });
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeHistory();
                }
            });
        })();

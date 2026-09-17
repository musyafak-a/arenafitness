            document.addEventListener('DOMContentLoaded', function () {
                const root = document.documentElement;
                const body = document.body;
                const toggleButton = document.getElementById('sidebarToggle');
                const toggleIcon = document.getElementById('sidebarToggleIcon');
                const overlay = document.getElementById('sidebarOverlay');
                const themeToggles = document.querySelectorAll('[data-theme-toggle]');
                const pageLoader = document.getElementById('pageLoader');
                const pageLoaderText = document.getElementById('pageLoaderText');

                const hidePageLoader = () => {
                    pageLoader?.classList.remove('is-visible');
                };

                const showPageLoader = (message = 'Memuat halaman...') => {
                    if (pageLoaderText) {
                        pageLoaderText.textContent = message;
                    }

                    pageLoader?.classList.add('is-visible');
                };

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', hidePageLoader);
                } else {
                    hidePageLoader();
                }
                window.addEventListener('pageshow', hidePageLoader);
                window.addEventListener('load', hidePageLoader);

                const syncThemeToggle = () => {
                    const theme = root.getAttribute('data-theme') || 'dark';
                    themeToggles.forEach((themeToggle) => {
                        themeToggle.setAttribute('aria-label', theme === 'dark' ? 'Aktifkan light mode' : 'Aktifkan dark mode');
                    });
                };

                const syncSidebarState = () => {
                    const isOpen = body.classList.contains('sidebar-open');
                    toggleButton?.setAttribute('aria-expanded', isOpen ? 'true' : 'false');

                    if (toggleIcon) {
                        // Tetap gunakan ini agar simbol berubah saat diklik
                        toggleIcon.textContent = isOpen ? '‹' : '›';
                    }
                };

                themeToggles.forEach((themeToggle) => {
                    themeToggle.addEventListener('click', function () {
                        const nextTheme = (root.getAttribute('data-theme') || 'dark') === 'dark' ? 'light' : 'dark';
                        root.setAttribute('data-theme', nextTheme);
                        localStorage.setItem('arena-gym-theme', nextTheme);
                        syncThemeToggle();
                    });
                });

                const openSidebar = () => {
                    body.classList.add('sidebar-open');
                    syncSidebarState();
                };

                const closeSidebar = () => {
                    body.classList.remove('sidebar-open');
                    syncSidebarState();
                };

                toggleButton?.addEventListener('click', function () {
                    if (body.classList.contains('sidebar-open')) {
                        closeSidebar();
                        return;
                    }

                    openSidebar();
                });

                overlay?.addEventListener('click', closeSidebar);

                document.addEventListener('keydown', function (event) {
                    if (event.key === 'Escape') {
                        closeSidebar();
                    }
                });

                document.addEventListener('click', function (event) {
                    const link = event.target.closest('a[href]');

                    if (!link || event.defaultPrevented) {
                        return;
                    }

                    const href = link.getAttribute('href') || '';
                    const target = link.getAttribute('target');
                    const isModifiedClick = event.metaKey || event.ctrlKey || event.shiftKey || event.altKey || event.button !== 0;

                    if (
                        link.dataset.noLoader !== undefined ||
                        target === '_blank' ||
                        link.hasAttribute('download') ||
                        isModifiedClick ||
                        href === '' ||
                        href.startsWith('#') ||
                        href.startsWith('javascript:') ||
                        href.startsWith('mailto:') ||
                        href.startsWith('tel:')
                    ) {
                        return;
                    }

                    try {
                        const url = new URL(link.href, window.location.href);

                        if (url.origin !== window.location.origin) {
                            return;
                        }
                    } catch (error) {
                        return;
                    }

                    showPageLoader('Membuka halaman...');
                });

                document.addEventListener('submit', function (event) {
                    const form = event.target;

                    if (
                        form?.dataset?.noLoader !== undefined ||
                        form?.target === '_blank' ||
                        event.defaultPrevented
                    ) {
                        return;
                    }

                    showPageLoader('Memproses data...');
                });

                syncThemeToggle();
                syncSidebarState();
            });

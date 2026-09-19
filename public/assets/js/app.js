document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const mobileClose = document.getElementById('mobileSidebarClose');
    const backdrop = document.getElementById('sidebarBackdrop');
    const appShell = document.querySelector('.app');
    const mobileQuery = window.matchMedia('(max-width: 768px)');

    // ==========================================
    // SIDEBAR DRAWER & COLLAPSE LOGIC
    // ==========================================
    function openMobileSidebar() {
        if (!sidebar) return;
        sidebar.classList.add('open');
        backdrop?.classList.add('active');
        sidebarToggle?.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
    }

    function closeMobileSidebar() {
        if (!sidebar) return;
        sidebar.classList.remove('open');
        backdrop?.classList.remove('active');
        sidebarToggle?.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
    }

    function setDesktopSidebarState(collapsed) {
        if (!sidebar || !appShell || mobileQuery.matches) return;
        appShell.classList.toggle('sidebar-collapsed', collapsed);
        sidebar.classList.toggle('is-collapsed', collapsed);
        sidebarToggle?.setAttribute('aria-expanded', String(!collapsed));
        localStorage.setItem('hp-business-sidebar-collapsed', String(collapsed));
    }

    // Initialize desktop sidebar state
    if (!mobileQuery.matches && localStorage.getItem('hp-business-sidebar-collapsed') === 'true') {
        setDesktopSidebarState(true);
    }

    sidebarToggle?.addEventListener('click', () => {
        if (mobileQuery.matches) {
            if (sidebar?.classList.contains('open')) {
                closeMobileSidebar();
            } else {
                openMobileSidebar();
            }
        } else {
            const isCollapsed = appShell?.classList.contains('sidebar-collapsed') ?? false;
            setDesktopSidebarState(!isCollapsed);
        }
    });

    mobileClose?.addEventListener('click', closeMobileSidebar);
    backdrop?.addEventListener('click', closeMobileSidebar);

    // Close mobile sidebar when clicking links
    document.querySelectorAll('.sidebar .nav-link, .sidebar-logout-btn, .sidebar-register-link').forEach((link) => {
        link.addEventListener('click', () => {
            if (mobileQuery.matches) {
                closeMobileSidebar();
            }
        });
    });

    // Close on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && sidebar?.classList.contains('open')) {
            closeMobileSidebar();
        }
    });

    mobileQuery.addEventListener('change', () => {
        closeMobileSidebar();
        if (mobileQuery.matches) {
            appShell?.classList.remove('sidebar-collapsed');
            sidebar?.classList.remove('is-collapsed');
        } else {
            setDesktopSidebarState(localStorage.getItem('hp-business-sidebar-collapsed') === 'true');
        }
    });

    // ==========================================
    // FLOATING SCROLL TO TOP BUTTON
    // ==========================================
    let scrollTopBtn = document.getElementById('scrollTopBtn');
    if (!scrollTopBtn) {
        scrollTopBtn = document.createElement('button');
        scrollTopBtn.id = 'scrollTopBtn';
        scrollTopBtn.className = 'scroll-top-btn';
        scrollTopBtn.setAttribute('aria-label', 'Kembali ke atas');
        scrollTopBtn.innerHTML = '▲';
        document.body.appendChild(scrollTopBtn);
    }

    window.addEventListener('scroll', () => {
        if (window.scrollY > 280) {
            scrollTopBtn.classList.add('show');
        } else {
            scrollTopBtn.classList.remove('show');
        }
    }, { passive: true });

    scrollTopBtn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // ==========================================
    // SALES UNIT AUTO-FILL TARGET PRICE
    // ==========================================
    const salesSelect = document.getElementById('salesUnitSelect');
    const sellingPriceInput = document.getElementById('sellingPriceInput');

    if (salesSelect && sellingPriceInput) {
        salesSelect.addEventListener('change', () => {
            const selected = salesSelect.options[salesSelect.selectedIndex];
            const target = selected?.getAttribute('data-target');
            if (target && !sellingPriceInput.value) {
                sellingPriceInput.value = Math.round(parseFloat(target));
            }
        });
    }

    // ==========================================
    // FLASH ALERT AUTO DISMISS
    // ==========================================
    const flashAlerts = document.querySelectorAll('.flash-alert');
    flashAlerts.forEach((alert) => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => alert.remove(), 400);
        }, 6000);
    });
});

<?php
$currentPage = $page ?? 'dashboard';
$user = $_SESSION['user'] ?? ['name' => 'Admin HP', 'email' => 'admin@hpbusiness.com', 'role' => 'admin'];
$userInitial = strtoupper(substr($user['name'], 0, 1));
?>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a href="<?= e(url()) ?>" class="brand">
            <span class="brand-mark">▣</span>
            <div>
                HP BUSINESS
                <small>MANAGEMENT SYSTEM</small>
            </div>
        </a>
        <button class="mobile-sidebar-close" id="mobileSidebarClose" aria-label="Tutup menu sidebar">✕</button>
    </div>

    <div class="sidebar-scrollable">
        <p class="menu-label">OPERASIONAL</p>
        <nav aria-label="Menu Operasional">
            <a class="nav-link <?= $currentPage === 'dashboard' ? 'active' : '' ?>" href="<?= e(url()) ?>">
                <span class="nav-icon">▦</span> Dashboard
            </a>
            <a class="nav-link <?= $currentPage === 'sales' ? 'active' : '' ?>" href="<?= e(url('sales')) ?>">
                <span class="nav-icon">↗</span> Penjualan
            </a>
            <a class="nav-link <?= $currentPage === 'purchases' ? 'active' : '' ?>" href="<?= e(url('purchases')) ?>">
                <span class="nav-icon">↙</span> Pembelian
            </a>
            <a class="nav-link <?= $currentPage === 'inventory' ? 'active' : '' ?>" href="<?= e(url('inventory')) ?>">
                <span class="nav-icon">▤</span> Stok &amp; IMEI
            </a>
            <a class="nav-link <?= $currentPage === 'services' ? 'active' : '' ?>" href="<?= e(url('services')) ?>">
                <span class="nav-icon">⌁</span> Servis Unit
            </a>
        </nav>

        <p class="menu-label">DATA MASTER</p>
        <nav aria-label="Menu Data Master">
            <a class="nav-link <?= $currentPage === 'products' ? 'active' : '' ?>" href="<?= e(url('products')) ?>">
                <span class="nav-icon">▣</span> Produk HP
            </a>
            <a class="nav-link <?= $currentPage === 'customers' ? 'active' : '' ?>" href="<?= e(url('customers')) ?>">
                <span class="nav-icon">☺</span> Pelanggan
            </a>
            <a class="nav-link <?= $currentPage === 'suppliers' ? 'active' : '' ?>" href="<?= e(url('suppliers')) ?>">
                <span class="nav-icon">◎</span> Supplier
            </a>
        </nav>

        <p class="menu-label">FINANSIAL &amp; LAPORAN</p>
        <nav aria-label="Menu Finansial & Laporan">
            <a class="nav-link <?= $currentPage === 'finance' ? 'active' : '' ?>" href="<?= e(url('finance')) ?>">
                <span class="nav-icon">¤</span> Keuangan Kas
            </a>
            <a class="nav-link <?= $currentPage === 'reports' ? 'active' : '' ?>" href="<?= e(url('reports')) ?>">
                <span class="nav-icon">▧</span> Laporan Penjualan
            </a>
        </nav>
    </div>

    <div class="sidebar-footer">
        <div class="sidebar-user-card">
            <div class="user-info-row">
                <span class="user-avatar-box"><?= e($userInitial) ?></span>
                <div class="user-meta">
                    <b><?= e($user['name']) ?></b>
                    <small><?= e($user['email']) ?></small>
                </div>
                <span class="badge user-role-badge"><?= strtoupper(e($user['role'] ?? 'ADMIN')) ?></span>
            </div>
            <div class="sidebar-actions">
                <a class="button sidebar-logout-btn" href="<?= e(url('logout')) ?>">
                    <span>⎋</span> Keluar Akun
                </a>
                <a class="sidebar-register-link" href="<?= e(url('register')) ?>">
                    ＋ Daftar Akun Baru →
                </a>
            </div>
        </div>
    </div>
</aside>
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

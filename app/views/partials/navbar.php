<?php
$currentPage = $page ?? 'dashboard';
$user = $_SESSION['user'] ?? ['name' => 'Admin HP', 'email' => 'admin@hpbusiness.com', 'role' => 'admin'];
$userInitial = strtoupper(substr($user['name'], 0, 1));
$displayTitle = strtoupper($pageTitleShort ?? ($page ?? 'DASHBOARD'));
?>
<header class="topbar">
    <div class="topbar-left">
        <button class="square-button menu-toggle" id="sidebarToggle" aria-label="Tampilkan atau sembunyikan sidebar" aria-expanded="true" title="Toggle Sidebar (Ctrl+B)">
            <span class="hamburger-box">
                <span class="hamburger-bar bar-top"></span>
                <span class="hamburger-bar bar-mid"></span>
                <span class="hamburger-bar bar-bot"></span>
            </span>
        </button>
        <div class="live-status-pill">
            <span class="live-pulse"></span>
            <b>● LIVE</b>
            <span class="crumb-divider">/</span>
            <span class="crumb-title"><?= e($displayTitle) ?></span>
        </div>
    </div>

    <div class="topbar-right">
        <div class="date-chip">
            <span class="date-icon">📅</span>
            <span class="date-text"><?= e(date('d M Y')) ?></span>
        </div>
        <div class="user-chip">
            <span class="user-chip-avatar"><?= e($userInitial) ?></span>
            <span class="user-chip-name"><?= e($user['name']) ?></span>
            <a class="button mini-logout-btn" href="<?= e(url('logout')) ?>" title="Keluar dari akun">
                <span>⎋</span> Keluar
            </a>
        </div>
    </div>
</header>

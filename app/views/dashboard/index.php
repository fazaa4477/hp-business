<?php
$page = 'dashboard';
$pageTitleShort = 'DASHBOARD UTAMA';
$flash = flash();
?>
<div class="app">
    <?php require __DIR__ . '/../partials/sidebar.php'; ?>
    <main id="dashboard">
        <?php require __DIR__ . '/../partials/navbar.php'; ?>

        <?php if ($flash): ?>
            <div class="flash-alert" role="alert">
                <span class="flash-icon">⚡</span>
                <span class="flash-text"><?= e($flash) ?></span>
                <button class="flash-close" onclick="this.parentElement.remove()">✕</button>
            </div>
        <?php endif; ?>

        <div class="page-container neo-page-animate">
            <!-- HERO SECTION -->
            <section class="hero">
                <div class="hero-copy">
                    <div class="hero-tag-row">
                        <span class="kicker">#CONTROL YOUR STOCK</span>
                        <span class="badge yellow">⚡ REAL-TIME DATABASE</span>
                    </div>
                    <h1>Bisnis HP lebih rapi, lebih cuan.</h1>
                    <p>Kelola unit HP, lacak 15 digit IMEI, pantau margin profit per unit, hingga catat pembelian dan penjualan dari satu ruang kerja yang cepat dan intuitif.</p>
                    <div class="hero-buttons">
                        <a class="button blue" href="<?= e(url('sales')) ?>">
                            <span>↗</span> Buat Penjualan HP
                        </a>
                        <a class="button yellow" href="<?= e(url('purchases')) ?>">
                            <span>↙</span> Catat Pembelian
                        </a>
                        <a class="button" href="<?= e(url('inventory')) ?>">
                            <span>🔍</span> Scan / Cari IMEI
                        </a>
                    </div>
                </div>

                <article class="summary-card interactive-summary-card">
                    <div class="summary-badge-float">
                        <span class="badge mint">★ CUAN AKTIF</span>
                    </div>
                    <img class="hero-device" src="<?= e(url('assets/images/device-stack.svg')) ?>" alt="Ilustrasi perangkat HP Neo-Brutalism">
                    <div class="summary-card-content">
                        <p class="summary-card-label">Estimasi Profit Bulan Ini</p>
                        <strong class="summary-card-value"><?= rupiah($summary['profitMonth']) ?></strong>
                        <p class="summary-card-sub">Dihitung otomatis dari akumulasi margin penjualan bulan berjalan.</p>
                    </div>
                </article>
            </section>

            <!-- STATS SECTION -->
            <section class="stats" aria-label="Ringkasan bisnis operasional">
                <article class="stat stat-card">
                    <div class="stat-header">
                        <span>STOK TERSEDIA ▣</span>
                        <span class="stat-corner-tag">READY</span>
                    </div>
                    <b><?= number_format($summary['stockAvailable']) ?> <small>UNIT</small></b>
                    <small>Unit siap dijual di etalase</small>
                </article>

                <article class="stat yellow stat-card">
                    <div class="stat-header">
                        <span>PENJUALAN HARI INI ↗</span>
                        <span class="stat-corner-tag">HARI INI</span>
                    </div>
                    <b><?= rupiah($summary['salesToday']) ?></b>
                    <small><?= $summary['salesTodayCount'] ?> transaksi berhasil hari ini</small>
                </article>

                <article class="stat mint stat-card">
                    <div class="stat-header">
                        <span>KULAK BULAN INI ↙</span>
                        <span class="stat-corner-tag">BULANAN</span>
                    </div>
                    <b><?= rupiah($summary['purchaseMonth']) ?></b>
                    <small><?= $summary['purchaseMonthCount'] ?> unit dibeli bulan ini</small>
                </article>

                <article class="stat pink stat-card">
                    <div class="stat-header">
                        <span>TOTAL PELANGGAN ☺</span>
                        <span class="stat-corner-tag">KONTEN</span>
                    </div>
                    <b><?= number_format($summary['customerCount']) ?></b>
                    <small>Pelanggan setia terdata</small>
                </article>
            </section>

            <!-- DASHBOARD GRID (TRANSAKSI & STOK) -->
            <section class="dashboard-grid">
                <!-- Panel Transaksi Terbaru -->
                <article class="panel" id="transaksi">
                    <div class="panel-head">
                        <div>
                            <h2>Transaksi Terbaru</h2>
                            <small>Aktivitas pembelian &amp; penjualan terkini</small>
                        </div>
                        <a class="button mini-btn" href="<?= e(url('sales')) ?>">Lihat Semua Penjualan →</a>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>KODE &amp; TANGGAL</th>
                                    <th>TIPE</th>
                                    <th>PARTNER</th>
                                    <th>NOMINAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($recentTransactions): ?>
                                    <?php foreach ($recentTransactions as $transaction): ?>
                                        <tr>
                                            <td>
                                                <b><?= e($transaction['transaction_code']) ?></b>
                                                <br><small><?= e(date('d M Y', strtotime($transaction['transaction_date']))) ?></small>
                                            </td>
                                            <td>
                                                <span class="badge <?= $transaction['transaction_type'] === 'PENJUALAN' ? 'sale' : 'buy' ?>">
                                                    <?= $transaction['transaction_type'] === 'PENJUALAN' ? '↗ JUAL' : '↙ BELI' ?>
                                                </span>
                                            </td>
                                            <td><b><?= e($transaction['partner']) ?></b></td>
                                            <td><b><?= rupiah((float) $transaction['total_amount']) ?></b></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="empty-state">
                                            <div class="empty-box">
                                                <span class="empty-icon">📱</span>
                                                <p>Belum ada transaksi pembelian atau penjualan.</p>
                                                <small>Catat transaksi pertama Anda untuk melihat ringkasan di sini.</small>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </article>

                <!-- Panel Perhatian Stok -->
                <article class="panel" id="stok">
                    <div class="panel-head">
                        <div>
                            <h2>Perhatian Stok</h2>
                            <small>Status dan perputaran unit</small>
                        </div>
                        <a class="button mini-btn yellow" href="<?= e(url('inventory')) ?>">Kelola Unit →</a>
                    </div>
                    <div class="stock-list">
                        <div class="stock-item">
                            <div>
                                <b>Unit Siap Jual</b>
                                <small>Tersedia dan siap dipilih pada penjualan</small>
                            </div>
                            <span class="qty good"><?= $summary['stockAvailable'] ?> UNIT</span>
                        </div>
                        <div class="stock-item">
                            <div>
                                <b>Unit Terjual</b>
                                <small>Unit yang telah diserahkan ke pelanggan</small>
                            </div>
                            <span class="qty good"><?= $summary['stockSold'] ?> UNIT</span>
                        </div>
                        <div class="stock-item">
                            <div>
                                <b>Stok Mengendap (&gt;30 Hari)</b>
                                <small>Perlu promo diskon atau flash sale</small>
                            </div>
                            <span class="qty <?= (int)$summary['stockAging'] > 0 ? '' : 'good' ?>">
                                <?= $summary['stockAging'] ?> UNIT
                            </span>
                        </div>
                    </div>
                </article>
            </section>

            <!-- QUICK ACTIONS -->
            <section class="quick" id="quick-actions">
                <a class="quick-card" href="<?= e(url('products')) ?>">
                    <span class="quick-icon">▣</span>
                    <div class="quick-info">
                        <b>Tambah Model HP</b>
                        <span>Master data merek, varian, dan spesifikasi</span>
                    </div>
                </a>
                <a class="quick-card" href="<?= e(url('purchases')) ?>">
                    <span class="quick-icon">↙</span>
                    <div class="quick-info">
                        <b>Catat Pembelian Baru</b>
                        <span>Kulak unit HP bekas dan catat IMEI otomatis</span>
                    </div>
                </a>
                <a class="quick-card" href="<?= e(url('sales')) ?>">
                    <span class="quick-icon">↗</span>
                    <div class="quick-info">
                        <b>Buat Penjualan HP</b>
                        <span>Pilih unit tersedia dan hitung keuntungan</span>
                    </div>
                </a>
            </section>
        </div>
    </main>
</div>

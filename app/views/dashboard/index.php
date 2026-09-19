<div class="app">
    <aside class="sidebar" id="sidebar">
        <div class="brand"><span class="brand-mark">▣</span><div>HP BUSINESS<small>MANAGEMENT SYSTEM</small></div></div>
        <p class="menu-label">MENU UTAMA</p>
        <nav aria-label="Navigasi utama">
            <a class="nav-link active" href="#dashboard"><span>▦</span>Dashboard</a>
            <a class="nav-link" href="sales"><span>↗</span>Penjualan</a>
            <a class="nav-link" href="purchases"><span>↙</span>Pembelian</a>
            <a class="nav-link" href="inventory"><span>▤</span>Stok &amp; IMEI</a>
        </nav>
        <p class="menu-label">DATA MASTER</p>
        <nav aria-label="Data master">
            <a class="nav-link" href="products"><span>▣</span>Produk</a>
            <a class="nav-link" href="customers"><span>☺</span>Pelanggan</a>
            <a class="nav-link" href="suppliers"><span>◎</span>Supplier</a>
        </nav>
        <div class="sidebar-footer"><b><?= e($_SESSION['user']['name']) ?></b><small><?= e($_SESSION['user']['email']) ?></small><form method="post" action="logout"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><button class="text-button">Keluar</button></form></div>
    </aside>
    <main id="dashboard">
        <header class="topbar"><div class="crumb"><b>● LIVE</b> / DASHBOARD UTAMA</div><div class="top-actions"><span><?= e($today) ?></span><button class="square-button menu-toggle" id="menuToggle" aria-label="Buka menu" aria-expanded="false">☰</button></div></header>
        <section class="hero"><div class="hero-copy"><span class="kicker">#CONTROL YOUR STOCK</span><h1>Bisnis HP lebih rapi, lebih cuan.</h1><p>Kelola unit HP, IMEI, pembelian, sampai penjualan dari satu ruang kerja yang jelas dan cepat.</p><div class="hero-buttons"><a class="button blue" href="#quick-actions">＋ Tambah Penjualan</a><a class="button" href="#stok">Scan / Cari IMEI</a></div></div><article class="summary-card"><p>Profit bulan ini</p><strong><?= rupiah($summary['profitMonth']) ?></strong><p>Dihitung dari penjualan bulan berjalan.</p></article></section>
        <section class="stats" aria-label="Ringkasan bisnis">
            <article class="stat"><span>STOK TERSEDIA ▣</span><b><?= number_format($summary['stockAvailable']) ?></b><small>Unit siap dijual</small></article>
            <article class="stat yellow"><span>PENJUALAN HARI INI ↗</span><b><?= rupiah($summary['salesToday']) ?></b><small><?= $summary['salesTodayCount'] ?> transaksi hari ini</small></article>
            <article class="stat mint"><span>PEMBELIAN BULAN INI ↙</span><b><?= rupiah($summary['purchaseMonth']) ?></b><small><?= $summary['purchaseMonthCount'] ?> transaksi bulan ini</small></article>
            <article class="stat pink"><span>TOTAL PELANGGAN ☺</span><b><?= number_format($summary['customerCount']) ?></b><small>Pelanggan aktif</small></article>
        </section>
        <section class="dashboard-grid">
            <article class="panel" id="transaksi"><div class="panel-head"><h2>Transaksi terbaru</h2><a href="#quick-actions">Lihat semua →</a></div><table><thead><tr><th>TRANSAKSI</th><th>TIPE</th><th>PARTNER</th><th>NOMINAL</th></tr></thead><tbody><?php if ($recentTransactions): ?><?php foreach ($recentTransactions as $transaction): ?><tr><td><b><?= e($transaction['transaction_code']) ?></b><br><small><?= e(date('d M Y', strtotime($transaction['transaction_date']))) ?></small></td><td><span class="badge <?= $transaction['transaction_type'] === 'PENJUALAN' ? 'sale' : 'buy' ?>"><?= e($transaction['transaction_type']) ?></span></td><td><?= e($transaction['partner']) ?></td><td><?= rupiah((float) $transaction['total_amount']) ?></td></tr><?php endforeach; ?><?php else: ?><tr><td colspan="4" class="empty">📱 Belum ada transaksi. Catat pembelian atau penjualan pertama.</td></tr><?php endif; ?></tbody></table></article>
            <article class="panel" id="stok"><div class="panel-head"><h2>Perhatian stok</h2><a href="#quick-actions">Kelola →</a></div><div class="stock-list"><div><b>Unit tersedia</b><span class="qty good"><?= $summary['stockAvailable'] ?> UNIT</span><small>Siap dipilih pada penjualan</small></div><div><b>Unit terjual</b><span class="qty good"><?= $summary['stockSold'] ?> UNIT</span><small>Terhubung ke riwayat penjualan</small></div><div><b>Stok lebih dari 30 hari</b><span class="qty"><?= $summary['stockAging'] ?> UNIT</span><small>Perlu promo atau follow-up</small></div></div></article>
        </section>
        <section class="quick" id="quick-actions"><a href="products"><b>＋</b>Tambah produk</a><a href="purchases"><b>↙</b>Catat pembelian</a><a href="sales"><b>↗</b>Buat penjualan</a></section>
    </main>
</div>

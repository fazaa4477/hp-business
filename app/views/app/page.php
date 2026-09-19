<?php
$labels = [
    'products' => 'Produk HP',
    'suppliers' => 'Supplier',
    'customers' => 'Pelanggan',
    'inventory' => 'Inventory & IMEI',
    'purchases' => 'Pembelian',
    'sales' => 'Penjualan',
    'services' => 'Servis Unit',
    'finance' => 'Keuangan',
    'reports' => 'Laporan Penjualan'
];
$pageTitleShort = $labels[$page] ?? ucfirst($page);
?>
<div class="app">
    <?php require __DIR__ . '/../partials/sidebar.php'; ?>
    <main>
        <?php require __DIR__ . '/../partials/navbar.php'; ?>

        <?php if ($flash): ?>
            <div class="flash-alert" role="alert">
                <span class="flash-icon">⚡</span>
                <span class="flash-text"><?= e($flash) ?></span>
                <button class="flash-close" onclick="this.parentElement.remove()">✕</button>
            </div>
        <?php endif; ?>

        <div class="page-container neo-page-animate">

        <!-- ========================================== -->
        <!-- MASTER DATA: PRODUCTS, SUPPLIERS, CUSTOMERS -->
        <!-- ========================================== -->
        <?php if (in_array($page, ['products', 'suppliers', 'customers'], true)):
            $meta = [
                'products' => [
                    'Produk HP',
                    'product',
                    [
                        'brand' => 'Merek HP',
                        'model' => 'Model HP',
                        'variant' => 'Varian (5G/Pro/Max)',
                        'color' => 'Warna',
                        'ram' => 'RAM (misal: 8GB)',
                        'storage' => 'Penyimpanan (misal: 128GB)'
                    ]
                ],
                'suppliers' => [
                    'Supplier',
                    'supplier',
                    [
                        'name' => 'Nama Supplier / Toko',
                        'phone' => 'Nomor WhatsApp / HP',
                        'email' => 'Email',
                        'address' => 'Alamat Lengkap'
                    ]
                ],
                'customers' => [
                    'Pelanggan',
                    'customer',
                    [
                        'name' => 'Nama Pelanggan',
                        'phone' => 'Nomor WhatsApp / HP',
                        'email' => 'Email',
                        'address' => 'Alamat Domisili'
                    ]
                ]
            ][$page];
        ?>
            <section class="form-card">
                <div class="form-card-header">
                    <p class="eyebrow">TAMBAH DATA MASTER</p>
                    <h1>Tambah <?= e($meta[0]) ?></h1>
                    <p class="section-note">Data yang tersimpan akan langsung terhubung ke pencatatan stok, pembelian, dan penjualan.</p>
                </div>
                <form class="grid-form" method="post" action="<?= e(url('master')) ?>">
                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                    <input type="hidden" name="type" value="<?= e($meta[1]) ?>">
                    <input type="hidden" name="return" value="<?= e($page) ?>">

                    <?php foreach ($meta[2] as $field => $fieldLabel): ?>
                        <label>
                            <span><?= e($fieldLabel) ?></span>
                            <input name="<?= e($field) ?>" <?= in_array($field, ['brand', 'model', 'name'], true) ? 'required' : '' ?> placeholder="Masukkan <?= strtolower($fieldLabel) ?>...">
                        </label>
                    <?php endforeach; ?>

                    <div class="grid-col-full form-submit-row">
                        <button type="submit" class="button blue">
                            <span>＋</span> Simpan Data <?= e($meta[0]) ?>
                        </button>
                    </div>
                </form>
            </section>

            <section class="panel list-panel">
                <div class="panel-head">
                    <div>
                        <h2>Daftar <?= e($meta[0]) ?> Terdaftar</h2>
                        <small>Total data aktif dalam sistem</small>
                    </div>
                    <span class="count-pill"><?= count(${$page}) ?> <?= strtolower($meta[0]) ?></span>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>KODE</th>
                                <th><?= $page === 'products' ? 'MEREK & MODEL' : 'NAMA LENGKAP' ?></th>
                                <th><?= $page === 'products' ? 'VARIAN & WARNA' : 'TELEPON / WA' ?></th>
                                <th><?= $page === 'products' ? 'RAM / STORAGE' : 'EMAIL & ALAMAT' ?></th>
                                <th class="text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (${$page}): ?>
                                <?php foreach (${$page} as $row): ?>
                                    <tr>
                                        <td>
                                            <span class="badge yellow">
                                                <?= e($row['product_code'] ?? $row['supplier_code'] ?? $row['customer_code'] ?? ('#' . $row['id'])) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <b><?= e($row['name'] ?? trim(($row['brand'] ?? '') . ' ' . ($row['model'] ?? ''))) ?></b>
                                        </td>
                                        <td>
                                            <?php if ($page === 'products'): ?>
                                                <?= e(trim(($row['variant'] ?? '') . ' ' . ($row['color'] ?? '')) ?: '-') ?>
                                            <?php else: ?>
                                                <?= !empty($row['phone']) ? '📱 ' . e($row['phone']) : '-' ?>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($page === 'products'): ?>
                                                <span class="badge mint"><?= e(($row['ram'] ? $row['ram'] . ' / ' : '') . ($row['storage'] ?: 'Standard')) ?></span>
                                            <?php else: ?>
                                                <div><?= e($row['email'] ?? '-') ?></div>
                                                <small class="muted-text"><?= e($row['address'] ?? '') ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="table-actions">
                                                <a class="button mini-btn yellow" href="<?= e(url($page . '/edit?id=' . $row['id'])) ?>" title="Edit data">
                                                    ✏ Edit
                                                </a>
                                                <form method="post" action="<?= e(url($page . '/delete')) ?>" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data <?= e($row['name'] ?? $row['model'] ?? 'ini') ?>?');" style="display:inline;">
                                                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                                    <input type="hidden" name="id" value="<?= e((string)$row['id']) ?>">
                                                    <button type="submit" class="button mini-btn pink" title="Hapus data">
                                                        🗑 Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="empty-state">
                                        <div class="empty-box">
                                            <span class="empty-icon">📂</span>
                                            <p>Belum ada data <?= strtolower($meta[0]) ?>.</p>
                                            <small>Gunakan formulir di atas untuk menambahkan data pertama.</small>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

        <!-- ========================================== -->
        <!-- PURCHASES (PEMBELIAN HP) -->
        <!-- ========================================== -->
        <?php elseif ($page === 'purchases'): ?>
            <section class="form-card">
                <div class="form-card-header">
                    <p class="eyebrow">STOK MASUK &amp; KULAK HP</p>
                    <h1>Catat Pembelian HP</h1>
                    <p class="section-note">Satu formulir akan membuat data pembelian, unit HP ber-IMEI, dan stok masuk secara otomatis.</p>
                </div>

                <?php if (!$suppliers || !$products): ?>
                    <div class="flash-alert warning">
                        <span class="flash-icon">⚠️</span>
                        <span class="flash-text">
                            Silakan tambahkan <?= !$suppliers ? '<a href="' . e(url('suppliers')) . '">Supplier</a>' : '' ?> <?= (!$suppliers && !$products) ? 'dan ' : '' ?> <?= !$products ? '<a href="' . e(url('products')) . '">Produk HP</a>' : '' ?> terlebih dahulu sebelum mencatat pembelian.
                        </span>
                    </div>
                <?php else: ?>
                    <form class="grid-form" method="post" action="<?= e(url('purchases')) ?>">
                        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

                        <label>
                            <span>Supplier / Sumber Kulak *</span>
                            <select name="supplier_id" required>
                                <option value="">-- Pilih Supplier --</option>
                                <?php foreach ($suppliers as $s): ?>
                                    <option value="<?= $s['id'] ?>"><?= e($s['name']) ?> (<?= e($s['phone'] ?? 'No HP') ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </label>

                        <label>
                            <span>Model HP *</span>
                            <select name="product_id" required>
                                <option value="">-- Pilih Model HP --</option>
                                <?php foreach ($products as $p): ?>
                                    <option value="<?= $p['id'] ?>"><?= e(trim($p['brand'] . ' ' . $p['model'] . ' ' . ($p['variant'] ?? ''))) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>

                        <label>
                            <span>Nomor IMEI (15 Digit) *</span>
                            <input name="imei" inputmode="numeric" minlength="10" maxlength="20" placeholder="Contoh: 352819001234567" required>
                        </label>

                        <label>
                            <span>Harga Beli Modal (Rp) *</span>
                            <input name="purchase_price" type="number" min="1" placeholder="Contoh: 3500000" required>
                        </label>

                        <label>
                            <span>Target Harga Jual (Rp) *</span>
                            <input name="selling_price" type="number" min="1" placeholder="Contoh: 4200000" required>
                        </label>

                        <label>
                            <span>Metode Pembayaran</span>
                            <select name="payment_method">
                                <option value="cash">Cash / Tunai</option>
                                <option value="transfer">Transfer Bank</option>
                                <option value="qris">QRIS</option>
                            </select>
                        </label>

                        <label>
                            <span>Battery Health (%)</span>
                            <input name="battery_health" type="number" min="0" max="100" placeholder="Contoh: 88">
                        </label>

                        <label>
                            <span>Kondisi Layar</span>
                            <input name="screen_condition" placeholder="Contoh: Mulus / No Shadow / TrueTone On">
                        </label>

                        <label>
                            <span>Kondisi Fisik / Body</span>
                            <input name="body_condition" placeholder="Contoh: Mulus 97%, lecet pemakaian tipis">
                        </label>

                        <label class="grid-col-full">
                            <span>Kelengkapan Unit</span>
                            <input name="completeness" placeholder="Contoh: Fullset Original (Dus + Kabel C)">
                        </label>

                        <div class="grid-col-full form-submit-row">
                            <button type="submit" class="button blue">
                                <span>＋</span> Simpan Pembelian &amp; Buat Unit Baru
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </section>

            <section class="panel list-panel">
                <div class="panel-head">
                    <div>
                        <h2>Riwayat Transaksi Pembelian</h2>
                        <small>Daftar pembelian HP dan unit masuk</small>
                    </div>
                    <span class="count-pill"><?= count($purchases) ?> pembelian</span>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>KODE &amp; TANGGAL</th>
                                <th>SUPPLIER</th>
                                <th>UNIT &amp; IMEI</th>
                                <th>MODAL BELI</th>
                                <th>STATUS UNIT</th>
                                <th class="text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($purchases): ?>
                                <?php foreach ($purchases as $row): ?>
                                    <tr>
                                        <td>
                                            <b><?= e($row['purchase_code']) ?></b>
                                            <br><small><?= e(date('d M Y', strtotime($row['purchase_date']))) ?></small>
                                        </td>
                                        <td>
                                            <?= e($row['supplier_name'] ?? 'Umum') ?>
                                            <?php if (!empty($row['supplier_phone'])): ?>
                                                <br><small><?= e($row['supplier_phone']) ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <b><?= e(trim(($row['brand'] ?? '') . ' ' . ($row['model'] ?? ''))) ?></b>
                                            <br><span class="badge yellow">IMEI: <?= e($row['imei'] ?? '-') ?></span>
                                        </td>
                                        <td>
                                            <b><?= rupiah((float)$row['total_amount']) ?></b>
                                            <br><small class="badge mini-badge"><?= strtoupper(e($row['payment_method'])) ?></small>
                                        </td>
                                        <td>
                                            <?php if (($row['unit_status'] ?? '') === 'sold'): ?>
                                                <span class="badge sale">TERJUAL</span>
                                            <?php else: ?>
                                                <span class="badge mint">TERSEDIA</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="table-actions">
                                                <a class="button mini-btn yellow" href="<?= e(url('purchases/edit?id=' . $row['id'])) ?>" title="Edit data pembelian">
                                                    ✏ Edit
                                                </a>
                                                <?php if (($row['unit_status'] ?? '') !== 'sold'): ?>
                                                    <form method="post" action="<?= e(url('purchases/delete')) ?>" onsubmit="return confirm('Batalkan pembelian ini? Unit HP akan dihapus dari stok.');" style="display:inline;">
                                                        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                                        <input type="hidden" name="id" value="<?= e((string)$row['id']) ?>">
                                                        <button type="submit" class="button mini-btn pink" title="Batalkan pembelian">
                                                            🗑 Batal
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <span class="muted-badge" title="Unit telah terjual">Terkunci</span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="empty-state">
                                        <div class="empty-box">
                                            <span class="empty-icon">📱</span>
                                            <p>Belum ada transaksi pembelian HP.</p>
                                            <small>Catat pembelian pertama untuk menambah stok unit ber-IMEI.</small>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

        <!-- ========================================== -->
        <!-- SALES (PENJUALAN HP) -->
        <!-- ========================================== -->
        <?php elseif ($page === 'sales'): ?>
            <section class="form-card">
                <div class="form-card-header">
                    <p class="eyebrow">STOK KELUAR &amp; PENJUALAN</p>
                    <h1>Buat Penjualan HP</h1>
                    <p class="section-note">Pilih unit HP yang siap jual. Profit dihitung otomatis dan status unit langsung berubah menjadi terjual.</p>
                </div>

                <?php if (!$customers || !$availableUnits): ?>
                    <div class="flash-alert warning">
                        <span class="flash-icon">⚠️</span>
                        <span class="flash-text">
                            <?= !$customers ? 'Tambahkan <a href="' . e(url('customers')) . '">Pelanggan</a> terlebih dahulu.' : 'Belum ada unit HP yang tersedia di inventaris. Silakan <a href="' . e(url('purchases')) . '">catat pembelian HP baru</a> terlebih dahulu.' ?>
                        </span>
                    </div>
                <?php else: ?>
                    <form class="grid-form" method="post" action="<?= e(url('sales')) ?>">
                        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

                        <label>
                            <span>Pelanggan *</span>
                            <select name="customer_id" required>
                                <option value="">-- Pilih Pelanggan --</option>
                                <?php foreach ($customers as $c): ?>
                                    <option value="<?= $c['id'] ?>"><?= e($c['name']) ?> (<?= e($c['phone'] ?? 'No WA') ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </label>

                        <label class="grid-col-2">
                            <span>Pilih Unit HP Tersedia (IMEI &amp; Model) *</span>
                            <select name="unit_id" id="salesUnitSelect" required>
                                <option value="">-- Pilih Unit HP Siap Jual --</option>
                                <?php foreach ($availableUnits as $u): ?>
                                    <option value="<?= $u['id'] ?>" data-modal="<?= (float)$u['purchase_price'] ?>" data-target="<?= (float)$u['selling_price'] ?>">
                                        <?= e($u['brand'] . ' ' . $u['model'] . ' · IMEI: ' . $u['imei'] . ' (Modal: ' . rupiah((float)$u['purchase_price']) . ' | Target: ' . rupiah((float)$u['selling_price']) . ')') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>

                        <label>
                            <span>Harga Jual Realisasi (Rp) *</span>
                            <input name="selling_price" id="sellingPriceInput" type="number" min="1" placeholder="Masukkan harga deal" required>
                        </label>

                        <label>
                            <span>Metode Pembayaran</span>
                            <select name="payment_method">
                                <option value="cash">Cash / Tunai</option>
                                <option value="transfer">Transfer Bank</option>
                                <option value="qris">QRIS</option>
                            </select>
                        </label>

                        <div class="grid-col-full form-submit-row">
                            <button type="submit" class="button blue">
                                <span>↗</span> Simpan Penjualan &amp; Cetak Transaksi
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </section>

            <section class="panel list-panel">
                <div class="panel-head">
                    <div>
                        <h2>Riwayat Penjualan HP</h2>
                        <small>Daftar transaksi penjualan dan profit</small>
                    </div>
                    <span class="count-pill"><?= count($sales) ?> penjualan</span>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>KODE &amp; TANGGAL</th>
                                <th>PELANGGAN</th>
                                <th>UNIT &amp; IMEI</th>
                                <th>HARGA JUAL</th>
                                <th>PROFIT CUAN</th>
                                <th class="text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($sales): ?>
                                <?php foreach ($sales as $row): ?>
                                    <tr>
                                        <td>
                                            <b><?= e($row['sale_code']) ?></b>
                                            <br><small><?= e(date('d M Y', strtotime($row['sale_date']))) ?></small>
                                        </td>
                                        <td>
                                            <b><?= e($row['customer_name'] ?? 'Umum') ?></b>
                                            <?php if (!empty($row['customer_phone'])): ?>
                                                <br><small><?= e($row['customer_phone']) ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?= e(trim(($row['brand'] ?? '') . ' ' . ($row['model'] ?? ''))) ?>
                                            <br><span class="badge yellow">IMEI: <?= e($row['imei'] ?? '-') ?></span>
                                        </td>
                                        <td>
                                            <b><?= rupiah((float)$row['total_amount']) ?></b>
                                            <br><small class="badge mini-badge"><?= strtoupper(e($row['payment_method'])) ?></small>
                                        </td>
                                        <td>
                                            <span class="badge <?= (float)($row['profit'] ?? 0) >= 0 ? 'sale' : 'buy' ?>">
                                                <?= ((float)($row['profit'] ?? 0) >= 0 ? '+' : '') . rupiah((float)($row['profit'] ?? 0)) ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="table-actions">
                                                <a class="button mini-btn yellow" href="<?= e(url('sales/edit?id=' . $row['id'])) ?>" title="Edit penjualan">
                                                    ✏ Edit
                                                </a>
                                                <form method="post" action="<?= e(url('sales/delete')) ?>" onsubmit="return confirm('Batalkan penjualan ini? Unit HP akan dikembalikan ke status tersedia.');" style="display:inline;">
                                                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                                    <input type="hidden" name="id" value="<?= e((string)$row['id']) ?>">
                                                    <button type="submit" class="button mini-btn pink" title="Batalkan penjualan">
                                                        🗑 Batal
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="empty-state">
                                        <div class="empty-box">
                                            <span class="empty-icon">💰</span>
                                            <p>Belum ada data penjualan tercatat.</p>
                                            <small>Catat penjualan HP untuk mulai menghasilkan profit.</small>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

        <!-- ========================================== -->
        <!-- INVENTORY (STOK & IMEI TRACKER) -->
        <!-- ========================================== -->
        <?php elseif ($page === 'inventory'): ?>
            <section class="form-card">
                <div class="form-card-header">
                    <p class="eyebrow">LACAK UNIT &amp; IMEI</p>
                    <h1>Inventory &amp; IMEI Tracker</h1>
                    <p class="section-note">Cari unit HP berdasarkan 15 digit IMEI, nama model, atau merek produk.</p>
                </div>
                <form class="search-form" method="get" action="<?= e(url('inventory')) ?>">
                    <input name="q" value="<?= e($_GET['q'] ?? '') ?>" placeholder="Ketik IMEI, model HP, atau merek..." autocomplete="off">
                    <button type="submit" class="button blue">🔍 Cari Unit</button>
                    <?php if (!empty($_GET['q'])): ?>
                        <a href="<?= e(url('inventory')) ?>" class="button yellow">✕ Reset</a>
                    <?php endif; ?>
                </form>
            </section>

            <section class="panel list-panel">
                <div class="panel-head">
                    <div>
                        <h2>Semua Unit HP Tercatat</h2>
                        <small>Unit siap jual maupun riwayat terjual</small>
                    </div>
                    <span class="count-pill"><?= count($units) ?> unit</span>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>UNIT HP</th>
                                <th>NOMOR IMEI</th>
                                <th>KONDISI / FISIK</th>
                                <th>MODAL AKHIR</th>
                                <th>HARGA JUAL</th>
                                <th>STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($units): ?>
                                <?php foreach ($units as $r): ?>
                                    <tr>
                                        <td>
                                            <b><?= e($r['brand'] . ' ' . $r['model']) ?></b>
                                            <br><small><?= e($r['variant'] ?: 'Standar') ?></small>
                                        </td>
                                        <td>
                                            <span class="badge yellow">IMEI: <?= e($r['imei']) ?></span>
                                            <?php if (!empty($r['battery_health'])): ?>
                                                <br><small>🔋 BH: <?= e((string)$r['battery_health']) ?>%</small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <small><?= e($r['screen_condition'] ?: 'Layar: -') ?></small>
                                            <br><small class="muted-text"><?= e($r['body_condition'] ?: 'Body: -') ?></small>
                                        </td>
                                        <td><?= rupiah((float)$r['purchase_price']) ?></td>
                                        <td><b><?= rupiah((float)$r['selling_price']) ?></b></td>
                                        <td>
                                            <?php if ($r['status'] === 'available'): ?>
                                                <span class="badge mint">TERSEDIA</span>
                                            <?php elseif ($r['status'] === 'sold'): ?>
                                                <span class="badge sale">TERJUAL</span>
                                            <?php else: ?>
                                                <span class="badge pink"><?= strtoupper(e($r['status'])) ?></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="empty-state">
                                        <div class="empty-box">
                                            <span class="empty-icon">🔍</span>
                                            <p>Tidak ada unit yang cocok dengan pencarian.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

        <!-- ========================================== -->
        <!-- SERVICES (SERVIS UNIT) -->
        <!-- ========================================== -->
        <?php elseif ($page === 'services'): ?>
            <section class="form-card">
                <div class="form-card-header">
                    <p class="eyebrow">BIAYA PERBAIKAN UNIT</p>
                    <h1>Catat Servis Unit HP</h1>
                    <p class="section-note">Biaya servis otomatis menambah modal HP terkait dan tercatat sebagai pengeluaran kas operasional.</p>
                </div>

                <?php if (!$availableUnits): ?>
                    <div class="flash-alert warning">
                        <span class="flash-icon">⚠️</span>
                        <span class="flash-text">Belum ada unit HP yang berstatus tersedia untuk dicatat perbaikan servisnya.</span>
                    </div>
                <?php else: ?>
                    <form class="grid-form" method="post" action="<?= e(url('services')) ?>">
                        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

                        <label class="grid-col-2">
                            <span>Pilih Unit HP *</span>
                            <select name="unit_id" required>
                                <option value="">-- Pilih Unit HP --</option>
                                <?php foreach ($availableUnits as $r): ?>
                                    <option value="<?= $r['id'] ?>">
                                        <?= e($r['brand'] . ' ' . $r['model'] . ' · IMEI: ' . $r['imei'] . ' (Modal Saat Ini: ' . rupiah((float)$r['purchase_price']) . ')') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>

                        <label>
                            <span>Biaya Servis (Rp) *</span>
                            <input name="cost" type="number" min="1" placeholder="Contoh: 150000" required>
                        </label>

                        <label class="grid-col-full">
                            <span>Rincian / Keterangan Servis *</span>
                            <input name="description" placeholder="Contoh: Ganti baterai baru / Ganti fleksibel on-off" required>
                        </label>

                        <div class="grid-col-full form-submit-row">
                            <button type="submit" class="button blue">
                                <span>＋</span> Simpan Biaya Servis
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </section>

            <section class="panel list-panel">
                <div class="panel-head">
                    <div>
                        <h2>Riwayat Biaya Servis</h2>
                        <small>Catatan seluruh servis unit HP</small>
                    </div>
                    <span class="count-pill"><?= count($services) ?> servis</span>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>UNIT &amp; IMEI</th>
                                <th>TANGGAL</th>
                                <th>KETERANGAN PERBAIKAN</th>
                                <th>BIAYA SERVIS</th>
                                <th class="text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($services): ?>
                                <?php foreach ($services as $r): ?>
                                    <tr>
                                        <td>
                                            <b><?= e($r['brand'] . ' ' . $r['model']) ?></b>
                                            <br><span class="badge yellow">IMEI: <?= e($r['imei']) ?></span>
                                        </td>
                                        <td><?= e(date('d M Y', strtotime($r['service_date']))) ?></td>
                                        <td><?= e($r['description']) ?></td>
                                        <td><b><?= rupiah((float)$r['cost']) ?></b></td>
                                        <td class="text-center">
                                            <div class="table-actions">
                                                <a class="button mini-btn yellow" href="<?= e(url('services/edit?id=' . $r['id'])) ?>" title="Edit servis">
                                                    ✏ Edit
                                                </a>
                                                <form method="post" action="<?= e(url('services/delete')) ?>" onsubmit="return confirm('Hapus catatan servis ini? Modal unit akan dikurangi kembali.');" style="display:inline;">
                                                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                                    <input type="hidden" name="id" value="<?= e((string)$r['id']) ?>">
                                                    <button type="submit" class="button mini-btn pink" title="Hapus catatan servis">
                                                        🗑 Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="empty-state">
                                        <div class="empty-box">
                                            <span class="empty-icon">🔧</span>
                                            <p>Belum ada biaya perbaikan servis yang dicatat.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

        <!-- ========================================== -->
        <!-- FINANCE (KEUANGAN KAS) -->
        <!-- ========================================== -->
        <?php elseif ($page === 'finance'): ?>
            <section class="form-card">
                <div class="form-card-header">
                    <p class="eyebrow">CASHFLOW &amp; KAS BISNIS</p>
                    <h1>Pencatatan Keuangan</h1>
                    <p class="section-note">Catat modal masuk tambahan, operasional umum, biaya sewa, prive pemilik, atau penyesuaian kas.</p>
                </div>
                <form class="grid-form" method="post" action="<?= e(url('finance')) ?>">
                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

                    <label>
                        <span>Jenis Transaksi Kas *</span>
                        <select name="transaction_type">
                            <option value="expense">Pengeluaran Operasional (Kas Keluar)</option>
                            <option value="capital">Modal Masuk Tambahan (Kas Masuk)</option>
                            <option value="withdrawal">Prive Pemilik (Kas Keluar)</option>
                            <option value="adjustment">Penyesuaian Kas Fisik</option>
                        </select>
                    </label>

                    <label>
                        <span>Nominal Transaksi (Rp) *</span>
                        <input name="amount" type="number" min="1" placeholder="Contoh: 500000" required>
                    </label>

                    <label class="grid-col-full">
                        <span>Keterangan Lengkap *</span>
                        <input name="description" placeholder="Contoh: Beli kuota internet konter / bayar listrik" required>
                    </label>

                    <div class="grid-col-full form-submit-row">
                        <button type="submit" class="button blue">
                            <span>＋</span> Simpan Transaksi Keuangan
                        </button>
                    </div>
                </form>
            </section>

            <section class="panel list-panel">
                <div class="panel-head">
                    <div>
                        <h2>Riwayat Cashflow Keuangan</h2>
                        <small>Aliran kas masuk dan kas keluar</small>
                    </div>
                    <span class="count-pill"><?= count($finances) ?> transaksi</span>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>KODE &amp; TANGGAL</th>
                                <th>JENIS</th>
                                <th>KETERANGAN</th>
                                <th>ARUS KAS</th>
                                <th>NOMINAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($finances): ?>
                                <?php foreach ($finances as $r): ?>
                                    <tr>
                                        <td>
                                            <b><?= e($r['transaction_code']) ?></b>
                                            <br><small><?= e(date('d M Y', strtotime($r['transaction_date']))) ?></small>
                                        </td>
                                        <td><span class="badge yellow"><?= strtoupper(e($r['transaction_type'])) ?></span></td>
                                        <td><?= e($r['description']) ?></td>
                                        <td>
                                            <span class="badge <?= $r['cash_flow'] === 'in' ? 'sale' : 'buy' ?>">
                                                <?= $r['cash_flow'] === 'in' ? 'MASUK (+)' : 'KELUAR (−)' ?>
                                            </span>
                                        </td>
                                        <td><b><?= rupiah((float)$r['amount']) ?></b></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="empty-state">
                                        <div class="empty-box">
                                            <span class="empty-icon">💵</span>
                                            <p>Belum ada catatan transaksi keuangan.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

        <!-- ========================================== -->
        <!-- REPORTS (LAPORAN PENJUALAN) -->
        <!-- ========================================== -->
        <?php else: ?>
            <?php
                $totalOmset = array_sum(array_column($report, 'total_amount'));
                $totalProfit = array_sum(array_column($report, 'profit'));
            ?>
            <section class="stats" aria-label="Ringkasan Penjualan">
                <article class="stat-card yellow">
                    <div class="stat-header">
                        <span class="stat-title">TOTAL TRANSAKSI</span>
                        <span class="stat-corner-tag">↗ PENJUALAN</span>
                    </div>
                    <div class="stat-body">
                        <span class="stat-number"><?= count($report) ?> <small>TRANSAKSI</small></span>
                    </div>
                    <div class="stat-footer">
                        <small class="stat-sub">Akumulasi seluruh penjualan sukses</small>
                    </div>
                </article>

                <article class="stat-card mint">
                    <div class="stat-header">
                        <span class="stat-title">TOTAL OMSET PENJUALAN</span>
                        <span class="stat-corner-tag">▣ BRUTO</span>
                    </div>
                    <div class="stat-body">
                        <span class="stat-number"><?= rupiah((float)$totalOmset) ?></span>
                    </div>
                    <div class="stat-footer">
                        <small class="stat-sub">Total penerimaan kotor dari penjualan unit</small>
                    </div>
                </article>

                <article class="stat-card pink">
                    <div class="stat-header">
                        <span class="stat-title">TOTAL PROFIT BERSIH</span>
                        <span class="stat-corner-tag">☺ LABA</span>
                    </div>
                    <div class="stat-body">
                        <span class="stat-number"><?= rupiah((float)$totalProfit) ?></span>
                    </div>
                    <div class="stat-footer">
                        <small class="stat-sub">Margin keuntungan setelah dikurangi modal unit (HPP)</small>
                    </div>
                </article>
            </section>

            <!-- EXPORT ACTION BAR -->
            <div class="report-export-bar">
                <div class="report-export-info">
                    <span class="badge blue">📊 LAPORAN BISNIS</span>
                    <b>Ekspor Laporan Kinerja Operasional &amp; Keuangan</b>
                    <p>Unduh data pembukuan lengkap dalam format spreadsheet Excel atau cetak dokumen resmi / simpan ke PDF.</p>
                </div>
                <div class="report-export-actions">
                    <a class="button yellow export-btn" href="<?= e(url('reports/export/excel')) ?>" title="Unduh format spreadsheet Microsoft Excel">
                        <span>📥</span> Unduh Excel (.xls)
                    </a>
                    <a class="button mint export-btn" href="<?= e(url('reports/export/pdf')) ?>" target="_blank" title="Buka preview dokumen siap cetak atau simpan sebagai PDF">
                        <span>🖨</span> Cetak / Simpan PDF
                    </a>
                </div>
            </div>

            <section class="panel list-panel">
                <div class="panel-head">
                    <div>
                        <p class="eyebrow">REKAPITULASI PENJUALAN</p>
                        <h2>Laporan Riwayat Penjualan</h2>
                        <small>Rincian invoice, tanggal, pelanggan, dan keuntungan per transaksi</small>
                    </div>
                    <span class="count-pill"><?= count($report) ?> transaksi</span>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>KODE INVOICE</th>
                                <th>TANGGAL</th>
                                <th>PELANGGAN</th>
                                <th>TOTAL TRANSAKSI</th>
                                <th>PROFIT CUAN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($report): ?>
                                <?php foreach ($report as $r): ?>
                                    <tr>
                                        <td><b><?= e($r['sale_code']) ?></b></td>
                                        <td><?= e(date('d M Y', strtotime($r['sale_date']))) ?></td>
                                        <td><b><?= e($r['customer']) ?></b></td>
                                        <td><b><?= rupiah((float)$r['total_amount']) ?></b></td>
                                        <td>
                                            <span class="badge sale">
                                                +<?= rupiah((float)$r['profit']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="empty-state">
                                        <div class="empty-box">
                                            <span class="empty-icon">📊</span>
                                            <p>Belum ada data penjualan untuk dilaporkan.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        <?php endif; ?>

        </div>
    </main>
</div>

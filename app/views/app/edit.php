<?php
$labels = [
    'product' => ['Produk HP', 'products', 'products/update'],
    'supplier' => ['Supplier', 'suppliers', 'suppliers/update'],
    'customer' => ['Pelanggan', 'customers', 'customers/update'],
    'purchase' => ['Pembelian HP', 'purchases', 'purchases/update'],
    'sale' => ['Penjualan HP', 'sales', 'sales/update'],
    'service' => ['Servis Unit', 'services', 'services/update'],
    'unit' => ['Unit HP & IMEI', 'inventory', 'inventory/update'],
    'finance' => ['Transaksi Kas', 'finance', 'finance/update'],
];
[$entityTitle, $returnRoute, $updateRoute] = $labels[$editType] ?? ['Data', 'products', 'products/update'];
$pageTitleShort = 'EDIT ' . strtoupper($entityTitle);
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

        <div class="page-container">
            <section class="form-card edit-card neo-card-animated">
                <div class="panel-head edit-head">
                    <div>
                        <p class="eyebrow">MODIFIKASI DATA / EDIT</p>
                        <h1>Edit <?= e($entityTitle) ?></h1>
                    </div>
                    <a class="button yellow" href="<?= e(url($returnRoute)) ?>">← Batal &amp; Kembali</a>
                </div>

                <form class="grid-form edit-form" method="post" action="<?= e(url($updateRoute)) ?>">
                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                    <input type="hidden" name="id" value="<?= e((string)$item['id']) ?>">

                    <?php if ($editType === 'product'): ?>
                        <label>Merek HP (Brand)
                            <input name="brand" value="<?= e($item['brand']) ?>" required>
                        </label>
                        <label>Model HP
                            <input name="model" value="<?= e($item['model']) ?>" required>
                        </label>
                        <label>Varian (misal: 5G, Pro, Plus)
                            <input name="variant" value="<?= e($item['variant'] ?? '') ?>">
                        </label>
                        <label>Warna
                            <input name="color" value="<?= e($item['color'] ?? '') ?>">
                        </label>
                        <label>Kapasitas RAM
                            <input name="ram" value="<?= e($item['ram'] ?? '') ?>" placeholder="Misal: 8GB">
                        </label>
                        <label>Kapasitas Penyimpanan
                            <input name="storage" value="<?= e($item['storage'] ?? '') ?>" placeholder="Misal: 256GB">
                        </label>
                        <label class="grid-col-full">Deskripsi / Catatan Produk
                            <textarea name="description" rows="3"><?= e($item['description'] ?? '') ?></textarea>
                        </label>

                    <?php elseif ($editType === 'supplier' || $editType === 'customer'): ?>
                        <label class="grid-col-2">Nama Lengkap
                            <input name="name" value="<?= e($item['name']) ?>" required>
                        </label>
                        <label>Nomor Telepon / WhatsApp
                            <input name="phone" value="<?= e($item['phone'] ?? '') ?>">
                        </label>
                        <label>Email
                            <input name="email" type="email" value="<?= e($item['email'] ?? '') ?>">
                        </label>
                        <label class="grid-col-full">Alamat
                            <textarea name="address" rows="2"><?= e($item['address'] ?? '') ?></textarea>
                        </label>
                        <label class="grid-col-full">Catatan Tambahan
                            <textarea name="notes" rows="2"><?= e($item['notes'] ?? '') ?></textarea>
                        </label>

                    <?php elseif ($editType === 'purchase'): ?>
                        <div class="grid-col-full purchase-info-banner">
                            <span class="badge yellow">KODE: <?= e($item['purchase_code']) ?></span>
                            <span class="badge mint">IMEI: <?= e($item['imei'] ?? '-') ?></span>
                            <span class="badge blue">UNIT: <?= e(trim(($item['brand'] ?? '') . ' ' . ($item['model'] ?? ''))) ?></span>
                        </div>
                        <label>Supplier
                            <select name="supplier_id" required>
                                <?php foreach ($suppliers as $s): ?>
                                    <option value="<?= $s['id'] ?>" <?= $s['id'] == $item['supplier_id'] ? 'selected' : '' ?>>
                                        <?= e($s['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label>Harga Beli (Modal)
                            <input name="purchase_price" type="number" min="0" value="<?= e((string)(int)$item['total_amount']) ?>" required>
                        </label>
                        <label>Target Harga Jual
                            <input name="selling_price" type="number" min="0" value="<?= e((string)(int)$item['unit_selling_price']) ?>" required>
                        </label>
                        <label>Metode Pembayaran
                            <select name="payment_method">
                                <option value="cash" <?= ($item['payment_method'] ?? '') === 'cash' ? 'selected' : '' ?>>Cash / Tunai</option>
                                <option value="transfer" <?= ($item['payment_method'] ?? '') === 'transfer' ? 'selected' : '' ?>>Transfer Bank</option>
                                <option value="qris" <?= ($item['payment_method'] ?? '') === 'qris' ? 'selected' : '' ?>>QRIS</option>
                            </select>
                        </label>
                        <label>Battery Health (%)
                            <input name="battery_health" type="number" min="0" max="100" value="<?= e((string)($item['battery_health'] ?? '')) ?>">
                        </label>
                        <label>Kondisi Layar
                            <input name="screen_condition" value="<?= e($item['screen_condition'] ?? '') ?>">
                        </label>
                        <label>Kondisi Fisik / Body
                            <input name="body_condition" value="<?= e($item['body_condition'] ?? '') ?>">
                        </label>
                        <label class="grid-col-2">Kelengkapan Unit (Dus, Charger, dsb)
                            <input name="completeness" value="<?= e($item['completeness'] ?? '') ?>">
                        </label>

                    <?php elseif ($editType === 'sale'): ?>
                        <div class="grid-col-full purchase-info-banner">
                            <span class="badge yellow">KODE: <?= e($item['sale_code']) ?></span>
                            <span class="badge mint">IMEI: <?= e($item['imei'] ?? '-') ?></span>
                            <span class="badge blue">UNIT: <?= e(trim(($item['brand'] ?? '') . ' ' . ($item['model'] ?? ''))) ?></span>
                            <span class="badge pink">MODAL AWAL: <?= rupiah((float)($item['unit_cost'] ?? 0)) ?></span>
                        </div>
                        <label class="grid-col-2">Pelanggan
                            <select name="customer_id" required>
                                <?php foreach ($customers as $c): ?>
                                    <option value="<?= $c['id'] ?>" <?= $c['id'] == $item['customer_id'] ? 'selected' : '' ?>>
                                        <?= e($c['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label>Harga Jual (Total Transaksi)
                            <input name="selling_price" type="number" min="1" value="<?= e((string)(int)$item['total_amount']) ?>" required>
                        </label>
                        <label>Metode Pembayaran
                            <select name="payment_method">
                                <option value="cash" <?= ($item['payment_method'] ?? '') === 'cash' ? 'selected' : '' ?>>Cash / Tunai</option>
                                <option value="transfer" <?= ($item['payment_method'] ?? '') === 'transfer' ? 'selected' : '' ?>>Transfer Bank</option>
                                <option value="qris" <?= ($item['payment_method'] ?? '') === 'qris' ? 'selected' : '' ?>>QRIS</option>
                            </select>
                        </label>

                    <?php elseif ($editType === 'service'): ?>
                        <div class="grid-col-full purchase-info-banner">
                            <span class="badge mint">IMEI: <?= e($item['imei']) ?></span>
                            <span class="badge blue">UNIT: <?= e(trim($item['brand'] . ' ' . $item['model'])) ?></span>
                        </div>
                        <label>Biaya Servis (Rp)
                            <input name="cost" type="number" min="1" value="<?= e((string)(int)$item['cost']) ?>" required>
                        </label>
                        <label class="grid-col-2">Keterangan / Rincian Servis
                            <input name="description" value="<?= e($item['description']) ?>" required>
                        </label>

                    <?php elseif ($editType === 'unit'): ?>
                        <div class="grid-col-full purchase-info-banner">
                            <span class="badge yellow">ID UNIT: #<?= e((string)$item['id']) ?></span>
                            <span class="badge blue">MODEL: <?= e(trim(($item['brand'] ?? '') . ' ' . ($item['model'] ?? ''))) ?></span>
                            <span class="badge pink">MODAL AWAL: <?= rupiah((float)($item['purchase_price'] ?? 0)) ?></span>
                        </div>
                        <label>Nomor IMEI (10-20 Digit)
                            <input name="imei" value="<?= e($item['imei']) ?>" minlength="10" maxlength="20" required>
                        </label>
                        <label>Status Unit
                            <select name="status">
                                <option value="available" <?= $item['status'] === 'available' ? 'selected' : '' ?>>Tersedia (Ready Stock)</option>
                                <option value="sold" <?= $item['status'] === 'sold' ? 'selected' : '' ?>>Terjual (Sold)</option>
                                <option value="damaged" <?= $item['status'] === 'damaged' ? 'selected' : '' ?>>Rusak / Butuh Servis (Damaged)</option>
                                <option value="returned" <?= $item['status'] === 'returned' ? 'selected' : '' ?>>Retur (Returned)</option>
                            </select>
                        </label>
                        <label>Target Harga Jual (Rp)
                            <input name="selling_price" type="number" min="0" value="<?= e((string)(int)$item['selling_price']) ?>" required>
                        </label>
                        <label>Harga Modal Beli (Rp)
                            <input name="purchase_price" type="number" min="0" value="<?= e((string)(int)$item['purchase_price']) ?>" required>
                        </label>
                        <label>Battery Health (%)
                            <input name="battery_health" type="number" min="0" max="100" value="<?= e((string)($item['battery_health'] ?? '')) ?>" placeholder="Misal: 90">
                        </label>
                        <label>Kondisi Layar
                            <input name="screen_condition" value="<?= e($item['screen_condition'] ?? '') ?>" placeholder="Misal: Normal / No Shadow">
                        </label>
                        <label>Kondisi Fisik / Body
                            <input name="body_condition" value="<?= e($item['body_condition'] ?? '') ?>" placeholder="Misal: Mulus 98%">
                        </label>
                        <label class="grid-col-2">Kelengkapan Unit
                            <input name="completeness" value="<?= e($item['completeness'] ?? '') ?>" placeholder="Misal: Fullset OEM / Unit Only">
                        </label>
                        <label class="grid-col-full">Catatan Kondisi Unit
                            <textarea name="condition_notes" rows="2" placeholder="Catatan internal kondisi HP..."><?= e($item['condition_notes'] ?? '') ?></textarea>
                        </label>

                    <?php elseif ($editType === 'finance'): ?>
                        <div class="grid-col-full purchase-info-banner">
                            <span class="badge yellow">KODE: <?= e($item['transaction_code']) ?></span>
                            <span class="badge <?= $item['cash_flow'] === 'in' ? 'sale' : 'buy' ?>">ARUS KAS: <?= strtoupper(e($item['cash_flow'])) ?></span>
                        </div>
                        <label>Jenis Transaksi Kas
                            <select name="transaction_type" required>
                                <option value="expense" <?= $item['transaction_type'] === 'expense' ? 'selected' : '' ?>>Pengeluaran Operasional (Kas Keluar)</option>
                                <option value="capital" <?= $item['transaction_type'] === 'capital' ? 'selected' : '' ?>>Modal Masuk Tambahan (Kas Masuk)</option>
                                <option value="withdrawal" <?= $item['transaction_type'] === 'withdrawal' ? 'selected' : '' ?>>Prive Pemilik (Kas Keluar)</option>
                                <option value="adjustment" <?= $item['transaction_type'] === 'adjustment' ? 'selected' : '' ?>>Penyesuaian Kas Fisik</option>
                                <option value="service" <?= $item['transaction_type'] === 'service' ? 'selected' : '' ?>>Biaya Servis</option>
                            </select>
                        </label>
                        <label>Nominal Transaksi (Rp)
                            <input name="amount" type="number" min="1" value="<?= e((string)(int)$item['amount']) ?>" required>
                        </label>
                        <label>Tanggal Transaksi
                            <input name="transaction_date" type="date" value="<?= e($item['transaction_date']) ?>" required>
                        </label>
                        <label class="grid-col-full">Keterangan Lengkap Transaksi
                            <input name="description" value="<?= e($item['description']) ?>" required>
                        </label>
                    <?php endif; ?>

                    <div class="grid-col-full form-submit-row">
                        <button type="submit" class="button blue submit-btn">
                            <span>✓</span> Simpan Perubahan
                        </button>
                        <a href="<?= e(url($returnRoute)) ?>" class="button">Batal</a>
                    </div>
                </form>
            </section>
        </div>
    </main>
</div>

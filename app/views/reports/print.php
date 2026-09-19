<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan_Bisnis_HP_<?= date('Ymd') ?></title>
    <style>
        :root {
            --ink: #101010;
            --blue: #8ed8ff;
            --yellow: #ffe66d;
            --mint: #b9f5d0;
            --pink: #ff9ebc;
            --soft: #f4faff;
            --border: 2px solid var(--ink);
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 20px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
            color: var(--ink);
            background: #fff;
            font-size: 11pt;
            line-height: 1.4;
        }
        .print-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 20px;
            margin-bottom: 24px;
            background: var(--yellow);
            border: 3px solid var(--ink);
            box-shadow: 4px 4px 0 var(--ink);
        }
        .print-btn-group {
            display: flex;
            gap: 10px;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            font-size: 10.5pt;
            font-weight: bold;
            text-decoration: none;
            color: var(--ink);
            background: #fff;
            border: 2px solid var(--ink);
            box-shadow: 2px 2px 0 var(--ink);
            cursor: pointer;
        }
        .btn.primary { background: var(--blue); }
        .btn.pink { background: var(--pink); }
        .btn:hover { transform: translate(-1px, -1px); box-shadow: 3px 3px 0 var(--ink); }

        /* Report Header */
        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding-bottom: 16px;
            border-bottom: 3px solid var(--ink);
            margin-bottom: 20px;
        }
        .brand-box {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .brand-logo {
            width: 44px;
            height: 44px;
            background: var(--yellow);
            border: 3px solid var(--ink);
            box-shadow: 3px 3px 0 var(--ink);
            display: grid;
            place-items: center;
            font-size: 22px;
            font-weight: 800;
        }
        .brand-title h1 {
            margin: 0;
            font-size: 18pt;
            letter-spacing: -0.5px;
        }
        .brand-title p {
            margin: 2px 0 0;
            font-size: 9.5pt;
            color: #444;
            letter-spacing: 0.8px;
            font-weight: 600;
        }
        .report-meta {
            text-align: right;
            font-size: 9pt;
            line-height: 1.5;
        }

        /* KPI Section */
        .section-heading {
            font-size: 11pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 20px 0 10px;
            padding: 4px 8px;
            background: var(--soft);
            border-left: 4px solid var(--ink);
        }
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }
        .kpi-card {
            border: 2px solid var(--ink);
            padding: 12px 14px;
            background: #fff;
        }
        .kpi-card.yellow { background: var(--yellow); }
        .kpi-card.mint { background: var(--mint); }
        .kpi-card.pink { background: var(--pink); }
        .kpi-card.blue { background: var(--blue); }
        .kpi-label {
            font-size: 8.5pt;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 4px;
            display: block;
        }
        .kpi-value {
            font-size: 14pt;
            font-weight: 800;
            line-height: 1.1;
            display: block;
        }
        .kpi-sub {
            font-size: 8pt;
            margin-top: 3px;
            display: block;
            opacity: 0.85;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
            margin-bottom: 24px;
        }
        th {
            background: var(--soft);
            border: 1px solid var(--ink);
            padding: 8px 10px;
            text-align: left;
            font-size: 8.5pt;
            letter-spacing: 0.5px;
            font-weight: 800;
        }
        td {
            border: 1px solid #999;
            padding: 7px 10px;
            vertical-align: middle;
        }
        tr:nth-child(even) td {
            background: #fafafa;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-row td {
            font-weight: 800;
            background: var(--mint) !important;
            border-top: 2px solid var(--ink);
            border-bottom: 2px solid var(--ink);
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border: 1px solid var(--ink);
            font-size: 7.5pt;
            font-weight: 800;
        }

        /* Signatures */
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 36px;
            page-break-inside: avoid;
        }
        .sig-box {
            text-align: center;
            width: 260px;
        }
        .sig-line {
            height: 60px;
        }
        .sig-name {
            font-weight: 800;
            border-top: 1px solid var(--ink);
            padding-top: 4px;
            display: block;
        }

        /* Print Media Styles */
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; font-size: 10pt; }
            @page {
                size: A4 landscape;
                margin: 10mm 12mm;
            }
            .kpi-card, th, .badge, .brand-logo {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>
<body>

    <!-- Print / Export Toolbar (Hidden during actual print) -->
    <div class="print-toolbar no-print">
        <div>
            <b>🖨 PREVIEW DOKUMEN LAPORAN BISNIS HP</b> — Siap dicetak atau diekspor ke PDF
        </div>
        <div class="print-btn-group">
            <button class="btn primary" onclick="window.print()">
                <span>🖨</span> Cetak / Simpan PDF
            </button>
            <a class="btn" href="<?= e(url('reports/export/excel')) ?>">
                <span>📥</span> Unduh Excel (.xls)
            </a>
            <a class="btn pink" href="<?= e(url('reports')) ?>">
                <span>←</span> Kembali
            </a>
        </div>
    </div>

    <!-- Letterhead / Kop Laporan -->
    <header class="report-header">
        <div class="brand-box">
            <div class="brand-logo">▣</div>
            <div class="brand-title">
                <h1>HP BUSINESS MANAGEMENT SYSTEM</h1>
                <p>LAPORAN KINERJA KEUANGAN &amp; OPERASIONAL PENJUALAN UNIT HP</p>
            </div>
        </div>
        <div class="report-meta">
            <b>DOKUMEN RESMI BISNIS</b><br>
            Tanggal Cetak: <?= e($printedAt) ?><br>
            Operator: <b><?= e($user['name']) ?></b>
        </div>
    </header>

    <!-- 1. Executive Summary -->
    <div class="section-heading">1. RINGKASAN EKSEKUTIF KEUANGAN (EXECUTIVE KPI)</div>
    <div class="kpi-grid">
        <div class="kpi-card mint">
            <span class="kpi-label">TOTAL OMSET PENJUALAN</span>
            <span class="kpi-value"><?= rupiah($summary['total_sales']) ?></span>
            <span class="kpi-sub"><?= $summary['total_transactions'] ?> transaksi penjualan</span>
        </div>
        <div class="kpi-card yellow">
            <span class="kpi-label">TOTAL MODAL POKOK (HPP)</span>
            <span class="kpi-value"><?= rupiah($summary['total_cogs']) ?></span>
            <span class="kpi-sub">Biaya kulak unit terjual</span>
        </div>
        <div class="kpi-card pink">
            <span class="kpi-label">KEUNTUNGAN KOTOR (GROSS)</span>
            <span class="kpi-value"><?= rupiah($summary['total_profit']) ?></span>
            <span class="kpi-sub">Margin akumulasi penjualan</span>
        </div>
        <div class="kpi-card blue">
            <span class="kpi-label">ESTIMASI LABA BERSIH (NET)</span>
            <span class="kpi-value"><?= rupiah($summary['net_profit']) ?></span>
            <span class="kpi-sub">Setelah biaya operasional</span>
        </div>
    </div>

    <!-- 2. Sales Register -->
    <div class="section-heading">2. RINCIAN TRANSAKSI PENJUALAN UNIT HP</div>
    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 35px;">NO</th>
                <th>INVOICE</th>
                <th>TANGGAL</th>
                <th>PELANGGAN</th>
                <th>UNIT HP</th>
                <th>NOMOR IMEI</th>
                <th class="text-right">MODAL (HPP)</th>
                <th class="text-right">HARGA JUAL</th>
                <th class="text-right">PROFIT</th>
                <th class="text-center">METODE</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($sales): ?>
                <?php $no = 1; $totalModal = 0; $totalJual = 0; $totalUntung = 0; ?>
                <?php foreach ($sales as $s): ?>
                    <?php 
                        $totalModal += (float)$s['unit_cost'];
                        $totalJual += (float)$s['unit_sale_price'];
                        $totalUntung += (float)$s['profit'];
                    ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><b><?= e($s['sale_code']) ?></b></td>
                        <td><?= e(date('d/m/Y', strtotime($s['sale_date']))) ?></td>
                        <td><?= e($s['customer_name']) ?></td>
                        <td><?= e(trim($s['brand'] . ' ' . $s['model'] . ' ' . ($s['variant'] ?? ''))) ?></td>
                        <td><span class="badge"><?= e($s['imei'] ?? '-') ?></span></td>
                        <td class="text-right"><?= rupiah((float)$s['unit_cost']) ?></td>
                        <td class="text-right"><b><?= rupiah((float)$s['unit_sale_price']) ?></b></td>
                        <td class="text-right" style="color: <?= (float)$s['profit'] >= 0 ? '#0a6327' : '#c00' ?>; font-weight: bold;">
                            <?= ((float)$s['profit'] >= 0 ? '+' : '') . rupiah((float)$s['profit']) ?>
                        </td>
                        <td class="text-center"><?= strtoupper(e($s['payment_method'])) ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td colspan="6" class="text-center">TOTAL KESELURUHAN</td>
                    <td class="text-right"><?= rupiah($totalModal) ?></td>
                    <td class="text-right"><?= rupiah($totalJual) ?></td>
                    <td class="text-right"><?= rupiah($totalUntung) ?></td>
                    <td></td>
                </tr>
            <?php else: ?>
                <tr>
                    <td colspan="10" class="text-center">Belum ada transaksi penjualan tercatat.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- 3. Purchases Register -->
    <div class="section-heading">3. RINCIAN KULAK / PEMBELIAN HP BER-IMEI</div>
    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 35px;">NO</th>
                <th>KODE BELI</th>
                <th>TANGGAL</th>
                <th>SUPPLIER</th>
                <th>MODEL HP</th>
                <th>NOMOR IMEI</th>
                <th class="text-right">HARGA MODAL BELI</th>
                <th class="text-center">STATUS UNIT</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($purchases): ?>
                <?php $no = 1; $totalBeli = 0; ?>
                <?php foreach ($purchases as $p): ?>
                    <?php $totalBeli += (float)$p['total_amount']; ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><b><?= e($p['purchase_code']) ?></b></td>
                        <td><?= e(date('d/m/Y', strtotime($p['purchase_date']))) ?></td>
                        <td><?= e($p['supplier_name'] ?? 'Umum') ?></td>
                        <td><?= e(trim(($p['brand'] ?? '') . ' ' . ($p['model'] ?? ''))) ?></td>
                        <td><span class="badge"><?= e($p['imei'] ?? '-') ?></span></td>
                        <td class="text-right"><?= rupiah((float)$p['total_amount']) ?></td>
                        <td class="text-center">
                            <b><?= ($p['unit_status'] ?? '') === 'sold' ? 'TERJUAL' : 'TERSEDIA' ?></b>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td colspan="6" class="text-center">TOTAL PEMBELIAN UNIT</td>
                    <td class="text-right"><?= rupiah($totalBeli) ?></td>
                    <td></td>
                </tr>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">Belum ada data pembelian tercatat.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Sign-off Block -->
    <div class="signatures">
        <div class="sig-box">
            <span>Dibuat Oleh,</span>
            <div class="sig-line"></div>
            <span class="sig-name"><?= e($user['name']) ?></span>
            <small>Administrator</small>
        </div>
        <div class="sig-box">
            <span>Mengetahui &amp; Menyetujui,</span>
            <div class="sig-line"></div>
            <span class="sig-name">Pemilik Bisnis (Owner)</span>
            <small>HP Business</small>
        </div>
    </div>

</body>
</html>

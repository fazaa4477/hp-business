<?php
// Template Export Excel (.xls) dengan format HTML Table kompatibel Microsoft Excel
?>
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--[if gte mso 9]>
<xml>
 <x:ExcelWorkbook>
  <x:ExcelWorksheets>
   <x:ExcelWorksheet>
    <x:Name>Laporan Bisnis HP</x:Name>
    <x:WorksheetOptions>
     <x:DisplayGridlines/>
    </x:WorksheetOptions>
   </x:ExcelWorksheet>
  </x:ExcelWorksheets>
 </x:ExcelWorkbook>
</xml>
<![endif]-->
<style>
  body { font-family: Calibri, Arial, sans-serif; font-size: 11pt; color: #101010; }
  .title { font-size: 16pt; font-weight: bold; text-align: left; }
  .subtitle { font-size: 10pt; color: #444; }
  .section-title { font-size: 12pt; font-weight: bold; background-color: #8ED8FF; padding: 6px; border: 1px solid #101010; }
  th { background-color: #FFE66D; font-weight: bold; border: 1px solid #101010; padding: 6px 8px; text-align: left; }
  td { border: 1px solid #CCCCCC; padding: 5px 8px; vertical-align: middle; }
  .kpi-title { background-color: #EAF8FF; font-weight: bold; border: 1px solid #101010; }
  .kpi-value { font-size: 12pt; font-weight: bold; border: 1px solid #101010; }
  .text-right { text-align: right; }
  .text-center { text-align: center; }
  .total-row { font-weight: bold; background-color: #B9F5D0; border-top: 2px solid #101010; }
</style>
</head>
<body>

<table>
  <tr>
    <td colspan="10" class="title">HP BUSINESS — SISTEM MANAJEMEN BISNIS HP SECOND</td>
  </tr>
  <tr>
    <td colspan="10" class="subtitle">LAPORAN KINERJA KEUANGAN &amp; OPERASIONAL PENJUALAN</td>
  </tr>
  <tr>
    <td colspan="10" class="subtitle">Tanggal Ekspor: <?= e($printedAt) ?> | Operator: <?= e($user['name']) ?></td>
  </tr>
  <tr><td colspan="10"></td></tr>
</table>

<!-- RINGKASAN EKSEKUTIF -->
<table>
  <tr>
    <td colspan="4" class="section-title">1. RINGKASAN EKSEKUTIF (EXECUTIVE KPI SUMMARY)</td>
  </tr>
  <tr>
    <td class="kpi-title">Total Omset Penjualan (Gross)</td>
    <td class="kpi-value text-right"><?= rupiah($summary['total_sales']) ?></td>
    <td class="kpi-title">Total Transaksi Penjualan</td>
    <td class="kpi-value text-center"><?= $summary['total_transactions'] ?> Transaksi</td>
  </tr>
  <tr>
    <td class="kpi-title">Total Modal Pokok Unit Terjual (HPP)</td>
    <td class="kpi-value text-right"><?= rupiah($summary['total_cogs']) ?></td>
    <td class="kpi-title">Total Unit Terjual</td>
    <td class="kpi-value text-center"><?= $summary['units_sold'] ?> Unit</td>
  </tr>
  <tr>
    <td class="kpi-title">Total Keuntungan Kotor (Gross Profit)</td>
    <td class="kpi-value text-right" style="color: #0b6623;"><?= rupiah($summary['total_profit']) ?></td>
    <td class="kpi-title">Stok Unit Ready (Tersedia)</td>
    <td class="kpi-value text-center"><?= $summary['units_ready'] ?> Unit</td>
  </tr>
  <tr>
    <td class="kpi-title">Total Biaya Servis Unit</td>
    <td class="kpi-value text-right"><?= rupiah($summary['total_services']) ?></td>
    <td class="kpi-title">Pengeluaran Operasional Lainnya</td>
    <td class="kpi-value text-right"><?= rupiah($summary['total_expenses']) ?></td>
  </tr>
  <tr style="background-color: #FFE66D;">
    <td class="kpi-title" style="font-size: 12pt;">ESTIMASI LABA BERSIH (NET PROFIT)</td>
    <td class="kpi-value text-right" style="font-size: 13pt; color: #101010;" colspan="3"><?= rupiah($summary['net_profit']) ?></td>
  </tr>
  <tr><td colspan="4"></td></tr>
</table>

<!-- TABEL DETAIL PENJUALAN -->
<table>
  <tr>
    <td colspan="10" class="section-title">2. RINCIAN TRANSAKSI PENJUALAN (SALES DETAIL)</td>
  </tr>
  <thead>
    <tr>
      <th>No</th>
      <th>Kode Transaksi</th>
      <th>Tanggal</th>
      <th>Pelanggan</th>
      <th>No. Telepon</th>
      <th>Unit &amp; Model HP</th>
      <th>Nomor IMEI</th>
      <th class="text-right">Modal Beli (HPP)</th>
      <th class="text-right">Harga Jual</th>
      <th class="text-right">Profit Cuan</th>
      <th>Metode Bayar</th>
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
          <td><?= e($s['customer_phone'] ?? '-') ?></td>
          <td><?= e(trim($s['brand'] . ' ' . $s['model'] . ' ' . ($s['variant'] ?? ''))) ?></td>
          <td style="mso-number-format:'\@';"><?= e($s['imei'] ?? '-') ?></td>
          <td class="text-right"><?= rupiah((float)$s['unit_cost']) ?></td>
          <td class="text-right"><?= rupiah((float)$s['unit_sale_price']) ?></td>
          <td class="text-right" style="font-weight: bold; color: <?= (float)$s['profit'] >= 0 ? '#0b6623' : '#c00' ?>;">
            <?= ((float)$s['profit'] >= 0 ? '+' : '') . rupiah((float)$s['profit']) ?>
          </td>
          <td class="text-center"><?= strtoupper(e($s['payment_method'])) ?></td>
        </tr>
      <?php endforeach; ?>
      <tr class="total-row">
        <td colspan="7" class="text-center">TOTAL KESELURUHAN PENJUALAN</td>
        <td class="text-right"><?= rupiah($totalModal) ?></td>
        <td class="text-right"><?= rupiah($totalJual) ?></td>
        <td class="text-right"><?= rupiah($totalUntung) ?></td>
        <td></td>
      </tr>
    <?php else: ?>
      <tr>
        <td colspan="11" class="text-center">Belum ada transaksi penjualan yang tercatat.</td>
      </tr>
    <?php endif; ?>
  </tbody>
  <tr><td colspan="11"></td></tr>
</table>

<!-- TABEL DETAIL PEMBELIAN / KULAK -->
<table>
  <tr>
    <td colspan="8" class="section-title">3. RINCIAN TRANSAKSI PEMBELIAN HP (PURCHASES REGISTER)</td>
  </tr>
  <thead>
    <tr>
      <th>No</th>
      <th>Kode Pembelian</th>
      <th>Tanggal</th>
      <th>Supplier</th>
      <th>Model HP</th>
      <th>Nomor IMEI</th>
      <th class="text-right">Harga Beli Modal</th>
      <th>Status Unit</th>
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
          <td style="mso-number-format:'\@';"><?= e($p['imei'] ?? '-') ?></td>
          <td class="text-right"><?= rupiah((float)$p['total_amount']) ?></td>
          <td class="text-center"><?= ($p['unit_status'] ?? '') === 'sold' ? 'TERJUAL' : 'TERSEDIA' ?></td>
        </tr>
      <?php endforeach; ?>
      <tr class="total-row">
        <td colspan="6" class="text-center">TOTAL MODAL PEMBELIAN</td>
        <td class="text-right"><?= rupiah($totalBeli) ?></td>
        <td></td>
      </tr>
    <?php else: ?>
      <tr>
        <td colspan="8" class="text-center">Belum ada data pembelian tercatat.</td>
      </tr>
    <?php endif; ?>
  </tbody>
  <tr><td colspan="8"></td></tr>
</table>

<!-- PENGESAHAN LAPORAN -->
<table>
  <tr><td colspan="8"></td></tr>
  <tr>
    <td colspan="4" class="text-center">
      Dibuat Oleh,<br><br><br><br>
      <b>( <?= e($user['name']) ?> )</b><br>
      Administrator
    </td>
    <td colspan="4" class="text-center">
      Mengetahui &amp; Menyetujui,<br><br><br><br>
      <b>( .................................................. )</b><br>
      Pemilik Bisnis (Owner)
    </td>
  </tr>
</table>

</body>
</html>

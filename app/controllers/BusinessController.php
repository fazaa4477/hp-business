<?php
declare(strict_types=1);

class BusinessController
{
    private Business $business;

    public function __construct(PDO $pdo)
    {
        $this->business = new Business($pdo);
    }

    public function page(string $page, array $extra = []): void
    {
        require_auth();
        $labels = [
            'products' => 'Produk',
            'suppliers' => 'Supplier',
            'customers' => 'Pelanggan',
            'inventory' => 'Inventory & IMEI',
            'purchases' => 'Pembelian',
            'sales' => 'Penjualan',
            'services' => 'Servis Unit',
            'finance' => 'Keuangan',
            'reports' => 'Laporan'
        ];

        $data = array_merge([
            'pageTitle' => ($labels[$page] ?? ucfirst($page)) . ' — HP Business',
            'page' => $page,
            'flash' => flash(),
            'products' => $this->business->products(),
            'suppliers' => $this->business->suppliers(),
            'customers' => $this->business->customers(),
            'units' => $this->business->units($_GET['q'] ?? ''),
            'availableUnits' => $this->business->availableUnits(),
            'purchases' => $this->business->purchases(),
            'sales' => $this->business->sales(),
            'report' => $this->business->report(),
            'finances' => $this->business->finances(),
            'services' => $this->business->services(),
            'editItem' => null,
            'editType' => null,
        ], $extra);

        render('app/page', $data);
    }

    public function products(): void { $this->page('products'); }
    public function suppliers(): void { $this->page('suppliers'); }
    public function customers(): void { $this->page('customers'); }
    public function inventory(): void { $this->page('inventory'); }
    public function purchases(): void { $this->page('purchases'); }
    public function sales(): void { $this->page('sales'); }
    public function reports(): void { $this->page('reports'); }
    public function financePage(): void { $this->page('finance'); }
    public function servicesPage(): void { $this->page('services'); }

    // ==========================================
    // CREATE ACTIONS
    // ==========================================
    public function saveMaster(): void
    {
        require_auth();
        verify_csrf();
        try {
            $this->business->create($_POST['type'] ?? '', $_POST);
            flash('Data master berhasil ditambahkan.');
        } catch (Throwable $e) {
            flash('Gagal menyimpan data: ' . $e->getMessage());
        }
        redirect($_POST['return'] ?? 'products');
    }

    public function purchase(): void
    {
        require_auth();
        verify_csrf();
        try {
            $this->business->purchase($_POST);
            flash('Pembelian berhasil dicatat dan unit baru telah masuk inventaris.');
        } catch (Throwable $e) {
            flash('Gagal mencatat pembelian: ' . $e->getMessage());
        }
        redirect('purchases');
    }

    public function sale(): void
    {
        require_auth();
        verify_csrf();
        try {
            $this->business->sale($_POST);
            flash('Penjualan berhasil dicatat! Status unit diperbarui menjadi terjual.');
        } catch (Throwable $e) {
            flash('Gagal mencatat penjualan: ' . $e->getMessage());
        }
        redirect('sales');
    }

    public function finance(): void
    {
        require_auth();
        verify_csrf();
        try {
            $this->business->recordFinance($_POST);
            flash('Transaksi keuangan kas berhasil dicatat.');
        } catch (Throwable $e) {
            flash('Gagal mencatat keuangan: ' . $e->getMessage());
        }
        redirect('finance');
    }

    public function service(): void
    {
        require_auth();
        verify_csrf();
        try {
            $this->business->recordService($_POST);
            flash('Biaya servis berhasil dicatat dan ditambahkan ke modal unit.');
        } catch (Throwable $e) {
            flash('Gagal mencatat servis: ' . $e->getMessage());
        }
        redirect('services');
    }

    // ==========================================
    // CRUD: PRODUCTS
    // ==========================================
    public function editProduct(): void
    {
        require_auth();
        $id = (int)($_GET['id'] ?? 0);
        $product = $this->business->findProduct($id);
        if (!$product) {
            flash('Data produk tidak ditemukan.');
            redirect('products');
        }

        render('app/edit', [
            'pageTitle' => 'Edit Produk — HP Business',
            'editType' => 'product',
            'item' => $product,
            'page' => 'products',
            'flash' => flash(),
        ]);
    }

    public function updateProduct(): void
    {
        require_auth();
        verify_csrf();
        $id = (int)($_POST['id'] ?? 0);
        try {
            $this->business->updateProduct($id, $_POST);
            flash('Data produk berhasil diperbarui.');
        } catch (Throwable $e) {
            flash('Gagal memperbarui produk: ' . $e->getMessage());
        }
        redirect('products');
    }

    public function deleteProduct(): void
    {
        require_auth();
        verify_csrf();
        $id = (int)($_POST['id'] ?? 0);
        try {
            $this->business->deleteProduct($id);
            flash('Produk berhasil dihapus.');
        } catch (Throwable $e) {
            flash('Gagal menghapus produk: ' . $e->getMessage());
        }
        redirect('products');
    }

    // ==========================================
    // CRUD: SUPPLIERS
    // ==========================================
    public function editSupplier(): void
    {
        require_auth();
        $id = (int)($_GET['id'] ?? 0);
        $supplier = $this->business->findSupplier($id);
        if (!$supplier) {
            flash('Data supplier tidak ditemukan.');
            redirect('suppliers');
        }

        render('app/edit', [
            'pageTitle' => 'Edit Supplier — HP Business',
            'editType' => 'supplier',
            'item' => $supplier,
            'page' => 'suppliers',
            'flash' => flash(),
        ]);
    }

    public function updateSupplier(): void
    {
        require_auth();
        verify_csrf();
        $id = (int)($_POST['id'] ?? 0);
        try {
            $this->business->updateSupplier($id, $_POST);
            flash('Data supplier berhasil diperbarui.');
        } catch (Throwable $e) {
            flash('Gagal memperbarui supplier: ' . $e->getMessage());
        }
        redirect('suppliers');
    }

    public function deleteSupplier(): void
    {
        require_auth();
        verify_csrf();
        $id = (int)($_POST['id'] ?? 0);
        try {
            $this->business->deleteSupplier($id);
            flash('Supplier berhasil dihapus.');
        } catch (Throwable $e) {
            flash('Gagal menghapus supplier: ' . $e->getMessage());
        }
        redirect('suppliers');
    }

    // ==========================================
    // CRUD: CUSTOMERS
    // ==========================================
    public function editCustomer(): void
    {
        require_auth();
        $id = (int)($_GET['id'] ?? 0);
        $customer = $this->business->findCustomer($id);
        if (!$customer) {
            flash('Data pelanggan tidak ditemukan.');
            redirect('customers');
        }

        render('app/edit', [
            'pageTitle' => 'Edit Pelanggan — HP Business',
            'editType' => 'customer',
            'item' => $customer,
            'page' => 'customers',
            'flash' => flash(),
        ]);
    }

    public function updateCustomer(): void
    {
        require_auth();
        verify_csrf();
        $id = (int)($_POST['id'] ?? 0);
        try {
            $this->business->updateCustomer($id, $_POST);
            flash('Data pelanggan berhasil diperbarui.');
        } catch (Throwable $e) {
            flash('Gagal memperbarui pelanggan: ' . $e->getMessage());
        }
        redirect('customers');
    }

    public function deleteCustomer(): void
    {
        require_auth();
        verify_csrf();
        $id = (int)($_POST['id'] ?? 0);
        try {
            $this->business->deleteCustomer($id);
            flash('Pelanggan berhasil dihapus.');
        } catch (Throwable $e) {
            flash('Gagal menghapus pelanggan: ' . $e->getMessage());
        }
        redirect('customers');
    }

    // ==========================================
    // CRUD: PURCHASES
    // ==========================================
    public function editPurchase(): void
    {
        require_auth();
        $id = (int)($_GET['id'] ?? 0);
        $purchase = $this->business->findPurchase($id);
        if (!$purchase) {
            flash('Data pembelian tidak ditemukan.');
            redirect('purchases');
        }

        render('app/edit', [
            'pageTitle' => 'Edit Pembelian — HP Business',
            'editType' => 'purchase',
            'item' => $purchase,
            'suppliers' => $this->business->suppliers(),
            'page' => 'purchases',
            'flash' => flash(),
        ]);
    }

    public function updatePurchase(): void
    {
        require_auth();
        verify_csrf();
        $id = (int)($_POST['id'] ?? 0);
        try {
            $this->business->updatePurchase($id, $_POST);
            flash('Data pembelian berhasil diperbarui.');
        } catch (Throwable $e) {
            flash('Gagal memperbarui pembelian: ' . $e->getMessage());
        }
        redirect('purchases');
    }

    public function deletePurchase(): void
    {
        require_auth();
        verify_csrf();
        $id = (int)($_POST['id'] ?? 0);
        try {
            $this->business->deletePurchase($id);
            flash('Transaksi pembelian dan unit terkait berhasil dihapus.');
        } catch (Throwable $e) {
            flash('Gagal membatalkan pembelian: ' . $e->getMessage());
        }
        redirect('purchases');
    }

    // ==========================================
    // CRUD: SALES
    // ==========================================
    public function editSale(): void
    {
        require_auth();
        $id = (int)($_GET['id'] ?? 0);
        $sale = $this->business->findSale($id);
        if (!$sale) {
            flash('Data penjualan tidak ditemukan.');
            redirect('sales');
        }

        render('app/edit', [
            'pageTitle' => 'Edit Penjualan — HP Business',
            'editType' => 'sale',
            'item' => $sale,
            'customers' => $this->business->customers(),
            'page' => 'sales',
            'flash' => flash(),
        ]);
    }

    public function updateSale(): void
    {
        require_auth();
        verify_csrf();
        $id = (int)($_POST['id'] ?? 0);
        try {
            $this->business->updateSale($id, $_POST);
            flash('Data penjualan berhasil diperbarui.');
        } catch (Throwable $e) {
            flash('Gagal memperbarui penjualan: ' . $e->getMessage());
        }
        redirect('sales');
    }

    public function deleteSale(): void
    {
        require_auth();
        verify_csrf();
        $id = (int)($_POST['id'] ?? 0);
        try {
            $this->business->deleteSale($id);
            flash('Penjualan berhasil dibatalkan dan status unit HP kembali tersedia (ready stock).');
        } catch (Throwable $e) {
            flash('Gagal membatalkan penjualan: ' . $e->getMessage());
        }
        redirect('sales');
    }

    // ==========================================
    // CRUD: SERVICES
    // ==========================================
    public function editService(): void
    {
        require_auth();
        $id = (int)($_GET['id'] ?? 0);
        $service = $this->business->findService($id);
        if (!$service) {
            flash('Data servis tidak ditemukan.');
            redirect('services');
        }

        render('app/edit', [
            'pageTitle' => 'Edit Servis Unit — HP Business',
            'editType' => 'service',
            'item' => $service,
            'page' => 'services',
            'flash' => flash(),
        ]);
    }

    public function updateService(): void
    {
        require_auth();
        verify_csrf();
        $id = (int)($_POST['id'] ?? 0);
        try {
            $this->business->updateService($id, $_POST);
            flash('Data servis berhasil diperbarui.');
        } catch (Throwable $e) {
            flash('Gagal memperbarui servis: ' . $e->getMessage());
        }
        redirect('services');
    }

    public function deleteService(): void
    {
        require_auth();
        verify_csrf();
        $id = (int)($_POST['id'] ?? 0);
        try {
            $this->business->deleteService($id);
            flash('Catatan servis berhasil dihapus dan modal unit disesuaikan.');
        } catch (Throwable $e) {
            flash('Gagal menghapus servis: ' . $e->getMessage());
        }
        redirect('services');
    }

    // ==========================================
    // EXPORT: REPORTS (EXCEL & PDF/PRINT)
    // ==========================================
    public function exportExcel(): void
    {
        require_auth();
        $summary = $this->business->reportSummary();
        $sales = $this->business->detailedReport();
        $purchases = $this->business->purchases();
        $services = $this->business->services();
        $finances = $this->business->finances();
        $user = $_SESSION['user'] ?? ['name' => 'Administrator'];
        $printedAt = date('d F Y, H:i') . ' WIB';

        $filename = 'Laporan_Bisnis_HP_' . date('Ymd_His') . '.xls';
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Pragma: public');

        require __DIR__ . '/../views/reports/excel.php';
        exit;
    }

    public function exportPdf(): void
    {
        require_auth();
        $summary = $this->business->reportSummary();
        $sales = $this->business->detailedReport();
        $purchases = $this->business->purchases();
        $services = $this->business->services();
        $finances = $this->business->finances();
        $user = $_SESSION['user'] ?? ['name' => 'Administrator'];
        $printedAt = date('d F Y, H:i') . ' WIB';

        require __DIR__ . '/../views/reports/print.php';
        exit;
    }
}

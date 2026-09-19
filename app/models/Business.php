<?php
declare(strict_types=1);

class Business
{
    public function __construct(private PDO $pdo) {}

    // ==========================================
    // PRODUCTS (PRODUK HP)
    // ==========================================
    public function products(): array
    {
        return $this->pdo->query("SELECT * FROM products WHERE status = 'active' ORDER BY brand, model")->fetchAll();
    }

    public function findProduct(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function updateProduct(int $id, array $data): void
    {
        $brand = trim($data['brand'] ?? '');
        $model = trim($data['model'] ?? '');
        if ($brand === '' || $model === '') {
            throw new InvalidArgumentException('Merek dan model produk wajib diisi.');
        }

        $stmt = $this->pdo->prepare(
            "UPDATE products 
             SET brand = ?, model = ?, variant = ?, color = ?, ram = ?, storage = ?, description = ? 
             WHERE id = ?"
        );
        $stmt->execute([
            $brand,
            $model,
            trim($data['variant'] ?? '') ?: null,
            trim($data['color'] ?? '') ?: null,
            trim($data['ram'] ?? '') ?: null,
            trim($data['storage'] ?? '') ?: null,
            trim($data['description'] ?? '') ?: null,
            $id
        ]);
    }

    public function deleteProduct(int $id): void
    {
        $unitsCount = (int) $this->pdo->query("SELECT COUNT(*) FROM product_units WHERE product_id = " . (int)$id)->fetchColumn();
        if ($unitsCount > 0) {
            // Soft delete jika ada unit terkait agar histori transaksi aman
            $stmt = $this->pdo->prepare("UPDATE products SET status = 'inactive' WHERE id = ?");
            $stmt->execute([$id]);
        } else {
            $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = ?");
            $stmt->execute([$id]);
        }
    }

    // ==========================================
    // SUPPLIERS (PEMASOK)
    // ==========================================
    public function suppliers(): array
    {
        return $this->pdo->query("SELECT * FROM suppliers WHERE status = 'active' ORDER BY name")->fetchAll();
    }

    public function findSupplier(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM suppliers WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function updateSupplier(int $id, array $data): void
    {
        $name = trim($data['name'] ?? '');
        if ($name === '') {
            throw new InvalidArgumentException('Nama supplier wajib diisi.');
        }

        $stmt = $this->pdo->prepare(
            "UPDATE suppliers 
             SET name = ?, phone = ?, email = ?, address = ?, notes = ? 
             WHERE id = ?"
        );
        $stmt->execute([
            $name,
            trim($data['phone'] ?? '') ?: null,
            trim($data['email'] ?? '') ?: null,
            trim($data['address'] ?? '') ?: null,
            trim($data['notes'] ?? '') ?: null,
            $id
        ]);
    }

    public function deleteSupplier(int $id): void
    {
        $purchasesCount = (int) $this->pdo->query("SELECT COUNT(*) FROM purchases WHERE supplier_id = " . (int)$id)->fetchColumn();
        if ($purchasesCount > 0) {
            $stmt = $this->pdo->prepare("UPDATE suppliers SET status = 'inactive' WHERE id = ?");
            $stmt->execute([$id]);
        } else {
            $stmt = $this->pdo->prepare("DELETE FROM suppliers WHERE id = ?");
            $stmt->execute([$id]);
        }
    }

    // ==========================================
    // CUSTOMERS (PELANGGAN)
    // ==========================================
    public function customers(): array
    {
        return $this->pdo->query("SELECT * FROM customers WHERE status = 'active' ORDER BY name")->fetchAll();
    }

    public function findCustomer(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM customers WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function updateCustomer(int $id, array $data): void
    {
        $name = trim($data['name'] ?? '');
        if ($name === '') {
            throw new InvalidArgumentException('Nama pelanggan wajib diisi.');
        }

        $stmt = $this->pdo->prepare(
            "UPDATE customers 
             SET name = ?, phone = ?, email = ?, address = ?, notes = ? 
             WHERE id = ?"
        );
        $stmt->execute([
            $name,
            trim($data['phone'] ?? '') ?: null,
            trim($data['email'] ?? '') ?: null,
            trim($data['address'] ?? '') ?: null,
            trim($data['notes'] ?? '') ?: null,
            $id
        ]);
    }

    public function deleteCustomer(int $id): void
    {
        $salesCount = (int) $this->pdo->query("SELECT COUNT(*) FROM sales WHERE customer_id = " . (int)$id)->fetchColumn();
        if ($salesCount > 0) {
            $stmt = $this->pdo->prepare("UPDATE customers SET status = 'inactive' WHERE id = ?");
            $stmt->execute([$id]);
        } else {
            $stmt = $this->pdo->prepare("DELETE FROM customers WHERE id = ?");
            $stmt->execute([$id]);
        }
    }

    // ==========================================
    // MASTER DATA GENERAL CREATION
    // ==========================================
    public function create(string $type, array $data): void
    {
        $map = [
            'product' => [
                'products',
                'product_code',
                'PRD',
                'brand,model,variant,color,ram,storage',
                'brand=:brand,model=:model,variant=:variant,color=:color,ram=:ram,storage=:storage'
            ],
            'supplier' => [
                'suppliers',
                'supplier_code',
                'SUP',
                'name,phone,email,address',
                'name=:name,phone=:phone,email=:email,address=:address'
            ],
            'customer' => [
                'customers',
                'customer_code',
                'CUS',
                'name,phone,email,address',
                'name=:name,phone=:phone,email=:email,address=:address'
            ]
        ];

        if (!isset($map[$type])) {
            throw new InvalidArgumentException('Data master tidak dikenal.');
        }

        [$table, $codeField, $prefix, $columns] = $map[$type];
        $count = (int) $this->pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
        $code = $prefix . str_pad((string)($count + 1), 3, '0', STR_PAD_LEFT);

        $fields = explode(',', $columns);
        $sql = "INSERT INTO $table ($codeField, $columns) VALUES (:code, :" . implode(', :', $fields) . ")";
        $statement = $this->pdo->prepare($sql);
        $statement->execute(['code' => $code] + array_intersect_key($data, array_flip($fields)));
    }

    // ==========================================
    // INVENTORY & UNITS
    // ==========================================
    public function units(string $search = ''): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT u.*, p.brand, p.model, p.variant 
             FROM product_units u 
             INNER JOIN products p ON p.id = u.product_id 
             WHERE u.imei LIKE ? OR p.model LIKE ? OR p.brand LIKE ?
             ORDER BY u.created_at DESC"
        );
        $stmt->execute(['%' . $search . '%', '%' . $search . '%', '%' . $search . '%']);
        return $stmt->fetchAll();
    }

    public function availableUnits(): array
    {
        return $this->pdo->query(
            "SELECT u.*, p.brand, p.model, p.variant 
             FROM product_units u 
             INNER JOIN products p ON p.id = u.product_id 
             WHERE u.status = 'available' 
             ORDER BY p.brand, p.model"
        )->fetchAll();
    }

    // ==========================================
    // PURCHASES (PEMBELIAN HP)
    // ==========================================
    public function purchases(): array
    {
        return $this->pdo->query(
            "SELECT p.*, s.name AS supplier_name, s.phone AS supplier_phone,
                    pu.id AS unit_id, pu.imei, pu.battery_health, pu.status AS unit_status,
                    pu.selling_price AS target_selling_price,
                    pr.brand, pr.model, pr.variant
             FROM purchases p
             LEFT JOIN suppliers s ON s.id = p.supplier_id
             LEFT JOIN purchase_items pi ON pi.purchase_id = p.id
             LEFT JOIN product_units pu ON pu.id = pi.product_unit_id
             LEFT JOIN products pr ON pr.id = pu.product_id
             ORDER BY p.purchase_date DESC, p.id DESC"
        )->fetchAll();
    }

    public function findPurchase(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT p.*, s.name AS supplier_name,
                    pu.id AS unit_id, pu.product_id, pu.imei, pu.battery_health,
                    pu.screen_condition, pu.body_condition, pu.completeness,
                    pu.purchase_price AS unit_purchase_price,
                    pu.selling_price AS unit_selling_price,
                    pu.status AS unit_status,
                    pr.brand, pr.model, pr.variant
             FROM purchases p
             LEFT JOIN suppliers s ON s.id = p.supplier_id
             LEFT JOIN purchase_items pi ON pi.purchase_id = p.id
             LEFT JOIN product_units pu ON pu.id = pi.product_unit_id
             LEFT JOIN products pr ON pr.id = pu.product_id
             WHERE p.id = ?
             LIMIT 1"
        );
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function purchase(array $d): void
    {
        $this->pdo->beginTransaction();
        try {
            $amount = (float)($d['purchase_price'] ?? 0);
            if ($amount <= 0) {
                throw new InvalidArgumentException('Harga modal beli harus lebih dari 0.');
            }

            $imei = trim($d['imei'] ?? '');
            if (strlen($imei) < 10) {
                throw new InvalidArgumentException('Nomor IMEI tidak valid.');
            }

            // Check IMEI uniqueness
            $checkImei = $this->pdo->prepare("SELECT id FROM product_units WHERE imei = ?");
            $checkImei->execute([$imei]);
            if ($checkImei->fetch()) {
                throw new RuntimeException("IMEI $imei sudah terdaftar pada sistem.");
            }

            $code = 'BUY-' . date('Ymd-His');
            $s = $this->pdo->prepare(
                'INSERT INTO purchases (purchase_code, supplier_id, purchase_date, total_amount, payment_method, payment_status, created_by) 
                 VALUES (?, ?, CURDATE(), ?, ?, ?, ?)'
            );
            $s->execute([$code, (int)$d['supplier_id'], $amount, $d['payment_method'] ?? 'cash', 'paid', $_SESSION['user']['id'] ?? null]);
            $purchaseId = (int)$this->pdo->lastInsertId();

            $s = $this->pdo->prepare(
                'INSERT INTO product_units (product_id, imei, battery_health, screen_condition, body_condition, completeness, purchase_price, selling_price, status) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, "available")'
            );
            $s->execute([
                (int)$d['product_id'],
                $imei,
                $d['battery_health'] ?: null,
                $d['screen_condition'] ?: null,
                $d['body_condition'] ?: null,
                $d['completeness'] ?: null,
                $amount,
                (float)($d['selling_price'] ?? 0)
            ]);
            $unitId = (int)$this->pdo->lastInsertId();

            $this->pdo->prepare('INSERT INTO purchase_items (purchase_id, product_unit_id, purchase_price) VALUES (?, ?, ?)')
                ->execute([$purchaseId, $unitId, $amount]);

            $this->pdo->prepare('INSERT INTO stock_movements (product_unit_id, movement_type, reference_type, reference_id) VALUES (?, "IN", "PURCHASE", ?)')
                ->execute([$unitId, $purchaseId]);

            $this->pdo->commit();
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function updatePurchase(int $id, array $d): void
    {
        $purchase = $this->findPurchase($id);
        if (!$purchase) {
            throw new RuntimeException('Data pembelian tidak ditemukan.');
        }

        $this->pdo->beginTransaction();
        try {
            $amount = (float)($d['purchase_price'] ?? $purchase['total_amount']);
            $sellingPrice = (float)($d['selling_price'] ?? $purchase['unit_selling_price']);
            $supplierId = (int)($d['supplier_id'] ?? $purchase['supplier_id']);
            $paymentMethod = $d['payment_method'] ?? $purchase['payment_method'];

            // Update header pembelian
            $stmt = $this->pdo->prepare("UPDATE purchases SET supplier_id = ?, total_amount = ?, payment_method = ? WHERE id = ?");
            $stmt->execute([$supplierId, $amount, $paymentMethod, $id]);

            // Update item & unit bila unit masih ada
            if (!empty($purchase['unit_id'])) {
                $unitId = (int)$purchase['unit_id'];

                $stmt = $this->pdo->prepare("UPDATE purchase_items SET purchase_price = ? WHERE purchase_id = ? AND product_unit_id = ?");
                $stmt->execute([$amount, $id, $unitId]);

                $stmt = $this->pdo->prepare(
                    "UPDATE product_units 
                     SET purchase_price = ?, selling_price = ?, battery_health = ?, screen_condition = ?, body_condition = ?, completeness = ? 
                     WHERE id = ?"
                );
                $stmt->execute([
                    $amount,
                    $sellingPrice,
                    $d['battery_health'] ?: null,
                    $d['screen_condition'] ?: null,
                    $d['body_condition'] ?: null,
                    $d['completeness'] ?: null,
                    $unitId
                ]);
            }

            $this->pdo->commit();
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function deletePurchase(int $id): void
    {
        $purchase = $this->findPurchase($id);
        if (!$purchase) {
            throw new RuntimeException('Data pembelian tidak ditemukan.');
        }

        if (!empty($purchase['unit_status']) && $purchase['unit_status'] === 'sold') {
            throw new RuntimeException('Pembelian ini tidak dapat dihapus karena unit HP sudah terjual.');
        }

        $this->pdo->beginTransaction();
        try {
            $unitId = !empty($purchase['unit_id']) ? (int)$purchase['unit_id'] : 0;

            if ($unitId > 0) {
                // Hapus pergerakan stok terkait unit
                $this->pdo->prepare("DELETE FROM stock_movements WHERE product_unit_id = ?")->execute([$unitId]);
                // Hapus biaya servis jika ada
                $this->pdo->prepare("DELETE FROM unit_services WHERE product_unit_id = ?")->execute([$unitId]);
                // Hapus purchase item
                $this->pdo->prepare("DELETE FROM purchase_items WHERE purchase_id = ?")->execute([$id]);
                // Hapus product unit
                $this->pdo->prepare("DELETE FROM product_units WHERE id = ?")->execute([$unitId]);
            }

            // Hapus pembelian
            $this->pdo->prepare("DELETE FROM purchases WHERE id = ?")->execute([$id]);

            $this->pdo->commit();
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    // ==========================================
    // SALES (PENJUALAN HP)
    // ==========================================
    public function sales(): array
    {
        return $this->pdo->query(
            "SELECT s.*, c.name AS customer_name, c.phone AS customer_phone,
                    pu.id AS unit_id, pu.imei, pr.brand, pr.model, pr.variant,
                    si.profit, si.selling_price AS item_price
             FROM sales s
             LEFT JOIN customers c ON c.id = s.customer_id
             LEFT JOIN sale_items si ON si.sale_id = s.id
             LEFT JOIN product_units pu ON pu.id = si.product_unit_id
             LEFT JOIN products pr ON pr.id = pu.product_id
             ORDER BY s.sale_date DESC, s.id DESC"
        )->fetchAll();
    }

    public function findSale(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT s.*, c.name AS customer_name,
                    pu.id AS unit_id, pu.imei, pu.purchase_price AS unit_cost,
                    pr.brand, pr.model, pr.variant,
                    si.profit, si.selling_price AS item_price
             FROM sales s
             LEFT JOIN customers c ON c.id = s.customer_id
             LEFT JOIN sale_items si ON si.sale_id = s.id
             LEFT JOIN product_units pu ON pu.id = si.product_unit_id
             LEFT JOIN products pr ON pr.id = pu.product_id
             WHERE s.id = ?
             LIMIT 1"
        );
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function sale(array $d): void
    {
        $this->pdo->beginTransaction();
        try {
            $unit = $this->pdo->prepare("SELECT * FROM product_units WHERE id = ? AND status = 'available' FOR UPDATE");
            $unit->execute([(int)$d['unit_id']]);
            $unit = $unit->fetch();
            if (!$unit) {
                throw new RuntimeException('Unit tidak tersedia atau sudah terjual.');
            }

            $price = (float)$d['selling_price'];
            if ($price <= 0) {
                throw new InvalidArgumentException('Harga jual harus lebih dari 0.');
            }

            $code = 'SAL-' . date('Ymd-His');
            $s = $this->pdo->prepare(
                'INSERT INTO sales (sale_code, customer_id, sale_date, total_amount, discount, payment_method, payment_status, created_by) 
                 VALUES (?, ?, CURDATE(), ?, 0, ?, "paid", ?)'
            );
            $s->execute([$code, (int)$d['customer_id'], $price, $d['payment_method'] ?? 'cash', $_SESSION['user']['id'] ?? null]);
            $saleId = (int)$this->pdo->lastInsertId();

            $profit = $price - (float)$unit['purchase_price'];

            $this->pdo->prepare('INSERT INTO sale_items (sale_id, product_unit_id, selling_price, profit) VALUES (?, ?, ?, ?)')
                ->execute([$saleId, (int)$unit['id'], $price, $profit]);

            $this->pdo->prepare("UPDATE product_units SET status = 'sold', selling_price = ? WHERE id = ?")
                ->execute([$price, (int)$unit['id']]);

            $this->pdo->prepare('INSERT INTO stock_movements (product_unit_id, movement_type, reference_type, reference_id) VALUES (?, "OUT", "SALE", ?)')
                ->execute([(int)$unit['id'], $saleId]);

            $this->pdo->commit();
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function updateSale(int $id, array $d): void
    {
        $sale = $this->findSale($id);
        if (!$sale) {
            throw new RuntimeException('Data penjualan tidak ditemukan.');
        }

        $this->pdo->beginTransaction();
        try {
            $customerId = (int)($d['customer_id'] ?? $sale['customer_id']);
            $newPrice = (float)($d['selling_price'] ?? $sale['total_amount']);
            $paymentMethod = $d['payment_method'] ?? $sale['payment_method'];

            if ($newPrice <= 0) {
                throw new InvalidArgumentException('Harga jual harus lebih dari 0.');
            }

            // Hitung ulang profit
            $unitCost = (float)($sale['unit_cost'] ?? 0);
            $newProfit = $newPrice - $unitCost;

            // Update sales header
            $stmt = $this->pdo->prepare("UPDATE sales SET customer_id = ?, total_amount = ?, payment_method = ? WHERE id = ?");
            $stmt->execute([$customerId, $newPrice, $paymentMethod, $id]);

            // Update sale items
            $stmt = $this->pdo->prepare("UPDATE sale_items SET selling_price = ?, profit = ? WHERE sale_id = ?");
            $stmt->execute([$newPrice, $newProfit, $id]);

            // Update selling_price pada product_units
            if (!empty($sale['unit_id'])) {
                $stmt = $this->pdo->prepare("UPDATE product_units SET selling_price = ? WHERE id = ?");
                $stmt->execute([$newPrice, (int)$sale['unit_id']]);
            }

            $this->pdo->commit();
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function deleteSale(int $id): void
    {
        $sale = $this->findSale($id);
        if (!$sale) {
            throw new RuntimeException('Data penjualan tidak ditemukan.');
        }

        $this->pdo->beginTransaction();
        try {
            $unitId = !empty($sale['unit_id']) ? (int)$sale['unit_id'] : 0;

            if ($unitId > 0) {
                // Kembalikan status unit HP menjadi 'available'
                $this->pdo->prepare("UPDATE product_units SET status = 'available' WHERE id = ?")->execute([$unitId]);
                // Hapus pergerakan stok OUT penjualan ini
                $this->pdo->prepare("DELETE FROM stock_movements WHERE product_unit_id = ? AND reference_type = 'SALE' AND reference_id = ?")
                    ->execute([$unitId, $id]);
            }

            // Hapus detail sale item
            $this->pdo->prepare("DELETE FROM sale_items WHERE sale_id = ?")->execute([$id]);
            // Hapus transaksi penjualan
            $this->pdo->prepare("DELETE FROM sales WHERE id = ?")->execute([$id]);

            $this->pdo->commit();
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    // ==========================================
    // SERVICES (SERVIS UNIT)
    // ==========================================
    public function services(): array
    {
        return $this->pdo->query(
            "SELECT us.*, p.brand, p.model, u.imei, u.status AS unit_status 
             FROM unit_services us 
             INNER JOIN product_units u ON u.id = us.product_unit_id 
             INNER JOIN products p ON p.id = u.product_id 
             ORDER BY us.service_date DESC, us.id DESC"
        )->fetchAll();
    }

    public function findService(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT us.*, p.brand, p.model, u.imei 
             FROM unit_services us 
             INNER JOIN product_units u ON u.id = us.product_unit_id 
             INNER JOIN products p ON p.id = u.product_id 
             WHERE us.id = ? 
             LIMIT 1"
        );
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function recordService(array $data): void
    {
        $unitId = (int)($data['unit_id'] ?? 0);
        $cost = (float)($data['cost'] ?? 0);
        $description = trim($data['description'] ?? '');

        if (!$unitId || $cost <= 0 || $description === '') {
            throw new InvalidArgumentException('Pilih unit HP, isi biaya servis, dan keterangan perbaikan.');
        }

        $this->pdo->beginTransaction();
        try {
            $this->pdo->prepare('INSERT INTO unit_services (product_unit_id, service_date, description, cost, created_by) VALUES (?, CURDATE(), ?, ?, ?)')
                ->execute([$unitId, $description, $cost, $_SESSION['user']['id'] ?? null]);

            $code = 'FIN-' . date('Ymd-His') . '-' . random_int(10, 99);
            $this->pdo->prepare("INSERT INTO financial_transactions (transaction_code, transaction_date, transaction_type, cash_flow, amount, description, created_by) VALUES (?, CURDATE(), 'service', 'out', ?, ?, ?)")
                ->execute([$code, $cost, 'Biaya Servis: ' . $description, $_SESSION['user']['id'] ?? null]);

            $this->pdo->prepare('UPDATE product_units SET purchase_price = purchase_price + ? WHERE id = ?')
                ->execute([$cost, $unitId]);

            $this->pdo->commit();
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function updateService(int $id, array $data): void
    {
        $service = $this->findService($id);
        if (!$service) {
            throw new RuntimeException('Data servis tidak ditemukan.');
        }

        $newCost = (float)($data['cost'] ?? $service['cost']);
        $newDesc = trim($data['description'] ?? $service['description']);
        if ($newCost <= 0 || $newDesc === '') {
            throw new InvalidArgumentException('Biaya servis dan keterangan wajib diisi.');
        }

        $this->pdo->beginTransaction();
        try {
            $costDiff = $newCost - (float)$service['cost'];
            $unitId = (int)$service['product_unit_id'];

            // Update servis
            $stmt = $this->pdo->prepare("UPDATE unit_services SET cost = ?, description = ? WHERE id = ?");
            $stmt->execute([$newCost, $newDesc, $id]);

            // Sesuaikan modal unit jika biaya berubah
            if ($costDiff != 0) {
                $stmt = $this->pdo->prepare("UPDATE product_units SET purchase_price = purchase_price + ? WHERE id = ?");
                $stmt->execute([$costDiff, $unitId]);
            }

            $this->pdo->commit();
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function deleteService(int $id): void
    {
        $service = $this->findService($id);
        if (!$service) {
            throw new RuntimeException('Data servis tidak ditemukan.');
        }

        $this->pdo->beginTransaction();
        try {
            $unitId = (int)$service['product_unit_id'];
            $cost = (float)$service['cost'];

            // Kurangi modal unit sebesar biaya servis yang dibatalkan
            $this->pdo->prepare("UPDATE product_units SET purchase_price = GREATEST(0, purchase_price - ?) WHERE id = ?")
                ->execute([$cost, $unitId]);

            // Hapus record servis
            $this->pdo->prepare("DELETE FROM unit_services WHERE id = ?")->execute([$id]);

            $this->pdo->commit();
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    // ==========================================
    // REPORTS & CASHFLOW
    // ==========================================
    public function report(): array
    {
        return $this->pdo->query(
            "SELECT s.sale_code, s.sale_date, c.name AS customer, s.total_amount, 
                    COALESCE(SUM(si.profit), 0) AS profit 
             FROM sales s 
             INNER JOIN customers c ON c.id = s.customer_id 
             LEFT JOIN sale_items si ON si.sale_id = s.id 
             GROUP BY s.id 
             ORDER BY s.sale_date DESC"
        )->fetchAll();
    }

    public function detailedReport(): array
    {
        return $this->pdo->query(
            "SELECT s.id AS sale_id, s.sale_code, s.sale_date, s.payment_method, s.payment_status,
                    c.name AS customer_name, c.phone AS customer_phone,
                    pu.imei, pr.brand, pr.model, pr.variant,
                    COALESCE(pu.purchase_price, 0) AS unit_cost,
                    si.selling_price AS unit_sale_price,
                    si.profit,
                    u.name AS cashier_name
             FROM sales s
             INNER JOIN customers c ON c.id = s.customer_id
             LEFT JOIN sale_items si ON si.sale_id = s.id
             LEFT JOIN product_units pu ON pu.id = si.product_unit_id
             LEFT JOIN products pr ON pr.id = pu.product_id
             LEFT JOIN users u ON u.id = s.created_by
             ORDER BY s.sale_date DESC, s.id DESC"
        )->fetchAll();
    }

    public function reportSummary(): array
    {
        $salesSummary = $this->pdo->query(
            "SELECT COUNT(DISTINCT s.id) AS total_transactions,
                    COALESCE(SUM(s.total_amount), 0) AS total_sales,
                    COALESCE(SUM(si.profit), 0) AS total_profit,
                    COALESCE(SUM(pu.purchase_price), 0) AS total_cogs
             FROM sales s
             LEFT JOIN sale_items si ON si.sale_id = s.id
             LEFT JOIN product_units pu ON pu.id = si.product_unit_id"
        )->fetch() ?: [];

        $servicesCost = (float) $this->pdo->query("SELECT COALESCE(SUM(cost), 0) FROM unit_services")->fetchColumn();
        $expensesCost = (float) $this->pdo->query("SELECT COALESCE(SUM(amount), 0) FROM financial_transactions WHERE cash_flow = 'out' AND transaction_type != 'service'")->fetchColumn();
        $unitsSold = (int) $this->pdo->query("SELECT COUNT(*) FROM product_units WHERE status = 'sold'")->fetchColumn();
        $unitsReady = (int) $this->pdo->query("SELECT COUNT(*) FROM product_units WHERE status = 'available'")->fetchColumn();

        $totalSales = (float)($salesSummary['total_sales'] ?? 0);
        $totalProfit = (float)($salesSummary['total_profit'] ?? 0);
        $totalCogs = (float)($salesSummary['total_cogs'] ?? 0);
        $netProfit = $totalProfit - $expensesCost;

        return [
            'total_transactions' => (int)($salesSummary['total_transactions'] ?? 0),
            'total_sales' => $totalSales,
            'total_cogs' => $totalCogs,
            'total_profit' => $totalProfit,
            'total_services' => $servicesCost,
            'total_expenses' => $expensesCost,
            'net_profit' => $netProfit,
            'units_sold' => $unitsSold,
            'units_ready' => $unitsReady,
        ];
    }

    public function finances(): array
    {
        return $this->pdo->query('SELECT * FROM financial_transactions ORDER BY transaction_date DESC, id DESC')->fetchAll();
    }

    public function recordFinance(array $data): void
    {
        $type = $data['transaction_type'] ?? '';
        $flow = in_array($type, ['capital'], true) ? 'in' : 'out';
        if (!in_array($type, ['capital', 'expense', 'withdrawal', 'adjustment'], true) || (float)($data['amount'] ?? 0) <= 0 || trim($data['description'] ?? '') === '') {
            throw new InvalidArgumentException('Isi jenis transaksi, nominal, dan keterangan dengan benar.');
        }

        $code = 'FIN-' . date('Ymd-His') . '-' . random_int(10, 99);
        $this->pdo->prepare('INSERT INTO financial_transactions (transaction_code, transaction_date, transaction_type, cash_flow, amount, description, created_by) VALUES (?, CURDATE(), ?, ?, ?, ?, ?)')
            ->execute([$code, $type, $flow, (float)$data['amount'], trim($data['description']), $_SESSION['user']['id'] ?? null]);
    }
}

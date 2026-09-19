<?php
declare(strict_types=1);

class Dashboard
{
    public function __construct(private PDO $pdo) {}

    public function summary(): array
    {
        $stock = $this->pdo->query("SELECT
            COALESCE(SUM(status = 'available'), 0) AS available,
            COALESCE(SUM(status = 'sold'), 0) AS sold,
            COALESCE(SUM(status = 'available' AND created_at < DATE_SUB(CURDATE(), INTERVAL 30 DAY)), 0) AS aging
            FROM product_units")->fetch();

        return [
            'stockAvailable' => (int) $stock['available'],
            'stockSold' => (int) $stock['sold'],
            'stockAging' => (int) $stock['aging'],
            'salesToday' => (float) $this->value('SELECT COALESCE(SUM(total_amount), 0) FROM sales WHERE sale_date = CURDATE()'),
            'salesTodayCount' => (int) $this->value('SELECT COUNT(*) FROM sales WHERE sale_date = CURDATE()'),
            'purchaseMonth' => (float) $this->value('SELECT COALESCE(SUM(total_amount), 0) FROM purchases WHERE purchase_date >= DATE_FORMAT(CURDATE(), "%Y-%m-01")'),
            'purchaseMonthCount' => (int) $this->value('SELECT COUNT(*) FROM purchases WHERE purchase_date >= DATE_FORMAT(CURDATE(), "%Y-%m-01")'),
            'profitMonth' => (float) $this->value('SELECT COALESCE(SUM(si.profit), 0) FROM sale_items si INNER JOIN sales s ON s.id = si.sale_id WHERE s.sale_date >= DATE_FORMAT(CURDATE(), "%Y-%m-01")'),
            'customerCount' => (int) $this->value("SELECT COUNT(*) FROM customers WHERE status = 'active'"),
        ];
    }

    public function recentTransactions(): array
    {
        return $this->pdo->query("SELECT transaction_code, transaction_type, partner, total_amount, transaction_date
            FROM (
                SELECT sale_code AS transaction_code, 'PENJUALAN' AS transaction_type, c.name AS partner, s.total_amount, s.sale_date AS transaction_date FROM sales s INNER JOIN customers c ON c.id = s.customer_id
                UNION ALL
                SELECT purchase_code AS transaction_code, 'PEMBELIAN' AS transaction_type, sp.name AS partner, p.total_amount, p.purchase_date AS transaction_date FROM purchases p INNER JOIN suppliers sp ON sp.id = p.supplier_id
            ) AS transactions ORDER BY transaction_date DESC, transaction_code DESC LIMIT 5")->fetchAll();
    }

    private function value(string $sql): mixed
    {
        return $this->pdo->query($sql)->fetchColumn();
    }
}

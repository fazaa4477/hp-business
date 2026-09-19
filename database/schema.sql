-- ============================================================
-- HP BUSINESS MANAGEMENT SYSTEM
-- Database Schema V1
-- Fokus: Bisnis HP Bekas + IMEI Tracking
-- Database: MySQL 8.x
-- ============================================================

CREATE DATABASE IF NOT EXISTS hp_business
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE hp_business;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS stock_movements;
DROP TABLE IF EXISTS sale_items;
DROP TABLE IF EXISTS sales;
DROP TABLE IF EXISTS purchase_items;
DROP TABLE IF EXISTS purchases;
DROP TABLE IF EXISTS product_units;
DROP TABLE IF EXISTS customers;
DROP TABLE IF EXISTS suppliers;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS users;

SET FOREIGN_KEY_CHECKS = 1;


-- ============================================================
-- 1. USERS
-- Pengguna aplikasi
-- ============================================================

CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(100) NOT NULL,

    email VARCHAR(100) NOT NULL UNIQUE,

    username VARCHAR(50) NOT NULL UNIQUE,

    password VARCHAR(255) NOT NULL,

    role ENUM(
        'admin',
        'staff',
        'owner'
    ) NOT NULL DEFAULT 'staff',

    status ENUM(
        'active',
        'inactive'
    ) NOT NULL DEFAULT 'active',

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP NOT NULL
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;


-- ============================================================
-- 2. PRODUCTS
-- Master data / model HP
-- ============================================================

CREATE TABLE products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    product_code VARCHAR(30) NOT NULL UNIQUE,

    brand VARCHAR(50) NOT NULL,

    model VARCHAR(100) NOT NULL,

    variant VARCHAR(100) NULL,

    color VARCHAR(50) NULL,

    ram VARCHAR(30) NULL,

    storage VARCHAR(30) NULL,

    description TEXT NULL,

    status ENUM(
        'active',
        'inactive'
    ) NOT NULL DEFAULT 'active',

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP NOT NULL
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_products_brand (brand),

    INDEX idx_products_model (model),

    INDEX idx_products_status (status)
) ENGINE=InnoDB;


-- ============================================================
-- 3. SUPPLIERS
-- Data pemasok / tempat kulak HP
-- ============================================================

CREATE TABLE suppliers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    supplier_code VARCHAR(30) NOT NULL UNIQUE,

    name VARCHAR(100) NOT NULL,

    phone VARCHAR(30) NULL,

    email VARCHAR(100) NULL,

    address TEXT NULL,

    notes TEXT NULL,

    status ENUM(
        'active',
        'inactive'
    ) NOT NULL DEFAULT 'active',

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP NOT NULL
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_suppliers_name (name),

    INDEX idx_suppliers_status (status)
) ENGINE=InnoDB;


-- ============================================================
-- 4. CUSTOMERS
-- Data pelanggan
-- ============================================================

CREATE TABLE customers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    customer_code VARCHAR(30) NOT NULL UNIQUE,

    name VARCHAR(100) NOT NULL,

    phone VARCHAR(30) NULL,

    email VARCHAR(100) NULL,

    address TEXT NULL,

    notes TEXT NULL,

    status ENUM(
        'active',
        'inactive'
    ) NOT NULL DEFAULT 'active',

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP NOT NULL
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_customers_name (name),

    INDEX idx_customers_phone (phone),

    INDEX idx_customers_status (status)
) ENGINE=InnoDB;


-- ============================================================
-- 5. PRODUCT_UNITS
-- Satu record = satu HP fisik
-- IMEI menjadi identitas unik unit
-- ============================================================

CREATE TABLE product_units (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    product_id BIGINT UNSIGNED NOT NULL,

    imei VARCHAR(20) NOT NULL,

    imei_2 VARCHAR(20) NULL,

    serial_number VARCHAR(100) NULL,

    -- Kondisi HP
    battery_health DECIMAL(5,2) NULL,

    screen_condition VARCHAR(255) NULL,

    body_condition VARCHAR(255) NULL,

    completeness VARCHAR(255) NULL,

    condition_notes TEXT NULL,

    -- Keuangan unit
    purchase_price DECIMAL(15,2)
        NOT NULL DEFAULT 0,

    selling_price DECIMAL(15,2)
        NOT NULL DEFAULT 0,

    -- Status unit
    status ENUM(
        'available',
        'sold',
        'returned',
        'damaged'
    ) NOT NULL DEFAULT 'available',

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP NOT NULL
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,


    -- ========================================================
    -- FOREIGN KEY
    -- ========================================================

    CONSTRAINT fk_product_units_product

        FOREIGN KEY (product_id)

        REFERENCES products(id)

        ON UPDATE CASCADE

        ON DELETE RESTRICT,


    -- ========================================================
    -- UNIQUE
    -- ========================================================

    CONSTRAINT uq_product_units_imei

        UNIQUE (imei),


    CONSTRAINT uq_product_units_imei_2

        UNIQUE (imei_2),


    -- ========================================================
    -- INDEX
    -- ========================================================

    INDEX idx_product_units_product (product_id),

    INDEX idx_product_units_status (status),

    INDEX idx_product_units_serial (serial_number)

) ENGINE=InnoDB;


-- ============================================================
-- 6. PURCHASES
-- Header transaksi pembelian
-- ============================================================

CREATE TABLE purchases (

    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    purchase_code VARCHAR(40) NOT NULL UNIQUE,

    supplier_id BIGINT UNSIGNED NOT NULL,

    purchase_date DATE NOT NULL,

    total_amount DECIMAL(15,2)
        NOT NULL DEFAULT 0,

    payment_method ENUM(
        'cash',
        'transfer',
        'qris',
        'other'
    ) NOT NULL DEFAULT 'cash',

    payment_status ENUM(
        'paid',
        'unpaid',
        'partial'
    ) NOT NULL DEFAULT 'paid',

    notes TEXT NULL,

    created_by BIGINT UNSIGNED NULL,

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP NOT NULL
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,


    CONSTRAINT fk_purchases_supplier

        FOREIGN KEY (supplier_id)

        REFERENCES suppliers(id)

        ON UPDATE CASCADE

        ON DELETE RESTRICT,


    CONSTRAINT fk_purchases_created_by

        FOREIGN KEY (created_by)

        REFERENCES users(id)

        ON UPDATE CASCADE

        ON DELETE SET NULL,


    INDEX idx_purchases_supplier (supplier_id),

    INDEX idx_purchases_date (purchase_date),

    INDEX idx_purchases_payment_status (payment_status)

) ENGINE=InnoDB;


-- ============================================================
-- 7. PURCHASE_ITEMS
-- Detail HP yang dibeli
-- Satu baris = satu unit HP
-- ============================================================

CREATE TABLE purchase_items (

    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    purchase_id BIGINT UNSIGNED NOT NULL,

    product_unit_id BIGINT UNSIGNED NOT NULL,

    purchase_price DECIMAL(15,2)
        NOT NULL DEFAULT 0,

    notes TEXT NULL,

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,


    CONSTRAINT fk_purchase_items_purchase

        FOREIGN KEY (purchase_id)

        REFERENCES purchases(id)

        ON UPDATE CASCADE

        ON DELETE CASCADE,


    CONSTRAINT fk_purchase_items_product_unit

        FOREIGN KEY (product_unit_id)

        REFERENCES product_units(id)

        ON UPDATE CASCADE

        ON DELETE RESTRICT,


    INDEX idx_purchase_items_purchase (purchase_id),

    INDEX idx_purchase_items_unit (product_unit_id)

) ENGINE=InnoDB;


-- ============================================================
-- 8. SALES
-- Header transaksi penjualan
-- ============================================================

CREATE TABLE sales (

    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    sale_code VARCHAR(40) NOT NULL UNIQUE,

    customer_id BIGINT UNSIGNED NOT NULL,

    sale_date DATE NOT NULL,

    total_amount DECIMAL(15,2)
        NOT NULL DEFAULT 0,

    discount DECIMAL(15,2)
        NOT NULL DEFAULT 0,

    payment_method ENUM(
        'cash',
        'transfer',
        'qris',
        'other'
    ) NOT NULL DEFAULT 'cash',

    payment_status ENUM(
        'paid',
        'unpaid',
        'partial'
    ) NOT NULL DEFAULT 'paid',

    notes TEXT NULL,

    created_by BIGINT UNSIGNED NULL,

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP NOT NULL
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,


    CONSTRAINT fk_sales_customer

        FOREIGN KEY (customer_id)

        REFERENCES customers(id)

        ON UPDATE CASCADE

        ON DELETE RESTRICT,


    CONSTRAINT fk_sales_created_by

        FOREIGN KEY (created_by)

        REFERENCES users(id)

        ON UPDATE CASCADE

        ON DELETE SET NULL,


    INDEX idx_sales_customer (customer_id),

    INDEX idx_sales_date (sale_date),

    INDEX idx_sales_payment_status (payment_status)

) ENGINE=InnoDB;


-- ============================================================
-- 9. SALE_ITEMS
-- Detail HP yang dijual
-- Satu baris = satu unit HP
-- ============================================================

CREATE TABLE sale_items (

    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    sale_id BIGINT UNSIGNED NOT NULL,

    product_unit_id BIGINT UNSIGNED NOT NULL,

    selling_price DECIMAL(15,2)
        NOT NULL DEFAULT 0,

    discount DECIMAL(15,2)
        NOT NULL DEFAULT 0,

    profit DECIMAL(15,2)
        NOT NULL DEFAULT 0,

    notes TEXT NULL,

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,


    CONSTRAINT fk_sale_items_sale

        FOREIGN KEY (sale_id)

        REFERENCES sales(id)

        ON UPDATE CASCADE

        ON DELETE CASCADE,


    CONSTRAINT fk_sale_items_product_unit

        FOREIGN KEY (product_unit_id)

        REFERENCES product_units(id)

        ON UPDATE CASCADE

        ON DELETE RESTRICT,


    INDEX idx_sale_items_sale (sale_id),

    INDEX idx_sale_items_unit (product_unit_id)

) ENGINE=InnoDB;


-- ============================================================
-- 10. STOCK_MOVEMENTS
-- Histori pergerakan stok
--
-- IN         = HP masuk
-- OUT        = HP keluar
-- ADJUSTMENT = Penyesuaian manual
-- ============================================================

CREATE TABLE stock_movements (

    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    product_unit_id BIGINT UNSIGNED NOT NULL,

    movement_type ENUM(
        'IN',
        'OUT',
        'ADJUSTMENT'
    ) NOT NULL,

    reference_type ENUM(
        'PURCHASE',
        'SALE',
        'MANUAL'
    ) NOT NULL,

    reference_id BIGINT UNSIGNED NULL,

    notes TEXT NULL,

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,


    CONSTRAINT fk_stock_movements_product_unit

        FOREIGN KEY (product_unit_id)

        REFERENCES product_units(id)

        ON UPDATE CASCADE

        ON DELETE RESTRICT,


    INDEX idx_stock_movements_unit (product_unit_id),

    INDEX idx_stock_movements_type (movement_type),

    INDEX idx_stock_movements_reference (
        reference_type,
        reference_id
    ),

    INDEX idx_stock_movements_created_at (created_at)

) ENGINE=InnoDB;


-- ============================================================
-- 11. BIAYA SERVIS UNIT
-- ============================================================
CREATE TABLE unit_services (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_unit_id BIGINT UNSIGNED NOT NULL,
    service_date DATE NOT NULL,
    description VARCHAR(255) NOT NULL,
    cost DECIMAL(15,2) NOT NULL DEFAULT 0,
    created_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_unit_services_unit FOREIGN KEY (product_unit_id) REFERENCES product_units(id) ON DELETE RESTRICT,
    CONSTRAINT fk_unit_services_user FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_unit_services_unit (product_unit_id)
) ENGINE=InnoDB;

-- ============================================================
-- 12. KAS, MODAL, PRIVE, DAN PENGELUARAN
-- ============================================================
CREATE TABLE financial_transactions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    transaction_code VARCHAR(40) NOT NULL UNIQUE,
    transaction_date DATE NOT NULL,
    transaction_type ENUM('capital','expense','withdrawal','service','adjustment') NOT NULL,
    cash_flow ENUM('in','out') NOT NULL,
    amount DECIMAL(15,2) NOT NULL DEFAULT 0,
    description VARCHAR(255) NOT NULL,
    created_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_financial_transactions_user FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_financial_transactions_date (transaction_date),
    INDEX idx_financial_transactions_type (transaction_type)
) ENGINE=InnoDB;


-- ============================================================
-- END OF SCHEMA
-- ============================================================

-- CATATAN PENTING:
--
-- 1. products = model/jenis HP.
--
-- 2. product_units = HP fisik individual.
--    Setiap HP mempunyai IMEI sendiri.
--
-- 3. Stok TIDAK disimpan sebagai angka manual
--    di tabel products.
--
-- 4. Stok tersedia dihitung dari:
--
--    product_units.status = 'available'
--
-- 5. Pembelian akan membuat:
--
--    purchases
--       ↓
--    purchase_items
--       ↓
--    product_units
--       ↓
--    stock_movements (IN)
--
-- 6. Penjualan akan membuat:
--
--    sales
--       ↓
--    sale_items
--       ↓
--    product_units.status = 'sold'
--       ↓
--    stock_movements (OUT)
--
-- 7. Semua proses pembelian/penjualan nantinya
--    harus menggunakan database transaction:
--
--    BEGIN
--    ...
--    COMMIT
--
--    Jika terjadi error:
--
--    ROLLBACK
--
-- ============================================================

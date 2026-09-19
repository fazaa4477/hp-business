-- Jalankan file ini sekali pada database hp_business yang dibuat sebelum fitur servis dan cashflow ditambahkan.

CREATE TABLE IF NOT EXISTS unit_services (
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

CREATE TABLE IF NOT EXISTS financial_transactions (
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

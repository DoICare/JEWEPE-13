-- 1. Membuat Database sesuai ketentuan
CREATE DATABASE IF NOT EXISTS db_toko_bangunan_jwp;
USE db_toko_bangunan_jwp;

-- 2. Membuat Tabel users 
-- (Berdasarkan catatan: otomatis dibuat saat instalasi Laravel. Ini adalah struktur standar bawaannya)
CREATE TABLE users (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB;

-- 3. Membuat Tabel categories
-- (Menyimpan kategori barang)
CREATE TABLE categories (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB;

-- 4. Membuat Tabel products
-- (Menyimpan daftar barang, kategori, satuan, stok, dan batas minimum)
CREATE TABLE products (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    category_id BIGINT(20) UNSIGNED NOT NULL,
    code VARCHAR(255) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    unit VARCHAR(255) NOT NULL,
    stock INT(11) NOT NULL DEFAULT 0,
    minimum_stock INT(11) NOT NULL DEFAULT 10,
    description TEXT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (id),
    -- Relasi 1 to n (Categories ke Products)
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 5. Membuat Tabel stock_transactions
-- (Menyimpan riwayat barang masuk dan keluar beserta stok terkini)
CREATE TABLE stock_transactions (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    product_id BIGINT(20) UNSIGNED NOT NULL,
    user_id BIGINT(20) UNSIGNED NOT NULL,
    type ENUM('masuk', 'keluar') NOT NULL,
    quantity INT(11) NOT NULL,
    stock_after INT(11) NOT NULL,
    transaction_date DATE NOT NULL,
    note TEXT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (id),
    -- Relasi 1 to n (Products ke Transactions)
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    -- Relasi 1 to n (Users ke Transactions)
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;





USE db_toko_bangunan_jwp;

-- ==============================================================
-- 1. INSERT DATA USERS
-- ==============================================================
-- Catatan: Password menggunakan hash Bcrypt bawaan Laravel untuk kata sandi: "password"
INSERT INTO users (name, email, password, created_at, updated_at) VALUES 
('Rizky Maulana Saputra', 'admin@jewepe.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW());


-- ==============================================================
-- 2. INSERT DATA CATEGORIES
-- ==============================================================
INSERT INTO categories (name, description, created_at, updated_at) VALUES 
('Material Dasar', 'Kategori untuk material dasar bangunan seperti pasir, semen, bata, dll.', NOW(), NOW()),
('Logam & Besi', 'Kategori untuk material berbahan logam murni atau campuran.', NOW(), NOW()),
('Cat & Bahan Kimia', 'Kategori untuk keperluan pengecatan dan cairan kimia pelarut.', NOW(), NOW());


-- ==============================================================
-- 3. INSERT DATA PRODUCTS
-- ==============================================================
-- Keterangan Stok:
-- BRG-001 (Aman): Stok 145
-- BRG-002 (Kosong): Stok 0
-- BRG-003 (Kritis): Stok 5 (di bawah batas 10)
-- BRG-004 (Kritis): Stok 8 (di bawah batas 10)
INSERT INTO products (category_id, code, name, unit, stock, minimum_stock, description, created_at, updated_at) VALUES 
(1, 'BRG-001', 'Semen Tiga Roda 50Kg', 'Sak', 145, 10, 'Semen portland kualitas standar SNI', NOW(), NOW()),
(2, 'BRG-002', 'Besi Beton 10mm', 'Batang', 0, 20, 'Besi beton ulir standar', NOW(), NOW()),
(3, 'BRG-003', 'Cat Avian Putih 1Kg', 'Kaleng', 5, 10, 'Cat kayu dan besi mengkilap', NOW(), NOW()),
(2, 'BRG-004', 'Paku Payung 5cm', 'Kg', 8, 10, 'Paku seng anti karat', NOW(), NOW());


-- ==============================================================
-- 4. INSERT DATA STOCK TRANSACTIONS
-- ==============================================================
-- Riwayat barang masuk dan keluar untuk mensimulasikan pergerakan stok
INSERT INTO stock_transactions (product_id, user_id, type, quantity, stock_after, transaction_date, note, created_at, updated_at) VALUES 
-- Transaksi Semen
(1, 1, 'masuk', 150, 150, CURDATE() - INTERVAL 2 DAY, 'Stok awal dari supplier PT Bangun Nusantara', NOW() - INTERVAL 2 DAY, NOW() - INTERVAL 2 DAY),
(1, 1, 'keluar', 5, 145, CURDATE() - INTERVAL 1 DAY, 'Terjual ke proyek perumahan', NOW() - INTERVAL 1 DAY, NOW() - INTERVAL 1 DAY),

-- Transaksi Cat
(3, 1, 'masuk', 10, 10, CURDATE() - INTERVAL 5 DAY, 'Stok awal cat', NOW() - INTERVAL 5 DAY, NOW() - INTERVAL 5 DAY),
(3, 1, 'keluar', 5, 5, CURDATE(), 'Terjual eceran', NOW(), NOW()),

-- Transaksi Paku
(4, 1, 'masuk', 10, 10, CURDATE() - INTERVAL 7 DAY, 'Restock dari distributor', NOW() - INTERVAL 7 DAY, NOW() - INTERVAL 7 DAY),
(4, 1, 'keluar', 2, 8, CURDATE() - INTERVAL 2 DAY, 'Pemakaian internal toko', NOW() - INTERVAL 2 DAY, NOW() - INTERVAL 2 DAY);
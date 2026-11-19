CREATE DATABASE IF NOT EXISTS shop_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE shop_db;

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price INT NOT NULL CHECK (price > 0),
    stock INT NOT NULL CHECK (stock >= 0),
    image VARCHAR(255) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET @add_image = IF(
    EXISTS(SELECT 1 FROM information_schema.COLUMNS
           WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'products' AND COLUMN_NAME = 'image'),
    'SELECT 1',
    'ALTER TABLE products ADD COLUMN image VARCHAR(255) NULL'
);
PREPARE add_image FROM @add_image;
EXECUTE add_image;
DEALLOCATE PREPARE add_image;

INSERT INTO products (name, price, stock)
SELECT sample.name, sample.price, sample.stock
FROM (
    SELECT 'Bút bi' AS name, 5000 AS price, 20 AS stock
    UNION ALL SELECT 'Vở ô ly', 12000, 15
    UNION ALL SELECT 'Thước kẻ', 8000, 0
    UNION ALL SELECT 'Bút chì', 4000, 30
    UNION ALL SELECT 'Tẩy chì', 6000, 24
    UNION ALL SELECT 'Gọt bút chì', 9000, 10
    UNION ALL SELECT 'Hộp bút', 32000, 8
    UNION ALL SELECT 'Bút đánh dấu', 15000, 12
    UNION ALL SELECT 'Giấy ghi chú', 18000, 16
    UNION ALL SELECT 'Kéo học sinh', 22000, 9
    UNION ALL SELECT 'Hồ dán', 11000, 14
    UNION ALL SELECT 'Bìa hồ sơ', 7000, 25
) AS sample
WHERE NOT EXISTS (SELECT 1 FROM products WHERE products.name = sample.name);

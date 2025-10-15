-- MySQL schema for Batatua1928
CREATE DATABASE IF NOT EXISTS batatua1928 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE batatua1928;

-- Admin users
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(64) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Menu categories
CREATE TABLE IF NOT EXISTS categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(64) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- Menu items
CREATE TABLE IF NOT EXISTS menu_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(128) NOT NULL,
  price INT NOT NULL,
  category_id INT NULL,
  image_path VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB;

INSERT IGNORE INTO categories (id, name) VALUES
  (1, 'makanan-ringan'),
  (2, 'makanan-berat'),
  (3, 'non-coffe'),
  (4, 'coffee');

-- Demo admin: username=admin, password=admin123 (change in production)
INSERT IGNORE INTO users (id, username, password_hash)
VALUES (1, 'admin', '$2y$10$0rU8JYq2v0aWkB1qkX1wfeq6zq7TgJ7mHbQH3D2t0mW2k5o9cQm2m');

-- Seed a few menu items
INSERT IGNORE INTO menu_items (id, name, price, category_id, image_path) VALUES
  (1, 'Mie Bangladesh', 15000, 2, 'Assets/mie bang.jpg'),
  (2, 'Sosis Crispy', 16000, 1, 'Assets/sosis.jpg'),
  (3, 'Teh O', 8000, 3, 'Assets/Esteh.jpg'),
  (4, 'Indomie Goreng Telor', 15000, 2, 'Assets/menu mie.jpg');

-- Gallery images
CREATE TABLE IF NOT EXISTS gallery (
  id INT AUTO_INCREMENT PRIMARY KEY,
  image_path VARCHAR(255) NOT NULL,
  caption VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;



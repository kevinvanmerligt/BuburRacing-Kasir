-- Database: db_kasirbubur
-- Kasir Bubur Racing POS System

CREATE DATABASE IF NOT EXISTS `db_kasirbubur` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `db_kasirbubur`;

-- --------------------------------------------------------
-- Table structure for `users`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(100) NOT NULL UNIQUE,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for `produk`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `produk` (
  `id_barang` INT AUTO_INCREMENT PRIMARY KEY,
  `nama_barang` VARCHAR(255) NOT NULL,
  `jenis` ENUM('Bubur', 'Toping', 'Minuman') NOT NULL DEFAULT 'Bubur',
  `harga_barang` INT NOT NULL DEFAULT 0,
  `stok` INT NOT NULL DEFAULT 0,
  `status` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1: Tersedia, 0: Habis',
  `foto` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for `transaksi`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `transaksi` (
  `id_transaksi` INT AUTO_INCREMENT PRIMARY KEY,
  `id_barang` INT NOT NULL,
  `harga_produk` INT NOT NULL DEFAULT 0,
  `total` INT NOT NULL DEFAULT 0,
  `uang` INT NOT NULL DEFAULT 0,
  `kembalian` INT NOT NULL DEFAULT 0,
  `nama` VARCHAR(100) DEFAULT 'Tanpa Nama',
  `meja` VARCHAR(50) DEFAULT '-',
  `pajak` INT NOT NULL DEFAULT 0,
  `metode` VARCHAR(50) NOT NULL DEFAULT 'CASH',
  `tgl` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX (`id_barang`),
  INDEX (`tgl`),
  CONSTRAINT `fk_transaksi_produk` FOREIGN KEY (`id_barang`) REFERENCES `produk` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Sample Data for `users` (Default login: admin / admin123)
-- --------------------------------------------------------
INSERT INTO `users` (`username`, `email`, `password`) VALUES
('admin', 'admin@buburracing.com', 'admin123')
ON DUPLICATE KEY UPDATE `username`=`username`;

-- --------------------------------------------------------
-- Sample Data for `produk`
-- --------------------------------------------------------
INSERT INTO `produk` (`nama_barang`, `jenis`, `harga_barang`, `stok`, `status`, `foto`) VALUES
('Bubur Ayam Racing Komplit', 'Bubur', 15000, 50, 1, ''),
('Bubur Polos Gurih', 'Bubur', 10000, 40, 1, ''),
('Sate Usus Gurih', 'Toping', 3000, 100, 1, ''),
('Sate Telur Puyuh', 'Toping', 4000, 80, 1, ''),
('Kerupuk Pangsit Ekstra', 'Toping', 2000, 60, 1, ''),
('Teh Manis Hangat / Es', 'Minuman', 5000, 75, 1, ''),
('Air Mineral Dingin', 'Minuman', 4000, 100, 1, '')
ON DUPLICATE KEY UPDATE `nama_barang`=`nama_barang`;

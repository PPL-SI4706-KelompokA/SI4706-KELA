-- Database Testing Seed Script for FoodShare Systems
-- Generated based on test cases: TCLDM, TCRD, and TCRP

SET FOREIGN_KEY_CHECKS = 0;

-- Drop tables in reverse order of dependencies
DROP TABLE IF EXISTS `permintaans`;
DROP TABLE IF EXISTS `donasis`;
DROP TABLE IF EXISTS `lokasis`;
DROP TABLE IF EXISTS `users`;

SET FOREIGN_KEY_CHECKS = 1;

-- -------------------------------------------------------------
-- Table structure for `users`
-- -------------------------------------------------------------
CREATE TABLE `users` (
  `id_user` BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `nama` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) UNIQUE NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` VARCHAR(20) NOT NULL, -- Admin, Donatur, Penerima
  `no_telp` VARCHAR(15) NOT NULL,
  `alamat` VARCHAR(255) NOT NULL,
  `status_verifikasi` VARCHAR(20) NOT NULL DEFAULT 'Belum Verifikasi',
  `foto_profil` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Table structure for `lokasis`
-- -------------------------------------------------------------
CREATE TABLE `lokasis` (
  `id_lokasi` BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `alamat` VARCHAR(255) NOT NULL,
  `kota` VARCHAR(255) NOT NULL,
  `latitude` DECIMAL(10, 8) NOT NULL,
  `longitude` DECIMAL(11, 8) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Table structure for `donasis`
-- -------------------------------------------------------------
CREATE TABLE `donasis` (
  `id_donasi` BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `id_user` BIGINT(20) UNSIGNED NOT NULL,
  `id_lokasi` BIGINT(20) UNSIGNED NOT NULL,
  `nama_makanan` VARCHAR(100) NOT NULL,
  `kategori` VARCHAR(50) NOT NULL,
  `jumlah` INT(11) NOT NULL,
  `tanggal_kadaluarsa` DATE NOT NULL,
  `deskripsi` VARCHAR(255) NOT NULL,
  `foto_url` VARCHAR(255) DEFAULT NULL,
  `status_donasi` VARCHAR(20) NOT NULL DEFAULT 'Available',
  `status_verifikasi` VARCHAR(20) NOT NULL DEFAULT 'Pending',
  `verified_by` INT(11) DEFAULT NULL,
  `tanggal_verifikasi` DATE DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_donasis_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  CONSTRAINT `fk_donasis_lokasi` FOREIGN KEY (`id_lokasi`) REFERENCES `lokasis` (`id_lokasi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Table structure for `permintaans`
-- -------------------------------------------------------------
CREATE TABLE `permintaans` (
  `id_permintaan` BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `id_user` BIGINT(20) UNSIGNED NOT NULL,
  `id_donasi` BIGINT(20) UNSIGNED NOT NULL,
  `jumlah_permintaan` INT(11) NOT NULL,
  `catatan` VARCHAR(255) DEFAULT NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'Pending',
  `tanggal_acc` DATE DEFAULT NULL,
  `tanggal_tolak` DATE DEFAULT NULL,
  `id_permintaan_parent` INT(11) DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_permintaans_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  CONSTRAINT `fk_permintaans_donasi` FOREIGN KEY (`id_donasi`) REFERENCES `donasis` (`id_donasi`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Dummy Data Insertion
-- -------------------------------------------------------------

-- 1. Users
-- Password is bcrypt hash for 'password'
INSERT INTO `users` (`id_user`, `nama`, `email`, `password`, `role`, `no_telp`, `alamat`, `status_verifikasi`, `foto_profil`, `created_at`, `updated_at`) VALUES
(1, 'Andi Pratama', 'admin@email.com', '$2y$12$ZnFpbHNFSljjReXrr9LwvOVv08qN6PKVTafDVkoP8Bfhr5C2TM4bK', 'Admin', '081234567890', 'Kantor Pusat FoodShare', 'Sudah Verifikasi', NULL, NOW(), NOW()),
(2, 'Dewi Lestari', 'dewi@email.com', '$2y$12$ZnFpbHNFSljjReXrr9LwvOVv08qN6PKVTafDVkoP8Bfhr5C2TM4bK', 'Donatur', '081234567890', 'Jl. Dago No. 12, Bandung', 'Sudah Verifikasi', NULL, NOW(), NOW()),
(3, 'Bambang Hermawan', 'bambang@email.com', '$2y$12$ZnFpbHNFSljjReXrr9LwvOVv08qN6PKVTafDVkoP8Bfhr5C2TM4bK', 'Donatur', '081234567891', 'Jl. Merdeka No. 45, Bandung', 'Sudah Verifikasi', NULL, NOW(), NOW()),
(4, 'Rian Hidayat', 'rian@email.com', '$2y$12$ZnFpbHNFSljjReXrr9LwvOVv08qN6PKVTafDVkoP8Bfhr5C2TM4bK', 'Penerima', '081234567892', 'Jl. Cihampelas No. 50, Bandung', 'Sudah Verifikasi', NULL, NOW(), NOW()),
(5, 'Budi Santoso', 'budi@email.com', '$2y$12$ZnFpbHNFSljjReXrr9LwvOVv08qN6PKVTafDVkoP8Bfhr5C2TM4bK', 'Donatur', '081221344', 'Jl. Dago, Bandung', 'Sudah Verifikasi', NULL, NOW(), NOW()),
(6, 'Siti Aminah', 'siti@email.com', '$2y$12$ZnFpbHNFSljjReXrr9LwvOVv08qN6PKVTafDVkoP8Bfhr5C2TM4bK', 'Penerima', '081234567892', 'Jl. Cihampelas No. 50, Bandung', 'Sudah Verifikasi', NULL, NOW(), NOW());

-- 2. Lokasis
INSERT INTO `lokasis` (`id_lokasi`, `alamat`, `kota`, `latitude`, `longitude`, `created_at`, `updated_at`) VALUES
(1, 'Jl. Dago No. 10, Bandung', 'Bandung', -6.91746400, 107.61912200, NOW(), NOW());

-- 3. Donasis
INSERT INTO `donasis` (`id_donasi`, `id_user`, `id_lokasi`, `nama_makanan`, `kategori`, `jumlah`, `tanggal_kadaluarsa`, `deskripsi`, `foto_url`, `status_donasi`, `status_verifikasi`, `verified_by`, `tanggal_verifikasi`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'Nasi Kotak Ayam Bakar', 'Makanan Berat', 10, '2026-12-31', 'Nasi kotak ayam bakar dengan lauk lengkap dan higienis', NULL, 'Available', 'Disetujui', NULL, NULL, DATE_SUB(NOW(), INTERVAL 2 HOUR), DATE_SUB(NOW(), INTERVAL 2 HOUR)),
(2, 2, 1, 'Roti Sobek Cokelat Lumer', 'Cemilan / Snack', 10, '2026-12-31', 'Roti sobek isi cokelat lumer rasa lezat', NULL, 'Available', 'Disetujui', NULL, NULL, NOW(), NOW()),
(3, 2, 1, 'Susu UHT Ultra Milk', 'Minuman', 0, '2026-12-31', 'Susu UHT siap minum rasa cokelat', NULL, 'Distributed', 'Disetujui', NULL, NULL, DATE_SUB(NOW(), INTERVAL 1 HOUR), DATE_SUB(NOW(), INTERVAL 1 HOUR)),
(4, 5, 1, 'Nasi Bungkus Padang', 'Makanan Berat', 5, '2026-12-31', 'Nasi bungkus Padang lengkap dengan rendang dan sayur', NULL, 'Available', 'Disetujui', NULL, NULL, NOW(), NOW()),
(5, 5, 1, 'Biskuit Roma Kelapa', 'Cemilan / Snack', 5, '2026-12-31', 'Biskuit renyah kelapa kemasan higienis', NULL, 'Available', 'Disetujui', NULL, NULL, NOW(), NOW());

-- 4. Permintaans
INSERT INTO `permintaans` (`id_permintaan`, `id_user`, `id_donasi`, `jumlah_permintaan`, `catatan`, `status`, `tanggal_acc`, `tanggal_tolak`, `id_permintaan_parent`, `created_at`, `updated_at`) VALUES
(1, 4, 4, 2, NULL, 'Disetujui', NULL, NULL, NULL, NOW(), NOW()),
(2, 4, 5, 1, NULL, 'Pending', NULL, NULL, NULL, NOW(), NOW());

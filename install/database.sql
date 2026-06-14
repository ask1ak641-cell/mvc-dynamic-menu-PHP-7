-- ═══════════════════════════════════════════════════════════════
--  Database: mvc (sesuaikan dengan nama DB kamu)
--  Copy-paste ke tab SQL di phpMyAdmin setelah pilih database mvc
-- ═══════════════════════════════════════════════════════════════

-- ─────────────────────────────────────────────────────────────
--  Tabel: roles
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `roles` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(50) NOT NULL UNIQUE,
  `description` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────────────────────
--  Tabel: menus (dynamic menu management)
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `menus` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `parent_id` INT DEFAULT 0,
  `name` VARCHAR(100) NOT NULL,
  `url` VARCHAR(200) NOT NULL,
  `icon` VARCHAR(100) DEFAULT 'fas fa-circle',
  `sort_order` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────────────────────
--  Tabel: users
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `nama` VARCHAR(100) NULL,
  `email` VARCHAR(100) NULL UNIQUE,
  `role_id` INT NOT NULL DEFAULT 2,
  `is_active` TINYINT(1) DEFAULT 1,
  `reset_token` VARCHAR(100) NULL,
  `reset_expires` DATETIME NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────────────────────
--  Tabel: role_permissions (many-to-many)
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `role_permissions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `role_id` INT NOT NULL,
  `menu_id` INT NOT NULL,
  UNIQUE KEY `unique_role_menu` (`role_id`, `menu_id`),
  FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`menu_id`) REFERENCES `menus`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────────────────────
--  Tabel: siswa (contoh modul CRUD)
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `siswa` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nis` VARCHAR(20) NOT NULL,
  `nama` VARCHAR(100) NOT NULL,
  `jenis_kelamin` ENUM('L', 'P') NOT NULL,
  `tempat_lahir` VARCHAR(100) NULL,
  `tanggal_lahir` DATE NULL,
  `alamat` TEXT NULL,
  `no_telp` VARCHAR(20) NULL,
  `email` VARCHAR(100) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ═══════════════════════════════════════════════════════════════
--  SEED DATA AWAL
-- ═══════════════════════════════════════════════════════════════

INSERT INTO `roles` (`id`, `name`, `description`) VALUES
(1, 'admin', 'Administrator - Akses penuh'),
(2, 'operator', 'Operator - Akses terbatas'),
(3, 'user', 'User biasa - Akses minimal');

-- Password: admin123 / operator123 / user123 (BCRYPT)
INSERT INTO `users` (`username`, `password`, `nama`, `email`, `role_id`) VALUES
('admin', '$2y$12$LJ3m4ys3Gql.ZhkBARVODevRpSIMRgNp6VlC6qOqmAXB/6bFPQPVS', 'Administrator', 'admin@example.com', 1),
('operator', '$2y$12$onLdVN7dkElZ7WXr2dKQle5RF7r5iL/4w7FyRVvIblHf9RqHAk0ia', 'Operator', 'operator@example.com', 2),
('user', '$2y$12$Id5GtXFHSG0x7L1vrMjJkOgGTOpRnB0yFrKMMJRTY3rBn7nRqYPWK', 'User Biasa', 'user@example.com', 3);

INSERT INTO `menus` (`id`, `parent_id`, `name`, `url`, `icon`, `sort_order`) VALUES
(1, 0, 'Dashboard', '/dashboard', 'fas fa-tachometer-alt', 1),
(2, 0, 'Manajemen', '#', 'fas fa-cogs', 2),
(3, 2, 'Users', '/user', 'fas fa-users', 1),
(4, 2, 'Roles', '/role', 'fas fa-user-tag', 2),
(5, 2, 'Menu', '/menu', 'fas fa-bars', 3),
(6, 0, 'Data Master', '#', 'fas fa-database', 3),
(7, 6, 'Data Siswa', '/siswa', 'fas fa-user-graduate', 1);

INSERT INTO `role_permissions` (`role_id`, `menu_id`) VALUES
(1, 1), (1, 2), (1, 3), (1, 4), (1, 5), (1, 6), (1, 7);

INSERT INTO `role_permissions` (`role_id`, `menu_id`) VALUES
(2, 1), (2, 6), (2, 7);

INSERT INTO `role_permissions` (`role_id`, `menu_id`) VALUES
(3, 1);

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 11 Jun 2025 pada 11.20
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smart-gate`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `access_logs`
--

CREATE TABLE `access_logs` (
  `log_id` bigint(20) UNSIGNED NOT NULL,
  `tags_id` bigint(20) UNSIGNED NOT NULL,
  `accessed_at` timestamp NOT NULL DEFAULT '2025-04-19 21:26:28',
  `status` enum('Allowed','Denied') NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `access_logs`
--

INSERT INTO `access_logs` (`log_id`, `tags_id`, `accessed_at`, `status`, `note`, `created_at`, `updated_at`) VALUES
(1, 2, '2025-04-24 07:04:18', 'Allowed', 'Test', '2025-04-24 00:04:25', '2025-04-24 00:04:25'),
(2, 1, '2025-06-09 08:16:35', 'Allowed', 'Uji', '2025-06-09 08:16:42', '2025-06-09 08:16:42');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel_cache_livewire-rate-limiter:2981aafe4bb048afdc872e08219ec52ae1d2967e', 'i:1;', 1749460683),
('laravel_cache_livewire-rate-limiter:2981aafe4bb048afdc872e08219ec52ae1d2967e:timer', 'i:1749460683;', 1749460683),
('laravel_cache_livewire-rate-limiter:a03482d47b45acecfdd4da52d94a0986080d88b3', 'i:1;', 1749463198),
('laravel_cache_livewire-rate-limiter:a03482d47b45acecfdd4da52d94a0986080d88b3:timer', 'i:1749463198;', 1749463198),
('laravel_cache_livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3', 'i:1;', 1749463379),
('laravel_cache_livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3:timer', 'i:1749463379;', 1749463379);

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(8, '2025_04_19_045648_create_users_table', 3),
(10, '0001_01_01_000001_create_cache_table', 4),
(11, '0001_01_01_000002_create_jobs_table', 4),
(12, '2025_04_19_041321_create_officers_table', 4),
(13, '2025_04_19_045753_create_users_table', 5),
(14, '2025_04_19_062529_create_vehicles_table', 5),
(15, '2025_04_20_024633_create_rfid_tags_table', 6),
(16, '2025_04_20_041343_create_access_logs_table', 7);

-- --------------------------------------------------------

--
-- Struktur dari tabel `officers`
--

CREATE TABLE `officers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `officers`
--

INSERT INTO `officers` (`id`, `name`, `email`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Lord Officer', 'officer@gmail.com', '$2y$12$cC/VnzslQv0SViAGVbvZKedtClAuBzAw5RoM8YxDDH9PDaA4891O6', NULL, '2025-04-18 23:30:16', '2025-04-18 23:30:16'),
(2, 'Muhammad Dafa Putra', 'dafa@gmail.com', '$2y$12$IjMkXUQtlxbq7SnSh9WGGOaKWbQJGCIndk1ebKq9PslI0ippo2wLu', NULL, '2025-04-18 23:30:30', '2025-04-18 23:30:30'),
(3, 'joni', 'joni@gmail.com', '$2y$12$iTdXUNEYnVUWY7jML8peOeiJsZFG82JL4YFu8L8bP/p91E0yIEqXe', NULL, '2025-06-08 08:30:25', '2025-06-08 08:30:25');

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `rfid_tags`
--

CREATE TABLE `rfid_tags` (
  `tags_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `vehicles_id` bigint(20) UNSIGNED NOT NULL,
  `tag_uid` varchar(255) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `rfid_tags`
--

INSERT INTO `rfid_tags` (`tags_id`, `user_id`, `vehicles_id`, `tag_uid`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'YA2412', 'active', '2025-04-19 20:40:55', '2025-04-19 20:59:32'),
(2, 2, 1, 'AN12315ZKO', 'inactive', '2025-04-19 20:48:34', '2025-04-24 00:02:50'),
(3, 9, 4, 'B217ANGGWP', 'active', '2025-06-09 08:13:17', '2025-06-09 08:13:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('4ucNf0k5RQT1zY7wd2HAUjRpZ76uBts2dmsZn4NF', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoicko3cWFLSVBxc2d4WjhXT2FDYmVyN2xqN2w2Y2xrYVRRajFNY2xtTiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbiI7fXM6NTQ6ImxvZ2luX29mZmljZXJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO3M6MjE6InBhc3N3b3JkX2hhc2hfb2ZmaWNlciI7czo2MDoiJDJ5JDEyJElqTWtYVVF0bHhicTdTblNoOVdHR09hS1diUUpHQ0luZGsxZWJLcTlQc2xJMGlwcG8yd0x1Ijt9', 1749463356),
('JfNxsawaMySLDT41HtUvHZgIupRYxYvMgwH3Qcze', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQXB2cG93ZmFGaVFwaHNIVU9DNHVhSkt4MGhKUE8ySVZ3c0RaOGhMdyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7fX0=', 1749462994);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`user_id`, `email`, `name`, `password`, `address`, `phone_number`, `created_at`, `updated_at`) VALUES
(1, '', 'Muhammad Dafa Putra', '', 'Perum. Muka Kuning Indah 1', '083181531047', '2025-04-18 23:32:08', '2025-04-18 23:32:18'),
(2, '', 'Firjatullah Anang Setiawan', '', 'Batu Aji', '082391512347', '2025-04-18 23:32:30', '2025-04-19 00:06:01'),
(3, '', 'Andre Adyatmajaya', '', 'Batam Center', '085127512745', '2025-04-18 23:32:41', '2025-04-18 23:32:41'),
(4, '', 'Yunita Caroline', '', 'Batu Aji SP', '081329485726', '2025-04-18 23:51:35', '2025-04-21 19:50:07'),
(9, 'ujang12@gmail.com', 'ujang', '$2y$12$4ScWWHqpKdZlfCPAEZN0kemXIeeTOPBI3U1Agn/OI5hWN5wJJtPU2', 'Batu Aji', '083181531047', '2025-06-09 07:25:51', '2025-06-09 07:25:51'),
(10, 'dhaput@gmail.com', 'Dhaput', '$2y$12$RLA64BhtegL72COOliEQJe8tcijfCwghh89DwvQwca7MsZOjloZPe', 'Busan, Korea', '083181531047', '2025-06-09 09:17:03', '2025-06-09 09:17:03');

-- --------------------------------------------------------

--
-- Struktur dari tabel `vehicles`
--

CREATE TABLE `vehicles` (
  `vehicles_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `plate_number` varchar(255) NOT NULL,
  `vehicle_type` enum('Mobil','Motor') NOT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `vehicles`
--

INSERT INTO `vehicles` (`vehicles_id`, `user_id`, `plate_number`, `vehicle_type`, `brand`, `color`, `created_at`, `updated_at`) VALUES
(1, 2, 'BA 1231 ZZ', 'Motor', 'Mio Mirza', 'Putih', '2025-04-18 23:55:34', '2025-04-24 00:03:28'),
(2, 1, 'BA 2205 GG', 'Mobil', 'Mazda Miata', 'Merah', '2025-04-18 23:57:56', '2025-04-18 23:57:56'),
(4, 9, 'B217AN', 'Mobil', 'Alpard', 'Kuning', '2025-06-09 08:12:54', '2025-06-09 08:12:54');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `access_logs`
--
ALTER TABLE `access_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `access_logs_tags_id_foreign` (`tags_id`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `officers`
--
ALTER TABLE `officers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `officers_email_unique` (`email`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `rfid_tags`
--
ALTER TABLE `rfid_tags`
  ADD PRIMARY KEY (`tags_id`),
  ADD UNIQUE KEY `rfid_tags_tag_uid_unique` (`tag_uid`),
  ADD KEY `rfid_tags_user_id_foreign` (`user_id`),
  ADD KEY `rfid_tags_vehicles_id_foreign` (`vehicles_id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indeks untuk tabel `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`vehicles_id`),
  ADD UNIQUE KEY `vehicles_plate_number_unique` (`plate_number`),
  ADD KEY `vehicles_user_id_foreign` (`user_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `access_logs`
--
ALTER TABLE `access_logs`
  MODIFY `log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `officers`
--
ALTER TABLE `officers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `rfid_tags`
--
ALTER TABLE `rfid_tags`
  MODIFY `tags_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `user_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `vehicles_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `access_logs`
--
ALTER TABLE `access_logs`
  ADD CONSTRAINT `access_logs_tags_id_foreign` FOREIGN KEY (`tags_id`) REFERENCES `rfid_tags` (`tags_id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `rfid_tags`
--
ALTER TABLE `rfid_tags`
  ADD CONSTRAINT `rfid_tags_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rfid_tags_vehicles_id_foreign` FOREIGN KEY (`vehicles_id`) REFERENCES `vehicles` (`vehicles_id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `vehicles`
--
ALTER TABLE `vehicles`
  ADD CONSTRAINT `vehicles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

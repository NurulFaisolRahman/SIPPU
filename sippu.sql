-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 08 Sep 2026 pada 17.23
-- Versi server: 10.4.27-MariaDB
-- Versi PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sippu`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `akunadmin`
--

CREATE TABLE `akunadmin` (
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `InputAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdateAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `DeleteAt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `akunadmin`
--

INSERT INTO `akunadmin` (`Username`, `Password`, `InputAt`, `UpdateAt`, `DeleteAt`) VALUES
('admin', '$2y$10$31BUT8ggyd.Brh8jhOXXC.lA8v.nUpX5/0117bTG3t1DRE.q5o.Be', '2026-07-11 13:53:09', '2026-07-11 15:43:11', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `datainvestasi`
--

CREATE TABLE `datainvestasi` (
  `id` int(11) NOT NULL,
  `id_profil` int(11) NOT NULL COMMENT 'Relasi ke ProfilUsaha',
  `Tahun` year(4) NOT NULL,
  `JenisInvestasi` enum('PMDN','PMA') NOT NULL DEFAULT 'PMDN',
  `NilaiInvestasi` bigint(20) NOT NULL DEFAULT 0,
  `TenagaKerjaLokal` int(11) NOT NULL DEFAULT 0,
  `TenagaKerjaAsing` int(11) NOT NULL DEFAULT 0,
  `InputAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `DeleteAt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `datainvestasi`
--

INSERT INTO `datainvestasi` (`id`, `id_profil`, `Tahun`, `JenisInvestasi`, `NilaiInvestasi`, `TenagaKerjaLokal`, `TenagaKerjaAsing`, `InputAt`, `UpdatedAt`, `DeleteAt`) VALUES
(4, 8, 2026, 'PMDN', 960000000, 7, 15, '2026-08-25 07:05:34', '2026-09-08 03:42:47', NULL),
(5, 7, 2026, 'PMA', 700000000, 15, 7, '2026-09-08 03:44:21', '2026-09-08 09:06:26', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `distrik`
--

CREATE TABLE `distrik` (
  `id` varchar(15) NOT NULL COMMENT 'Kode Wilayah Kemendagri',
  `NamaDistrik` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `distrik`
--

INSERT INTO `distrik` (`id`, `NamaDistrik`) VALUES
('94.04.01', 'Mimika Baru'),
('94.04.02', 'Agimuga'),
('94.04.03', 'Mimika Timur'),
('94.04.04', 'Mimika Barat'),
('94.04.05', 'Jita'),
('94.04.06', 'Jila'),
('94.04.07', 'Mimika Timur Jauh'),
('94.04.08', 'Mimika Tengah'),
('94.04.09', 'Kuala Kencana'),
('94.04.10', 'Tembagapura'),
('94.04.11', 'Mimika Barat Jauh'),
('94.04.12', 'Mimika Barat Tengah'),
('94.04.13', 'Kwamki Narama'),
('94.04.14', 'Hoya'),
('94.04.15', 'Alama'),
('94.04.16', 'Wania'),
('94.04.17', 'Amar'),
('94.04.18', 'Iwaka');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jenisizin`
--

CREATE TABLE `jenisizin` (
  `id` int(11) NOT NULL,
  `JenisIzin` varchar(255) NOT NULL,
  `InputAt` datetime DEFAULT current_timestamp(),
  `UpdatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `DeleteAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jenisizin`
--

INSERT INTO `jenisizin` (`id`, `JenisIzin`, `InputAt`, `UpdatedAt`, `DeleteAt`) VALUES
(1, 'Surat Izin Spikolog Klinis (SIPPK)', '2026-09-07 10:05:30', '2026-09-07 10:05:55', NULL),
(2, 'Surat Izin Penyelenggaraan Optik (SIPO)', '2026-09-07 10:06:26', '2026-09-07 15:06:26', NULL),
(3, 'Surat Izin Dokter Gigi (SIPDGI)', '2026-09-07 10:07:07', '2026-09-07 11:11:59', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `opd`
--

CREATE TABLE `opd` (
  `id` int(11) NOT NULL,
  `NamaOpd` varchar(255) NOT NULL,
  `InputAt` datetime DEFAULT current_timestamp(),
  `UpdatedAt` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `DeleteAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `opd`
--

INSERT INTO `opd` (`id`, `NamaOpd`, `InputAt`, `UpdatedAt`, `DeleteAt`) VALUES
(1, 'Badan Narkotika Nasional', '2026-09-08 16:22:53', '2026-09-08 21:33:28', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelayananmpp`
--

CREATE TABLE `pelayananmpp` (
  `id` int(11) NOT NULL,
  `id_opd` int(11) NOT NULL,
  `NamaPelayanan` varchar(255) NOT NULL,
  `InputAt` datetime DEFAULT current_timestamp(),
  `UpdatedAt` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `DeleteAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pelayananmpp`
--

INSERT INTO `pelayananmpp` (`id`, `id_opd`, `NamaPelayanan`, `InputAt`, `UpdatedAt`, `DeleteAt`) VALUES
(1, 1, 'Jumlah Pengunjung Loket', '2026-09-08 16:24:15', '2026-09-08 16:52:03', NULL),
(2, 1, 'Surat Keterangan Hasil Pemeriksaan Narkotika (SKHPN)', '2026-09-08 16:25:58', '2026-09-08 16:52:03', NULL),
(3, 1, 'Konseling Tentang Bahaya Narkoba', '2026-09-08 16:26:57', '2026-09-08 21:52:58', NULL),
(4, 1, 'Layanan Lainnya', '2026-09-08 16:26:57', '2026-09-08 16:52:03', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `profilusaha`
--

CREATE TABLE `profilusaha` (
  `id` int(11) NOT NULL,
  `NamaUsaha` varchar(255) NOT NULL,
  `NIB` varchar(50) NOT NULL,
  `NamaPemilik` varchar(150) NOT NULL,
  `SektorUsaha` varchar(100) NOT NULL,
  `Alamat` text NOT NULL,
  `Tahun` int(4) NOT NULL,
  `id_distrik` varchar(15) NOT NULL COMMENT 'Relasi ke tabel Distrik',
  `InputAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdateAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `DeleteAt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `profilusaha`
--

INSERT INTO `profilusaha` (`id`, `NamaUsaha`, `NIB`, `NamaPemilik`, `SektorUsaha`, `Alamat`, `Tahun`, `id_distrik`, `InputAt`, `UpdateAt`, `DeleteAt`) VALUES
(7, 'PT Izanamy', '1092837465996', 'Jygen', 'Industri Pengolahan', 'Jalan Raya Agimuga No. 7', 2026, '94.04.02', '2026-09-08 07:19:10', '2026-09-08 09:06:35', NULL),
(8, 'PT Izanagy', '1092837465915', 'Jiren', 'Konstruksi', 'Jalan Raya Jita No 15', 2026, '94.04.05', '2026-09-08 08:03:48', '2026-09-08 09:02:51', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `rekapizinbulan`
--

CREATE TABLE `rekapizinbulan` (
  `id` int(11) NOT NULL,
  `id_jenis_izin` int(11) NOT NULL,
  `Bulan` tinyint(2) NOT NULL COMMENT '1 = Januari, 12 = Desember',
  `Tahun` int(4) NOT NULL COMMENT 'Format 4 Digit (Harus > 2015)',
  `Jumlah` int(11) NOT NULL DEFAULT 0,
  `InputAt` datetime DEFAULT current_timestamp(),
  `UpdatedAt` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `DeleteAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `rekapizinbulan`
--

INSERT INTO `rekapizinbulan` (`id`, `id_jenis_izin`, `Bulan`, `Tahun`, `Jumlah`, `InputAt`, `UpdatedAt`, `DeleteAt`) VALUES
(1, 1, 1, 2026, 0, '2026-09-07 10:05:30', '2026-09-07 10:05:55', NULL),
(2, 1, 2, 2026, 0, '2026-09-07 10:05:30', '2026-09-07 10:05:55', NULL),
(3, 1, 3, 2026, 0, '2026-09-07 10:05:30', '2026-09-07 10:05:55', NULL),
(4, 1, 4, 2026, 0, '2026-09-07 10:05:30', '2026-09-07 10:05:55', NULL),
(5, 1, 5, 2026, 0, '2026-09-07 10:05:30', '2026-09-07 10:05:55', NULL),
(6, 1, 6, 2026, 0, '2026-09-07 10:05:30', '2026-09-07 10:05:55', NULL),
(7, 1, 7, 2026, 0, '2026-09-07 10:05:30', '2026-09-07 10:05:55', NULL),
(8, 1, 8, 2026, 0, '2026-09-07 10:05:30', '2026-09-07 10:05:55', NULL),
(9, 1, 9, 2026, 0, '2026-09-07 10:05:30', '2026-09-07 10:05:55', NULL),
(10, 1, 10, 2026, 0, '2026-09-07 10:05:30', '2026-09-07 10:05:55', NULL),
(11, 1, 11, 2026, 0, '2026-09-07 10:05:30', '2026-09-07 10:05:55', NULL),
(12, 1, 12, 2026, 0, '2026-09-07 10:05:30', '2026-09-07 10:05:55', NULL),
(13, 2, 1, 2026, 0, '2026-09-07 10:06:26', '2026-09-07 15:06:26', NULL),
(14, 2, 2, 2026, 0, '2026-09-07 10:06:26', '2026-09-07 15:06:26', NULL),
(15, 2, 3, 2026, 0, '2026-09-07 10:06:26', '2026-09-07 15:06:26', NULL),
(16, 2, 4, 2026, 0, '2026-09-07 10:06:26', '2026-09-07 15:06:26', NULL),
(17, 2, 5, 2026, 0, '2026-09-07 10:06:26', '2026-09-07 15:06:26', NULL),
(18, 2, 6, 2026, 0, '2026-09-07 10:06:26', '2026-09-07 15:06:26', NULL),
(19, 2, 7, 2026, 0, '2026-09-07 10:06:26', '2026-09-07 15:06:26', NULL),
(20, 2, 8, 2026, 0, '2026-09-07 10:06:26', '2026-09-07 15:06:26', NULL),
(21, 2, 9, 2026, 0, '2026-09-07 10:06:26', '2026-09-07 15:06:26', NULL),
(22, 2, 10, 2026, 0, '2026-09-07 10:06:26', '2026-09-07 15:06:26', NULL),
(23, 2, 11, 2026, 0, '2026-09-07 10:06:26', '2026-09-07 15:06:26', NULL),
(24, 2, 12, 2026, 0, '2026-09-07 10:06:26', '2026-09-07 15:06:26', NULL),
(25, 3, 1, 2026, 5, '2026-09-07 10:07:07', '2026-09-07 11:11:59', NULL),
(26, 3, 2, 2026, 4, '2026-09-07 10:07:07', '2026-09-07 11:11:59', NULL),
(27, 3, 3, 2026, 0, '2026-09-07 10:07:07', '2026-09-07 11:11:59', NULL),
(28, 3, 4, 2026, 2, '2026-09-07 10:07:07', '2026-09-07 11:11:59', NULL),
(29, 3, 5, 2026, 0, '2026-09-07 10:07:07', '2026-09-07 11:11:59', NULL),
(30, 3, 6, 2026, 10, '2026-09-07 10:07:07', '2026-09-07 11:11:59', NULL),
(31, 3, 7, 2026, 4, '2026-09-07 10:07:07', '2026-09-07 11:11:59', NULL),
(32, 3, 8, 2026, 0, '2026-09-07 10:07:07', '2026-09-07 11:11:59', NULL),
(33, 3, 9, 2026, 0, '2026-09-07 10:07:07', '2026-09-07 11:11:59', NULL),
(34, 3, 10, 2026, 0, '2026-09-07 10:07:07', '2026-09-07 11:11:59', NULL),
(35, 3, 11, 2026, 0, '2026-09-07 10:07:07', '2026-09-07 11:11:59', NULL),
(36, 3, 12, 2026, 0, '2026-09-07 10:07:07', '2026-09-07 11:11:59', NULL),
(37, 3, 1, 2025, 1, '2026-09-07 10:07:50', '2026-09-07 16:11:18', '2026-09-07 11:11:18'),
(38, 3, 2, 2025, 2, '2026-09-07 10:07:50', '2026-09-07 16:11:18', '2026-09-07 11:11:18'),
(39, 3, 3, 2025, 3, '2026-09-07 10:07:50', '2026-09-07 16:11:18', '2026-09-07 11:11:18'),
(40, 3, 4, 2025, 4, '2026-09-07 10:07:50', '2026-09-07 16:11:18', '2026-09-07 11:11:18'),
(41, 3, 5, 2025, 5, '2026-09-07 10:07:50', '2026-09-07 16:11:18', '2026-09-07 11:11:18'),
(42, 3, 6, 2025, 6, '2026-09-07 10:07:50', '2026-09-07 16:11:18', '2026-09-07 11:11:18'),
(43, 3, 7, 2025, 0, '2026-09-07 10:07:50', '2026-09-07 16:11:18', '2026-09-07 11:11:18'),
(44, 3, 8, 2025, 0, '2026-09-07 10:07:50', '2026-09-07 16:11:18', '2026-09-07 11:11:18'),
(45, 3, 9, 2025, 0, '2026-09-07 10:07:50', '2026-09-07 16:11:18', '2026-09-07 11:11:18'),
(46, 3, 10, 2025, 0, '2026-09-07 10:07:50', '2026-09-07 16:11:18', '2026-09-07 11:11:18'),
(47, 3, 11, 2025, 0, '2026-09-07 10:07:50', '2026-09-07 16:11:18', '2026-09-07 11:11:18'),
(48, 3, 12, 2025, 0, '2026-09-07 10:07:50', '2026-09-07 16:11:18', '2026-09-07 11:11:18');

-- --------------------------------------------------------

--
-- Struktur dari tabel `rekapmppbulan`
--

CREATE TABLE `rekapmppbulan` (
  `id` int(11) NOT NULL,
  `id_pelayanan` int(11) NOT NULL,
  `Bulan` tinyint(2) NOT NULL COMMENT '1 = Jan, 12 = Des',
  `Tahun` year(4) NOT NULL,
  `Jumlah` int(11) DEFAULT 0,
  `InputAt` datetime DEFAULT current_timestamp(),
  `UpdatedAt` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `DeleteAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `rekapmppbulan`
--

INSERT INTO `rekapmppbulan` (`id`, `id_pelayanan`, `Bulan`, `Tahun`, `Jumlah`, `InputAt`, `UpdatedAt`, `DeleteAt`) VALUES
(1, 1, 1, 2026, 0, '2026-09-08 16:24:15', '2026-09-08 16:52:03', NULL),
(2, 1, 2, 2026, 43, '2026-09-08 16:24:15', '2026-09-08 16:52:03', NULL),
(3, 1, 3, 2026, 17, '2026-09-08 16:24:15', '2026-09-08 16:52:03', NULL),
(4, 1, 4, 2026, 21, '2026-09-08 16:24:15', '2026-09-08 16:52:03', NULL),
(5, 1, 5, 2026, 29, '2026-09-08 16:24:15', '2026-09-08 16:52:03', NULL),
(6, 1, 6, 2026, 0, '2026-09-08 16:24:15', '2026-09-08 16:52:03', NULL),
(7, 1, 7, 2026, 0, '2026-09-08 16:24:15', '2026-09-08 16:52:03', NULL),
(8, 1, 8, 2026, 0, '2026-09-08 16:24:15', '2026-09-08 16:52:03', NULL),
(9, 1, 9, 2026, 0, '2026-09-08 16:24:15', '2026-09-08 16:52:03', NULL),
(10, 1, 10, 2026, 0, '2026-09-08 16:24:15', '2026-09-08 16:52:03', NULL),
(11, 1, 11, 2026, 0, '2026-09-08 16:24:15', '2026-09-08 16:52:03', NULL),
(12, 1, 12, 2026, 0, '2026-09-08 16:24:15', '2026-09-08 16:52:03', NULL),
(13, 2, 1, 2026, 43, '2026-09-08 16:25:58', '2026-09-08 16:52:03', NULL),
(14, 2, 2, 2026, 43, '2026-09-08 16:25:58', '2026-09-08 16:52:03', NULL),
(15, 2, 3, 2026, 17, '2026-09-08 16:25:58', '2026-09-08 16:52:03', NULL),
(16, 2, 4, 2026, 19, '2026-09-08 16:25:58', '2026-09-08 16:52:03', NULL),
(17, 2, 5, 2026, 13, '2026-09-08 16:25:58', '2026-09-08 16:52:03', NULL),
(18, 2, 6, 2026, 0, '2026-09-08 16:25:58', '2026-09-08 16:52:03', NULL),
(19, 2, 7, 2026, 0, '2026-09-08 16:25:58', '2026-09-08 16:52:03', NULL),
(20, 2, 8, 2026, 0, '2026-09-08 16:25:58', '2026-09-08 16:52:03', NULL),
(21, 2, 9, 2026, 0, '2026-09-08 16:25:58', '2026-09-08 16:52:03', NULL),
(22, 2, 10, 2026, 0, '2026-09-08 16:25:58', '2026-09-08 16:52:03', NULL),
(23, 2, 11, 2026, 0, '2026-09-08 16:25:58', '2026-09-08 16:52:03', NULL),
(24, 2, 12, 2026, 0, '2026-09-08 16:25:58', '2026-09-08 16:52:03', NULL),
(25, 3, 1, 2026, 0, '2026-09-08 16:26:57', '2026-09-08 21:52:51', NULL),
(26, 3, 2, 2026, 0, '2026-09-08 16:26:57', '2026-09-08 21:52:51', NULL),
(27, 3, 3, 2026, 0, '2026-09-08 16:26:57', '2026-09-08 21:52:51', NULL),
(28, 3, 4, 2026, 2, '2026-09-08 16:26:57', '2026-09-08 21:52:51', NULL),
(29, 3, 5, 2026, 8, '2026-09-08 16:26:57', '2026-09-08 21:52:51', NULL),
(30, 3, 6, 2026, 0, '2026-09-08 16:26:57', '2026-09-08 21:52:51', NULL),
(31, 3, 7, 2026, 0, '2026-09-08 16:26:57', '2026-09-08 21:52:51', NULL),
(32, 3, 8, 2026, 0, '2026-09-08 16:26:57', '2026-09-08 21:52:51', NULL),
(33, 3, 9, 2026, 0, '2026-09-08 16:26:57', '2026-09-08 21:52:51', NULL),
(34, 3, 10, 2026, 0, '2026-09-08 16:26:57', '2026-09-08 21:52:51', NULL),
(35, 3, 11, 2026, 0, '2026-09-08 16:26:57', '2026-09-08 21:52:51', NULL),
(36, 3, 12, 2026, 0, '2026-09-08 16:26:57', '2026-09-08 21:52:51', NULL),
(37, 4, 1, 2026, 1, '2026-09-08 16:26:57', '2026-09-08 16:52:03', NULL),
(38, 4, 2, 2026, 0, '2026-09-08 16:26:57', '2026-09-08 16:52:03', NULL),
(39, 4, 3, 2026, 0, '2026-09-08 16:26:57', '2026-09-08 16:52:03', NULL),
(40, 4, 4, 2026, 0, '2026-09-08 16:26:57', '2026-09-08 16:52:03', NULL),
(41, 4, 5, 2026, 8, '2026-09-08 16:26:57', '2026-09-08 16:52:03', NULL),
(42, 4, 6, 2026, 0, '2026-09-08 16:26:57', '2026-09-08 16:52:03', NULL),
(43, 4, 7, 2026, 0, '2026-09-08 16:26:57', '2026-09-08 16:52:03', NULL),
(44, 4, 8, 2026, 0, '2026-09-08 16:26:57', '2026-09-08 16:52:03', NULL),
(45, 4, 9, 2026, 0, '2026-09-08 16:26:57', '2026-09-08 16:52:03', NULL),
(46, 4, 10, 2026, 0, '2026-09-08 16:26:57', '2026-09-08 16:52:03', NULL),
(47, 4, 11, 2026, 0, '2026-09-08 16:26:57', '2026-09-08 16:52:03', NULL),
(48, 4, 12, 2026, 0, '2026-09-08 16:26:57', '2026-09-08 16:52:03', NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `akunadmin`
--
ALTER TABLE `akunadmin`
  ADD PRIMARY KEY (`Username`);

--
-- Indeks untuk tabel `datainvestasi`
--
ALTER TABLE `datainvestasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_datainvestasi_profilusaha` (`id_profil`);

--
-- Indeks untuk tabel `distrik`
--
ALTER TABLE `distrik`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jenisizin`
--
ALTER TABLE `jenisizin`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `opd`
--
ALTER TABLE `opd`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pelayananmpp`
--
ALTER TABLE `pelayananmpp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pelayanan_opd` (`id_opd`);

--
-- Indeks untuk tabel `profilusaha`
--
ALTER TABLE `profilusaha`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `rekapizinbulan`
--
ALTER TABLE `rekapizinbulan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_jenis_izin` (`id_jenis_izin`),
  ADD KEY `idx_tahun_bulan` (`Tahun`,`Bulan`);

--
-- Indeks untuk tabel `rekapmppbulan`
--
ALTER TABLE `rekapmppbulan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_pelayanan_bulan_tahun` (`id_pelayanan`,`Bulan`,`Tahun`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `datainvestasi`
--
ALTER TABLE `datainvestasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `jenisizin`
--
ALTER TABLE `jenisizin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `opd`
--
ALTER TABLE `opd`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `pelayananmpp`
--
ALTER TABLE `pelayananmpp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `profilusaha`
--
ALTER TABLE `profilusaha`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `rekapizinbulan`
--
ALTER TABLE `rekapizinbulan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT untuk tabel `rekapmppbulan`
--
ALTER TABLE `rekapmppbulan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `datainvestasi`
--
ALTER TABLE `datainvestasi`
  ADD CONSTRAINT `fk_datainvestasi_profilusaha` FOREIGN KEY (`id_profil`) REFERENCES `profilusaha` (`id`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pelayananmpp`
--
ALTER TABLE `pelayananmpp`
  ADD CONSTRAINT `fk_pelayanan_opd` FOREIGN KEY (`id_opd`) REFERENCES `opd` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `rekapizinbulan`
--
ALTER TABLE `rekapizinbulan`
  ADD CONSTRAINT `fk_rekap_jenis_izin` FOREIGN KEY (`id_jenis_izin`) REFERENCES `jenisizin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `rekapmppbulan`
--
ALTER TABLE `rekapmppbulan`
  ADD CONSTRAINT `fk_rekap_pelayanan` FOREIGN KEY (`id_pelayanan`) REFERENCES `pelayananmpp` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 25 Agu 2026 pada 14.07
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
  `UpdateAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `DeleteAt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `datainvestasi`
--

INSERT INTO `datainvestasi` (`id`, `id_profil`, `Tahun`, `JenisInvestasi`, `NilaiInvestasi`, `TenagaKerjaLokal`, `TenagaKerjaAsing`, `InputAt`, `UpdateAt`, `DeleteAt`) VALUES
(1, 1, 2025, 'PMDN', 15000000000, 120, 0, '2026-08-25 11:59:54', '2026-08-25 11:59:54', NULL),
(2, 2, 2025, 'PMA', 500000000000, 450, 25, '2026-08-25 11:59:54', '2026-08-25 11:59:54', NULL),
(3, 1, 2026, 'PMDN', 5000000000, 30, 0, '2026-08-25 11:59:54', '2026-08-25 11:59:54', NULL),
(4, 6, 2026, 'PMA', 9600000000, 15, 7, '2026-08-25 07:05:34', '2026-08-25 07:06:45', NULL);

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
  `InputAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdateAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `DeleteAt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jenisizin`
--

INSERT INTO `jenisizin` (`id`, `JenisIzin`, `InputAt`, `UpdateAt`, `DeleteAt`) VALUES
(1, 'Surat Izin Psikolog Klinis (SIPPK)', '2026-07-11 13:43:20', '2026-07-11 16:35:02', NULL),
(2, 'Surat Izin Penyelenggaraan Optik (SIPO)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(3, 'Surat Izin Dokter Gigi (SIPDGI)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(4, 'Surat Izin Kerja Dokter Interensif (SIPDI)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(5, 'Surat Izin Kerja Perawat Anastesi (SIKPA)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(6, 'Surat Izin Operasional Klinik (SIOK)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(7, 'Surat Izin Pengendalian Vektor (IPV)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(8, 'Surat Izin Operasional Rumah Sakit (SIO-RS)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(9, 'Surat Izin Pengendalian Hama (PEST CONTROL)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(10, 'Surat Izin Praktik Ahli Teknologi Laboratorium Medik (SIP-ATLM)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(11, 'Surat Izin Praktek Dokter (SIPD)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(12, 'Surat Izin Praktek Dokter Gigi Mandiri (SIPDGM)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(13, 'Surat Izin Praktek Dokter Mandiri (SIPDM)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(14, 'Surat Izin Kerja Ahli Kesehatan Masyarakat (SIKAKM)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(15, 'Surat Izin Kerja Asisten Entomolog Kesehatan (SIKAEK)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(16, 'Surat Izin Praktek Tenaga Teknik Kefarmasian (SIPTTK)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(17, 'Surat Izin Praktek Mandiri Bidan (SIPMB)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(18, 'Surat Izin Apotik (SIA) by OSS-RBA', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(19, 'Surat Izin Kerja Bidan (SIKB) / Praktek Bidan (SIPB)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(20, 'Surat Izin Kerja Fisioterapi (SIKF)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(21, 'Surat Izin Kerja Perawat Gigi (SIKPG)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(22, 'Surat Izin Kerja Perawat (SIKP) / Surat Izin Praktik Perawat (SIPP)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(23, 'Surat Izin Kerja Radiodiagnotik Dan Radioterapi (SIKRR)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(24, 'Surat Izin Kerja Radiologi (SIKR)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(25, 'Surat Izin Kerja Refraksionis Optien (SIKRO)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(26, 'Surat Izin Kerja Rekam Medis Dan Informasi Kesehatan (SIKRMIK)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(27, 'Surat Izin Kerja Teknisi Transfusi Darah', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(28, 'Surat Izin Kerja Tenaga Analis (SIKTA)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(29, 'Surat Izin Kerja Tenaga Gizi (SIKTGZ)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(30, 'Surat Izin Kerja Tenaga Sanitarian (SIKTS)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(31, 'Surat Izin Kerja Terapis Gigi Dan Mulut (SIKTGM)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(32, 'Surat Izin Praktek Mandiri Terapis Gigi Dan Mulut (SIPMTGM)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(33, 'Surat Izin Praktek Dokter Spesialis (SIPDS)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(34, 'Surat Izin Ambulan Evakuasi Dan Repatriasi Pasien Maupun Jenazah (SIAER)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(35, 'Surat Izin Praktek Penata Anastesi (SIPPA)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(36, 'Surat Izin Praktek Apoteker (SIPA)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(37, 'Surat Izin Praktek Elektro Medis (SIP-E)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(38, 'Surat Izin Praktek Laboratorium (SIL)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(39, 'Surat Izin Praktek Psikologi (SIPP)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(40, 'Surat Izin Toko Obat', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(41, 'Surat Izin Toko Alat Kesehatan (SITAK)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(42, 'Surat Izin Operasional Puskesmas (SIOP)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(43, 'Surat Izin Praktek Dokter Hewan (SIPDRH)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(44, 'Surat Izin Persetujuan Kesesuaian Kegiatan Pemanfaatan Ruang (PKKPR)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(45, 'Surat Izin Usaha Perdagangan (SIUP)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(46, 'Surat Izin Jasa Konstruksi (SIUJK)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(47, 'Surat Izin Penangkapan Ikan (SUIP-K)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(48, 'Surat Izin Tanda Daftar Kapal (TDK) / BPKP', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(49, 'Surat Izin Upaya Pengelolaan Lingkungan Dan Upaya Pemantauan Lingkungan (UKL-UPL) / Persetujuan Lingkungan', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(50, 'Surat Izin Pernyataan Pengelolaan Lingkungan (SPPL)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(51, 'Surat Izin Usaha Perdagangan - Peternakan (SIUP - PETERNAKAN)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(52, 'Surat Izin Perdagangan - Minuman Beralkohol (SIUP - MB)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(53, 'Surat Izin Khusus Tempat Usaha - Minuman Beralkohol (SIKTP - MB)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(54, 'Surat Izin Pembuangan Limbah (IPAL)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(55, 'Persetujuan Bangunan Gedung (PBG)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(56, 'Surat Izin Limbah Bahan Berbahaya Dan Beracun (ITPS LB3)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(57, 'Surat Izin Trayek', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(58, 'Surat Rekomendasi Antar Pulau', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(59, 'Surat Izin Reklame', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(60, 'Surat Izin LPCL (LIMBAH CAIR)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(61, 'Nomor Induk Berusaha (NIB) BY ONLINE SINGLE SUBMISSION RISK BASED APPROACH (OSS-RBA)', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(62, 'Surat Izin Tanda Daftar Industri (TDI) BY OSS-RBA', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(63, 'Surat Izin Pangan Industri Rumah Tangga (PIRT) BY OSS-RBA', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(64, 'Surat Izin Tanda Daftar Gudang (TDG) BY OSS-RBA', '2026-07-11 13:43:20', '2026-07-11 13:43:20', NULL),
(65, 'Surat Izin Persetujuan Bangunan Gedung (PBG)', '2026-07-11 13:43:20', '2026-07-11 11:53:31', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `profilusaha`
--

CREATE TABLE `profilusaha` (
  `id` int(11) NOT NULL,
  `NamaUsaha` varchar(255) NOT NULL,
  `NIB` varchar(50) NOT NULL,
  `NamaPemilik` varchar(150) NOT NULL,
  `Alamat` text NOT NULL,
  `id_distrik` varchar(15) NOT NULL COMMENT 'Relasi ke tabel Distrik',
  `id_sektor` int(11) NOT NULL COMMENT 'Relasi ke tabel SektorUsaha',
  `InputAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdateAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `DeleteAt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `profilusaha`
--

INSERT INTO `profilusaha` (`id`, `NamaUsaha`, `NIB`, `NamaPemilik`, `Alamat`, `id_distrik`, `id_sektor`, `InputAt`, `UpdateAt`, `DeleteAt`) VALUES
(1, 'PT. Mimika Jaya Abadi', '9120391238471', 'Budi Santoso', 'Jl. Budi Utomo Ujung, Kelurahan Kwamki', '94.04.01', 1, '2026-08-25 11:47:29', '2026-08-25 11:47:29', NULL),
(2, 'CV. Tambang Nusantara', '1829304918231', 'Yohanes Kogoya', 'Kompleks Ruko Sentra Timika Jaya', '94.04.09', 2, '2026-08-25 11:47:29', '2026-08-25 11:47:29', NULL),
(3, 'Koperasi Amungme Makmur', '5719283749102', 'Yohana', 'Jl. Cenderawasih SP 2, Timika', '94.04.01', 3, '2026-08-25 11:47:29', '2026-08-25 11:47:29', NULL),
(4, 'PT. Konstruksi Kuala Kencana', '3718293048571', 'Andi Wirawan', 'Blok A3 Perumahan Kuala Kencana', '94.04.09', 4, '2026-08-25 11:47:29', '2026-08-25 11:47:29', NULL),
(5, 'Rumah Makan Cita Rasa Papua', '1092837465920', 'Hj. Siti Aminah', 'Pasar Sentral Lama Timika', '94.04.01', 6, '2026-08-25 11:47:29', '2026-08-25 11:47:29', NULL),
(6, 'PT Izanamy', '1092837465996', 'Jygen', 'Jalan Raya Jita No. 7', '94.04.05', 8, '2026-08-25 06:49:31', '2026-08-25 11:51:35', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sektorusaha`
--

CREATE TABLE `sektorusaha` (
  `id` int(11) NOT NULL,
  `NamaSektor` varchar(150) NOT NULL,
  `InputAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdateAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `DeleteAt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sektorusaha`
--

INSERT INTO `sektorusaha` (`id`, `NamaSektor`, `InputAt`, `UpdateAt`, `DeleteAt`) VALUES
(1, 'Perdagangan & Reparasi', '2026-08-24 08:51:58', '2026-08-24 08:51:58', NULL),
(2, 'Pertambangan & Penggalian', '2026-08-24 08:51:58', '2026-08-24 08:51:58', NULL),
(3, 'Industri Pengolahan', '2026-08-24 08:51:58', '2026-08-24 08:51:58', NULL),
(4, 'Konstruksi & Infrastruktur', '2026-08-24 08:51:58', '2026-08-24 08:51:58', NULL),
(5, 'Jasa Pendidikan & Kesehatan', '2026-08-24 08:51:58', '2026-08-24 08:51:58', NULL),
(6, 'Akomodasi & Makanan Minuman (F&B)', '2026-08-24 08:51:58', '2026-08-24 08:51:58', NULL),
(7, 'Pertanian, Kehutanan & Perikanan', '2026-08-24 08:51:58', '2026-08-24 08:51:58', NULL),
(8, 'Pertambangan & Konstruksi', '2026-08-24 04:07:12', '2026-08-24 09:08:52', NULL);

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
  ADD PRIMARY KEY (`id`);

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
-- Indeks untuk tabel `profilusaha`
--
ALTER TABLE `profilusaha`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sektorusaha`
--
ALTER TABLE `sektorusaha`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `datainvestasi`
--
ALTER TABLE `datainvestasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `jenisizin`
--
ALTER TABLE `jenisizin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT untuk tabel `profilusaha`
--
ALTER TABLE `profilusaha`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `sektorusaha`
--
ALTER TABLE `sektorusaha`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

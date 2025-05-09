-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 09 Bulan Mei 2025 pada 19.46
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kerjapraktek`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `buku_tanah`
--

CREATE TABLE `buku_tanah` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `kecamatan` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `kecamatan_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `buku_tanah`
--

INSERT INTO `buku_tanah` (`id`, `nama`, `kecamatan`, `deskripsi`, `file_path`, `created_at`, `kecamatan_id`) VALUES
(9, 'M-1886-DMG', 'Demangan', '', 'uploads/110-Article Text-434-1-10-20161209.pdf', '2025-02-18 16:02:51', NULL),
(13, 'bayu', 'Umbulharjo', 'BISA', 'uploads/cheatsheet prak iot.pdf', '2025-02-18 16:45:15', NULL),
(14, 'kraton', 'Demangan', '', 'uploads/cheatsheet prak iot.pdf', '2025-02-18 17:23:25', NULL),
(17, 'rio', 'Semaki', '', 'uploads/PERKEMBANGAN_TEKNOLOGI_5G.pdf', '2025-02-19 11:49:13', NULL),
(18, 'M-684-MJM', 'Muja-muju', '', 'uploads/A20250220_09325637.pdf', '2025-02-20 03:28:46', NULL),
(19, 'contoh verponding', 'Demangan', '', 'uploads/A20250220_09502619.pdf', '2025-02-20 05:45:20', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kecamatan`
--

CREATE TABLE `kecamatan` (
  `id` int(11) NOT NULL,
  `nama_kecamatan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kecamatan`
--

INSERT INTO `kecamatan` (`id`, `nama_kecamatan`) VALUES
(1, 'Demangan'),
(2, 'Umbulharjo'),
(4, 'Semaki'),
(6, 'Muja-muju');

-- --------------------------------------------------------

--
-- Struktur dari tabel `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id` int(11) NOT NULL,
  `tanggal_hari` varchar(255) NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `nama_peminjam` varchar(255) NOT NULL,
  `jenis_hak` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `file_keterangan` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `tanda_tangan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `peminjaman`
--

INSERT INTO `peminjaman` (`id`, `tanggal_hari`, `tanggal_pinjam`, `tanggal_kembali`, `nama_peminjam`, `jenis_hak`, `status`, `keterangan`, `file_keterangan`, `created_at`, `tanda_tangan`) VALUES
(29, 'Jumat, 28 Februari 2025', '2025-02-28', NULL, 'Rio', 'M-1234-ABC', 'Dipinjam', '', '', '2025-02-28 03:50:55', 'uploads/ttd_1740714655.png'),
(30, 'Selasa, 4 Maret 2025', '2025-03-04', '2025-03-03', 'mas bagus', 'M-1234-ABC', 'Dikembalikan', '', '', '2025-03-03 22:06:47', 'uploads/ttd_1741039588.png'),
(31, 'Senin, 17 Maret 2025', '2025-03-17', '2025-03-17', 'AAAAAAAAAAAA', 'AAAAb', 'Dikembalikan', 'AAAAA', '', '2025-03-16 23:41:45', ''),
(32, 'Senin, 17 Maret 2025', '2025-03-17', NULL, 'CCCC', 'BBBBBBB', 'Dipinjam', 'AAAAA', '', '2025-03-16 23:37:52', ''),
(33, 'Rabu, 30 April 2025', '2025-04-30', NULL, 'Kecu', 'M-1234-AKC', 'Dipinjam', 'AAA', '', '2025-04-30 07:20:56', 'uploads/ttd_1745997628.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `peminjaman_su`
--

CREATE TABLE `peminjaman_su` (
  `id` int(11) NOT NULL,
  `tanggal_hari` varchar(255) NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `nama_peminjam` varchar(255) NOT NULL,
  `jenis_hak` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `file_keterangan` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `tanda_tangan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `peminjaman_su`
--

INSERT INTO `peminjaman_su` (`id`, `tanggal_hari`, `tanggal_pinjam`, `tanggal_kembali`, `nama_peminjam`, `jenis_hak`, `status`, `keterangan`, `file_keterangan`, `created_at`, `tanda_tangan`) VALUES
(1, 'Selasa, 4 Maret 2025', '2025-03-04', NULL, 'bagus', 'GS-12345', 'Dipinjam', 'untuk keperluan tertentu di hari tertentu', '', '2025-04-30 07:41:03', 'uploads_su/ttd_1741039143.png'),
(2, 'Selasa, 4 Maret 2025', '2025-03-04', NULL, 'bagus', 'GS-1234', 'Dipinjam', 'untuk keperluan tertentu di hari tertentu', '', '2025-04-30 07:36:22', 'uploads_su/ttd_1741039143.png'),
(3, 'Rabu, 30 April 2025', '2025-04-30', '2025-04-30', 'AAAA', 'CCCCCCC', 'Dikembalikan', 'AAAA', '', '2025-04-30 07:42:41', 'uploads_su/ttd_1745998801.png'),
(4, 'Rabu, 30 April 2025', '2025-04-30', NULL, 'AAAA', 'BBBBBBB', 'Dipinjam', 'AAAA', '', '2025-04-30 07:40:01', 'uploads_su/ttd_1745998801.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `peminjaman_warkah`
--

CREATE TABLE `peminjaman_warkah` (
  `id` int(11) NOT NULL,
  `tanggal_hari` varchar(255) NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `nama_peminjam` varchar(255) NOT NULL,
  `jenis_hak` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `file_keterangan` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `tanda_tangan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `peminjaman_warkah`
--

INSERT INTO `peminjaman_warkah` (`id`, `tanggal_hari`, `tanggal_pinjam`, `tanggal_kembali`, `nama_peminjam`, `jenis_hak`, `status`, `keterangan`, `file_keterangan`, `created_at`, `tanda_tangan`) VALUES
(1, 'Rabu, 30 April 2025', '2025-04-30', NULL, 'bagus', 'AAAAABCD', 'Dipinjam', 'AAAAA', '', '2025-04-30 07:42:26', 'uploads_warkah/ttd_1745998330.png'),
(2, 'Rabu, 30 April 2025', '2025-04-30', '2025-05-09', 'bagas bang', 'AAAAA', 'Dikembalikan', 'AAAAA', '', '2025-05-09 17:32:59', 'uploads_warkah/ttd_1745998330.png'),
(3, 'Rabu, 30 April 2025', '2025-04-30', '2025-04-30', 'bagus', 'AAAAA', 'Dikembalikan', 'AAAAA', '', '2025-04-30 07:32:18', 'uploads_warkah/ttd_1745998330.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `profil` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'Umum',
  `verifikasi` tinyint(1) NOT NULL DEFAULT 0,
  `code` varchar(6) DEFAULT NULL,
  `otp_expiry` datetime DEFAULT NULL,
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id`, `nama`, `email`, `password`, `profil`, `role`, `verifikasi`, `code`, `otp_expiry`, `reset_token`, `reset_token_expiry`) VALUES
(16, 'Seksi PHP', 'seksiphpkantahkotayk@gmail.com', '$2y$10$vj8pu1DdtxgluiA7/CpnburT5yMiOJYVJ.unP1qqIXOn.FfL8ClE.', '16.png', 'Admin', 1, NULL, NULL, NULL, NULL),
(17, 'yudha', 'yudha@gmail.com', '$2y$10$BV8yhfOPFneQcGnNOaA8O.fjHimg384ijODWcYneJl0ykCaQGIhQi', '17.png', 'Umum', 1, NULL, NULL, NULL, NULL),
(18, 'rio', 'rio@gmail.com', '$2y$10$WC/EF83o78oEGge8Cpe/D.VinZ5E6u09RF.ucMfPuzmYoT56RkJ3u', 'profil-default.png', 'Umum', 0, NULL, NULL, NULL, NULL),
(21, 'Aryamukti Satria Hendrayana ', 'aryamuktisatria@gmail.com', '$2y$10$ExquXJNl2JirJAGFQvl8K.WTukeV476SA/cU4BmzgkAfr7axEayr6', '21.jpg', 'Bagian BT', 1, '313734', '0000-00-00 00:00:00', NULL, NULL),
(22, 'rio', 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx@gmail.com', '$2y$10$jg0.IWhtwkmostDcjg1/q.bYyp5FQPSXzhL3jh94e0YLvCU/inNii', 'profil-default.png', 'Umum', 0, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `buku_tanah`
--
ALTER TABLE `buku_tanah`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kecamatan`
--
ALTER TABLE `kecamatan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `peminjaman_su`
--
ALTER TABLE `peminjaman_su`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `peminjaman_warkah`
--
ALTER TABLE `peminjaman_warkah`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `buku_tanah`
--
ALTER TABLE `buku_tanah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `kecamatan`
--
ALTER TABLE `kecamatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT untuk tabel `peminjaman_su`
--
ALTER TABLE `peminjaman_su`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `peminjaman_warkah`
--
ALTER TABLE `peminjaman_warkah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

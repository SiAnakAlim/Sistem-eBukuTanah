-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 11 Feb 2025 pada 08.25
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
(1, 'Senin, 3 Februari 2025', '2025-02-03', NULL, '', 'w-1012-qwe', 'dipinjam', NULL, '', '2025-02-03 07:32:16', ''),
(2, 'Senin, 3 Februari 2025', '2025-02-03', '2025-02-04', '', 'w-1012-asd', 'Dikembalikan', '', '', '2025-02-04 08:13:27', ''),
(3, 'Rabu, 5 Februari 2025', '2025-02-05', NULL, 'mas yogi', 'w-1012-zxc', 'Dipinjam', NULL, '', '2025-02-05 07:11:43', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAACWCAYAAABkW7XSAAAAAXNSR0IArs4c6QAADG1JREFUeF7tnTmrNUUQhutzVxQFA1ExVXHF0MjMwMhAMBIEf4H/wH9gbuQSCQZGBkYaGYobKBgIoqAgKIr79pWe0nacpXu6ZrrrzHNA7v28Pd3Vz1vznu6enpkLwgcCEIBAEAIXgsRJmBCAAAQEwyIJIACBMAQwrDBSESgEI'),
(4, 'Rabu, 5 Februari 2025', '2025-02-05', NULL, 'mas yoga', 'w-1012-iop', 'Dipinjam', NULL, '', '2025-02-05 07:45:28', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAACWCAYAAABkW7XSAAAAAXNSR0IArs4c6QAADLdJREFUeF7tnUvLLUcVht/kRI3XKMT7JTgQ4kBUEPwFojgQRUdOHQiKA00GCiIZKCpEHYiigiPBiYjiQAQH+gMEbwMFByYq4iWIF9CoiboXdGHZ9v52V3fVWl21nw2Hj3O+rlqrnrf6PVW1q6tvEx8IQAACnRC4rZM8SRMCE'),
(5, 'Rabu, 5 Februari 2025', '2025-02-05', NULL, 'aaaaa', 'w-1012-jkl', 'Dipinjam', NULL, '', '2025-02-05 08:00:41', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAACWCAYAAABkW7XSAAAAAXNSR0IArs4c6QAADAdJREFUeF7tnVvILlUZx/8eMnWXdtiiRZaomRndBAqiWFISJpg3KV2kNyJ4AAMvItrWLrdIkKBQGBEUdRHlRQeoEBUpDMEggijPpnmh4anSPGXqPH4zur7Z72HmfWfWu555fgPDfr/9rVnrWb//M/9vzbxr1uwjNghAAAJOC'),
(6, 'Jumat, 7 Februari 2025', '2025-02-07', NULL, 'mas yoga', 'M-1234-WBJ', 'Dipinjam', NULL, '', '2025-02-07 03:06:29', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAACWCAYAAABkW7XSAAAAAXNSR0IArs4c6QAADhBJREFUeF7tncnrLUcVx795Q6IJxrdwAHHjwiAoSXCj4kpXTqAoDrhx40JciRL/CEPElfgHiDigqOCwcifqRmJQkLgVJYpBI1GTOL2jXVCvf31vVXdXdZ2q+7mb5L1bwzmfc+73VVVXVd8lPhCAAAQ6IXBXJ3ZiJgQgAAEhW');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

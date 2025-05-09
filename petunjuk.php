<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Petunjuk Penggunaan</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" type="image/png" href="assets/img/logo-bpn.png" />
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            background: white;
        }
        .container {
            max-width: 900px;
            margin: auto;
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #007bff;
        }
        .section {
            margin-bottom: 20px;
            padding: 15px;
            background: white;
            border-left: 5px solid #007bff;
            border-radius: 5px;
        }
        .section h3 {
            color: #007bff;
        }
        ul {
            padding-left: 20px;
        }
        a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Petunjuk Penggunaan Sistem</h2>

    <div class="section">
        <h3>1. Navigasi Menu</h3>
        <p>Menu utama dapat diakses melalui sidebar kiri, terdiri dari:</p>
        <ul>
            <li><strong>Dashboard</strong>: Menampilkan ringkasan data peminjaman dan grafik statistik.</li>
            <li><strong>Peminjaman</strong>: Mengelola data peminjaman Buku Tanah, Surat Ukur (SU), dan Warkah.</li>
            <li><strong>Pengaturan</strong>: Mengelola akun pengguna dan pengaturan sistem.</li>
            <li><strong>Petunjuk</strong>: Halaman ini berisi panduan penggunaan sistem.</li>
        </ul>
    </div>

    <div class="section">
        <h3>2. Dashboard</h3>
        <p>Dashboard menampilkan informasi peminjaman secara ringkas, termasuk:</p>
        <ul>
            <li><strong>Jumlah peminjaman aktif</strong> dan yang telah dikembalikan.</li>
            <li><strong>Grafik harian</strong>: Menampilkan jumlah peminjaman per hari dalam sebulan terakhir.</li>
            <li><strong>Grafik bulanan</strong>: Menunjukkan tren peminjaman dalam beberapa bulan terakhir.</li>
        </ul>
    </div>

    <div class="container">
    <h2>Petunjuk Penggunaan Sistem</h2>

    <div class="section">
        <h3>1. Navigasi Menu</h3>
        <p>Menu utama dapat diakses melalui sidebar kiri, terdiri dari:</p>
        <ul>
            <li><strong>Dashboard</strong>: Menampilkan ringkasan data peminjaman.</li>
            <li><strong>Peminjaman</strong>: Melihat, menambah, mengelola, dan mengubah status peminjaman buku tanah, surat ukur (SU), dan warkah.</li>
            <li><strong>Cetak Laporan</strong>: Mencetak laporan peminjaman berdasarkan filter tertentu.</li>
            <li><strong>Pengaturan</strong>: Mengelola akun pengguna dan pengaturan data.</li>
            <li><strong>Petunjuk</strong>: Halaman ini berisi panduan penggunaan sistem.</li>
        </ul>
    </div>

    <div class="section">
        <h3>2. Role Pengguna</h3>
        <p>Setiap pengguna memiliki hak akses yang berbeda sesuai dengan perannya dalam sistem:</p>
        <ul>
            <li><strong>Pimpinan Kantor</strong>: Memiliki akses penuh untuk melihat seluruh data peminjaman.</li>
            <li><strong>Admin</strong>: Dapat mengelola akun pengguna dan mengatur data peminjaman.</li>
            <li><strong>Bagian Buku Tanah</strong>: Hanya dapat mengakses dan mengelola peminjaman buku tanah.</li>
            <li><strong>Bagian Surat Ukur (SU)</strong>: Hanya dapat mengakses dan mengelola peminjaman surat ukur.</li>
            <li><strong>Bagian Warkah</strong>: Hanya dapat mengakses dan mengelola peminjaman warkah.</li>
            <li><strong>Umum</strong>: Memiliki akses terbatas untuk melihat data peminjaman tanpa bisa mengedit.</li>
        </ul>
    </div>

    <div class="section">
        <h3>3. Manajemen Peminjaman</h3>
        <p>Pada halaman peminjaman, pengguna dapat:</p>
        <ul>
            <li><strong>Tambah Input Baru</strong>: Memasukkan data peminjaman baru untuk buku tanah, SU, atau warkah.</li>
            <li><strong>Edit</strong>: Mengubah data peminjaman yang sudah ada.</li>
            <li><strong>Hapus</strong>: Menghapus data peminjaman jika diperlukan.</li>
            <li><strong>Filter Data</strong>: Mencari data berdasarkan kategori (buku tanah, SU, warkah) atau rentang tanggal tertentu.</li>
            <li><strong>Mengubah Status Peminjaman</strong>: Status peminjaman dapat diperbarui menjadi "Dikembalikan" jika sudah selesai.</li>
        </ul>
    </div>

    <div class="section">
        <h3>4. Cetak Laporan</h3>
        <p>Pengguna dapat mencetak laporan peminjaman berdasarkan:</p>
        <ul>
            <li><strong>Rentang Tanggal</strong>: Memilih periode tertentu untuk mencetak laporan.</li>
            <li><strong>Kategori</strong>: Memilih laporan berdasarkan buku tanah, SU, atau warkah.</li>
            <li><strong>Status</strong>: Memfilter laporan berdasarkan status peminjaman (dipinjam atau dikembalikan).</li>
        </ul>
    </div>

    <div class="section">
        <h3>5. Pengaturan dan Akun Pengguna</h3>
        <p>Pada halaman pengaturan, pengguna dapat:</p>
        <ul>
            <li><strong>Mengubah Username dan Email</strong>: Dapat diedit di halaman pengaturan profil.</li>
            <li><strong>Mengunggah Foto Profil</strong>: Foto baru akan tersimpan di database.</li>
            <li><strong>Menambahkan dan Mengelola Pengguna</strong>: Hanya admin yang dapat menambah atau mengubah data pengguna.</li>
        </ul>
    </div>

    <div class="section">
        <h3>6. Fitur Pencarian dan Filter Data</h3>
        <p>Untuk kemudahan dalam mengelola data, sistem menyediakan fitur pencarian dan filter:</p>
        <ul>
            <li><strong>Pencarian Nama</strong>: Mengetik nama peminjam langsung menampilkan hasil.</li>
            <li><strong>Filter Kategori</strong>: Memilih kategori (buku tanah, SU, atau warkah) untuk melihat data terkait.</li>
            <li><strong>Filter Tanggal Peminjaman</strong>: Menyesuaikan tampilan data berdasarkan periode tertentu.</li>
            <li><strong>Pengaturan Jumlah Data</strong>: Pengguna bisa memilih 5, 10, atau 15 data per halaman.</li>
        </ul>
    </div>

    <p style="text-align: center;"><a href="index.php">Kembali ke Halaman Utama</a></p>
</div>


</body>
</html>

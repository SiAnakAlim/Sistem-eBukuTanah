<?php
include 'koneksi.php'; // Pastikan file koneksi database sudah ada

// Pastikan ID tersedia
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Pastikan ID adalah angka

    // Query untuk menghapus data
    $query = "DELETE FROM peminjaman_warkah WHERE id = $id";

    if ($conn->query($query) === TRUE) {
        echo "<script>alert('Data berhasil dihapus!'); window.location.href='tabel_warkah.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data!'); window.location.href='tabel_warkah.php';</script>";
    }
} else {
    echo "<script>alert('ID tidak ditemukan!'); window.location.href='tabel_warkah.php';</script>";
}
?>

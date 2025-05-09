<?php
include 'koneksi.php';
session_start(); // Gunakan session untuk menyimpan notifikasi

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Ambil data file sebelum dihapus
    $query = "SELECT file_path FROM buku_tanah WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $file_path = $row['file_path'];

        // Hapus data dari database
        $delete_query = "DELETE FROM buku_tanah WHERE id = $id";
        if (mysqli_query($conn, $delete_query)) {
            // Hapus file jika ada
            if (!empty($file_path) && file_exists($file_path)) {
                unlink($file_path);
            }
            $_SESSION['success'] = "✅ Data berhasil dihapus!";
        } else {
            $_SESSION['error'] = "❌ Gagal menghapus data. Silakan coba lagi.";
        }
    } else {
        $_SESSION['error'] = "⚠ Data tidak ditemukan.";
    }
} else {
    $_SESSION['error'] = "⚠ ID tidak valid.";
}

// Kembali ke halaman inventaris
header("Location: inventaris_buku.php");
exit();
?>

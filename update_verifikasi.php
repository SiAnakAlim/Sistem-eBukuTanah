<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Ambil status verifikasi saat ini
    $result = mysqli_query($conn, "SELECT verifikasi FROM user WHERE id=$id");
    $row = mysqli_fetch_assoc($result);
    $newStatus = $row['verifikasi'] ? 0 : 1; // Toggle status

    $query = "UPDATE user SET verifikasi=$newStatus WHERE id=$id";
    if (mysqli_query($conn, $query)) {
        echo "Verifikasi berhasil diperbarui!";
    } else {
        echo "Gagal memperbarui verifikasi.";
    }
}
?>

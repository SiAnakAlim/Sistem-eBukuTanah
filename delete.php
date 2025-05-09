<?php
require 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = mysqli_real_escape_string($conn, $_POST['id']);

    // Ambil file keterangan sebelum dihapus
    $sql_get = "SELECT file_keterangan FROM peminjaman WHERE id = ?";
    $stmt_get = $conn->prepare($sql_get);
    $stmt_get->bind_param("i", $id);
    $stmt_get->execute();
    $stmt_get->bind_result($file_keterangan);
    $stmt_get->fetch();
    $stmt_get->close();

    // Hapus file keterangan jika ada
    if (!empty($file_keterangan) && file_exists("uploads/" . $file_keterangan)) {
        unlink("uploads/" . $file_keterangan);
    }

    // Hapus data dari database
    $sql_delete = "DELETE FROM peminjaman WHERE id = ?";
    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }

    $stmt->close();
    $conn->close();
}

?>

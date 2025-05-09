<?php
require 'koneksi.php';

// Ambil ID dari URL
$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $nama_peminjam = mysqli_real_escape_string($conn, $_POST['nama_peminjam']);
    $tanggal_pinjam = mysqli_real_escape_string($conn, $_POST['tanggal_pinjam']);
    $jenis_hak = mysqli_real_escape_string($conn, $_POST['jenis_hak']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
    $tanggal_kembali = $status === 'Dikembalikan' ? "'" . date('Y-m-d') . "'" : "NULL";

    // Ambil data lama
    $sql_get = "SELECT file_keterangan FROM peminjaman_su WHERE id = ?";
    $stmt_get = $conn->prepare($sql_get);
    $stmt_get->bind_param("i", $id);
    $stmt_get->execute();
    $stmt_get->bind_result($old_file);
    $stmt_get->fetch();
    $stmt_get->close();

    // Proses file baru jika ada
    $file_keterangan = $old_file;
    if (!empty($_FILES["file_keterangan"]["name"])) {
        $target_dir = "uploads/";
        $file_keterangan = time() . "_" . basename($_FILES["file_keterangan"]["name"]);
        $target_file = $target_dir . $file_keterangan;
        
        // Hapus file lama jika ada
        if (!empty($old_file) && file_exists("uploads/" . $old_file)) {
            unlink("uploads/" . $old_file);
        }

        move_uploaded_file($_FILES["file_keterangan"]["tmp_name"], $target_file);
    }

    // Update data dengan prepared statement
    $sql_update = "UPDATE peminjaman_su SET nama_peminjam=?, tanggal_pinjam=?, jenis_hak=?, status=?, keterangan=?, file_keterangan=?, tanggal_kembali=$tanggal_kembali WHERE id=?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("ssssssi", $nama_peminjam, $tanggal_pinjam, $jenis_hak, $status, $keterangan, $file_keterangan, $id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location.href='tabel_su.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data: " . $stmt->error . "');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

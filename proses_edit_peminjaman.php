<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nama_peminjam = $_POST['nama_peminjam'];
    $tanggal_pinjam = $_POST['tanggal_pinjam'];
    $jenis_hak = $_POST['jenis_hak'];
    $status = $_POST['status'];
    $keterangan = $_POST['keterangan'];
    
    // Jika status dikembalikan, atur tanggal kembali otomatis jika belum diisi
    if ($status == "Dikembalikan") {
        $tanggal_kembali = date("Y-m-d");
    } else {
        $tanggal_kembali = NULL;
    }

    // Update data di database
    $query = "UPDATE peminjaman SET nama_peminjam=?, tanggal_pinjam=?, jenis_hak=?, status=?, keterangan=?, tanggal_kembali=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssi", $nama_peminjam, $tanggal_pinjam, $jenis_hak, $status, $keterangan, $tanggal_kembali, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location='peminjaman.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data!'); window.history.back();</script>";
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('Akses tidak diizinkan!'); window.location='peminjaman.php';</script>";
}

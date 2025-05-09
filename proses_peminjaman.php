<?php
require 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tanggal_hari = $_POST['tanggal_hari'];
    $tanggal_pinjam = $_POST['tanggal_pinjam'];
    $tanggal_kembali = $_POST['tanggal_kembali'] ? "'".$_POST['tanggal_kembali']."'" : "NULL";  
    $keterangan = $_POST['keterangan'];
    $nama_peminjam = $_POST['nama_peminjam'];
    $jenis_hak_list = $_POST['jenis_hak'];  // Array dari input jenis_hak

    // Proses file keterangan
    $file_keterangan = "";
    if (!empty($_FILES["file_keterangan"]["name"])) {
        $target_dir = "uploads/";
        $file_keterangan = time() . "_" . basename($_FILES["file_keterangan"]["name"]);
        $target_file = $target_dir . $file_keterangan;
        move_uploaded_file($_FILES["file_keterangan"]["tmp_name"], $target_file);
    }

    // Proses tanda tangan (Base64 ke file)
    $tanda_tangan = "";
    if (!empty($_POST['tanda_tangan'])) {
        $img = $_POST['tanda_tangan'];
        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        $tanda_tangan = "uploads/ttd_" . time() . ".png";
        file_put_contents($tanda_tangan, $data);
    }

    // Simpan ke database dengan status 'Dipinjam' untuk setiap jenis_hak
    $status = 'Dipinjam'; 

    foreach ($jenis_hak_list as $jenis_hak) {
        $sql = "INSERT INTO peminjaman (tanggal_hari, tanggal_pinjam, tanggal_kembali, jenis_hak, keterangan, file_keterangan, nama_peminjam, tanda_tangan, status) 
                VALUES ('$tanggal_hari', '$tanggal_pinjam', $tanggal_kembali, '$jenis_hak', '$keterangan', '$file_keterangan', '$nama_peminjam', '$tanda_tangan', '$status')";

        $conn->query($sql);
    }

    echo "<script>alert('Data berhasil disimpan!'); window.location.href='index.php';</script>";
}
?>

?>

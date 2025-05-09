<?php
// Konfigurasi database
$server = "localhost"; // Nama server (biasanya localhost)
$username = "root";    // Username database (default: root)
$password = "";        // Password database (default: kosong untuk XAMPP)
$db = "kerjapraktek"; // Ganti "nama_database" dengan nama database Anda

// Membuat koneksi
$conn = new mysqli($server, $username, $password, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>

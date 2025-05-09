<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['email_verifikasi'])) {
    echo "0";
    exit;
}

$email = $_SESSION['email_verifikasi'];
$query = "SELECT verifikasi FROM user WHERE email = '$email'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

echo $user['verifikasi']; // Mengembalikan 1 jika sudah diverifikasi, 0 jika belum
?>

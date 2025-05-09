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

// Jika akun sudah diverifikasi, hapus sesi verifikasi agar tidak terus dicek
if ($user['verifikasi'] == 1) {
    unset($_SESSION['email_verifikasi']);
}

echo $user['verifikasi']; // 0 jika belum, 1 jika sudah
?>


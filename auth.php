<?php
session_start();

// Dapatkan nama halaman saat ini
$current_page = basename($_SERVER['PHP_SELF']);

// Izinkan akses ke login dan register tanpa login
if (!isset($_SESSION['user_id']) && !in_array($current_page, ['login.php', 'register.php'])) {
    $_SESSION['error'] = "Anda harus login terlebih dahulu!";
    header("Location: login.php");
    exit();
}

// Jika sudah login, tidak perlu akses login/register
if (isset($_SESSION['user_id']) && in_array($current_page, ['login.php', 'register.php'])) {
    header("Location: index.php");
    exit();
}
?>

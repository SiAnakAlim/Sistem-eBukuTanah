<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Akses ditolak!";
    exit;
}

$user_id = $_SESSION['user_id'];
$query_role = "SELECT role FROM user WHERE id = '$user_id'";
$result_role = mysqli_query($conn, $query_role);
$user_data = mysqli_fetch_assoc($result_role);
$user_role = $user_data['role'];

if ($user_role !== 'Admin' && $user_role !== 'Pimpinan Kantor') {
    echo "Anda tidak memiliki izin!";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $newRole = $_POST['role'];

    $updateQuery = "UPDATE user SET role = '$newRole' WHERE id = '$id'";
    if (mysqli_query($conn, $updateQuery)) {
        echo "Role berhasil diperbarui!";
    } else {
        echo "Gagal memperbarui role!";
    }
}
?>
